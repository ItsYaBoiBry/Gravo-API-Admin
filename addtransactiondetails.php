<?php

header("Content-Type:application/json");
include 'config.php';
$userid = $_POST['userid'];
$transactionid = $_POST['transactionid'];
$getweight = $_POST['weight'];
$getprice = $_POST['price'];
$category = $_POST['category'];
$query_get_cart = "SELECT * FROM transaction WHERE id = '$transactionid'";


if ($conn) {
    $getcart = GetCart($conn, $query_get_cart);
    if (empty($getcart)) {
        JSONResponse(404, "User does not have this transaction yet", null);
    } else {
        $query_add_cart_details = "INSERT INTO transaction_details (id, category_id, transaction_id, transaction_recycler_id, weight, price) VALUES (NULL, $category, $transactionid, $userid, $getweight, $getprice);";
        $getcartdetails = AddCartDetails($conn, $query_add_cart_details);
        if ($getcartdetails) {
            $query_get_cart_details = "SELECT * FROM transaction_details WHERE transaction_id = '$transactionid'";
            $getcartdetails = GetCart($conn, $query_get_cart_details);
            if (empty($getcartdetails)) {
                JSONResponse2(404, "Transaction does not have any items", $transactionid, null);
            } else {
                JSONResponse2(200, "OK", $transactionid, $getcartdetails);
            }
        } else {
            JSONResponse2(404, "Transaction does not have any items", $transactionid, $getcartdetails);
        }
    }
} else {
    JSONResponse(403, "Unauthorized", $addtocart);
}


function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

function jsonResponse2($status, $status_message, $item, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['transactionid'] = $item;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}


function AddCartDetails($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetCart($conn, $query)
{
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

