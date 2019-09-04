<?php
final class UserController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
    }

    public static function New()
    {
        return new self;
    }

    public function Login()
    {
        $data = parent::HttpRequestInput();

        $emailExists = $this->service->emailExists($data->email);
        if ($emailExists) {
            $user = $this->service->getByEmail($data->email);
            $response = MiddleWare::VerifyPassword($data, $user);
            if ($response::$statusCode === 200) {
                echo Response::Ok($response::$message, $response::$data);
            } else {
                echo Response::AccessDenied($response::$message);
            }
        } else {
            echo Response::AccessDenied("Unsuccessful login: E-mail does not exist");
        }
    }

    public function AllUsers()
    {
        parent::Authorize();

        $result = $this->service->all();
        echo Response::Ok("All users", $result);
    }

    public function NewUser()
    {
        $data = parent::HttpRequestInput();

        $user = new CreateUser(
            $data->firstname,
            $data->lastname,
            $data->email,
            $data->password
        );
        if ($this->service->emailExists($user->email)) {
            echo Response::Warning("E-mail is already in use");
        } else {
            $result = $this->service->create($user);
            echo Response::Created("Profile created", $result);
        }
    }

    public function UpdateUserinfo()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $user = new UpdateUserinfo(
            $data->id,
            $data->firstname,
            $data->lastname,
            $data->email
        );
        $foundUser = $this->service->find($user->id);
        if ($this->service->emailExists($user->email) && $user->email !== $foundUser->email) {
            echo Response::Warning("E-mail is already in use");
        } else {
            $result = $this->service->update($user);
            echo Response::Ok("Updated profile info", $result);
        }
    }
}
