
<?php
//** Used as a generic api response for all requests */
final class ApiResponse
{
    public $message;
    public $data;
    public $severity;
    public $status;

    private function __construct($message = null, $data = null, $severity = null, $status = null)
    {
        $this->message = $message;
        $this->data = $data;
        $this->severity = $severity;
        $this->status = $status;
    }

    public static function OK($message = null, $data = null)
    {
        self::Status(200);
        return self::ReturnEncodedApiResponse($message, $data, null, ApiResponseConstants::STATUS_SUCCESS);
    }

    public static function Created($message = null, $data)
    {
        self::Status(201);
        return self::ReturnEncodedApiResponse($message, $data, null, ApiResponseConstants::STATUS_SUCCESS);
    }

    public static function Warning($message = "Invalid data", $data = null, $status = null)
    {
        self::Status(400);
        return self::ReturnEncodedApiResponse($message, $data, ApiResponseConstants::SEVERITY_WARNING, $status);
    }

    public static function AccessDenied($message = "Access denied.")
    {
        self::Status(401);
        return self::ReturnEncodedApiResponse($message, null, ApiResponseConstants::SEVERITY_ERROR, ApiResponseConstants::STATUS_FAILED);
    }

    public static function MethodNotAllowed($message = "Method is not allowed")
    {
        self::Status(405);
        return self::ReturnEncodedApiResponse($message, null, ApiResponseConstants::SEVERITY_ERROR, ApiResponseConstants::STATUS_FAILED);
    }

    public static function InternalServerError($message = "Internal server error")
    {
        self::Status(500);
        return self::ReturnEncodedApiResponse($message, null, ApiResponseConstants::SEVERITY_ERROR, ApiResponseConstants::STATUS_FAILED);
    }

    private static function Status($code)
    {
        http_response_code($code);
    }

    private static function ReturnEncodedApiResponse($message, $data = null, $severity = ApiResponseConstants::SEVERITY_INFO, $status = null)
    {
        $response = new self($message, $data, $severity, $status);
        return json_encode(
            $response
        );
    }
}
