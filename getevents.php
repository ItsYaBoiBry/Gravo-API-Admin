<?php
include 'config.php';
$query_select_events = "select * from events;";
if($conn){
   $resultset = mysqli_query($conn,$query_select_events) or die(json_encode($response["error"]=mysqli_error($conn)));
   $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    if($data!=[]){
    	JSONResponse(200,"events retrieved",$data);
    }else{
    	JSONResponse(404,"No events", $data);
    }
   
}else{
   JSONResponse(403,"Unauthorized",null);
}
function JSONResponse($status, $status_message, $data)
{
    $response['status'] = $status;
    $response['message'] = $status_message;
    $response['result'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}