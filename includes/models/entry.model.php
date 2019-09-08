<?php
final class Entry extends Base
{
    public $id;
    /**
     * @param DateTime date of performance
     */
    public $date;
    public $entryDetails;
    public $comment;

    public function __construct(
        $id = null,
        $date,
        $entryDetails,
        $comment
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->entryDetails = $entryDetails;
        $this->comment = $comment;
    }
}
