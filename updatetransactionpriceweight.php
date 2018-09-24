<?php

include 'config.php';
$transactionid = $_POST['transactionid'];
$collectingperson = $_POST['weight'];
$collectionnumber = $_POST['price'];

$query_changepassword = "UPDATE transaction SET total_weight = '$collectingperson', total_price = '$collectionnumber' WHERE id = $transactionid";
$query_get_transactions = "SELECT T.*, S.status_type FROM transaction AS T INNER JOIN status AS S ON (S.id=T.status_id) WHERE T.id = $transactionid";
$changepassword = ChangePassword($conn, $query_changepassword);
if ($conn) {
    if ($changepassword) {
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "Success", $gettransactions);
        } else {
            JSONResponse(404, "Transaction not found", $gettransactions);
        }
    } else {
        JSONResponse(400, "transaction not updated", $changepassword);
    }
} else {
    JSONResponse(403, "Unauthorized", null);
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

function ChangePassword($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetTransactions($conn, $query) {
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

