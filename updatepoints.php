<?php
include 'config.php';
$userid = $_POST['userid'];
$transactionid = $_POST['transactionid'];
$getweight = $_POST['weight'];
$getprice = $_POST['price'];
$category = $_POST['category'];

if ($conn) {
    if(UpdatePoints($category,$userid,$getweight,$conn)){
        JSONResponse(200,"Updated points",null);
    }else{
        JSONResponse(400,"Update failed",null);
    }
} else {
    JSONResponse(403, "Unauthorized",null);
}


function UpdatePoints($catid, $userid, $weight, $conn)
{
    echo "Category id $catid<br>";
    if ($catid == 1 OR $catid == 2 OR $catid == 3 OR $catid == 4) {
        $querygetpoints = "SELECT points FROM achievement_points WHERE category_id = 'paper' AND achievements_recycler_id = $userid;";
        $getpoints = GetCart($conn, $querygetpoints)[0]['points'];
        echo "Current Points $getpoints<br>";
        if ($catid == 1) {
            $getpoints = $getpoints + (int)(1 * $weight);
        } else if ($catid == 2) {
            $getpoints = $getpoints + (int)(1 * $weight);
        } else if ($catid == 3) {
            $getpoints = $getpoints + (int)(1 * $weight);
        } else if ($catid == 4) {
            $getpoints = $getpoints + (int)(1 * $weight);
        }
        $queryupdatepoints = "UPDATE achievement_points SET points = $getpoints WHERE category_id = 'paper' AND achievements_recycler_id = $userid;";
        if (AddAchievements($conn, $queryupdatepoints)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }

}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
function AddAchievements($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetCart($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}