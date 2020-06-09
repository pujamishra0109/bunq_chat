<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../../config/Database.php');
include_once('../../models/Message.php');

include_once('../../models/User.php');


$database=new Database();
$db=$database->connect();

$msg=new Message($db);
$user=new User($db);

$msg->page = isset($_GET['page']) ? $_GET['page'] : 1;

$msg->recordsPerPage=isset($_GET['per_page']) ? $_GET['per_page'] : 25;



//if the URL has query parameters
if(isset($_GET['receiverId']) || isset($_GET['senderId']))
{

   //if it has receiverId and senderId
if(isset($_GET['receiverId']) && isset($_GET['senderId']))
{
    $msg->receiverId=$_GET['receiverId'];
    $msg->senderId=$_GET['senderId'];
    if($msg->receiverId==$msg->senderId){
        echo json_encode(
            array('message' => 'Sender and Receiver cannot be same')
        );
        return;
    }
    $result=$msg->readMessageByUsers();
    $msg_arr= array();
    while($row = $result->fetch(PDO :: FETCH_ASSOC))
    {
        extract($row);
        if(empty($msg_arr[$senderId]))
        {
            $msg_arr[$senderId]=[];
        }
        $newMsg = array(
                'MessageId' => $id,
                'SenderId' => $senderId,
                'ReceiverId' => $receiverId,
                'Message_body' => $message_body,
                'Created_at' => $created_at
        );
        array_push($msg_arr[$senderId],$newMsg);   
         }      
        echo json_encode($msg_arr);
        return;
}
//if the url has receiverId only
else if(isset($_GET['receiverId']))
{
    $msg->receiverId= isset($_GET['receiverId'])?$_GET['receiverId']:null;
    $user->userName=$msg->receiverId;
    $row=$user->readSingleUser()->fetchAll();
    if($row==null)
    {
        echo json_encode(
            array('message' => 'Receiver Id doesn\'t exists as a User, please create it as a User')
        );
        return;

    }
    $result=$msg->readMessageById();
    $msg_arr= array();
    while($row = $result->fetch(PDO :: FETCH_ASSOC))
    {
    extract($row);
    if(empty($msg_arr[$senderId]))
    {
        $msg_arr[$senderId]=[];
    }
    $newMsg = array(
            'MessageId' => $id,
            'SenderId' => $senderId,
            'ReceiverId' => $receiverId,
            'Message_body' => $message_body,
            'Created_at' => $created_at

    );
    array_push($msg_arr[$senderId],$newMsg);   
    }

    
    

    echo json_encode($msg_arr);
}
//if the URL has senderId
else
{
    if(isset($_GET['senderId'])){
    $msg->senderId= isset($_GET['senderId'])?$_GET['senderId']:null;
    $user->userName=$msg->senderId;
    $row=$user->readSingleUser()->fetchAll();
    if($row==null)
    {
            echo json_encode(
                array('message' => 'Sender Id doesn\'t exists as a User, please create it as a User')
            );
            return;
    }
    $result=$msg->readMessageBySenderId();
    $msg_arr= array();
    while($row = $result->fetch(PDO :: FETCH_ASSOC))
        {
            extract($row);
            if(empty($msg_arr[$receiverId]))
            {
              $msg_arr[$receiverId]=[];
             }
             $newMsg = array(
                'MessageId' => $id,
                'SenderId' => $senderId,
                'ReceiverId' => $receiverId,
                'Message_body' => $message_body,
                'Created_at' => $created_at
    
              );
              array_push($msg_arr[$receiverId],$newMsg);   
            
        }
        echo json_encode($msg_arr);
    }
}
}
//TO get all the messages from all the users
else
{

$result=$msg->readAllMessages();
$msg_arr= array();
while($row = $result->fetch(PDO :: FETCH_ASSOC))
    {
        extract($row);
        $msg_item = array('id' => $id,
            'senderId' => $senderId,
            'receiverId' => $receiverId,
            'message_body' => $message_body,
            'created_at' => $created_at

        );
        array_push($msg_arr,$msg_item);

    }
        echo json_encode($msg_arr);
       
    }


?>
