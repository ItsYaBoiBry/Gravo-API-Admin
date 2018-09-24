<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16/7/2018
 * Time: 12:22 PM
 */


include 'config.php';
$transactionid = $_POST['transactionid'];
$statusmessage = $_POST['message'];
$queryaddtransactionhistory = "INSERT INTO transaction_history(transaction_id, status) VALUES ($transactionid, '$statusmessage')";
$queryselectid = "SELECT id FROM transaction WHERE id = $transactionid;";
if ($conn) {
    $insert = mysqli_query($conn, $queryaddtransactionhistory);
    $data = array();
    $getdata = mysqli_query($conn,$queryselectid);
    while($rows = mysqli_fetch_assoc($getdata)){
        $data[] = $rows;
    }
    if ($insert) {
        JSONResponse(200, "OK", $insert,$data);
    } else {
        JSONResponse(400, "UNABLE TO INSERT", $insert,$data);
    }
} else {
    JSONResponse(403, "Failed to connect to database:", null,null);
}

function JSONResponse($status, $status_message, $data, $result)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['data'] = $result;

    $json_response = json_encode($response);
    echo $json_response;
}