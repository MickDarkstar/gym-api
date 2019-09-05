<?php

/**
 * Base short summary.
 *
 * Base class for controllers.
 * Unused as extending classes only has static members
 *
 * @version 1.0
 * @author Micke@tempory.org
 */
class BaseController
{
    protected static $currentUser;
    public function __construct()
    { }

    protected static function Authorize()
    {
        $response = MiddleWare::Authorize();
        if ($response::$statusCode === 401) {
            echo Response::AccessDenied($response::$message . ". " . $response::$data);
            die;
        } else {
            self::$currentUser = new AppUser(
                $response::$data->data->id,
                $response::$data->data->firstname,
                $response::$data->data->lastname,
                $response::$data->data->email
            );
            return self::$currentUser;
        }
    }

    protected static function HttpRequestInput()
    {
        return json_decode(file_get_contents("php://input"));
    }
}
