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

    public function Create()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();

        $exercise = Exercise::Create(
            parent::$currentUser,
            $data->muscleId,
            $data->name,
            $data->type,
            $data->level
        );
        $result = $this->service->Create($exercise);
        echo Response::Created("Exercise created", $result);
    }

    public function Update()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $exercise = $this->service->getById($data->id);
        if ($exercise === null) {
            echo Response::Warning("Exercise does not exist");
        } else {
            $exercise->Update(
                parent::$currentUser,
                $data->muscleId,
                $data->name,
                $data->type,
                $data->level
            );
            $result = $this->service->update($exercise);

            echo ($result) ? Response::Ok("Updated exercise info", $result) : Response::Ok("Could not update exercise info", $result);
        }
    }

    public function Delete()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $exercise = $this->service->getById($data->id);
        if ($exercise === null) {
            echo Response::Warning("Exercise does not exist");
        } else {
            $result = $this->service->delete($exercise);
            echo Response::Ok("Deleted exercise", $result);
        }
    }
}
