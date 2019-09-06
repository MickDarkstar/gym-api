<?php
class ExerciseCreateDTO
{
    public $muscleId;
    public $name;
    public $type;
    public $level;

    public function __construct(
        $muscleId = null,
        $name,
        $type,
        $level
    ) {
        $this->muscleId = $muscleId;
        $this->name = $name;
        $this->type = $type;
        $this->level = $level;
    }
}
