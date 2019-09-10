<?php
// Todo: break down to separate create, update-models
final class EntryDetail extends Base
{
    /**
     * @var int id
     */
    public $id;
    /**
     * @var int entry id
     */
    public $entryId;
    /**
     * @param Exercise exercise id
     */
    public $exercise;
    public $weight;
    public $reps;
    public $rest;
    public $sets;
    public $date;
    public $comment;

    public function __construct(
        $id = null,
        int $entryId,
        Exercise $exercise,
        $weight,
        $reps,
        $rest,
        $sets,
        $date = null,
        $comment
    ) {
        $this->id = $id;
        $this->entryId = $entryId;
        $this->exercise = $exercise;
        $this->weight = $weight;
        $this->reps = $reps;
        $this->rest = $rest;
        $this->sets = $sets;
        $this->date = $date;
        $this->comment = $comment;
    }

    public static function Create(
        int $entryId,
        Exercise $exercise,
        $weight,
        $reps,
        $rest,
        $sets,
        $date = null,
        $comment
    ) {
        $self = new self(
            null,
            $entryId,
            $exercise,
            $weight,
            $reps,
            $rest,
            $sets,
            $date,
            $comment
        );
        return $self;
    }

    public function Update(
        Exercise $exercise,
        $weight,
        $reps,
        $rest,
        $sets,
        $date = null,
        $comment
    ) {
        $this->exercise = $exercise;
        $this->weight   = $weight;
        $this->reps     = $reps;
        $this->rest     = $rest;
        $this->sets     = $sets;
        $this->date     = $date;
        $this->comment  = $comment;
    }
}
