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
    public function __construct()
    { }

    protected static function Authorize()
    {
        $response = MiddleWare::Authorize();
        if ($response::$statusCode === 401) {
            echo Response::AccessDenied($response::$message . ". " . $response::$data);
            die;
        }
    }

    protected static function HttpRequestInput()
    {
        return json_decode(file_get_contents("php://input"));
    }
}
