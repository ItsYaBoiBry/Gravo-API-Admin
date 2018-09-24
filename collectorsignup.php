<?php

header("Content-Type:application/json");
include 'config.php';

/* @var $_POST type */
$getFirstName = $_POST['firstname'];
$getLastName = $_POST['lastname'];
$getEmail = $_POST['email'];
$getPassword =sha1($_POST['password']);
$getContactNumber = $_POST['contactnumber'];
$getAddress = $_POST['address'];
$getNRIC = $_POST['nric'];
$getLiscenceNumber = $_POST['liscencenumber'];
$getVehicleNumber = $_POST['vehiclenumber'];

$query_insert = "INSERT INTO  `collector` (`id`, `first_name`, `last_name`, `email`, `password`, `forgotPassword`, `phone`, `address`, `block`,`unit`,`street`,`postal`, `nric`, `liscence_number`, `vehicle_number`,`status`) VALUES (NULL,'$getFirstName', '$getLastName', '$getEmail', '$getPassword', NULL, '$getContactNumber', '$getAddress','$getBlock','$getUnit','$getStreet','$getPostal','$getNRIC', '$getLiscenceNumber', '$getVehicleNumber',0);";
$query_check_email = "SELECT email FROM collector WHERE email = '$getEmail'";
$query_get_user = "SELECT * FROM collector WHERE email = '$getEmail'";

if ($conn) {
    $checkEmail = CheckEmail($conn, $query_check_email);
    if ($checkEmail != []) {
        JSONResponse(404, "User has registered", null);
    } else {
        $register = Register($conn, $query_insert);
        if ($register) {
            $getuser = GetUser($conn, $query_get_user);
            if ($getuser != []) {
                JSONResponse(200, "OK",$getuser);
                sendEmail($getEmail,$getFirstName);
            }else{
                JSONResponse(404, "Failed to retrieve User",null);
            }
        } else {
            JSONResponse(400, "Failed to add User", null);
        }
    }
} else {
    JSONResponse(403, "Unauthorised", null);
}

function AddCart($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['users'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

function CheckEmail($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function Register($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function AddAchievements($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetUser($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function sendEmail($email,$name) {
        session_start();
        $_SESSION['name'] = $name;
         

        $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	ob_start();
        include "registerEmail.php";
        $message = ob_get_clean(); 
        
        $subject = "Gravo";
        
	if (mail($email, $subject, $message, $headers)) {
		echo"success";
	} else {
		echo"failed";
	}
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

