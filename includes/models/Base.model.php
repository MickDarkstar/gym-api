<?php
class Base
{
    /**
     * $param int
     */
    public $id;
    /**
     * $param int
     */
    public $createdByUserId;
    public $created;
    public $modifiedByUserId;
    public $modified;

    public function __construct(
        $id = null,
        int $createdByUserId
    ) {
        $this->id = $id;
        $this->createdByUserId = $createdByUserId;
    }
}
