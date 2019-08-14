<?php

namespace Core\Models;

class Todolist 
{
    private $conn;
    private $table_name = "todolist";
    public $user_id;
    public $list_content;
    
    public function __construct($db) 
    {
        $this->conn = $db;
    }
    
    /**
     * Insere os valores do objeto(this) no banco
     * @return boolean
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id = :user_id, content = :list_content";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':list_content', $this->list_content);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Verifica se existe uma lista cadastrada no banco para este usuario
     * @return boolean
     */
    public function listExists() 
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        $num = $stmt->rowCount();
        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($num > 0) {
            return true;
        }
        return false;        
    }
    
    /**
     * Retrieves The user list and set in the object
     * @return boolean
     */
    public function getList() 
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        $num = $stmt->rowCount();        
        if ($num > 0) {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $this->list_content = $row['content'];
            // return true because email exists in the database
            return true;
        }
        return false;        
    }    
    
    public function update() 
    {
        $query = "UPDATE " . $this->table_name . " SET content = :list_content WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':list_content', $this->list_content);
        $stmt->bindParam(':user_id', $this->user_id);        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
}
