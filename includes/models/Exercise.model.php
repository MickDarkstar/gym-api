<?php
class Exercise extends Base
{
    public $muscleId;
    public $name;
    public $type;
    public $level;

    public function __construct(
        $id = null,
        int $createdByUserId,
        $muscleId = null,
        $name,
        $type,
        $level
    ) {
        $this->id = $id;
        $this->createdByUserId = $createdByUserId;
        $this->muscleId = $muscleId;
        $this->name = $name;
        $this->type = $type;
        $this->level = $level;
    }
}
