<?php

include 'config.php';
		
$id = $_POST['id'];
$status = $_POST['status'];


$query_get_user = "SELECT * FROM collector WHERE id = $id";
//$query_check_email = "SELECT email FROM collector WHERE email = '$email'";

$query_get_transactions = "UPDATE collector SET status='$status' WHERE id = $id";

if ($conn) {
    
           $updateuser = changeStatus($conn, $query_get_transactions);
            if ($updateuser) {
                $getUserDetails = GetTransactions($conn, $query_get_user);
                if ($getUserDetails != []) {
                    echo "Update successful";
                } else {
                    echo "Unable to retrieve updated user details";
                }
            }
} else {
    echo "Unauthorized";
}



function changeStatus($conn, $query) {
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


?>