<?php

class ValidationMessage
{
    public $invalid = array();

    public function invalid($property, $message)
    {
        array_push($this->invalid, array($property => $message));
    }

    public function ok()
    {
        return empty($this->invalid);
    }

    public function getMessages()
    {
        return $this->invalid;
    }
}
