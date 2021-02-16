<?php

namespace elzobrito\Config;

use elzobrito\ADatabase;

class Database extends ADatabase
{
    //put your code here

    public static function getDB($name = null)
    {
        $db = null;
        switch ($name) {
            case 'mysql':
                $db = [
                    'host' => '',
                    'port' => '',
                    'database' => '',
                    'user' => '',
                    'password' => '',
                ];
                break;
            default:
                $db = [
                    'host' => '',
                    'port' => '',
                    'database' => '',
                    'user' => '',
                    'password' => '',
                ];
                break;
        }
        return $db;
    }
}
