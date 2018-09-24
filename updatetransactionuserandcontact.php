<?php

include 'config.php';
$transactionid = $_POST['transactionid'];
$collectingperson = $_POST['user'];
$collectionnumber = $_POST['number'];
$status = $_POST['status'];
$scollectorid = $_POST['collectorid'];

$query_get_all_transactions = "SELECT T.*, S.status_type FROM transaction T, status S WHERE S.id = T.status_id AND T.collector_id = $scollectorid ORDER BY T.collection_postal ASC;";
$query_changepassword = "UPDATE transaction SET status_id = $status, collection_user = '$collectingperson', collection_contact_number = '$collectionnumber' WHERE id = $transactionid";
$query_get_transactions = "SELECT T.*, S.status_type FROM transaction AS T INNER JOIN status AS S ON (S.id=T.status_id) WHERE T.id = $transactionid";
$changepassword = ChangePassword($conn, $query_changepassword);
if ($conn) {
    if ($changepassword) {
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            $getalltransactions = GetTransactions($conn, $query_get_all_transactions);
            if ($getalltransactions != []) {
                JSONResponse(200, "OK", $gettransactions, $getalltransactions);
            } else {
                JSONResponse(404, "No transactions", $gettransactions, null);
            }
        } else {
            JSONResponse(404, "No transactions", $gettransactions, null);
        }
    } else {
        JSONResponse(400, "status not updated", $changepassword, null);
    }
} else {
    JSONResponse(403, "Unauthorized", null, null);
}

function JSONResponse($status, $status_message, $data, $allTransactions) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['data'] = $data;
    $response['result'] = $allTransactions;

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

