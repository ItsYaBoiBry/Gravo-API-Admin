<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 21/9/2018
 * Time: 5:20 PM
 */
include 'config.php';
if(isset($_POST["id"])){
    $id = $_POST["id"];
    $query_update_otw = "UPDATE bulk_transaction_item SET bulk_transaction_status_id = 4 WHERE id = $id";
    if(mysqli_query($conn, $query_update_otw)){
        JSONResponse(200,"OK",null);
    }else{
        JSONResponse(400,"Failed to update",null);
    }
}else{
    JSONResponse(404, "no data inserted", null);
}
function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
