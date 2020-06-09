<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once('../../config/Database.php');
  include_once('../../models/Message.php');

  include_once('../../models/User.php');

$database=new Database();
$db=$database->connect();
$msg=new Message($db);

$user=new User($db);

//echo  "hello";
$data=json_decode(file_get_contents("php://input"));
$msg->senderId=$data->senderId;
$msg->receiverId=$data->receiverId;
$msg->message_body=$data->message_body;



if($data->senderId==null){
    echo json_encode(
        array('message' => 'invalid senderId')
    );
    return;
}
if($data->receiverId==null){
    echo json_encode(
        array('message' => 'Invalid receiverId')
    );
    return;
}
if($data->message_body==null){
    echo json_encode(
        array('message' => 'Message body is invalid')
    );
    return;
}
if (strlen(trim($data->message_body)) == 0)
{
    echo json_encode(
        array('message' => 'Message body has no characters')
    );
    return;   
}
if($msg->receiverId==$msg->senderId){
    echo json_encode(
        array('message' => 'Sender and Receiver cannot be same')
    );
    return;
}

$user->userName=$data->senderId;


$row=$user->readSingleUser()->fetchAll();

 if($row==null)
    {
        echo json_encode(
            array('message' => 'SenderId doesn\'t exists as a User, please create it as a User')
        );
        return;

    }

$user->userName=$data->receiverId;
$row=$user->readSingleUser()->fetchAll();

 if($row==null)
        {
            echo json_encode(
                array('message' => 'ReceiverId doesn\'t exists as a User, please create it as a User')
            );
            return;
        }
    

//echo $msg->message_body;

if($msg->createMessage()){
    echo json_encode(
        array('message' => 'Message Sent to the ' .$data->receiverId)
    );

}
else{
    echo json_encode(
        array('message' => 'Could not send the message')
    );
}




?>