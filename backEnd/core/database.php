<?php

namespace Core;

class Database {
    
    private static $instance = null;
    private $conn;

    private $host = DB["host"];
    private $name = DB["db_name"];   
    private $user = DB["username"];
    private $pass = DB["password"];
    
    
    
    private function __construct() {
        try {
            $this->conn = new \PDO("mysql:host=$this->host;
                dbname=$this->name", $this->user, $this->pass
            );
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

  
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Database();
        }
   
        return self::$instance;
    }
  
    public function getConnection() {
        return $this->conn;
    }

}
?>