<?php
class Router
{
    public static $validRoutes = array();

    public static function set($route, $function)
    {
        // Todo: $_SERVER['REQUEST_URI']
        // https://medium.com/the-andela-way/how-to-build-a-basic-server-side-routing-system-in-php-e52e613cf241
        self::$validRoutes[] = $route;
        // $url = explode('/',preg_replace('/^(\/)/','', $_SERVER['REQUEST_URI']));
        // var_dump($url);
        if ($_GET['url'] == $route) {
            $function->__invoke();
        }
    }
}
