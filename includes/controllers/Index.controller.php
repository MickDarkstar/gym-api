<?php

/**
 * Example controller
 */
final class IndexController extends BaseController
{
    public static function Home()
    {
        $currentUser = parent::Authorize();
        // Example, parent::Authorize returns authorized user and you can also use parent::$currentUser after user has been authorized to get the instance of AppUser
        $firstname = parent::$currentUser->firstname;
        $lastname = $currentUser->lastname;

        echo ApiResponse::OK("Welcome, " . $firstname . " " . $lastname);
    }
}
