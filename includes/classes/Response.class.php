<?php
//** Used as standard-response for all requests */
class Response
{
    public static function OK($message = null, $data = null)
    {
        self::Status(200);
        return self::Return($message, $data);
    }

    public static function Created($message = null, $data)
    {
        self::Status(201);
        return self::Return($message, $data);
    }

    public static function Warning($message = "Invalid data")
    {
        self::Status(400);
        return self::Return($message);
    }

    public static function AccessDenied($message = "Access denied.")
    {
        self::Status(401);
        return self::Return($message);
    }

    public static function MethodNotAllowed($message = "Method is not allowed")
    {
        self::Status(405);
        return self::Return($message);
    }

    public static function InternalServerError($message = null)
    {
        self::Status(500);
        return self::Return($message);
    }

    private static function Status($code)
    {
        http_response_code($code);
    }

    private static function Return($message, $data = null)
    {
        $array = array(
            "message" => $message,
            "data" => $data
        );

        return json_encode(
            $array
        );
    }
}
