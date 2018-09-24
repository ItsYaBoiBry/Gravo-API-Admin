
<?php
include("config.php");


//getting id of the data from url
$id	 = $_GET['id'];
$query = "Delete FROM bulk_transaction_item where id='$id'";
if($conn)
{

 $result = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
  
 if($result)
 {
	
	 echo "Deleted Successfully";
 }
}
else
{
	echo "Database not connected";
}
//redirecting to the display page (index.php in our case)
header("Location:index.php");

?>