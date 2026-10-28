<?php

namespace Core;

use Error;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use mysqli_stmt;

class Database {
    private static ?mysqli $db = null;
    const DB_CREDENTIALS_PATH = __DIR__ . '/../config/database.php';

    public static function getDb(): mysqli{
        return static::$db;
    }

    public static function conectarDb()
    {
        $configs = require Database::DB_CREDENTIALS_PATH;
        //Programming Oriented Object connection way.
        try {
            $successConnection = new \mysqli(
                $configs['host'], 
                $configs['username'],
                $configs['password'],
                $configs['database'],
            );
            static::$db = $successConnection;
        } catch (mysqli_sql_exception $ex) {
            $errorMessage = $ex->getMessage();
            include __DIR__.'/../includes/templates/error.php';
            exit;
        }
    }

    public static function rollback(){
        if(static::$db === null) return false;

        return static::$db->rollback();
    }

    public static function beginTransaction(): bool{
        return self::$db->begin_transaction();
    }

    public static function prepare(string $query, array $paramInfo): mysqli_stmt | false{
        $stmt = self::$db->prepare($query);
        $result = self::bindParams($stmt, $paramInfo);

        if(!$result) return false;
        return $stmt;
    }

    public static function bindParams(mysqli_stmt $stmt, array $paramInfo){
        [$types, $variables] = $paramInfo;
        if(!is_array($variables)) throw new Error("Can't bind params since array index 1 does not contains a array with values to bind.");
        if(count($variables) != strlen($types)) throw new Error("Can't bind since types string in array index 0 does not match with every value passed in array index 1.");

        $params = [];

        foreach ($variables as $index => &$value) {
            $params[] = &$value;
        }

        return $stmt->bind_param($types, ...$params);
    }

    public static function commit() {
        return self::$db->commit();
    }
    public static function execute(mysqli_stmt $stmt): mysqli_result | bool {
        $result = $stmt->execute();

        if(!$result) return false;
        
        return ($stmt->field_count > 0)? $stmt->get_result(): true;
    }
}