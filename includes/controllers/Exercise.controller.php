<?php
final class ExerciseController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ExerciseService();
    }

    public static function New()
    {
        return new self;
    }

    public function All()
    {
        parent::Authorize();

        $result = $this->service->getAll();
        echo ApiResponse::Ok("All exercises", $result);
    }

    public function Create()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();

        $validation = $this->service->validateExerciseData($data);
        parent::HandleValidationErrors($validation);

        $exercise = Exercise::Create(
            parent::$currentUser,
            $data->muscleId,
            $data->name,
            $data->type,
            $data->level
        );

        $result = $this->service->Create($exercise);
        if ($result) {
            echo ApiResponse::Created("Exercise created", $result);
        } else {
            echo ApiResponse::Warning("Could not create exercise");
        }
    }

    public function Update()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $model = $this->service->getById($data->id);
        if ($model === null) {
            echo ApiResponse::Warning("Exercise does not exist");
        } else if ($model instanceof Exercise) {
            $model->Update(
                parent::$currentUser,
                $data->muscleId,
                $data->name,
                $data->type,
                $data->level
            );
            $result = $this->service->update($model);

            echo ($result) ? ApiResponse::Ok("Updated exercise info", $result) : ApiResponse::Ok("Could not update exercise info", $result);
        } else {
            echo ApiResponse::Warning("Something went wrong, exercise could not be updated");
            die();
        }
    }

    public function Delete()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $exercise = $this->service->getById($data->id);
        if ($exercise === null) {
            echo ApiResponse::Warning("Exercise does not exist");
            die();
        }
        $result = $this->service->delete($exercise);
        if ($result === false) {
            echo ApiResponse::Warning("Could not delete exercise, it may be in use");
            die();
        }
        echo ApiResponse::Ok("Deleted exercise", $result);
    }
}
