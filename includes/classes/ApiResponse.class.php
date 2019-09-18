
<?php
//** Used as standard-response for all requests */
final class ApiResponse
{
    public $message;
    public $data;
    
    private function __construct($message = null, $data = null) {
        $this->message = $message;
        $this->data = $data;
    }

    public static function OK($message = null, $data = null)
    {
        self::Status(200);
        return self::ReturnEncodedApiResponse($message, $data);
    }

    public static function Created($message = null, $data)
    {
        self::Status(201);
        return self::ReturnEncodedApiResponse($message, $data);
    }

    public static function Warning($message = "Invalid data")
    {
        self::Status(400);
        return self::ReturnEncodedApiResponse($message);
    }

    public static function AccessDenied($message = "Access denied.")
    {
        self::Status(401);
        return self::ReturnEncodedApiResponse($message);
    }

    public static function MethodNotAllowed($message = "Method is not allowed")
    {
        self::Status(405);
        return self::ReturnEncodedApiResponse($message);
    }

    public static function InternalServerError($message = null)
    {
        self::Status(500);
        return self::ReturnEncodedApiResponse($message);
    }

    private static function Status($code)
    {
        http_response_code($code);
    }

    private static function ReturnEncodedApiResponse($message, $data = null)
    {
        $response = new self($message, $data);
        return json_encode(
            $response
        );
    }
}
