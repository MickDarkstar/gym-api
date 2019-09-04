<?php
//** Used as standard-response for all middlware requests */
class MiddleWareMessage
{
    public static $statusCode;
    public static $message;
    public static $data;

    private function __construct($statusCode, $message, $data)
    {
        self::$statusCode = $statusCode;
        self::$message = $message;
        self::$data = $data;
    }

    public static function Get($statusCode, $message = "Login failed. Wrong password", $data = null)
    {
        return new self($statusCode, $message, $data);
    }
}
