<?php
class ExerciseUpdateDTO
{
    public $id;
    public $muscleId;
    public $name;
    public $type;
    public $level;

    public function __construct(
        $id = null,
        $muscleId = null,
        $name,
        $type,
        $level
    ) {
        $this->id = $id;
        $this->muscleId = $muscleId;
        $this->name = $name;
        $this->type = $type;
        $this->level = $level;
    }
}
