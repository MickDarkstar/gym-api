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
                echo ApiResponse::Ok($response::$message, $response::$data);
            } else {
                echo ApiResponse::AccessDenied($response::$message);
            }
        } else {
            echo ApiResponse::AccessDenied("Unsuccessful login: E-mail does not exist");
        }
    }

    public function AllUsers()
    {
        parent::Authorize();

        $result = $this->service->all();
        echo ApiResponse::Ok("All users", $result);
    }

    public function NewUser()
    {
        $data = parent::HttpRequestInput();

        $user = new AppUserCreate(
            $data->firstname,
            $data->lastname,
            $data->email,
            $data->password
        );
        if ($this->service->emailExists($user->email)) {
            echo ApiResponse::Warning("E-mail is already in use");
        } else {
            $result = $this->service->create($user);
            if ($result instanceof AppUser == false) {
                echo ApiResponse::InternalServerError("Could not create user, pls try again or contact support");
                exit();
            }
            echo ApiResponse::Created("Profile created", $result);
        }
    }

    public function UpdateUserinfo()
    {
        parent::Authorize();

        $data = parent::HttpRequestInput();
        $user = new AppUserUpdate(
            $data->id,
            $data->firstname,
            $data->lastname,
            $data->email
        );
        $foundUser = $this->service->find($user->id);
        if ($this->service->emailExists($user->email) && $user->email !== $foundUser->email) {
            echo ApiResponse::Warning("E-mail is already in use");
        } else {
            $result = $this->service->update($user);
            echo ApiResponse::Ok("Updated profile info", $result);
        }
    }
}
