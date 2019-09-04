<?php
final class IndexController extends BaseController
{
    public static function Home()
    {
        parent::Authorize();
        echo Response::OK("This is home");
    }
}
