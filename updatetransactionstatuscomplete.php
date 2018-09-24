<?php

include 'config.php';
$transactionid = $_POST['transactionid'];
$status = $_POST['status'];
$scollectorid = $_POST['collectorid'];

$query_get_all_transactions = "SELECT T.*, S.status_type FROM transaction T, status S WHERE S.id = T.status_id AND T.collector_id = $scollectorid ORDER BY T.collection_postal ASC;";
$query_get_bulk = "SELECT T.*, S.status FROM bulk_transaction_item T , bulk_transaction_status S WHERE T.bulk_transaction_status_id = S.id AND T.collector_id = $collectorid ORDER BY T.collection_date DESC, T.collection_date_timing ASC;";
$query_changepassword = "UPDATE transaction SET status_id = $status WHERE id = $transactionid;";
$query_get_transactions = "SELECT T.*, S.status_type FROM transaction AS T INNER JOIN status AS S ON (S.id=T.status_id) WHERE T.id = $transactionid;";
$query_get_transaction_details = "SELECT * FROM transaction_details WHERE transaction_id = $transactionid;";
$changepassword = ChangePassword($conn, $query_changepassword);
if ($conn) {
    if ($changepassword) {
        $gettransactions = GetTransactions($conn, $query_get_transactions);
        if ($gettransactions != []) {
            $getalltransactions = GetTransactions($conn, $query_get_all_transactions);
            if ($getalltransactions != []) {
                $gettransactiondetails = GetTransactions($conn, $query_get_transaction_details);
                if ($gettransactiondetails != []) {
                    $getUpdated = "";

                    for ($i = 0; $i < count($gettransactiondetails); $i++) {
                        $category_id = $gettransactiondetails[$i]["category_id"];
                        $weight = $gettransactiondetails[$i]["weight"];
                        $recycler_id = $gettransactiondetails[$i]["transaction_recycler_id"];
                        $updated = UpdatePoints($category_id, $recycler_id, $weight, $conn);
                        if ($updated) {
                            $updatesummary = UpdateSummary($conn, $category_id,$scollectorid,$weight);
                            if($updatesummary){
                                $getUpdated = "updated";
                            }else{
                                $getUpdated = "not updated";
                            }
                        } else {
                            $getUpdated = "not updated";
                        }
                    }
                    if ($getUpdated == "updated") {
                        JSONResponse(200, "OK", $gettransactions, $getalltransactions);
                    } else {
                        JSONResponse(200, "Not Updated", $gettransactions, $getalltransactions);
                    }
                } else {
                    JSONResponse(404, "No transaction details", $gettransactions, $getalltransactions);
                }
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

function UpdatePoints($catid, $userid, $weight, $conn)
{
    $query_get_rate = "SELECT rate, points, co2_points, achievement_tag FROM category;";
    $getpointsrate = GetCart($conn, $query_get_rate);
    $tag = $getpointsrate[$catid]['achievement_tag'];
    $points = $getpointsrate[$catid]['points'];
    $co2points = $getpointsrate[$catid]['co2_points'];
    $rates = explode("/", $getpointsrate[$catid]['rate']);

    $rate = $rates[0];

    $querygetpoints = "SELECT points FROM achievement_points WHERE category_id = '$tag' AND achievements_recycler_id = $userid;";
    $querygetpointstotalweight = "SELECT points FROM achievement_points WHERE category_id = 'total_kg' AND achievements_recycler_id = $userid;";
    $querygetpointstotalco = "SELECT points FROM achievement_points WHERE category_id = 'total_co2' AND achievements_recycler_id = $userid;";
    $querygettotalpoints = "SELECT total_points FROM achievements WHERE recycler_id = $userid;";
    $querygettotalprice = "SELECT points FROM achievement_points WHERE category_id = 'total_price' AND achievements_recycler_id = $userid;";

    $getpoints = GetCart($conn, $querygetpoints)[0]['points'];
    $gettotalpoints = GetCart($conn, $querygettotalpoints)[0]['total_points'];
    $gettotalkg = GetCart($conn, $querygetpointstotalweight)[0]['points'];
    $gettotalco = GetCart($conn, $querygetpointstotalco)[0]['points'];
    $gettotalprice = GetCart($conn, $querygettotalprice)[0]['points'];


    $getpoints = $getpoints + (int)($points * $weight);
    $gettotalpoints = $gettotalpoints + (int)($points * $weight);
    $gettotalkg = $gettotalkg + (int)(1 * $weight);
    $gettotalco = $gettotalco + (int)($co2points * $weight);
    $gettotaltreepoints = (int)($gettotalkg / 12);
    $gettotalprice = $gettotalprice + ($weight * $rate);


    $queryupdatepoints = "UPDATE achievement_points SET points = $getpoints WHERE category_id = '$tag' AND achievements_recycler_id = $userid;";
    $queryupdatetotalpoints = "UPDATE achievements SET total_points = $gettotalpoints WHERE recycler_id = $userid;";
    $queryupdatetotalpointskg = "UPDATE achievement_points SET points = $gettotalkg WHERE category_id = 'total_kg' AND achievements_recycler_id = $userid;";
    $queryupdatetotalpointsco = "UPDATE achievement_points SET points = $gettotalco WHERE category_id = 'total_co2' AND achievements_recycler_id = $userid;";
    $queryupdatetotaltreepoints = "UPDATE achievement_points SET points = $gettotaltreepoints WHERE category_id = 'total_trees' AND achievements_recycler_id = $userid;";
    $queryupdatetotalprice = "UPDATE achievement_points SET points = $gettotalprice WHERE category_id = 'total_price' AND achievements_recycler_id = $userid;";
    if (AddCartDetails($conn, $queryupdatepoints)) {
        if (AddCartDetails($conn, $queryupdatetotalpoints)) {
            if (AddCartDetails($conn, $queryupdatetotalpointskg)) {
                if (AddCartDetails($conn, $queryupdatetotalpointsco)) {
                    if (AddCartDetails($conn, $queryupdatetotaltreepoints)) {
                        if (AddCartDetails($conn, $queryupdatetotalprice)) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }

}

function UpdateSummary($conn, $catid, $collectorid, $weight)
{
    $today = date('Y-m-d');
    $query_check_date = "SELECT * FROM collection_summary WHERE summary_date = '$today' AND collector_id = $collectorid;";
    $query_get_price = "SELECT rate, achievement_tag FROM category;";
    $rate = GetCart($conn, $query_get_price);
    $rates = explode("/", $rate[$catid]['rate']);
    $price = $rates[0];
    $ach_tag = $rate[$catid]['achievement_tag'];
    $type = "total_$ach_tag";


    $getsumaries = GetTransactions($conn, $query_check_date);
    if ($getsumaries != []) {
        $totalprice = $getsumaries[0]["total_price"];
        $totalweight = $getsumaries[0]["total_weight"];
        $totaltype = $getsumaries[0][$type];
        $newtotalprice = (double)($totalprice + ($weight * $price));
        $newtotalweight = (double)($totalweight + $weight);
        $newtotaltypeweight = (double)($totaltype + $weight);
        $query_update_summary = "UPDATE collection_summary SET total_weight = '$newtotalweight', total_price = '$newtotalprice', ".$type."='$newtotaltypeweight' WHERE summary_date = '$today' AND collector_id = $collectorid;";
        if(ChangePassword($conn, $query_update_summary)){
            return true;
        }else{
            return false;
        }
    }else {
        $today = date('Y-m-d');
        $query_insert_summary = "INSERT INTO `collection_summary`(`collector_id`,`summary_date`) VALUES ($collectorid,'$today');";
        ChangePassword($conn, $query_insert_summary);
        UpdateSummary($conn, $catid, $collectorid, $weight);
    }
    return false;

}


function JSONResponse($status, $status_message, $data, $allTransactions)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['data'] = $data;
    $response['result'] = $allTransactions;

    $json_response = json_encode($response);
    echo $json_response;
}

function ChangePassword($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
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

function GetCart($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function AddCartDetails($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

?>


