<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 7/8/2018
 * Time: 3:03 PM
 */
include 'config.php';
$transactionid = $_POST['id'];

$query_get_transaction_status = "SELECT t.*, s.status_type FROM transaction t, status s WHERE t.status_id = s.id AND t.id = $transactionid;";
$query_update_transaction = "UPDATE transaction SET status_id = 8 WHERE id = $transactionid;";
$query_add_history = "INSERT INTO transaction_history(transaction_id, status) VALUES ($transactionid, 'Transaction has been cancelled by you');";
if ($conn) {
    $getstatus = mysqli_query($conn, $query_get_transaction_status) or die(json_encode($response['error'] = mysqli_error($conn)));
    $data = array();
    while ($row = mysqli_fetch_assoc($getstatus)) {
        $data[] = $row;
    }
    if ($data != []) {
        if ($data[0]['status_id'] == 3 or $data[0]['status_id'] == 4 or $data[0]['status_id'] == 5) {
            $id = $data[0]['status_id'];
            $type = $data[0]['status_type'];
            $response['status'] = 400;
            $response['message'] = "status error :: status_id $id($type)is not suitable for editing";
            echo json_encode($response);
        } else {
            $updateschedule = mysqli_query($conn, $query_update_transaction) or die(json_encode($response['error'] = mysqli_error($conn)));
            if ($updateschedule) {
                $updatehistory = mysqli_query($conn, $query_add_history) or die(json_encode($response['error'] = mysqli_error($conn)));
                if ($updatehistory) {
                    session_start();
                    $_SESSION['transactioncode'] = $data[0]['transaction_id_key'];
                    $_SESSION['transactionaddress'] = $data[0]['collection_address'];
                    $_SESSION['transactionpostal'] = $data[0]['collection_postal'];
                    $_SESSION['transactionuser'] = $data[0]['collection_user'];
                    $_SESSION['transactionnumber'] = $data[0]['collection_contact_number'];
                    $_SESSION['transactionweight'] = $data[0]['total_weight'];
                    $_SESSION['transactionprice'] = $data[0]['total_price'];

                    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    ob_start();
                    include "cancellation.php";
                    $message = ob_get_clean();

                    if (mail("bryanlowsk@gmail.com, rpc333@gmail.com, recycling@greenravolution.com.sg", "transaction cancelled", $message, $headers)) {
                        $response['status'] = 200;
                        $response['message'] = "OK :: transaction has been cancelled";
                        echo json_encode($response);
                    } else {
                        $response['status'] = 200;
                        $response['message'] = "OK :: email error :: email to admin has failed to send";
                        echo json_encode($response);
                    }
                } else {
                    $response['status'] = 200;
                    $response['message'] = "OK :: history error :: server is unable to add history into the transaction";
                    echo json_encode($response);
                }
            } else {
                $response['status'] = 400;
                $response['message'] = "update error :: server is unable to update details";
                echo json_encode($response);
            }
        }
    } else {
        $response['status'] = 404;
        $response['message'] = "data error :: there is no such transaction in the database";
        echo json_encode($response);
    }
} else {
    $response['status'] = 403;
    $response['message'] = "unauthorized :: you do not have access to the database $database";
    echo json_encode($response);
}