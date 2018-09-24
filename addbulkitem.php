<?php

/**
 * Created by PhpStorm.
 * User: asus
 * Date: 19/7/2018
 * Time: 9:44 AM
 */
include 'config.php';
header('Content-Type: application/json');
$getImage = $_POST['image'];
$getDescription = $_POST['description'];
$id = $_POST['id'];
$imagename = sha1($id + time());
$gettoken = "BT";
$gettoken .= $userid;
$gettoken .= date("dyHms");

$uploadresult = UploadImage($imagename, $getImage);
if ($uploadresult == "null") {
    JSONResponse(400, "Unable to Upload image to server", null);
} else {
    $query_add_transaction = "INSERT INTO `bulk_transaction_item`(`id`, `recycler_id`, `description`, `image`, `price_quote`,`bulk_transaction_status_id`, `collection_date`, `transaction_id_key`) VALUES (NULL ,$id,'$getDescription','$uploadresult','Waiting for quote',1,'','$gettoken');";
    if (SQLBoolean($conn, $query_add_transaction)) {
        $query_get_all_bulk = "SELECT b.*, r.full_name, r.email, r.address FROM bulk_transaction_item b, recycler r WHERE b.recycler_id = r.id AND b.recycler_id = $id ORDER BY b.id DESC LIMIT 1;";
        $getallbulk = SQLData($conn, $query_get_all_bulk);
        if ($getallbulk != []) {
            $userid = $getallbulk[0]['recycler_id'];
            $userimage = $getallbulk[0]['image'];
            $username = $getallbulk[0]['full_name'];
            $useremail = $getallbulk[0]['email'];
            $useraddress = $getallbulk[0]['address'];
            if (SendAdminEmail("New Bulk Item", $username, $userimage, $getDescription, $useraddress)) {
                if (SendEmail($useremail, $username, $gettoken)) {
                    $query_select_all_bulk = "SELECT b.*, r.full_name, r.email, r.address FROM bulk_transaction_item b, recycler r WHERE b.recycler_id = r.id AND b.recycler_id = $userid ORDER BY b.id DESC;";
                    $getuserbulk = SQLData($conn, $query_select_all_bulk);
                    if ($getuserbulk != []) {
                        $id = $getuserbulk[0]['id'];
                        if(mysqli_query($conn,"INSERT INTO bulk_transaction_history (bulk_transaction_item_id,history) VALUES ($id,'$username has added a bulk item');")){
                            $query_get_history = "SELECT bh.* FROM bulk_transaction_history bh WHERE bh.bulk_transaction_item_id = $id;";
                            $history = SQLData($conn,$query_get_history);
                            JSONResponse2(200, "Bulk Data Added", $getuserbulk,$history);
                            
                        }

                    } else {
                        JSONResponse(200, "Unable to get bulk transactions", $getuserbulk);
                    }
                }
            } else {
                JSONResponse(400, "Unable to Send email to admin", null);
            }
        }
    } else {
        JSONResponse(400, "Unable to Upload Bulk item to server", null);
    }
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
function JSONResponse2($status, $status_message, $data, $history)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;
    $response['history'] = $history;

    $json_response = json_encode($response);
    echo $json_response;
}

function SQLBoolean($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function SQLData($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function UploadImage($photo_name, $encoded_string)
{
    $decoded_string = base64_decode($encoded_string);
    $path = "bulk/" . $photo_name . ".png";
    $file = fopen($path, "wb");
    $is_written = fwrite($file, $decoded_string);
    fclose($file);

    if ($is_written > 0) {
        return "http://ehostingcentre.com/gravo/bulk/" . $photo_name . ".png";
    } else {
        return "null";
    }
}

function SendAdminEmail($subject, $name, $link, $description, $address)
{
    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "Reply-To: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = "<html><body>";
    $message .= "<h1>New Bulk Item</h1><br>";
    $message .= "<br>You have a new big item from $name<br><br>";
    $message .= "<br>Item Description:<br>$description<br><br>Pickup Address:<br>$address<br><br><img src='$link'/> ";
    $message .= "</body></html>";
    if (mail("bryanlowsk@gmail.com, rcp333@gmail.com", $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

function SendEmail($email, $name, $transactionid)
{

    session_start();
    $_SESSION['name'] = $name;


    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "Reply-To: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    ob_start();
    include "bulkEnquiry.php";
    $message = ob_get_clean();

    $subject = "Big Item Submission ($transactionid)";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
