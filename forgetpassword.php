<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include 'config.php';
$type = $_POST['role'];
$email = $_POST['email'];
$date = (new DateTime());
$gettoken = "";
foreach ($date as $item) {
    $gettoken .= $item;
}
$gettoken .= $email;
$token = sha1($gettoken . "/" . $email);

$query_update_email_password_token = "UPDATE recycler SET forgotPassword = '$token' WHERE email = '$email'";
$query_get_user_token = "SELECT * FROM recycler WHERE email = '$email'";

$query_update_email_password_token_collector = "UPDATE collector SET forgotPassword = '$token' WHERE email = '$email'";
$query_get_user_token_collector = "SELECT * FROM collector WHERE email = '$email'";


if ($conn) {
    if ($type == "recycler") {
        if (CheckEmail($conn, $query_get_user_token) != []) {
            $name = CheckEmail($conn, $query_get_user_token)[0]['full_name'];
            $storeToken = AddPasswordToken($conn, $query_update_email_password_token);
            if ($storeToken) {
                //send email
                $checkemail = SendEmail($email, "Change Password", $token, $name, $type);
                if ($checkemail) {
                    JSONResponse(200, "An email has been sent to you.", $checkemail);
                }
            } else {
                JSONResponse(400, "Failed to store token", null);
            }
        } else {
            JSONResponse(404, "User has not registered", null);
        }
    } else if ($type == "collector") {
        if (CheckEmail($conn, $query_get_user_token_collector) != []) {
            $name = CheckEmail($conn, $query_get_user_token_collector)[0]['first_name'];
            $storeToken = AddPasswordToken($conn, $query_update_email_password_token_collector);
            if ($storeToken) {
                //send email
                $checkemail = SendEmail($email, "Change Password", $token, $name, $type);
                if ($checkemail) {
                    JSONResponse(200, "An email has been sent to you.", $checkemail);
                }
            } else {
                JSONResponse(400, "Failed to store token", null);
            }
        } else {
            JSONResponse(404, "User has not registered", null);
        }
    } else {
        JSONResponse(405, "No Such Role", null);
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

//To change this license header, choose License Headers in Project Properties.
//To change this template file, choose Tools | Templates
//and open the template in the editor.
function AddPasswordToken($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function CheckEmail($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function SendEmail($email, $subject, $token, $name,$type)
{
    $headers = "From: " . "Gravo <rpc333@gmail.com>" . "\r\n";
    $headers .= "Reply-To: " . "Gravo <rpc333@gmail.com>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    session_start();
    $_SESSION['name'] = $name;
    $_SESSION['token'] = $token;
    $_SESSION['type'] = $type;

    ob_start();
    include "changePassword.php";
    $message = ob_get_clean();


    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
