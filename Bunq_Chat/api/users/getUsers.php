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


$user->userName= isset($_GET['userName'])?$_GET['userName']:null;

$user->page = isset($_GET['page']) ? $_GET['page'] : 1;

$user->recordsPerPage=isset($_GET['per_page']) ? $_GET['per_page'] : 25;

$user_arr= array();

if($user->userName!=null){
$result=$user->readSingleUser();


while($row = $result->fetch(PDO :: FETCH_ASSOC))
{
    extract($row);

    

    $user_item = array('userName' => $userName,
        'created_at' => $created_at

    );
    array_push($user_arr,$user_item);

}


}
else{



$result=$user->readAllUsers();



while($row = $result->fetch(PDO :: FETCH_ASSOC))
    {

        extract($row);

        

        $user_item = array('userName' => $userName,
            'created_at' => $created_at,
           
        );
        array_push($user_arr,$user_item);

    }

   
}

if($user_arr==null)
{
    echo json_encode(
        array('User' => 'with userName ' .$user->userName. ' does not exists')
    );
    
}
else
echo json_encode($user_arr);



?>
