<?php

class Database {
    public static function connect() {
        return new PDO(
            "mysql:host=localhost;dbname=propertyDB;charset=utf8",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}