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


    public function validate(Exercise $exercise)
    {
        // Todo: return ServiceMessage
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
        return $this->repository->exerciseExist($id);
    }

    /**
     * Not implemented
     */
    public function muscleExists(int $muscleId)
    {
        return $this->repository->muscleExists($muscleId);
    }

    public function create(Exercise $exercise)
    {
        $exercise = $this->sanitize($exercise);
        return $this->repository->create($exercise);
    }

    public function getById(string $id)
    {
        $id = htmlspecialchars(strip_tags($id));
        if($id > 0 === false || $id === "") {
            $validation = new ValidationMessage();
            $validation->invalid("ExerciseId", "Id of exercise must be a number");
            return $validation;
        }
        return $this->repository->getById($id);
    }

    public function update(Exercise $exercise)
    {
        // Todo: Validate updated fields
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
