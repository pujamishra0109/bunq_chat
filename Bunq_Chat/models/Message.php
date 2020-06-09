<?php

class Message{
    
    private $conn;
    private $table='Messages';

    public $id;
    public $senderId;
    public $receiverId;
    public $message_body;
    public $created_at;

    public $page;
    public $recordsPerPage;

    public function __construct($db){
        $this->conn=$db;
    }

    public function readAllMessages(){

        
        
     
        // calculate for the query LIMIT clause
        $fromRecordNum = ($this->recordsPerPage * $this->page) - $this->recordsPerPage;

        $query='SELECT * from '. $this->table. ' LIMIT ' .$fromRecordNum .',' .$this->recordsPerPage;
        $stmt= $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readMessageById(){
        $fromRecordNum = ($this->recordsPerPage * $this->page) - $this->recordsPerPage;

       $query='SELECT * from '. $this->table. ' where UPPER(receiverId) = UPPER(:receiverId) ORDER by senderId, created_at DESC LIMIT ' .$fromRecordNum .',' .$this->recordsPerPage;
       $stmt= $this->conn->prepare($query);
       $stmt->execute(['receiverId' => $this->receiverId]);
       return $stmt;
     }

     public function readMessageBySenderId()
     {
        $fromRecordNum = ($this->recordsPerPage * $this->page) - $this->recordsPerPage;

        $query='SELECT * from '. $this->table. ' where UPPER(senderId) = UPPER(:senderId) ORDER by senderId, created_at DESC LIMIT ' .$fromRecordNum .',' .$this->recordsPerPage;
        $stmt= $this->conn->prepare($query);
        $stmt->execute(['senderId' => $this->senderId]);
        return $stmt;
      }
 
     public function readMessageByUsers()
     {
        $fromRecordNum = ($this->recordsPerPage * $this->page) - $this->recordsPerPage;

        $query='SELECT * from '. $this->table. ' where UPPER(receiverId) = UPPER(:receiverId )AND  UPPER(senderId) = UPPER(:senderId) ORDER by created_at DESC LIMIT ' .$fromRecordNum .',' .$this->recordsPerPage;
        $stmt= $this->conn->prepare($query);
    
        $stmt->execute(['receiverId' => $this->receiverId,'senderId' => $this->senderId]);
        return $stmt;
    }

     public function  createMessage(){

        $query='INSERT INTO ' . $this->table . ' (senderId,receiverId,message_body) VALUES (:senderId,:receiverId,:message_body)';
        $stmt= $this->conn->prepare($query);
        $this->senderId=htmlspecialchars(strip_tags($this->senderId));
        $this->receiverId=htmlspecialchars(strip_tags($this->receiverId));
        $this->message_body=htmlspecialchars(strip_tags($this->message_body));
        if($stmt->execute(['receiverId'=> $this->receiverId,'senderId' => $this->senderId,'message_body' => $this->message_body]))
            return true;
        printf("Error: %s.\n", $stmt->error);
        return false;
        
     }
    
}
?>