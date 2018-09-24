<?php
include 'config.php';
$getName = $_POST['name'];
$getEmail = $_POST['email'];
$getNumber = $_POST['number'];
$getMessage = $_POST['message'];

if($conn){
    if(SendAdminEmail("bryanlowsk@gmail.com, rpc333@gmail.com, recycling@greenravolution.com.sg","Feedback",$getName,$getMessage,$getNumber)){
        if(SendUserEmail($getName, $getEmail,"FeedBack","")){
            JSONResponse(200,"OK",null);
        }else{
            JSONResponse(400,"unable to send email to user",null);   
        }
    }else{
        JSONResponse(400,"unable to send email to admin",null);
    }
}else{
    JSONResponse(402, "Unable to connect to the database", null);
}

function SendUserEmail($name, $email, $subject) {
	session_start();
	$_SESSION['name'] = $name;
	
	$headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	ob_start();
	include "feedbackEmail.php";
	$message = ob_get_clean(); 
	
	
	if (mail($email, $subject, $message, $headers)) {
		return true;
	} else {
		return false;
	}
}
function SendAdminEmail($email, $subject, $name, $messages,$number) {
    
    	session_start();
	$_SESSION['name'] = $name;
	
	$headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	ob_start();
	include "feedbackEmail.php";
	$message = ob_get_clean(); 
	
	if (mail($email, $subject, $message, $headers)) {
		return true;
	} else {
		return false;
	}
}
function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}