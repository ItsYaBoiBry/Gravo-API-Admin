<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 7/8/2018
 * Time: 11:39 AM
 */
include 'config.php';
$transactionid = $_POST['id'];
$transactiontime = $_POST['time'];
$transactiondate = $_POST['date'];
$query_get_transaction_status = "SELECT t.*, s.status_type FROM transaction t, status s WHERE t.status_id = s.id AND t.id = $transactionid;";
$query_update_transaction = "UPDATE transaction SET collection_date = '$transactiondate', collection_date_timing = '$transactiontime' WHERE id = $transactionid;";
$query_add_history = "INSERT INTO transaction_history(transaction_id, status) VALUES ($transactionid, 'Transaction rescheduled to " . dateformattodate($transactiondate) . " (" . $transactiontime . ")" . "');";
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
                    $_SESSION['transactiondate'] = dateformattodate($transactiondate);
                    $_SESSION['transactiontime'] = $transactiontime;



                    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    ob_start();
                    include "rescheduling.php";
                    $message = ob_get_clean();

                    if(mail("bryanlowsk@gmail.com, rpc333@gmail.com, recycling@greenravolution.com.sg", "Rescheduling of transaction", $message, $headers)){
                        $response['status'] = 200;
                        $response['message'] = "OK :: details have successfully been updated";
                        echo json_encode($response);
                    }else{
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
function dateformattodate($date)
{
    $dates = explode("-", $date);
    switch ($dates[1]) {
        case "01":
            return $dates[2] . " January " . $dates[0];
            break;
        case "02":
            return $dates[2] . " February " . $dates[0];
            break;
        case "03":
            return $dates[2] . " March " . $dates[0];
            break;
        case "04":
            return $dates[2] . " April " . $dates[0];
            break;
        case "05":
            return $dates[2] . " May " . $dates[0];
            break;
        case "06":
            return $dates[2] . " June " . $dates[0];
            break;
        case "07":
            return $dates[2] . " July " . $dates[0];
            break;
        case "08":
            return $dates[2] . " August " . $dates[0];
            break;
        case "09":
            return $dates[2] . " September " . $dates[0];
            break;
        case "10":
            return $dates[2] . " October" . $dates[0];
            break;
        case "11":
            return $dates[2] . " November " . $dates[0];
            break;
        case "12":
            return $dates[2] . " December " . $dates[0];
            break;
        default:
            return "Date Unavailable";
            break;

    }
}
