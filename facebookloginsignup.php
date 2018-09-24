<?php

include 'config.php';

/* @var $_POST type */
$fbid = $_POST['facebook_id'];
$fullname = $_POST['fullname'];
$getEmail = $_POST['email'];
$getContactNumber = $_POST['contactnumber'];
$getAddress = $_POST['address'];
$getToken = $_POST['token'];


$query_insert = "INSERT INTO recycler (id,facebook_id, first_name, last_name,full_name, email, password, contact_number, address,token) VALUES (NULL, '$fbid','', '','$fullname', '$getEmail', 'facebooklogin', '$getContactNumber', '$getAddress','$getToken');";
$query_check_email = "SELECT * FROM recycler WHERE facebook_id = '$fbid'";
$query_get_user = "SELECT id FROM recycler WHERE facebook_id = '$fbid'";
$query_login = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE R.id = A.recycler_id AND A.achievement_rank_id = AR.id AND facebook_id = '$fbid';";


if ($conn) {
    $checkEmail = CheckEmail($conn, $query_check_email);
    if ($checkEmail != []) {
        //have details
        $doLogin = Login($conn, $query_login);
        if ($doLogin != []) {
            JSONResponse(200, "facebook login Success", $doLogin);
        } else {
            JSONResponse(403, "Wrong email login", null);
        }

    } else {
        //no details
        //register
        $register = Register($conn, $query_insert);
        if ($register == true) {
            $getuser = GetUser($conn, $query_get_user);
            if ($getuser != []) {
                $getId = $getuser[0]["id"];
                $query_init_achievements = "INSERT INTO achievements (total_points, recycler_id, achievement_rank_id) VALUES (50, $getId, 1);";
                $query_getUserDetails = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE R.id = A.recycler_id AND A.achievement_rank_id = AR.id AND R.id = $getId;";
                $query_add_cart = "INSERT INTO cart (id, recycler_id) VALUES (NULL, $getId);";
                $addachievements = AddAchievements($conn, $query_init_achievements);
                if ($addachievements == true) {
                    $addcart = AddCart($conn, $query_add_cart);
                    if ($addcart) {
                        $getalldetails = GetUser($conn, $query_getUserDetails);
                        if ($getalldetails != []) {
                            $user = $getalldetails[0];
                            $userid = $user["id"];
                            $newPath = "http://www.ehostingcentre.com/gravo/uploads/b2ab857f40d15ec732311b4229e4e145678a5b21.png";
                            $query_upload_image = "UPDATE recycler SET photo = '$newPath' WHERE id = $userid";
                            $result = mysqli_query($conn, $query_upload_image) or die(json_encode($response["error"] = mysqli_error($conn)));
                            if ($result) {
                                $getfinaldetails = GetUser($conn, $query_getUserDetails);
                                if ($getfinaldetails != []) {
                                    JSONResponse(201, "user added", $getfinaldetails);
                                } else {
                                    JSONResponse(400, "failed to get user details", $getfinaldetails);
                                }
                            } else {
                                JSONResponse(400, "failed to add user image", $getalldetails);
                            }


                        }
                    } else {
                        JSONResponse(400, "Failed to add User Cart", null);
                    }
                } else {
                    JSONResponse(400, "Unable to get User Details", $getalldetails);
                }
            } else {
                JSONResponse(400, "Failed to add User Achievements", null);
            }
        } else {
            JSONResponse(400, "Failed to add User", null);
        }
    }
} else {
    JSONResponse(403, "Unauthorised", null);
}

function AddCart($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
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

function Register($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function AddAchievements($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetUser($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function Login($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

