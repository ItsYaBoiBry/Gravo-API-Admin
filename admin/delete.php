
<?php
include("config.php");


//getting id of the data from url
$email	 = $_GET['email'];
$query = "Delete FROM collector where email='$email'";
if($conn)
{
//$result = mysqli_query($conn, "Delete FROM collector where email=$email"); 
 $result = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
  
 if($result)
 {
	  echo $email;
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