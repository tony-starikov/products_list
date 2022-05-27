<?php

class Database
{
    private static $_instance = null;

    public static function getConnection()
    {
        if (self::$_instance) {
            return self::$_instance;
        } else {
            $paramsPath = ROOT . '/config/database.php';
            $parameters =  require_once $paramsPath;

            $db = null;

            try {
                $db = new PDO('mysql:host=' . $parameters['host'] . ';dbname=' . $parameters['db_name'], $parameters['user'], $parameters['password']);
            } catch (PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }

            self::$_instance = $db;
        }

        return self::$_instance;
    }
}