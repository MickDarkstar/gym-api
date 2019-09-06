<?php
class EntryDetail
{
    /**
     * @param int id
     */
    public $id;
    /**
     * @param int entry id
     */
    public $entryId;

    /**
     * @param int created by user
     */
    public $createdByUserId;
    
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
        int $createdByUserId,
        Exercise $exercise,
        $weight,
        $reps,
        $rest,
        $sets,
        $date = null,
        $comment
        )
    {
        $this->id = $id;
        $this->entryId = $entryId;
        $this->createdByUserId = $createdByUserId;
        $this->exercise = $exercise;
        $this->weight = $weight;
        $this->reps = $reps;
        $this->rest = $rest;
        $this->sets = $sets;
        $this->date = $date;
        $this->comment = $comment;
    }
}
