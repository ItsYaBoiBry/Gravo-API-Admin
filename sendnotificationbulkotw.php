<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 21/9/2018
 * Time: 5:44 PM
 */

function send_notification($token, $id)
{
    define('API_ACCESS_KEY', 'AIzaSyDe_IMHcOZQcZnK65C7F-1wlnoqz2HWrjo');
    //   $registrationIds = ;
    $msg = array
    (

        'body' => 'Your collector has for #' . $id . ' has arrived! Please get ready!',
        'title' => 'Collector arrived'

    );
    $fields = array
    (
        'to' => $token,
        'priority' => "high",
        'notification' => $msg,
        'timestamp' => date('Y-m-d H:i:s'),
        'data' => $msg
    );
    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    echo $result;
    curl_close($ch);
}

include 'config.php';
date_default_timezone_set('Asia/Singapore');

if ($conn) {
    if (isset($_POST["userID"])) {

        $user_id = $_POST["userID"];
        $transactionid = $_POST["transaction_code"];
        mysqli_query($conn, "INSERT INTO notifications(recycler_id, title, message) VALUES ($user_id,'Collector arrived!','Yout Collector for #".$transactionid."has arrived! Please get ready!');");

        $sql = "SELECT token FROM recycler WHERE id = '$user_id'";
        $getToken = mysqli_query($conn, $sql);

        if (empty($getToken)) {
            JSONResponse(404, "User does not have a token yet", null);
        } else {
            $data[] = mysqli_fetch_assoc($getToken);
            $token = $data[0]["token"];
            mysqli_close($conn);
            $message = array("message" => "Driver is on the way!!");
            send_notification($token, $transactionid);
            JSONResponse(200, "Success", $token);
        }
    } else {
        JSONResponse(403, "No data received", null);
    }
} else {
    JSONResponse(403, "Unauthorised", null);
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $json_response = json_encode($response);
    //echo $json_response;
}

