<?php

namespace App;

class Connection {

    public static function getDB() 
    {
        try {
            //code...
            $conn = new \PDO(
                "mysql:host=localhost;dbname=twitter_clone;charset=utf8",
                "root",
                ""
            );

            return $conn;

        } catch (\PDOExceptino $e) {
            //.. tratar de alguma forma
            
        }
    }


}

?>