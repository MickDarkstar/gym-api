<?php
final class WorkoutExercise
{
    /**
     * @var int id
     */
    public $id;
    public $workoutId;
    public $exercise;

    public function __construct(
        $id,
        $workoutId,
        $exercise
    ) {
        $this->id = $id;
        $this->workoutId = $workoutId;
        $this->exercise = $exercise;
    }

    public static function Create(
        Workout $workout,
        $exerciseId
    ) {
        $self = new self(
            null,
            $workout->id,
            $exerciseId
        );
        return $self;
    }
}
