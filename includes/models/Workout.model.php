<?php
final class Workout extends Base
{
    /**
     * @var int id
     */
    public $id;
    /**
     * @var string name Required
     */
    public $name;
    /**
     * @var string type
     */
    public $type;
    /**
     * @var string description
     */
    public $description;
    /**
     * @var Exercises[] by relation table WorkoutExercise
     */
    public $exercises;

    public function __construct(
        $id = null,
        $name,
        $type,
        $description,
        $exercises = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->exercises = $exercises;
    }

    public static function Create(
        AppUser $user,
        $name,
        $type = "",
        $description = ""
    ) {
        $self = new self(
            null,
            $type,
            $description
        );
        $self->setCreatedByUser($user);
        return $self;
    }

    public function Update(
        AppUser $user,
        $name,
        $type = "",
        $description = ""
    ) {
        $this->name   = $name;
        $this->type   = $type;
        $this->description     = $description;
        $this->setModifiedByUser($user);
    }
}
