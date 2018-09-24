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
	$email = $_GET['email'];

	$query_get_collector = "SELECT * FROM collector WHERE email = '$email'";
	
	if ($conn) {
    $getcollector = Getcollector($conn, $query_get_collector);
		if (!empty($getcollector)) {
			 $id = $getcollector[0]['id'];
         $first_name = $getcollector[0]['first_name'];
		 $last_name = $getcollector[0]['last_name'];
		$email =$getcollector[0]['email'];
		$password =$getcollector[0]['password'];
		$phone =$getcollector[0]['phone'];
		$address = $getcollector[0]['address'];
		$block = $getcollector[0]['block'];
		$unit = $getcollector[0]['unit'];
		$street = $getcollector[0]['street'];
		$postal =$getcollector[0]['postal'];
		$nric = $getcollector[0]['nric'];
		$liscence_number = $getcollector[0]['liscence_number'];
		$vehicle_number = $getcollector[0]['vehicle_number'];
		$status = $getcollector[0]['status'];
		
		if($status ==1 )
		{
			$status='Accepted';
		}
		elseif($status ==2 )
		{
			$status='Rejected';
		}
		elseif($status ==0 )
		{
			$status='Pending';
		}
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
	 <link rel="stylesheet" href="bootstrap.min.css" />
	</head>

	<body>
	 <!-- Navigation Bar -->
 
<div class="topnav">
<div><a class= "active" href="#"><img src="gravo_logo.png" alt="logo" width="52" height="28"></a> </div>
  <a  href="index.php">Collector</a>
  <a href="users.php">User</a>
  <a href="bulkitem.php">Bulk Item Update</a>
  <a href="list.php">Transaction History</a>
 
  <a href="#"><?php echo $_SESSION['username']; ?> </a> 
 
  <div> <a href="index.php?logout='1'">Logout</a> 
 
   
 </div>
</div>
	<a href="index.php">Back to list</a>
	<br/><br/>

	
		<table border="0">
			<tr > 
				<td class="col-md-3">First Name</td>
				<td class="col-md-10"><input class="form-control" type="text" id="first_name" value="<?php echo $first_name;?>"></td>
			</tr> 
			
			<tr> 
				<td class="col-md-3">Last Name</td>
				<td class="col-md-10"><input class="form-control" type="text" id="last_name" value="<?php echo $last_name;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Email	</td>
				<td class="col-md-10"><input class="form-control" type="text" id="email" value="<?php echo $email;?>"></td>
			</tr>
			<tr> 
				
				<td ><input type="hidden" id="password" value="<?php echo "";?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Phone</td>
				<td class="col-md-10"><input class="form-control" type="text" id="phone" value="<?php echo $phone;?>"></td>
			</tr>
			
			<tr> 
				<td class="col-md-3">Address</td>
				<td class="col-md-10"><input class="form-control" type="text" id="address" value="<?php echo $address;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Block</td>
				<td class="col-md-10"><input class="form-control" type="text" id="block" value="<?php echo $block;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Unit</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="unit" value="<?php echo $unit;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Street</td>
				<td class="col-md-10"><input class="form-control" type="text" id="street" value="<?php echo $street;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Postal</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="postal" value="<?php echo $postal;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">NRIC</td>
				<td class="col-md-10"><input class="form-control" type="text" id="nric" value="<?php echo $nric;?>"></td>
			</tr>
			
			<tr> 
				<td class="col-md-3">License Number</td>
				<td class="col-md-10"><input class="form-control" type="text" id="liscence_number" value="<?php echo $liscence_number;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Vehicle Number</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="vehicle_number" value="<?php echo $vehicle_number;?>"></td>
			</tr>
			<tr> 
				<td class="col-md-3">Status</td>
				<td class="col-md-10"><input class="form-control" type="text" id="status" value="<?php echo $status;?>" readonly></td>
			</tr>
			
			<tr>
				<td ><input type="hidden" id="id" value=<?php echo $id;?>></td> 
				
				<td><input type="submit" name="update" value="Update" method = "Post" class="btn btn-success" onclick = "updatedetails();" ></td>
			</tr>
		</table>
		
		
		<div id= "result" ></div>
		
		<div> <h2> Change Status </h2> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<input type="submit" id="accept" value="Accept" method = "post"  class="btn btn-primary" onclick="updatestatus1();">
		<input type="submit" id="reject" value="Reject"  method = "post"  class="btn btn-danger" onclick="updatestatus2();">
		
	</div>
	
	<script type = "text/javascript">
	
	function updatedetails()
	{
		var id = $('#id').val();
		var first_name = $('#first_name').val();
		var last_name = $('#last_name').val();
		var email = $('#email').val();
		var phone = $('#phone').val();
		var address = $('#address').val();
		var block = $('#block').val();
		var unit = $('#unit').val();
		var street = $('#street').val();
		var postal = $('#postal').val();
		var nric = $('#nric').val();
		var status = $('#status').val();
		var liscence_number = $('#liscence_number').val();
		var vehicle_number = $('#vehicle_number').val();
	$.post('update.php',{id:id,first_name:first_name,last_name:last_name,email:email,phone:phone,address:address,status:status,block:block,unit:unit,street:street,postal:postal,nric:nric,liscence_number:liscence_number,
				vehicle_number:vehicle_number}, 
	function(data) 
		{
			$('#result').html('Update Successful');
		
	});
	}
	
	
	function updatestatus1()
	{
		var id = $('#id').val();
		
		
		
	$.post('updatestatus.php',{id:id,status:1}, 
	function(data) 
		{
			$('#result').html('User Accepted');
		
	});
	}
	
	
function updatestatus2()
	{
		var id = $('#id').val();
		
		
		
	$.post('updatestatus.php',{id:id,status:2}, 
	function(data) 
		{
			$('#result').html('User Rejected');
		
	});
	}
	
	
		</script>
	</body>
	</html>