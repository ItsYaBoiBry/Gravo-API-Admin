<?php

include 'config.php';
$userid = $_POST['userid'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$contactnumber = $_POST['contactnumber'];
$email = $_POST['email'];
$getBlock = $_POST['block'];
$getUnit = $_POST['unit'];
$getStreet = $_POST['street'];
$getPostal = $_POST['postal']; 
$address = $_POST["address"];
$getLicenseNo = $_POST['licensenumber'];
$getVehicleNo = $_POST['vehiclenumber'];


$query_get_user = "SELECT * FROM `collector` WHERE `id` = $userid";
$query_check_email = "SELECT `email` FROM `collector` WHERE `email` = '$email'";
$query_get_transactions = "UPDATE `collector` SET `first_name`= '$firstname',`last_name`='$lastname',`phone`='$contactnumber',`address`='$address',`block`='$getBlock',`unit`='$getUnit',`street`='$getStreet',`postal`='$getPostal',`liscence_number`='$getLicenseNo',`vehicle_number`='$getVehicleNo' WHERE id = $userid";

if ($conn) {
    $checkid = GetTransactions($conn, $query_get_user);
    if ($checkid != []) {
        $checkemail = GetTransactions($conn, $query_check_email);
        if ($checkemail != []) {
           $updateuser = ChangePassword($conn, $query_get_transactions);
            if ($updateuser) {
                $getUserDetails = GetTransactions($conn, $query_get_user);
                if ($getUserDetails != []) {
                    JSONResponse(200, "Success", $getUserDetails);
                } else {
                    JSONResponse(400, "Unable to retrieve updated user details", null);
                }
            } else {
                JSONResponse(400, "Unable to update user", null);
            }
        } else {
         JSONResponse(404, "$email is not in the database", null);
            
        }
    } else {
        JSONResponse(400, "user not available", null);
    }
} else {
    JSONResponse(403, "Unauthorized", null);
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

function ChangePassword($conn, $query) {
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

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

