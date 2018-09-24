<?php

include 'config.php';
		
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$password = sha1($_POST['password']);
$phone = $_POST['phone'];
$address = $_POST['address'];
$block = $_POST['block'];
$unit = $_POST['unit'];
$street = $_POST['street'];
$postal = $_POST['postal'];



$query_get_user = "SELECT * FROM recycler WHERE id = $id";


$query_get_transactions = "UPDATE recycler SET first_name= '$first_name',
last_name='$last_name',full_name= '$full_name',email='$email',contact_number='$phone',
address='$address',block='$block',unit='$unit',street='$street',
postal='$postal' WHERE id = $id";

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