<?php
final class WorkoutRepository extends BaseRepository
{
    private $exerciseRepository;
    const TABLE_WORKOUT = "gym_workout";
    const TABLE_WORKOUT_EXERCISE = "gym_workout_exercise";

    public function __construct(PDO $pdo = null)
    {
        parent::__construct($pdo);
        $this->exerciseRepository = new ExerciseRepository();
    }

    /**
     * @return Workout
     */
    protected function mapToWorkout($row)
    {
        if ($row == null) {
            return null;
        }
        $model = new Workout(
            $row['id'],
            $row['name'],
            $row['type'],
            $row['description'],
            []
        );

        // foreach exercise
        return $model;
    }

    /**
     * @return Workout[]
     */
    protected function mapToWorkouts($rows)
    {
        $models = [];
        foreach ($rows as $row) {
            $model = self::mapToWorkout($row);
            array_push($models, $model);
        }
        return $models;
    }

    public function GetAll()
    {
        $query = "SELECT *
                FROM " . self::TABLE_WORKOUT;

        $stmt = self::$dbHandle->prepare($query);

        $stmt->execute();

        $rows = $stmt->fetchAll();
        return self::mapToWorkouts($rows);
    }

    public function GetWorkoutsByUser(AppUser $user)
    {
        $query = "SELECT *
                FROM " . self::TABLE_WORKOUT . "
                WHERE createdByUserId = :createdByUserId
                ";

        $stmt = self::$dbHandle->prepare($query);
        $stmt->bindParam(':createdByUserId', $user->id, PDO::PARAM_INT);

        $stmt->execute();

        $rows = $stmt->fetchAll();
        return self::mapToWorkouts($rows);
    }

    /**
     * @return ValidationMessage
     */
    public static function ValidateCreateWorkout(Workout $model)
    {
        $validationMessage = new ValidationMessage();
        if (isset($model->createdByUserId) == false || self::IsInvalidId($model->workoutId)) {
            $validationMessage->Add("createdByUserId", "CreatedByUserId is required to create workout. ");
        }

        if (isset($model->name) == false || $model->name === null) {
            $validationMessage->Add("name", "Name is required to create workout. ");
        }

        return $validationMessage;
    }

    /**
     * @return ValidationMessage
     */
    public static function ValidateCreateWorkoutExercise(WorkoutExercise $model)
    {
        $validationMessage = new ValidationMessage();
        if (isset($model) == false) {
            $validationMessage->Add("WorkoutExercise", "Posted data is undefined");
        }

        if (isset($model->workoutId) == false || self::IsInvalidId($model->workoutId)) {
            $validationMessage->Add("workoutId", "WorkoutId is required to add exercise to workout. ");
        }


        if (isset($model->exerciseId) == false || self::IsInvalidId($model->exerciseId)) {
            $validationMessage->Add("exerciseId", "ExerciseId is required to add exercise to workout. ");
        }

        return $validationMessage;
    }

    public function CreateWorkout(Workout $model)
    {
        $query = "INSERT INTO " . self::TABLE_WORKOUT . "
        SET
            createdByUserId = :createdByUserId,
            name = :name,
            type = :type,
            description = :description
            ";

        $stmt = self::$dbHandle->prepare($query);

        // Should be default date for server, client should be responsible for choosing timezone and format based on users location..
        // date_default_timezone_set('UTC');

        $exerciseId = $model->exercise->id;
        $stmt->bindParam(':createdByUserId', $model->createdByUserId);
        $stmt->bindParam(':name', $model->name);
        $stmt->bindParam(':type', $model->type);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function CreateWorkoutExercise(WorkoutExercise $model)
    {
        $query = "INSERT INTO " . self::TABLE_WORKOUT_EXERCISE . "
                SET
                workoutId = :workoutId
                exerciseId = :exerciseId
            ";

        $stmt = self::$dbHandle->prepare($query);
        $stmt->bindParam(':workoutId', $model->workoutId);
        $stmt->bindParam(':exerciseId', $model->exerciseId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function GetWorkoutById(string $id)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_WORKOUT . "
                    WHERE id = ?
                    LIMIT 0,1";

        $stmt = self::$dbHandle->prepare($query);

        $id = htmlspecialchars(strip_tags($id));

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::mapToWorkout($row);
    }

    public function GetWorkoutExercisesByWorkoutId(int $workoutId)
    {
        $query = "SELECT *
                    FROM " . self::TABLE_WORKOUT_EXERCISE . "
                    WHERE workoutId = :workoutId";

        $stmt = self::$dbHandle->prepare($query);

        $id = htmlspecialchars(strip_tags($workoutId));

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return self::mapToWorkouts($rows);
    }

    public function UpdateWorkout(Workout $model)
    {
        $query = "UPDATE " . self::TABLE_WORKOUT . "
            SET
                name = :name,
                type = :type,
                description = :description,
                modifiedByUserId = :modifiedByUserId 
            WHERE id = :id";

        $stmt = self::$dbHandle->prepare($query);

        $model = $this->sanitizeWorkout($model);

        $stmt->bindParam(':name', $model->name);
        $stmt->bindParam(':type', $model->type);
        $stmt->bindParam(':description', $model->description);

        $stmt->bindParam(':modifiedByUserId', $model->modifiedByUserId);
        $stmt->bindParam(':id', $model->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function DeleteWorkout(Workout $model)
    {
        $query = "DELETE
                    FROM " . self::TABLE_WORKOUT . "
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

    public function DeleteWorkoutExercise(WorkoutExercise $model)
    {
        $query = "DELETE
                    FROM " . self::TABLE_WORKOUT_EXERCISE . "
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

    private function sanitizeWorkout(Workout $model)
    {
        $model->name = htmlspecialchars(strip_tags($model->name));
        $model->type = htmlspecialchars(strip_tags($model->type));
        $model->description = htmlspecialchars(strip_tags($model->description));
        return $model;
    }
}
