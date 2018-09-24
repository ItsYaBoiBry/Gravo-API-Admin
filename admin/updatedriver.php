<?php

include 'config.php';
		
$trans_id = $_POST['trans_id'];
$collector_id = $_POST['collector_id'];



$query_get_detail = "SELECT * FROM transaction WHERE id = $trans_id";
//$query_check_email = "SELECT email FROM collector WHERE email = '$email'";

$query_get_transactions = "UPDATE transaction SET collector_id= '$collector_id'
WHERE id = '$trans_id'";

if ($conn) {
    
           $updateuser = update($conn, $query_get_transactions);
            if ($updateuser) {
                $getUserDetails = GetTransactions($conn, $query_get_detail);
                if ($getUserDetails != []) {
                    echo "Success";
                } 
    }
} 
else {
   echo "Unauthorized";
}



function update($conn, $query) {
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