<?php

include 'config.php';
		
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = sha1($_POST['password']);
$phone = $_POST['phone'];
$address = $_POST['address'];
$block = $_POST['block'];
$unit = $_POST['unit'];
$street = $_POST['street'];
$postal = $_POST['postal'];
$nric = $_POST['nric'];
$liscence_number = $_POST['liscence_number'];
$vehicle_number = $_POST['vehicle_number'];
$status = $_POST['status'];


$query_get_user = "SELECT * FROM collector WHERE id = $id";
$query_check_email = "SELECT email FROM collector WHERE email = '$email'";

$query_get_transactions = "UPDATE collector SET first_name= '$first_name',
last_name='$last_name',email='$email',phone='$phone',
address='$address',block='$block',unit='$unit',street='$street',
postal='$postal',nric='$nric',liscence_number='$liscence_number',
vehicle_number='$vehicle_number'  WHERE id = $id";

if ($conn) {
    
           $updateuser = update($conn, $query_get_transactions);
            if ($updateuser) {
                $getUserDetails = GetTransactions($conn, $query_get_user);
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