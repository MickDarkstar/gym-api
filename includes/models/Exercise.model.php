<?php
final class Exercise extends Base
{
    /**
     * @var int id
     */
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

    public static function Create(
        AppUser $creatingUser,
        $muscleId,
        $name,
        $type,
        $level
    ) {
        $self = new self(
            null,
            $muscleId,
            $name,
            $type,
            $name,
            $level
        );
        $self->setCreatedByUser($creatingUser);
        return $self;
    }

    public function Update(
        AppUser $updatingUser,
        $muscleId,
        $name,
        $type,
        $level
    ) {
        $this->muscleId         = $muscleId;
        $this->name             = $name;
        $this->type             = $type;
        $this->level            = $level;
        $this->setModifiedByUser($updatingUser);
    }
}
