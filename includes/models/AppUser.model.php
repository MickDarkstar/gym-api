<?php

class AppUser
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    public function __construct($id, $firstname, $lastname, $email, $password = null)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }
}
