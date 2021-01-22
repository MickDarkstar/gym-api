<?php

/**
 * ExerciseService
 *
 * @version 2.0
 * @author Mick
 */
final class ExerciseService
{
    private $repository;

    /**
     * @param ExerciseRepository $repository
     */
    function __construct(ExerciseRepository $repository = null)
    {
        if ($repository == null)
            $repository = new ExerciseRepository();

        $this->repository = $repository;
    }

    /**
     * @return ValidationMessage
     */
    public static function validateExerciseData($data)
    {
        $validationMessage = new ValidationMessage();
        if (isset($data->name) === false) {
            $validationMessage->Add("Exercise", "Name is required to create Exercise. ");
        }

        return $validationMessage;
    }

    public function exerciseExist(int $id)
    {
        return $this->repository->exerciseExist($id);
    }

    /**
     * @return Exercise
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function muscleExists(int $muscleId)
    {
        return $this->repository->muscleExists($muscleId);
    }

    public function create(Exercise $exercise)
    {
        $exercise = $this->sanitize($exercise);
        return $this->repository->create($exercise);
    }

    public function update(Exercise $exercise)
    {
        $exercise = $this->sanitize($exercise);
        return $this->repository->update($exercise);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function delete(Exercise $exercise)
    {
        return $this->repository->delete($exercise);
    }

    private function sanitize(Exercise $exercise)
    {
        $exercise->createdByUserId = htmlspecialchars(strip_tags($exercise->createdByUserId));
        $exercise->modifiedByUserId = htmlspecialchars(strip_tags($exercise->modifiedByUserId));
        $exercise->name = htmlspecialchars(strip_tags($exercise->name));
        $exercise->type = htmlspecialchars(strip_tags($exercise->type));
        $exercise->muscleId = htmlspecialchars(strip_tags($exercise->muscleId));
        $exercise->level = htmlspecialchars(strip_tags($exercise->level));
        return $exercise;
    }
}
