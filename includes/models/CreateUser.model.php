<?php

final class CreateUser
{
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    public function __construct($firstname, $lastname, $email, $password = null)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }
}
