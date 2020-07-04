<?php
final class ExerciseRepository extends BaseRepository
{
    const DB_TABLE = "gym_exercise";
    const DB_TABLE_MUSCLES = "gym_muscle";

    public function __construct(PDO $pdo = null)
    {
        parent::__construct($pdo);
    }

    private function mapToModel($row)
    {
        $model = new Exercise(
            $row['id'],
            $row['muscleId'],
            $row['name'],
            $row['type'],
            $row['level']
        );

        if (isset($row['muscleName']) && isset($row['muscleGroupId'])) {
            $muscleGroup = ($row['muscleGroupId'] > 0 && $row['muscleGroupId'] < 7) ? MuscleGroupStaticRepository::Get($row['muscleGroupId']) : null;
            $model->muscle = new Muscle($row['muscleId'], $row['muscleName'], $muscleGroup);
        }
        $model = self::mapHistoricalData($model, $row);
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

    public function validate(Exercise $exercise)
    {
        if ($exercise->createdByUserId === null) {
            http_response_code(400);
            echo json_encode(array("message" => "CreatedByUserId is required to create exercise. "));
            return false;
        }

        if ($exercise->name === null) {
            http_response_code(400);
            echo json_encode(array("message" => "Name is required to create exercise. "));
            return false;
        }

        if ($exercise->type === null) {
            http_response_code(400);
            echo json_encode(array("message" => "Type is required to create exercise."));
            return false;
        }

        // Not yet implemented
        // if ($exercise->muscle === null) {
        //     http_response_code(400);
        //     echo json_encode(array("message" => "Muscle is required to create exercise."));
        //     return false;
        // }

        if ($exercise->level === null || $exercise->level === "") {
            http_response_code(400);
            echo json_encode(array("message" => "Level is required to create exercise."));
            return false;
        }
        return true;
    }

    public function exerciseExist(int $id)
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

    public function create(Exercise $exercise)
    {
        $query = "INSERT INTO " . self::DB_TABLE . "
        SET
            createdByUserId = :createdByUserId,
            name = :name,
            type = :type,
            muscleId = :muscleId";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(':createdByUserId', $exercise->createdByUserId);
        $stmt->bindParam(':name', $exercise->name);
        $stmt->bindParam(':type', $exercise->type);
        $stmt->bindParam(':muscleId', $exercise->muscleId);

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

    public function update(Exercise $exercise)
    {
        $query = "UPDATE " . self::DB_TABLE . "
            SET
                modifiedByUserId = :modifiedByUserId,
                name = :name,
                type = :type,
                muscleId = :muscleId,
                level = :level
            WHERE id = :id";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(':modifiedByUserId', $exercise->modifiedByUserId);
        $stmt->bindParam(':name', $exercise->name);
        $stmt->bindParam(':type', $exercise->type);
        $stmt->bindParam(':muscleId', $exercise->muscleId);
        $stmt->bindParam(':level', $exercise->level);

        $stmt->bindParam(':id', $exercise->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAll()
    {
        $query = "SELECT e.*, m.name as muscleName, m.muscleGroupId 
        FROM " . self::DB_TABLE . " e 
        inner join " . self::DB_TABLE_MUSCLES . " m on m.id = e.muscleId";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->mapToModels($rows);
    }

    public function delete(Exercise $exercise)
    {
        $query = "DELETE
                    FROM " . self::DB_TABLE . "
                    WHERE id = ?
                    ";

        $stmt = self::$dbHandle->prepare($query);

        $stmt->bindParam(1, $exercise->id);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        }
        return false;
    }
}
