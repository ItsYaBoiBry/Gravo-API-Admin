<?php

include 'config.php';
$cartitemid = $_POST['id'];
$query_get_cart = "SELECT * FROM transaction_details WHERE id = ".$cartitemid.";";


if ($conn) {
    $getcart = GetCart($conn, $query_get_cart);
    if ($getcart!=[]) {
     $query_delete_cart_item = "DELETE FROM transaction_details WHERE id = ".$cartitemid.";";
            if (DeleteCart($conn, $query_delete_cart_item)) {
                JSONResponse(200, "Success", $getcartdetails);
            } else {
                JSONResponse(404, "Unable to delete item", null);
            }

    } else {
        JSONResponse(404, "Details are not in the transaction", null);        
           
        
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
    $response['cartid'] = $item;
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

function DeleteCart($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

