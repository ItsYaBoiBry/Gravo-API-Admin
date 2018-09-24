	<?php
	session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
	include 'config.php';
	$id = $_GET['id'];

	$query_get_item = "SELECT b.id id,recycler_id,r.full_name fullname,r.email recycler_email,description,image,price_quote FROM bulk_transaction_item b , recycler r 
where r.id = b.recycler_id and b.id=$id;" ;
	
	if ($conn) {
    $getcollector = Getcollector($conn, $query_get_item);
		if (!empty($getcollector)) {
			 $id = $getcollector[0]['id'];
         $recycler_id = $getcollector[0]['recycler_id'];
		  $fullname = $getcollector[0]['fullname'];
		  $recycler_email = $getcollector[0]['recycler_email'];
		$description =$getcollector[0]['description'];
		$image =$getcollector[0]['image'];
		$price_quote =$getcollector[0]['price_quote'];
	
		
    } 
		
	}
	
	function Getcollector($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
	
	}
	
	
	?>
	
	<html>
	<head>    
	<title>Edit </title>
	<script language="javascript" type="text/javascript" src="jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
	 <!-- Navigation Bar -->
<div class="topnav">
<div><a class= "active" href="#"><img src="gravo_logo.png" alt="logo" width="50" height="22"></a> </div>
  <a  href="index.php">Collector</a>
  <a href="users.php">User</a>
  <a href="bulkitem.php">Bulk Item Update</a>
 
  <a href="#"><?php echo $_SESSION['username']; ?> </a> 
 
  <div> <a href="index.php?logout='1'">Logout</a> 
 
   
 </div>
</div>
	<a href="bulkitem.php">Back to list</a>
	<br/><br/>
	<form method="post" action="bulkupdate.php">

	
		<table border="0">
			<tr> 
				<td>Bulk ID</td>
				<td><input type="text" id="id"   value="<?php echo $id;?>" readonly >
				 
			</tr>
			<tr> 
				<td>Recycler ID</td>
				<td><input type="text" id="recycler_id" value="<?php echo $recycler_id;?>" readonly></td>
			</tr>
			<tr> 
				<td>Recycler Name	</td>
				<td><input type="text" id="fullname" value="<?php echo $fullname;?>"  readonly></td>
			</tr>
			<tr> 
				<td>Recycler Email	</td>
				<td><input type="text" id="recycler_email" name="recycler_email"value="<?php echo $recycler_email;?>"  readonly></td>
			</tr>
		
			<tr> 
				<td>Description</td>
				<td><input type="text" id="description" value="<?php echo $description;?>" readonly></td>
			</tr>
			
		
			
				<tr> 
				<td>Image</td>
				<td>
				<a href= "<?php  echo $image; ?>" >
				<img src="<?php  echo $image; ?>" width="135" height="135" />
				</a>
				
				</td>
			</tr>
			
			<tr> 
				<td>Price Quote</td> 
				<td><input type="text" id="price_quote" name="price_quote"value="<?php echo $price_quote;?>"  ></td>
			</tr>
		
		
			
			<tr>
				<td><input type="hidden" id="id" name="id" value=<?php echo $id;?>></td> 
				
				<td><input type="submit" name="update" value="Update"  ></td>
			</tr>
		</table>
		</form>
		
		
		<div id= "result" ></div>
		
	
	
	
	</body>
	</html>