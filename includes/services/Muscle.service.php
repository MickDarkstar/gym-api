<?php

/**
 * MuscleService
 *
 * @version 1.0
 * @author Mick
 */
final class MuscleService
{
    private $repository;

    /**
     * @param MuscleRepository $repository
     */
    function __construct(MuscleRepository $repository = null)
    {
        if ($repository == null)
            $repository = new MuscleRepository();

        $this->repository = $repository;
    }

    /**
     * @return ValidationMessage
     */
    public static function validateMuscleData($data)
    {
        $validationMessage = new ValidationMessage();
        if (isset($data->name) === false) {
            $validationMessage->Add("Muscle", "Name is required to create Muscle. ");
        }

        return $validationMessage;
    }

    public function getById($id)
    {
        $id = htmlspecialchars(strip_tags($id));
        if ($id > 0 === false || $id === "") {
            $validation = new ValidationMessage();
            $validation->invalid("Muscle", "Invalid id");
            return $validation;
        }
        return $this->repository->getById($id);
    }

    /**
     * Not implemented
     */
    public function muscleExists(int $muscleId)
    {
        return $this->repository->muscleExists($muscleId);
    }

    public function create(Muscle $model)
    {
        $model = $this->sanitize($model);
        return $this->repository->create($model);
    }

    public function update(Muscle $model)
    {
        $model = $this->sanitize($model);
        return $this->repository->update($model);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function delete(Muscle $model)
    {
        return $this->repository->delete($model);
    }

    private function sanitize(Muscle $model)
    {
        $model->muscleGroup = htmlspecialchars(strip_tags($model->muscleGroup));
        $model->name = htmlspecialchars(strip_tags($model->name));
        return $model;
    }
}
