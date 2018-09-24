<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 20/9/2018
 * Time: 8:44 AM
 */
include 'config.php';
if (isset($_GET['collectorid'])) {
    $cid = $_GET['collectorid'];
    $today = date('Y-m-d');
    getsummaries($conn, $cid, $today);


}
function getsummaries($conn, $cid, $date)
{
    $query_get_all_summaries = "SELECT * FROM collection_summary WHERE collector_id = " . $cid . " ORDER BY summary_date DESC;";
    $query_get_summary_today = "SELECT * FROM collection_summary WHERE collector_id = " . $cid . " AND summary_date = '$date';";
    $query_get_collector = "SELECT * FROM collector WHERE id =" . $cid;
    $checkid = get($conn, $query_get_collector);
    if ($checkid != []) {
        $checksummarytoday = get($conn, $query_get_summary_today);
        if ($checksummarytoday != []) {
            $getsummaries = get($conn, $query_get_all_summaries);
            if ($getsummaries != []) {
                JSONResponse(200, "OK", $getsummaries);
            } else {
                JSONResponse(404, "Unable to find summaries", $getsummaries);
            }
        } else {
            $query_insert_summary = "INSERT INTO `collection_summary`(`collector_id`,`summary_date`) VALUES ($cid,'$date');";
            if (post($conn, $query_insert_summary)) {
                getsummaries($conn, $cid, $date);
            } else {
                JSONResponse(400, "Unable to get today's summary", $checkid);
            }
        }
    } else {
        JSONResponse(404, "Unable to find collector", $checkid);
    }
}

function getMonthlySummary($array)
{
    $months = array();
    for ($i=0;$i<count($array);$i++) {
        array_push($months,$array[$i]);
    }
    return $months;
}

function get($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response['error'] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function post($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response['error'] = mysqli_error($conn)));
    return $resultset;
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['summaries'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

function JSONResponeArray($array)
{
    return json_encode($array);
}
