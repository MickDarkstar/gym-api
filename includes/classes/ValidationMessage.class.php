<?php

final class ValidationMessage
{
    private $invalid = array();

    public function Add($property, $message)
    {
        array_push($this->invalid, array($property => $message));
    }

    public function Ok()
    {
        return empty($this->invalid);
    }

    public function Invalid()
    {
        return $this->Ok() == false;
    }

    public function GetMessages()
    {
        return $this->invalid;
    }
}
