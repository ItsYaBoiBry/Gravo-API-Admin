<?php
include 'config.php';
if (isset($_POST['id'])) {
    $getid = $_POST['id'];
    $query_getUserDetails = "SELECT R.*, A.total_points, AR.rank_name FROM recycler R, achievements A, achievement_rank AR WHERE A.achievement_rank_id = AR.id AND R.id = $getid;";
    $getuserachievements = GetUserAchievement($conn, "SELECT id, recycler_id FROM achievements WHERE recycler_id = $getid;");
    if ($getuserachievements != []) {
        $achievementid = $getuserachievements[0]['id'];
        $addachievementdetailsquery = "INSERT INTO achievement_points(achievement_name, points, achievements_id, achievements_recycler_id, category_id) VALUES ('Paper','0',$achievementid,$getid,'paper'),('Metals','0',$achievementid,$getid,'metals'),('E-waste','0',$achievementid,$getid,'ewaste'),('Total KG Recycled (2018)','0',$achievementid,$getid,'total_kg'),('Total $ Recieved (2018)','0',$achievementid,$getid,'total_price'),('CO2 Emission Avoided-(kgCO2e)','0',$achievementid,$getid,'total_co2'),('Carbon Sink-(Trees Saved)','0',$achievementid,$getid,'total_trees');";
        $addachievementdetails = AddAchievementDetail($conn, $addachievementdetailsquery);
        if ($addachievementdetails) {
            $getfinaldetails = GetUser($conn, $query_getUserDetails);
            if ($getfinaldetails != []) {
                JSONResponse(200, "user added", $getfinaldetails);
            } else {
                JSONResponse(400, "failed to get user details", $getfinaldetails);
            }
        } else {
            JSONResponse(400, "unable to add achievement s", null);
        }
    }
} else {
    JSONResponse(404, "Unable to get id", null);

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

function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
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