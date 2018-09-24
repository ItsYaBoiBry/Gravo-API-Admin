<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 31/7/2018
 * Time: 10:29 AM
 */
include 'config.php';
if ($conn) {
    $getid = $_POST['id'];
    $getname = $_POST['name'];
    $querycheckitemexist = "SELECT * FROM bulk_transaction_item WHERE id = $getid;";
    $query_get_single_item = "SELECT b.*, r.full_name, r.email, r.address, bs.status FROM bulk_transaction_item b, recycler r, bulk_transaction_status bs WHERE b.bulk_transaction_status_id = bs.id AND b.recycler_id = r.id AND b.id = $getid;";
    $checkitemexist = getbulkitem($conn, $querycheckitemexist);
    if ($checkitemexist != []) {
        $queryupdateitem = "UPDATE bulk_transaction_item SET bulk_transaction_status_id = 6 WHERE id = $getid;";
        if (changebulkstatus($conn, $queryupdateitem)) {
            $queryaddhistory = "INSERT INTO bulk_transaction_history (bulk_transaction_item_id, history) VALUES ($getid,'$getname has accepted the quoted price.')";
            if (changebulkstatus($conn, $queryaddhistory)) {
                $results = getbulkitem($conn, $query_get_single_item);
                $query_get_history = "SELECT * FROM bulk_transaction_history WHERE bulk_transaction_item_id = $getid;";
                $history = getbulkitem($conn, $query_get_history);
                if ($results != []) {
                    JSONResponse2(200, "Bulk Item Retrieved", $results,$history);
                } else {
                    JSONResponse(404, "No Bulk Items in the database", $results);
                }
            }
        } else {
            JSONResponse(400, "unable to update the transaction status!", null);
        }
    } else {
        JSONResponse(404, "Bulk Transaction does not exist!", null);
    }
    
} else {
    JSONResponse(403, "Unauthoried", null);
      
}

function changebulkstatus($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function getbulkitem($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
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