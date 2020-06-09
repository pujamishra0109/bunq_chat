<?php


class Database{


private $conn;

//specify the database path 
private $db_path='/Applications/XAMPP/xamppfiles/htdocs/Bunq_Chat/db';

    public function connect(){
      //  $this->conn=null;
        try
         {
           
       
            if($this->conn==null){

             
               
            $this->conn=new PDO('sqlite:' .$this->db_path. '/bunq_messages.db');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,
                                PDO::ERRMODE_EXCEPTION);
            $this->createSchema();
            }

         }

       catch (PDOException $e)
        {
           //throw $th;
           echo 'Connection Exception :  '.$e->getMessage();
       }
       return $this->conn;

    }


public function createSchema()
{

try{

    

    $this->conn->exec('CREATE TABLE IF NOT EXISTS User (userName varchar(255) NOT NULL PRIMARY KEY,created_at DATETIME DEFAULT CURRENT_TIMESTAMP)');

    $this->conn->exec('CREATE TABLE IF NOT EXISTS Messages(id INTEGER PRIMARY key AUTOINCREMENT,senderId varchar(255), receiverId varchar(255),
   message_body varchar(255),created_at DATETIME DEFAULT CURRENT_TIMESTAMP ,FOREIGN KEY (senderId)
   REFERENCES User(userName)
,FOREIGN KEY (receiverId)
   REFERENCES User(userName))');

  /**************************************
    * Set initial data                    *
    **************************************/

    // Array with some test data to insert to database
     // Prepare INSERT statement to SQLite3 file db

   //Insert into User Table

    $stmt=$this->conn->prepare('SELECT COUNT(*) as num FROM User');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if($row['num'] > 0)
        return;

    $users=array(
            array('userName' => 'PujaMishra'),
            array('userName' =>'SumitMishra'),
            array('userName' => 'RohitMishra'),
            array('userName' => 'NamitaShahi'),
            array('userName' => 'MrinalManuj'),

            array('userName' => 'John'),
            array('userName' => 'Tim'),
            array('userName' => 'Alex'),
            array('userName' => 'Joe'),
            array('userName' => 'Cersie')
            );
    $stmt = $this->conn->prepare('INSERT INTO  User (userName) VALUES (:userName)');
        $stmt->bindParam(':userName', $userId);

        forEach($users as $u){
            $userId=$u['userName'];
            $stmt->execute();
        }

        //Insert into Messages Table

    $messages=array(
            array('receiverId' => 'PujaMishra',
                    'senderId' =>'SumitMishra',
                    'message_body' => 'Hello this is Message1 from SumitMishra'),
             array('receiverId' => 'PujaMishra',
                    'senderId' =>'SumitMishra',
                    'message_body' => 'Hello this is Message2 from SumitMishra'),
            array('receiverId' => 'SumitMishra',
                    'senderId' =>'PujaMishra',
                    'message_body' => 'Hello this is Message1 from PujaMishra'),
            array('receiverId' => 'MrinalManuj',
                    'senderId' =>'SumitMishra',
                    'message_body' => 'Hello this is Message from SumitMishra'),
            array('receiverId' => 'PujaMishra',
                    'senderId' =>'MrinalManuj',
                    'message_body' => 'Hello this is Message from Mrinal to PujaMishra'),
            array('receiverId' => 'PujaMishra',
                    'senderId' =>'Cersie',
                    'message_body' => 'Hello PujaMishra! '),
            array('receiverId' => 'Cersie',
                    'senderId' =>'SumitMishra',
                    'message_body' => 'Hello Cersie, SumitMishra here! '),
            array('receiverId' => 'NamitaShahi',
                    'senderId' =>'RohitMishra',
                    'message_body' => 'Hello Namita this is Message from Rohit'),

             array('receiverId' => 'NamitaShahi',
                    'senderId' =>'RohitMishra',
                    'message_body' => 'Hello Namita this is second  Message from Rohit'),
            array('receiverId' => 'NamitaShahi',
                    'senderId' =>'RohitMishra',
                    'message_body' => 'Hello Namita this is third Message from Rohit'),
            array('receiverId' => 'John',
                    'senderId' =>'Tim',
                    'message_body' => 'Hello John this is Message from Tim'),
            array('receiverId' => 'John',
                    'senderId' =>'Joe',
                    'message_body' => 'Hello John this is Message from Tim')


            );
    $stmt = $this->conn->prepare('INSERT INTO Messages (senderId,receiverId,message_body) VALUES (:senderId,:receiverId,:message_body)');
        $stmt->bindParam(':senderId', $senderId);
        $stmt->bindParam(':receiverId', $receiverId);
        $stmt->bindParam(':message_body', $message_body);


        forEach($messages as $m){
            $senderId=$m['senderId'];
            $receiverId=$m['receiverId'];
            $message_body=$m['message_body'];
            $stmt->execute();
        }


    }

catch(PDOException $e){

 echo 'Connection Exception :  '.$e->getMessage();
       }

}
}





?>