<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 21/9/2018
 * Time: 6:31 PM
 */
include "config.php";
if(isset($_GET["id"])){
    $id = $_GET["id"];
    $qgetnoti = "SELECT * FROM notifcations WHERE recycler_id = ".$id;
    $getnoti = GetNotifications($conn, $qgetnoti);
    if($getnoti!=[]){
        JSONResponse(200,"OK",$getnoti);
    }else{
        JSONResponse(404,"no notifications",$getnoti);
    }
}else{
    JSONResponse(404,"No details",null);
}
function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

function GetNotifications($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
