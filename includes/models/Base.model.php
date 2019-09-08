<?php
class Base
{
    /**
     * @var int createdByUserId
     */
    public $createdByUserId;
    /**
     * @var DateTime date of creation 
     */
    public $created;
    /**
     * @var int modifiedByUserId
     */
    public $modifiedByUserId;
    /**
     * @var DateTime date of creation 
     */
    public $modified;

    public function setCreatedByUser(AppUser $user)
    {
        $this->createdByUserId = $user->id;
    }

    public function setModifiedByUser(AppUser $user)
    {
        $this->modifiedByUserId = $user->id;
    }

    public function setCreatedByUserId(int $userId)
    {
        $this->createdByUserId = $userId;
    }

    public function setModifiedByUserId($userId = null)
    {
        $this->modifiedByUserId = $userId;
    }
}
