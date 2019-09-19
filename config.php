<?php
class Config
{
    private static $mode = 'live';
    private static $loadedConfigs = array();
    private static $configLocation = 'config/';
    private static $configConfiged = false;

    static function GetBaseLine()
    {
        if (!self::$configConfiged) {
            self::$mode = (getenv('mode'))
                ? getenv('mode')
                : self::$mode;

            self::$configLocation = (getenv('configLocation'))
                ? getenv('configLocation')
                : self::$configLocation;

            self::$configConfiged = true;
        }
    }

    static function Get($config)
    {
        self::getBaseLine();
        if (!isset(self::$loadedConfigs[$config])) {
            $filepath = self::$configLocation . self::$mode . '/' . $config . '.json';
            if (!file_exists($filepath)) {
                $filepath = self::$configLocation . $config . '.json';
                if (!file_exists($filepath)) {
                    die('Error, trying to load unknown configuration: ' . $config);
                }
            }

            self::$loadedConfigs[$config] = json_decode(file_get_contents($filepath));
        }
        return self::$loadedConfigs[$config];
    }
}
