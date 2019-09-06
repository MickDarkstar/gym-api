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
        echo Response::Ok("All exercises", $result);
    }

    public static function Create()
    {
        echo Response::Warning("NOT IMPLEMENTED");
        parent::Authorize();

        $data = parent::HttpRequestInput();

        // Redundant.... validate input instead and create exercise directly
        $model = new ExerciseCreateDTO(
            $data->muscleId,
            $data->name,
            $data->type,
            $data->level
        );
        $exercise = new Exercise(
            null,
            parent::$currentUser->id,
            $model->muscleId,
            $model->name,
            $model->type,
            $model->level
        );
        $result = $this->service->Create($exercise);
        echo Response::Created("Exercise created", $result);
    }

    public function Update()
    {
        echo Response::Warning("NOT IMPLEMENTED");

        parent::Authorize();

        $data = parent::HttpRequestInput();

        $model = new ExerciseUpdateDTO(
            $data->id,
            $data->muscleId,
            $data->name,
            $data->type,
            $data->level
        );
        $exercise = $this->service->getById($model->id);
        if ($exercise === null) {
            echo Response::Warning("Exercise does not exist");
        } else {
            $exercise->muscleId = $model->muscleId;
            $exercise->name     = $model->name;
            $exercise->type     = $model->type;
            $exercise->level    = $model->level;
            $result = $this->service->update($exercise);
            echo Response::Ok("Updated exercise info", $result);
        }
    }
}
