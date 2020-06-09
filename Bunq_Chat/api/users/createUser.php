<?php

header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once('../../config/Database.php');
  include_once('../../models/User.php');

$database=new Database();
$db=$database->connect();
$user=new User($db);
$data=json_decode(file_get_contents("php://input"));
$user->userName=$data->userName;

if($data->userName==null){
    echo json_encode(
        array('message' => 'invalid  userName')
    );
    return;
}

if($user->createUser()){
    echo json_encode(
        array('created new user' =>  $data->userName)
    );
    

}
else{
    echo json_encode(
        array('message' => 'User Name already exists')
    );
}

?> 