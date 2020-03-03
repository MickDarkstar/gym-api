<?php
final class MuscleGRoup
{
    /**
     * @var number $id          Should contain a unique id
     */
    public $id;
    /**
     * @var string $name        Should contain a description
     */
    public $name;

    public function __construct($id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
