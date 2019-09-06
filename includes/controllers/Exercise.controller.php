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
        echo Response::Ok("All exercices", $result);
    }

    public function Create()
    {
        $data = parent::HttpRequestInput();

        $model = new Exercise(
            $data->firstname,
            $data->lastname,
            $data->email,
            $data->password
        );
        $result = $this->service->Create($model);
        echo Response::Created("Profile created", $result);
    }

    public function Update()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        
        $model = new Exercise(
            $data->id,
            $data->firstname,
            $data->lastname,
            $data->email
        );
        $exists = $this->service->exerciseExist($model->id);
        if ($exists === false) {
            echo Response::Warning("Exercise does not exist");
        } else {
            $result = $this->service->update($model);
            echo Response::Ok("Updated exercise info", $result);
        }
    }
}
