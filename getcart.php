<?php

include 'config.php';
$userid = $_GET['userid'];
$query_get_cart = "SELECT * FROM cart WHERE recycler_id = '$userid'";


if ($conn) {
    $getcart = GetCart($conn, $query_get_cart);
    if (empty($getcart)) {
        JSONResponse(404, "User does not have a cart yet", null);
    } else {
        $cartid = $getcart[0]['id'];
        $query_get_cart_details = "SELECT * FROM cart_details WHERE cart_id = '$cartid'";
        $getcartdetails = GetCart($conn, $query_get_cart_details);
        if (empty($getcartdetails)) {
            JSONResponse2(404, "User does not have any items",$cartid, null);
        } else {
            JSONResponse2(200, "Success", $cartid, $getcartdetails);
        }
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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

