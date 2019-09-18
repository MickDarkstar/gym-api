<?php
final class IndexController extends BaseController
{
    public static function Home()
    {
        $currentUser = parent::Authorize();
        // Example, use parents or returned user
        echo ApiResponse::OK("This is home for: " . parent::$currentUser->firstname . " " . $currentUser->lastname);
    }
}
