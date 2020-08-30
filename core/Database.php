<?php

namespace core;

use \src\Config;

class Database
{
    /** @var [type] */
    private static $_pdo;
    
    /**
     *
     * @return void
     */
    public static function getInstance(): \PDO
    {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(Config::DB_DRIVER . ":dbname=" . Config::DB_DATABASE . ";host=" . Config::DB_HOST, Config::DB_USER, Config::DB_PASS);
        }
        return self::$_pdo;
    }

    /**
     * Connect construct
     */
    private function __construct()
    {
    }

    /**
     * Connect clone
     */
    private function __clone()
    {
    }

    /**
     * Connect wakeup
     */
    private function __wakeup()
    {
    }
}
