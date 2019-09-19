<?php
include_once 'MiddleWare.settings.php';

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
                "iss" => ISS,
                "aud" => AUD,
                "iat" => IAT,
                "nbf" => NBF,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email
                )
            );
            $jwt = JWT::encode($token, SECRET_KEY);
            return MiddleWareMessage::Get(
                200,
                "Access granted",
                array(
                    "jwt" => $jwt,
                    "expiresIn" => NBF,
                    "user" => array(
                        "id" => $user->id,
                        "firstname" => $user->firstname,
                        "lastname" => $user->lastname,
                        "email" => $user->email
                    )
                )
            );
        } else {
            return MiddleWareMessage::Get(401, "Login failed. Wrong password", "datta");
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
        $TOKEN = $headers['Authorization'];
        if ($TOKEN) {
            try {
                $token = JWT::decode($TOKEN, SECRET_KEY, array('HS256'));
                return MiddleWareMessage::Get(200, "Access granted", $token);
            } catch (Exception $e) {
                return MiddleWareMessage::Get(401, "Access denied. Invalid token", $e->getMessage());
            }
        } else {
            return MiddleWareMessage::Get(401, "Access denied. Token not set.");
        }
    }
}
