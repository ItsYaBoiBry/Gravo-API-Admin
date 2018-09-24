<?php

header("Content-Type:application/json");
include 'config.php';

$getEmail = $_POST['email'];
$getPassword = sha1($_POST['password']);

$query_check_email = "SELECT email FROM collector WHERE email = '$getEmail'";
$query_login = "SELECT * FROM collector WHERE email = '$getEmail' AND password = '$getPassword'";

if($conn){
    $checkEmail = CheckEmail($conn, $query_check_email);
    if($checkEmail!=[]){
        $doLogin = Login($conn, $query_login);
        if($doLogin != []){
            JSONResponse (200, "Success", $doLogin);
        }else{
            JSONResponse (403, "Wrong Username or Password", null);
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


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

