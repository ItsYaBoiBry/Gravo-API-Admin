<?php
include 'config.php';
$type = $_GET['type'];
if($type == "all"){
    $query_get_all = "SELECT b.*, r.full_name, r.email, r.address, bs.status FROM bulk_transaction_item b, recycler r, bulk_transaction_status bs WHERE b.bulk_transaction_status_id = bs.id AND b.recycler_id = r.id;";
    $results = SQLData($conn, $query_get_all);
    if($results!=[]){
        JSONResponse(200,"All Bulk Items Retrieved", $results);
    }else{
        JSONResponse(404,"No Bulk Items in the database", $results);
    }
}else if($type == "user"){
    $userid = $_GET['userid'];
    $query_get_all_user = "SELECT b.*, r.full_name, r.email, r.address, bs.status FROM bulk_transaction_item b, recycler r, bulk_transaction_status bs WHERE b.bulk_transaction_status_id = bs.id AND b.recycler_id = r.id AND b.recycler_id = $userid;";
    $results = SQLData($conn, $query_get_all_user);
    if($results!=[]){
        JSONResponse(200,"User's Bulk Items Retrieved", $results);
    }else{
        JSONResponse(404,"No Bulk Items in the database", $results);
    }
}else if($type == "single_item"){
    $id = $_GET['id'];
    $query_get_single_item = "SELECT b.*, r.full_name, r.email, r.address, bs.status FROM bulk_transaction_item b, recycler r, bulk_transaction_status bs WHERE b.bulk_transaction_status_id = bs.id AND b.recycler_id = r.id AND b.id = $id;";
    $query_get_history = "SELECT bh.* FROM bulk_transaction_history bh WHERE bh.bulk_transaction_item_id = $id;";
    $results = SQLData($conn, $query_get_single_item);
    $history = SQLData($conn, $query_get_history);
    if($results!=[]){
        JSONResponse2(200,"Bulk Item Retrieved", $results,$history);
    }else{
        JSONResponse(404,"No Bulk Items in the database", $results);
    }
}else{
    JSONResponse(402, "Invalid query for 'type'",null);
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
function JSONResponse2($status, $status_message, $data, $history)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['history'] = $history;

    $json_response = json_encode($response);
    echo $json_response;
}

function SQLData($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}