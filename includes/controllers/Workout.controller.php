<?php
final class WorkoutController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new WorkoutRepository();
    }

    public static function New()
    {
        return new self;
    }

    public function All()
    {
        parent::Authorize();

        $result = $this->service->GetAll();
        echo ApiResponse::Ok("All workouts", $result);
    }

    public function Create()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();

        // if($data instanceof CreateWorkoutDTO) {

        // }

        $validation = $this->service->ValidateCreateWorkout($data);
        parent::HandleValidationErrors($validation);

        $workout = Workout::Create(
            parent::$currentUser,
            $data->name,
            $data->type,
            $data->description
        );

        $result = $this->service->CreateWorkout($workout);
        if ($result) {
            echo ApiResponse::Created("Workout created", $result);
        } else {
            echo ApiResponse::Warning("Could not create workout");
        }
    }

    public function Update()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->GetWorkoutById($data->id);
        if ($model === null) {
            echo ApiResponse::Warning("Workout does not exist");
        } else if ($model instanceof Workout) {
            $model->Update(
                parent::$currentUser,
                $data->name,
                $data->type,
                $data->description
            );
            $result = $this->service->UpdateWorkout($model);

            echo ($result) ? ApiResponse::Ok("Updated workout info", $result) : ApiResponse::Ok("Could not update workout info", $result);
        } else {
            echo ApiResponse::Warning("Something went wrong, workout could not be updated");
            die();
        }
    }

    public function Delete()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $workout = $this->service->GetWorkoutById($data->id);
        if ($workout === null) {
            echo ApiResponse::Warning("Workout does not exist");
            die();
        }
        $result = $this->service->DeleteWorkout($workout);
        if ($result === false) {
            echo ApiResponse::Warning("Could not delete workout, it may be in use");
            die();
        }
        echo ApiResponse::Ok("Deleted workout", $result);
    }


    public function AddWorkoutExercise()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();

        // if($data instanceof CreateWorkoutDTO) {

        // }

        $validation = $this->service->ValidateCreateWorkoutExercise($data);
        parent::HandleValidationErrors($validation);

        $model = WorkoutExercise::Create(
            $data->workoutId,
            $data->exerciseId
        );

        $result = $this->service->CreateWorkoutExercise($model);
        if ($result) {
            echo ApiResponse::Created("Exercise added", $result);
        } else {
            echo ApiResponse::Warning("Could not add exercise to workout with id:" + $model->workoutId);
        }
    }

    public function DeleteWorkoutExercise()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        if (isset($data) == false || isset($data->id) == false) {
            echo ApiResponse::Warning("Posted data is missing");
            die();
        }

        $result = $this->service->DeleteWorkoutExercise($data->id);
        if ($result === false) {
            echo ApiResponse::Warning("Could not delete exercise from workout, maybe it´s already deleted");
            die();
        }
        echo ApiResponse::Ok("Exercise removed from workout", $result);
    }
}
