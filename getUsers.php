<?php
include 'config.php';

$getEmail = $_GET['email'];
$getToken = $_GET['token'];

$query_check_email = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE R.id = A.recycler_id AND A.achievement_rank_id = AR.id AND R.email = '$getEmail';";


if($conn){
    $checkEmail = CheckEmail($conn, $query_check_email);
    if($checkEmail!=[]){
        $userid = $checkEmail[0]["id"];
        updateToken($conn,"UPDATE recycler SET token='$getToken' WHERE id=$userid");
        JSONResponse(200, "OK|TOKEN UPDATED", $checkEmail);
    }else{
        JSONResponse(404, "No such user", null);
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
function CheckEmail($conn, $query){
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function updateToken($conn, $query){
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

