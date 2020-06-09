<?php
class User
{
    private $conn;

    public $userName;
    public $created_at;
    private $table='User';

    public $page;
    public $recordsPerPage;


    public function __construct($db){
        $this->conn=$db;
    }
    public function  createUser(){

       try
       {
        $query='INSERT INTO ' .$this->table.  ' (userName) VALUES (:userName)';
        $stmt= $this->conn->prepare($query);
        $this->userName=htmlspecialchars(strip_tags($this->userName));
        if($stmt->execute(['userName'=> $this->userName]))
            return true;
        }
       catch(PDOException $e){
        echo 'Could not create user :  '.$e->getMessage();
        return false;
        }
        
     }

    public function readSingleUser(){

        $query='SELECT * from User where UPPER(userName) = UPPER(:userName)';
        $stmt= $this->conn->prepare($query);
        $stmt->execute(['userName'=> $this->userName]);
         return $stmt;
      
    }
     public function readAllUsers(){
        $fromRecordNum = ($this->recordsPerPage * $this->page) - $this->recordsPerPage;

        $query='SELECT * from '. $this->table . ' LIMIT ' .$fromRecordNum .',' .$this->recordsPerPage;
        $stmt= $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
        
    }
 

}




?> 