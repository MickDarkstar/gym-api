<?php
class Entry
{
    /**
     * @param int id
     */
    public $id;
    /**
     * @param int created by user
     */
    public $createdByUserId;
    /**
     * @param DateTime date of performance
     */
    public $date;
    public $entryDetails;
    public $comment;

    public function __construct(
        $id = null, 
        int $createdByUserId, 
        $date,
        $entryDetails,
        $comment
        )
    {
        $this->id = $id;
        $this->createdByUserId = $createdByUserId;
        $this->date = $date;
        $this->entryDetails = $entryDetails;
        $this->comment = $comment;
    }
}
