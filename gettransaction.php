<?php

include 'config.php';
$type = $_GET['type'];

if ($conn) {
    if ($type === "all") {
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {

            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions", $gettransactions);
        }
    } else if ($type === "userid") {
        $userid = $_GET['userid'];
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.recycler_id = $userid ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions", $gettransactions);
        }
    } else if ($type === "withid") {
        $transactionid = $_GET['transactionid'];
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.id = $transactionid ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            $query_get_details = "SELECT td.*, c.item, c.rate FROM transaction_details td, category c WHERE td.category_id = c.id AND td.transaction_id = $transactionid;";
            $getdetails = GetTransactions($conn, $query_get_details);
            if ($getdetails != []) {
                $query_get_history = "SELECT * FROM transaction_history WHERE transaction_id = $transactionid ORDER BY id ASC;";
                $gethistory = GetTransactions($conn, $query_get_history);
                if ($gethistory != []) {
                    JSONResponse3(200, "OK", $gettransactions, $getdetails, $gethistory);
                } else {
                    JSONResponse3(200, "No transaction history", $gettransactions, $getdetails, array());
                }
            } else {
                JSONResponse3(200, "No transaction details", $gettransactions, array(), array());
            }
        } else {
            JSONResponse3(404, "No transactions", $gettransactions, array(), array());
        }
    } else if ($type === "withdate") {
        $date = $_GET['date'];
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.collection_date = '$date' ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions on $date", $gettransactions);
        }
    } else if ($type === "today") {
        $today = date("Y-m-d");
        $now = date("h:i:sa");
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.collection_date = '$today' ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions on $today at $now", $gettransactions);
        }
    } else if ($type === "sortpostaltoday") {
        $today = date("Y-m-d");
        $now = date("h:i:sa");
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.collection_date = '$today' ORDER BY T.collection_date ASC, T.status_id ASC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions on $today at $now", $gettransactions);
        }
    } else if ($type === "sortpostal") {
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id ORDER BY collection_postal DESC, T.collection_date ASC, T.status_id ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            JSONResponse(200, "OK", $gettransactions);
        } else {
            JSONResponse(404, "No transactions now", $gettransactions);
        }
    } else if ($type === "withcollectorid") {
        $collectorid = $_GET["id"];
        $query_get_transactions = "SELECT T.*, S.status_type FROM transaction T ,status S WHERE T.status_id = S.id AND T.collector_id = $collectorid ORDER BY T.collection_date DESC, T.collection_date_timing ASC;";
        $query_get_bulk = "SELECT T.*, S.status, R.full_name, R.address, R.unit,R.contact_number, R.email FROM bulk_transaction_item T , bulk_transaction_status S, recycler R WHERE T.bulk_transaction_status_id = S.id AND T.recycler_id = R.id AND T.collector_id = $collectorid ORDER BY T.collection_date DESC, T.collection_date_timing ASC;";
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            $getbulk = GetTransactions($conn, $query_get_bulk);
            if ($getbulk != []) {
                JSONResponse4(200, "OK", $gettransactions, $getbulk);
            } else {
                JSONResponse(200, "No Bulk Transactions", $gettransactions);
            }

        } else {
            JSONResponse(404, "No transactions now", $gettransactions);
        }
    } else {
        JSONResponse(400, "No Such Type", $gettransactions);
    }
    mysqli_close($conn);
} else {
    JSONResponse(403, "Unauthorised", null);
    mysqli_close($conn);
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

function JSONResponse2($status, $status_message, $data, $data2)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['details'] = $data2;
    $json_response = json_encode($response);
    echo $json_response;
}

function JSONResponse3($status, $status_message, $data, $data2, $data3)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['details'] = $data2;
    $response['history'] = $data3;

    $json_response = json_encode($response);
    echo $json_response;
}

function JSONResponse4($status, $status_message, $data, $data2)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['bulk_items'] = $data2;

    $json_response = json_encode($response);
    echo $json_response;
}

function GetTransactions($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }

    return $data;
}
