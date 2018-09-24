<?php

include '../config.php';
$newpassword = sha1($_POST['newpassword']);
$token = $_POST['token'];
$type = $_POST['type'];
$query_changepassword = "";
if($type == "collector"){
$query_changepassword = "UPDATE collector SET password = '$newpassword' where forgotpassword = '$token'";
}else if($type == "recycler"){
$query_changepassword = "UPDATE recycler SET password = '$newpassword' where forgotpassword = '$token'";
}


$changepassword = ChangePassword($conn, $query_changepassword);
if ($conn) {
    if ($changepassword) {?>
       <html>
        <head>
            <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="http://www.w3schools.com/w3css/4/w3.css">
            <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
            <meta charset="UTF-8">
            <title>Change Password</title>
        </head>
        <body>
            <div style="margin:20px">
                <center>
                    <h1>Your Password has been updated!<br>You can now log in with your new password</h1>
                </center>
            </div>
        </body>
    </html><?php
    } else {?>
        <html>
        <head>
            <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="http://www.w3schools.com/w3css/4/w3.css">
            <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
            <meta charset="UTF-8">
            <title>Change Password</title>
        </head>
        <body>
            <div style="margin:20px">
                <center>
                    <h1>We are unable to udpate your password!<br>We apologize for the inconvenience caused.<br> please try again in a few minutes</h1>
                </center>
            </div>
        </body>
    </html><?php
    }
}else{
     JSONResponse(403, "Unauthorized",null);
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

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

