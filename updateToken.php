<?php
        include 'config.php';
        if(isset($_POST["token"]) && isset($_POST["userid"])){
          
            
            $token = $_POST["token"];
            $userID = $_POST["userid"]; 


             if(mysqli_connect_errno($conn)){
                 echo 'Failed to connect to database: '.mysqli_connect_error();
                 JSONResponse(400, "Failed to connect to database:", mysqli_connect_error()+"");
            } else { 
                $query = "UPDATE recycler SET token = '$token' WHERE id = '$userID'";
                mysqli_query($conn,$query);
                mysqli_close($conn);
                JSONResponse(200, "Successfully register token", $token+"");
            }
            
        } else {
            JSONResponse(400, "Failed to register ", null);
        }
        
        function JSONResponse($status, $status_message, $data) {
            $response['status'] = $status;
            $response['message'] = $status_message;
            $response['result'] = $data;

            $json_response = json_encode($response);
            echo $json_response;
        }
 
?>
