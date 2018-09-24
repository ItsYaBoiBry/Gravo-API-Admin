<?php
include 'config.php';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($conn) {
        if (mysqli_query($conn, "DELETE FROM `notifications` WHERE id = $id;")) {
            $msg = array("status" => 200);
            echo JSONResponse($msg);
        } else {
            $msg = array("status" => 400);
            echo JSONResponse($msg);
        }
    } else {
        $msg = array("status" => 403);
        echo JSONResponse($msg);
    }

} else {
    $msg = array("status" => 417);
    echo JSONResponse($msg);
}
function JSONResponse($array)
{
    $json_response = json_encode($array);
    return $json_response;
}