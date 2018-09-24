<?php

header("Content-Type:application/json");
include 'config.php';
$type = $_GET['type'];


$conn = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect($host, $user, $password, $database)) {
    if ($type === "all") {
        $items = getDetails($conn);
        if (empty($items)) {
            $jsonString = jsonResponse(401, "Invalid Request", NULL);
            echo $jsonString;
        } else {
            $jsonString = jsonResponse(200, "retrieved categories", $items);
            echo $jsonString;
        }
    }else if($type === "withid"){
        $itemid = $_GET['category'];
        $items = getDetail($conn, $itemid);

        if (empty($items)) {
            $jsonString = jsonResponse(401, "Invalid Request", NULL);
            echo $jsonString;
        } else {
            $jsonString = jsonResponse(200, "retrieved category item", $items);
            echo $jsonString;
        }
    }else{
         $jsonString = jsonResponse(404, "invalid type", NULL);
          echo $jsonString;
    }
} else {
    $jsonString = jsonResponse(403, "Forbidden", NULL);
    echo $jsonString;
}

function jsonResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    return $json_response;
//    header("Location: https://greenravolution.com/signup.php");
}

function getDetails($conn) {
    $query = "select * FROM category;";
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));

    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
function getDetail($conn, $category) {
    $query = "select * FROM category WHERE id = $category;";
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));

    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}
