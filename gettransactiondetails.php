<?php

header("Content-Type:application/json");
include 'config.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
$transactionid = $_GET['transactionid'];

if ($conn) {
    $query_get_cart_details = "SELECT * FROM transaction_details WHERE transaction_id = $transactionid";
    $getcartdetails = GetCart($conn, $query_get_cart_details);
    if ($getcartdetails!=[]) {
     JSONResponse2(200, "OK", $transactionid, $getcartdetails);

    } else {
        JSONResponse2(404, "User does not have any items", $transactionid,$getcartdetails);       
    }
} else {
    JSONResponse(403, "Unauthorized", $addtocart);
}
function jsonResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
//    header("Location: https://greenravolution.com/signup.php");
}

function jsonResponse2($status, $status_message, $item, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['transactionid'] = $item;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
//    header("Location: https://greenravolution.com/signup.php");
}

function GetCart($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
