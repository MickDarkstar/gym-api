<?php

/**
 * Base short summary.
 *
 * Base class for controllers.
 *
 * @version 1.0
 * @author Micke@tempory.org
 */
class BaseController
{
    /**
     *
     * @var AppUser
     */
    protected static $currentUser;
    public function __construct()
    { }

    protected static function HandleValidationErrors($serviceResponse)
    {
        if ($serviceResponse instanceof ValidationMessage) {
            if ($serviceResponse->Invalid()) {
                echo ApiResponse::InternalServerError($serviceResponse->GetMessages());
                die();
            }
        }
    }

    protected static function Authorize()
    {
        $response = MiddleWare::Authorize();
        if ($response::$statusCode === 401) {
            echo ApiResponse::AccessDenied($response::$message . ". " . $response::$data);
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
