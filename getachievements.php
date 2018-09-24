<?php 

include 'config.php';
$userid = $_GET['id'];
$query_get_achievements = "SELECT * FROM achievement_points WHERE achievements_recycler_id = $userid";
$getach = GetQueries($conn, $query_get_achievements);
if($getach!=[]){
     JSONResponse(200,"OK",$getach);
}else{
    JSONResponse(404,"No Achievements",$getach);
}


function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
function GetQueries($conn, $query)
{
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}