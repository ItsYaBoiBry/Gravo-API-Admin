<?php

include 'config.php';
$userid = $_POST['userid'];
$query_select_user = "SELECT * FROM recycler WHERE id = $userid";

if ($conn) {
    $users = GetUser($conn, $query_select_user);
    if ($users != []) {
        $user = $users[0];
        $id = $user["id"];
        $firstname = $user["first_name"];
        $email = $user["email"];
        $date = time();
        $image_name = sha1($_POST["image_name"]);
        $photo_name = sha1("$email" . "$firstname" . "$date" . "$image_name");

        if (isset($_POST["encoded_string"])) {
            $encoded_string = $_POST["encoded_string"];


            $decoded_string = base64_decode($encoded_string);

            $path = "uploads/" . $photo_name . ".png";
            $newPath = "http://www.ehostingcentre.com/gravo/uploads/".$photo_name.".png";
            

            $file = fopen($path, "wb");
            $is_written = fwrite($file, $decoded_string);
            fclose($file);

            if ($is_written > 0) {
                $query_upload_image = "UPDATE recycler SET photo = '$newPath' WHERE id = $userid";
                $result = mysqli_query($conn, $query_upload_image) or die(json_encode($response["error"] = mysqli_error($conn)));
                if ($result) {
                	$getusers = GetUser($conn, $query_select_user);
                	if($getusers !=[]){
                	JSONResponse(200, "Success", $getusers);
                	}else{
                	JSONResponse(400, "Success but unable to get user", null);
                	}
                    
                } else {
                    JSONResponse(400, "failed", null);
                }
            }
        }
    } else {
        JSONResponse(404, "User not in database", null);
    }
} else {
    JSONResponse(403, "Unauthorised", null);
}

function GetUser($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function JSONResponse($status, $status_message, $data) {
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
