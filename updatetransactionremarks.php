<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 17/8/2018
 * Time: 2:57 PM
 */
include 'config.php';
$id = $_POST['id'];
$remarks = addslashes($_POST['remarks']);

$query_update_item = "UPDATE `transaction` SET `remarks`='$remarks' WHERE `id` = $id";
$query_select_item = "SELECT * FROM `transaction` WHERE `id` = $id";
if ($conn) {
    $update = mysqli_query($conn, $query_update_item) or die(json_encode($reponse["error"] = mysqli_error($conn)));
    if ($update) {
        $get = mysqli_query($conn, $query_select_item) or die(json_encode($response['error'] = mysqli_error($conn)));
        $data = array();
        while ($rows = mysqli_fetch_assoc($get)) {
            $data[] = $rows;
        }
        if ($data != []) {
            $response['status'] = 200;
            $response['message'] = "OK";
            $response['result'] = $data;
            echo json_encode($response);
        } else {
            $response['status'] = 404;
            $response['message'] = "NO DATA";
            echo json_encode($response);
        }
    } else {
        $response['status'] = 400;
        $response['message'] = "FAILED";
        echo json_encode($response);
    }

} else {
    $response['status'] = 403;
    $response['message'] = "UNAUTHORIZED";
    echo json_encode($response);
}
