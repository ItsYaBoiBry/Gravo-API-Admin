<?php

include 'config.php';
$userid = $_POST['userid'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$contactnumber = $_POST['contactnumber'];
$address = $_POST['address'];
$fullname = $firstname . " " . $lastname;
$getBlock = $_POST['block'];
$getUnit = $_POST['unit'];
$getStreet = $_POST['street'];
$getPostal = $_POST['postal'];

$query_get_user = "SELECT * FROM recycler WHERE id = $userid";
$query_check_email = "SELECT email FROM recycler WHERE email = '$email'";
$query_get_transactions = "UPDATE recycler SET first_name='$firstname',last_name='$lastname',full_name='$fullname',email='$email',contact_number='$contactnumber',address='$address', block='$getBlock', unit='$getUnit', street = '$getStreet', postal='$getPostal' WHERE id = $userid";

if ($conn) {
    $checkid = GetTransactions($conn, $query_get_user);
    if ($checkid != []) {
        $facebookid = $checkid[0]["facebook_id"];
        $first_name = $checkid[0]["first_name"];
        //not facebook
        if ($facebookid == "") {

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
            //facebook
            if ($first_name == "") {
                //first time facebook         
                $checkemail = GetTransactions($conn, $query_check_email);
                if ($checkemail != []) {
                    JSONResponse(404, "$email has been registered", null);
                } else {
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

                }
                
            }else{
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
            }
        }

    } else {
        JSONResponse(400, "user not available", null);
    }
} else {
    JSONResponse(403, "Unauthorized", null);
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

