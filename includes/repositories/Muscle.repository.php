<?php
final class MuscleRepository extends BaseRepository
{
    const DB_TABLE = "gym_muscle";

    public function __construct(PDO $pdo = null)
    {
        parent::__construct($pdo);
    }

    private function mapToModel($row)
    {
        $model = new Muscle(
            $row['id'],
            $row['name']
        );

        return $model;
    }

    private function mapToModels($rows)
    {
        $models = [];
        foreach ($rows as $row) {
            $object = self::mapToModel($row);
            array_push($models, $object);
        }
        return $models;
    }

    public function validate(Muscle $model)
    {
        if ($model->createdByUserId === null) {
            http_response_code(400);
            echo json_encode(array("message" => "CreatedByUserId is required to create muscle. "));
            return false;
        }

        if ($model->name === null) {
            http_response_code(400);
            echo json_encode(array("message" => "Name is required to create muscle. "));
            return false;
        }

        return true;
    }

    public function modelExist(int $id)
    {
        $query = "SELECT id password
                    FROM " . self::DB_TABLE . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        }
        return false;
    }

    /**
     * Not implemented
     */
    public function muscleExists(int $muscleId)
    {
        $query = "SELECT id password
                    FROM " . self::DB_TABLE . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $muscleId = htmlspecialchars(strip_tags($muscleId));

        $stmt->bindParam(1, $muscleId);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        }

        return false;
    }

    public function create(Muscle $model)
    {
        $query = "INSERT INTO " . self::DB_TABLE . "
        SET
            name = :name";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(':createdByUserId', $model->createdByUserId);
        $stmt->bindParam(':name', $model->name);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getById(string $id)
    {
        $query = "SELECT *
                    FROM " . self::DB_TABLE . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return self::mapToModel($row);
        } else {
            return null;
        }
    }

    public function update(Muscle $model)
    {
        $query = "UPDATE " . self::DB_TABLE . "
            SET
                name = :name,
                muscleGroupId = :muscleGroupId
            WHERE id = :id";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(':muscleGroupId', $model->muscleGroup);
        $stmt->bindParam(':name', $model->name);
        
        $stmt->bindParam(':id', $model->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAll()
    {
        $query = "SELECT *
        FROM " . self::DB_TABLE;

        $stmt = self::$dbHandle->prepare($query);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return self::mapToModels($rows);
    }

    public function delete(Muscle $model)
    {
        $query = "DELETE
                    FROM " . self::DB_TABLE . "
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
}
