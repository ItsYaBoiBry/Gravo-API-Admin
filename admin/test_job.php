<?php

include 'config.php';

$query = "insert into tb (id,time) values (2,CURRENT_TIMESTAMP())";
  $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    if( $resultset)
	{
		echo "insert successfull";
	}
	else 
	{
		
		echo "insert failed";
	}
		

	?>