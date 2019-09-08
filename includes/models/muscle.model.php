<?php
final class Muscle
{
    /**
     * @var number $id          Should contain a unique id
     */
    public $id;
    /**
     * @var string $name        Should contain a description
     */
    public $name;
    /**
     * @var string $muscleGroup Should contain a MuscleGroup
     */
    public $muscleGroup;

    public function __construct($id, string $name, MuscleGroup $muscleGroup)
    {
        $this->id = $id;
        $this->name = $name;
        $this->muscleGroup = $muscleGroup;
    }
}

