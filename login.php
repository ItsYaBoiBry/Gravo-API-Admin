<?php
include 'config.php';

$getEmail = $_POST['email'];
$getPassword = sha1($_POST['password']);

$query_check_email = "SELECT password FROM recycler WHERE email = '$getEmail'";
$query_login = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE R.id = A.recycler_id AND A.achievement_rank_id = AR.id AND email = '$getEmail' AND password = '$getPassword'";

if($conn){
    $checkEmail = CheckEmail($conn, $query_check_email);
    if($checkEmail!=[]){
        $doLogin = Login($conn, $query_login);
        if($doLogin != []){
            JSONResponse (200, "Success", $doLogin);
        }else{
            $userpassword = $checkEmail[0]['password'];
            JSONResponse (405, "Wrong Username or Password", $userpassword);
        }
    }else{
        JSONResponse(404, "No such user", $checkEmail);
    }
}else{
    JSONResponse(403, "Unauthorised", null);
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['users'] = $data;
    
    $json_response = json_encode($response);
    echo $json_response;
}
function Login($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
function CheckEmail($conn, $query){
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
