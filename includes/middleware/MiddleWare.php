<?php
use \Firebase\JWT\JWT;

/**
 * MiddleWare for authorization with jwt-token
 * @version 1.0
 * @author Micke@tempory.org
 */
class MiddleWare
{
    public static function VerifyPassword($data, AppUser $user)
    {
        if (password_verify($data->password, $user->password)) {
            $token = array(
                "iss" => Config::Get('middleware')->iss,
                "aud" => Config::Get('middleware')->aud,
                "iat" => Config::Get('middleware')->iat,
                "nbf" => Config::Get('middleware')->nbf,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email
                )
            );
            $jwt = JWT::encode($token, Config::Get('middleware')->secretkey);
            return MiddleWareMessage::Get(
                200,
                "Access granted",
                array(
                    "jwt" => $jwt,
                    "expiresIn" => Config::Get('middleware')->nbf,
                    "user" => array(
                        "id" => $user->id,
                        "firstname" => $user->firstname,
                        "lastname" => $user->lastname,
                        "email" => $user->email
                    )
                )
            );
        } else {
            return MiddleWareMessage::Get(401, "Login failed. Wrong password");
        }
    }

    /**
     * If user is not authorized then a response is sent to client and code execution stops 
     */
    public static function Authorize()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization']) == false) {
            return MiddleWareMessage::Get(401, "Access denied. Auth-header not set");
            exit;
        }
        $token = $headers['Authorization'];
        if ($token) {
            try {
                $decodedToken = JWT::decode($token, Config::Get('middleware')->secretkey, array('HS256'));
                return MiddleWareMessage::Get(200, "Access granted", $decodedToken);
            } catch (Exception $e) {
                return MiddleWareMessage::Get(401, "Access denied. Invalid token", $e->getMessage());
            }
        } else {
            return MiddleWareMessage::Get(401, "Access denied. Token not set.");
        }
    }
}
