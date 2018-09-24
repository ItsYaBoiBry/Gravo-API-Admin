<?php

include 'config.php';

/* @var $_POST type */
$getFirstName = $_POST['firstname'];
$getLastName = $_POST['lastname'];
$getFullName = $getFirstName." ".$getLastName;
$getEmail = $_POST['email'];
$getPassword = sha1($_POST['password']);
$getContactNumber = $_POST['contactnumber'];
$getAddress = $_POST['address'];
$getBlock = $_POST['block'];
$getUnit = $_POST['unit'];
$getStreet = $_POST['street'];
$getPostal = $_POST['postal'];


$query_insert = "INSERT INTO recycler (id, first_name, last_name, full_name, email, password, contact_number, address, block, unit, street, postal) VALUES (NULL, '$getFirstName', '$getLastName', '$getFullName','$getEmail', '$getPassword', '$getContactNumber', '$getAddress', '$getBlock', '$getUnit', '$getStreet', '$getPostal');";
$query_check_email = "SELECT email FROM recycler WHERE email = '$getEmail'";
$query_get_user = "SELECT id FROM recycler WHERE email = '$getEmail'";

if ($conn) {
    $checkEmail = CheckEmail($conn, $query_check_email);
    if ($checkEmail != []) {
        JSONResponse(404, "User has registered", null);
    } else {
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
                            $firstname = $user["first_name"];
                            $email = $user["email"];
                            $date = time();

                            $newPath = "http://www.ehostingcentre.com/gravo/uploads/b2ab857f40d15ec732311b4229e4e145678a5b21.png";

                            $query_upload_image = "UPDATE recycler SET photo = '$newPath' WHERE id = $userid";
                            $result = mysqli_query($conn, $query_upload_image) or die(json_encode($response["error"] = mysqli_error($conn)));
                            if ($result) {
                                $query_getUserDetails = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE R.id = A.recycler_id AND A.achievement_rank_id = AR.id AND R.id = $userid;";
                                $getuserachievements = GetUserAchievement($conn, "SELECT id FROM achievements WHERE recycler_id = $getId;");
                                if ($getuserachievements != []) {
                                    $achievementid = $getuserachievements[0]['id'];
                                    $addachievementdetailsquery = "INSERT INTO achievement_points(achievement_name, points, achievements_id, achievements_recycler_id, category_id) VALUES ('Paper','0',$achievementid,$userid,'paper')
,('Metals','0',$achievementid,$userid,'metals')
,('E-waste','0',$achievementid,$userid,'ewaste')
,('Total KG Recycled (2018)','0',$achievementid,$userid,'total_kg')
,('Total $ Recieved (2018)','0',$achievementid,$userid,'total_price')
,('CO2 Emission Avoided-(kgCO2e)','0',$achievementid,$userid,'total_co2')
,('Carbon Sink-(Trees Saved)','0',$achievementid,$userid,'total_trees');";
                                    $addachievementdetails = AddAchievementDetail($conn, $addachievementdetailsquery);
                                    if ($addachievementdetails) {

                                        $getfinaldetails = GetUser($conn, $query_getUserDetails);
                                        if ($getfinaldetails != []) {
                                            JSONResponse(200, "user added", $getfinaldetails);
                                            sendEmail($getEmail,$getFirstName);
                                            SendAdminEmail($getFirstName);
                                        } else {
                                            JSONResponse(400, "failed to get user details", $getfinaldetails);
                                        }
                                    }else{
                                        JSONResponse(400, "unable to add achievement 1", null);
                                    }

                                }

                            } else {
                                JSONResponse(400, "failed to add user image", $getalldetails);
                            }
                        } else {
                            JSONResponse(400, "Failed to add User Cart", null);
                        }
                    } else {
                        JSONResponse(400, "Unable to get User Details", null);
                    }
                } else {
                    JSONResponse(400, "Failed to add User Achievements", null);
                }
            } else {
                JSONResponse(400, "Failed to add User", null);
            }
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

function AddAchievementDetail($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetUserAchievement($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function sendEmail($email,$name) {

    session_start();
    $_SESSION['name'] = $name;


    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    ob_start();
    include "registerEmail.php";
    $message = ob_get_clean();

    $subject = "Gravo";

    if (mail($email, $subject, $message, $headers)) {
        echo"success";
    } else {
        echo"failed";
    }
}

function SendAdminEmail($name) {

    session_start();
    $_SESSION['name'] = $name;


    $headers = "From: " . "Gravo <recycling@greenravolution.com.sg>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    ob_start();
    include "registerEmail.php";
    $message = ob_get_clean();

    $subject = "Gravo";

    if (mail("rpc333@gmail.com", $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}