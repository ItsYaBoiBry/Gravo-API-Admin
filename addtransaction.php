<?php

include 'config.php';
$collection_date = $_POST['collectiondate']; //yyyy-mm-dd
$collection_address = $_POST['collectionaddress'];
$collection_user = $_POST['collectionuser'];
$collection_number = $_POST['collectionnumber'];
$total_price = $_POST['total_price'];
$total_weight = $_POST['total_weight'];
$remarks = $_POST['remarks'];
$transaction_id = $_POST['transaction_id'];
$userid = $_POST['userid'];
$statusid = $_POST['status'];
$collectiondatetiming = $_POST['collectiondatetiming'];

$gettoken = "GT";
$gettoken .= $userid;
$gettoken .= date("dyHms");
$gettransactionaddress = explode(" ", $collection_address);
$getpostal = end($gettransactionaddress);

$query_insert_transaction = "INSERT INTO transaction (id, collection_date, collection_date_timing, collection_address,collection_postal, collection_user,collection_contact_number, total_price, total_weight, remarks, transaction_id_key, recycler_id, status_id) VALUES (NULL, '$collection_date', '$collectiondatetiming', '$collection_address','$getpostal', '$collection_user', '$collection_number', '$total_price', '$total_weight', '$remarks', '$gettoken',$userid, $statusid);";
$query_get_transactions = "SELECT T.*, S.status_type FROM transaction T, status S WHERE T.status_id = S.id AND transaction_id_key = '$gettoken';";

if ($conn) {
    $addtransaction = AddTransaction($conn, $query_insert_transaction);
    if ($addtransaction) {
       
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
           
            JSONResponse(200,"OK",$gettransactions);
        } else {
            JSONResponse(404, "No transactions", $gettransactions);
        }
    } else {
        JSONResponse(400, "Failed to add transaction", null);
    }
} else {
    JSONResponse(403, "Unauthorised", null);
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

function AddTransaction($conn, $query) {
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
