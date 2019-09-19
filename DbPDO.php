<?php

/**
 * DbPDO extension of PDO
 * Class for db connection
 * @version 2.0
 * @author Mick
 */
class DbPDO extends PDO
{
    private static $_instance;
    protected $_dsn;
    protected $_password;
    protected $_username;

    public function __construct()
    {
        Config::GetBaseLine();
        if (!self::$_instance) {
            try {
                $this->_dsn      = Config::Get('database')->dsn . "dbname=" .  Config::Get('database')->dbname . ";";
                $this->_username = Config::Get('database')->username;
                $this->_password = Config::Get('database')->password;

                self::$_instance = parent::__construct(
                    $this->_dsn,
                    $this->_username,
                    $this->_password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        return self::$_instance;
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
            self::$_instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$_instance;
    }
}
