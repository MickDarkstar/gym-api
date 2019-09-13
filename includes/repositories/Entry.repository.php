<?php
final class EntryRepository extends BaseRepository
{
    private $exerciseRepository;
    const TABLE_ENTRY = "entry";
    const TABLE_ENTRY_DETAIL = "entry_detail";

    public function __construct(PDO $pdo = null)
    {
        parent::__construct($pdo);
        $this->exerciseRepository = new ExerciseRepository();
    }

    protected function mapToEntry($row)
    {
        if ($row == null) {
            return null;
        }
        $entryDetails = $this->getEntryDetailsByEntryId($row['id']);
        $model = new Entry(
            $row['id'],
            $row['date'],
            $entryDetails,
            $row['comment']
        );

        return $model;
    }

    protected function mapToEntries($rows)
    {
        $models = [];
        foreach ($rows as $row) {
            $model = self::mapToEntry($row);
            array_push($models, $model);
        }
        return $models;
    }

    protected function mapToEntryDetail($row)
    {
        if ($row == null) {
            return null;
        }
        $exercise = $this->exerciseRepository->getById($row['exerciseId']);
        $entryDetail = new EntryDetail(
            $row['id'],
            $row['entryId'],
            $exercise,
            $row['weight'],
            $row['reps'],
            $row['rest'],
            $row['sets'],
            $row['date'],
            $row['comment']
        );
        return $entryDetail;
    }

    protected function mapToEntryDetails($rows)
    {
        $entryDetails = [];
        foreach ($rows as $row) {
            $object = self::mapToEntryDetail($row);
            array_push($entryDetails, $object);
        }
        return $entryDetails;
    }

    public function getEntriesByUser(AppUser $user)
    {
        $query = "SELECT *
                FROM " . self::TABLE_ENTRY . "
                WHERE createdByUserId = :createdByUserId
                ";

        $stmt = self::$dbHandle->prepare($query);
        $stmt->bindParam(':createdByUserId', $user->id, PDO::PARAM_INT);

        $stmt->execute();

        // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return $rows;
        return self::mapToEntries($rows);
    }

    public function validateEntryDetail(EntryDetail $model)
    {
        if ($model->createdByUserId === null) {
            http_response_code(400);
            echo json_encode(array("message" => "CreatedByUserId is required to create entry. "));
            return false;
        }

        if ($model->exercise->id === null) {
            http_response_code(400);
            echo json_encode(array("message" => "Exercise is required to create entry. "));
            return false;
        }

        if ($model->entryId === null || $model->entryId === 0) {
            http_response_code(400);
            echo json_encode(array("message" => "EntryId is required to create entry detail. "));
            return false;
        }
        return true;
    }

    public function createEntry(AppUser $user, $date = null)
    {
        $query = "INSERT INTO " . self::TABLE_ENTRY . "
        SET
            createdByUserId = :createdByUserId,
            date = :date
            ";
        $stmt = self::$dbHandle->prepare($query);
        if ($date == null) {
            $currentTime = new DateTime();
            $date = $currentTime->format('Y-m-d H:i:s');
        }
        $stmt->bindParam(':createdByUserId', $user->id);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            $id = self::$dbHandle->lastInsertId();
            return self::getEntryById($id);
        }

        return false;
    }

    public function createEntryDetail(EntryDetail $model)
    {
        $query = "INSERT INTO " . self::TABLE_ENTRY_DETAIL . "
        SET
            createdByUserId = :createdByUserId,
            entryId = :entryId,
            exerciseId = :exerciseId
            ";

        $stmt = self::$dbHandle->prepare($query);

        // Should be default date for server, client should be responsible for choosing timezone and format based on users location..
        // date_default_timezone_set('UTC');

        // set current date if null, should be removed and set in client app
        $exerciseId = $model->exercise->id;
        $stmt->bindParam(':createdByUserId', $model->createdByUserId);
        $stmt->bindParam(':entryId', $model->entryId);
        $stmt->bindParam(':exerciseId', $exerciseId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getEntryById(string $id)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_ENTRY . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $id = htmlspecialchars(strip_tags($id));

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::mapToEntry($row);
    }

    public function getTodaysEntry(AppUser $user)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_ENTRY . "
                    where date_format(date, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') AND createdByUserId = ? 
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $user->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::mapToEntry($row);
    }

    public function getEntryDetailById(int $id)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_ENTRY_DETAIL . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $id = htmlspecialchars(strip_tags($id));

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::mapToEntryDetail($row);
    }

    public function getEntryDetailsByEntryId(int $id)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_ENTRY_DETAIL . "
                    WHERE entryId = ?";

        $stmt = self::$dbHandle->prepare($query);

        $id = htmlspecialchars(strip_tags($id));

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return self::mapToEntryDetails($rows);
    }

    public function updateEntry(Entry $model)
    {
        $query = "UPDATE " . self::TABLE_ENTRY . "
            SET
                date = :date,
                comment = :comment
            WHERE id = :id";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(':date', $model->date);
        $stmt->bindParam(':comment', $model->comment);

        $stmt->bindParam(':id', $model->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateEntryDetail(EntryDetail $model)
    {
        $query = "UPDATE " . self::TABLE_ENTRY_DETAIL . "
            SET
                weight = :weight,
                reps = :reps,
                sets = :sets,
                rest = :rest,
                comment = :comment
            WHERE id = :id";

        $stmt = self::$dbHandle->prepare($query);

        $model = $this->sanitizeEntryDetail($model);

        $stmt->bindParam(':weight', $model->weight);
        $stmt->bindParam(':reps', $model->reps);
        $stmt->bindParam(':sets', $model->sets);
        $stmt->bindParam(':rest', $model->rest);
        $stmt->bindParam(':comment', $model->comment);

        $stmt->bindParam(':id', $model->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteEntry(Entry $model)
    {
        $query = "DELETE
                    FROM " . self::TABLE_ENTRY . "
                    WHERE id = ?
                    ";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $model->id);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        }
        return false;
    }

    public function deleteEntryDetail(EntryDetail $model)
    {
        $query = "DELETE
                    FROM " . self::TABLE_ENTRY_DETAIL . "
                    WHERE id = ?
                    ";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $model->id);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        }
        return false;
    }

    private function sanitizeEntryDetail(EntryDetail $model)
    {
        $model->weight = htmlspecialchars(strip_tags($model->weight));
        $model->reps = htmlspecialchars(strip_tags($model->reps));
        $model->sets = htmlspecialchars(strip_tags($model->sets));
        $model->rest = htmlspecialchars(strip_tags($model->rest));
        $model->comment = htmlspecialchars(strip_tags($model->comment));
        return $model;
    }
}
