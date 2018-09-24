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
	$id = $_GET['id']; //transaction id

	$query_get_collector = "SELECT distinct t.id,t.collection_date,t.collection_address, CONCAT(c.first_name, ' ', c.last_name) 'Driver',
`collection_postal`,`collection_contact_number`,`collection_user`,
`driver_arrival_time`,`total_price`,`total_weight`,
`remarks`,`transaction_id_key`,
recycler_id,r.full_name Recycler,s.status_type'status'
FROM transaction t,recycler r, collector c,status s 
where t.recycler_id=r.id AND t.status_id=s.id and t.id='$id' and t.collector_id=c.id";




$query = "SELECT id 'collector_id',CONCAT(c.first_name, ' ', c.last_name) 'Driver' FROM collector c";
$result1 = mysqli_query($conn, $query);


	
	if ($conn) {
    $getcollector = Getcollector($conn, $query_get_collector);
		if (!empty($getcollector)) {
			 $id = $getcollector[0]['id'];
         $collection_date = $getcollector[0]['collection_date'];
		  $driver = $getcollector[0]['Driver'];
		 $collection_address = $getcollector[0]['collection_address'];
		$collection_postal =$getcollector[0]['collection_postal'];
		$collection_contact_number =$getcollector[0]['collection_contact_number'];
		$collection_user =$getcollector[0]['collection_user'];
		$driver_arrival_time = $getcollector[0]['driver_arrival_time'];
		$total_price = $getcollector[0]['total_price'];
		$total_weight = $getcollector[0]['total_weight'];
		$remarks = $getcollector[0]['remarks'];
		$transaction_id_key =$getcollector[0]['transaction_id_key'];
		$Recycler = $getcollector[0]['Recycler'];
		$status = $getcollector[0]['status'];
		
		
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
	<title>Transaction Detail </title>
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
	<a href="list.php">Back to list</a>
	<br/><br/>

	
		<table border="0">
			<tr> 
				<td class="col-md-3">Transaction ID</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="id" value="<?php echo $id;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Driver</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="driver" value="<?php echo $driver;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Collection Date</td>
				<td class="col-md-10"><input  class="form-control"type="text" id="collection_date" value="<?php echo $collection_date;?>" readonly> </td>
			</tr>
			<tr> 
				<td class="col-md-3">Collection Address	</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="collection_address" value="<?php echo $collection_address;?>" readonly></td>
			</tr>
			<tr> 
				
				<td class="col-md-3">Collection Postal</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="collection_postal" value="<?php echo $collection_postal;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Contact Number</td>
				<td class="col-md-10"><input  class="form-control"type="text" id="collection_contact_number" value="<?php echo $collection_contact_number;?>" readonly></td>
			</tr>
			
			<tr> 
				<td class="col-md-3">Collection User</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="collection_user" value="<?php echo $collection_user;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Driver Arrival Time</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="driver_arrival_time" value="<?php echo $driver_arrival_time;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Total Price</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="total_price" value="<?php echo $total_price;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Total Weight</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="total_weight" value="<?php echo $total_weight;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Remarks</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="remarks" value="<?php echo $remarks;?>" readonly></td>
			</tr>
			<tr> 
				<td class="col-md-3">Transaction ID key</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="transaction_id_key" value="<?php echo $transaction_id_key;?>" readonly></td>
			</tr>
			
			<tr> 
				<td class="col-md-3">Recycler</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="Recycler" value="<?php echo $Recycler;?>" readonly> </td>
			</tr>
			
			<tr> 
				<td class="col-md-3">Status</td>
				<td class="col-md-10"><input  class="form-control" type="text" id="status" value="<?php echo $status;?>" readonly></td>
			</tr>
			
		<!--	<tr>
				<td><input type="hidden" id="id" value=<?php echo $id;?>></td> 
				
				<td><input type="submit" name="update" value="Update" method = "Post"  onclick = "updatedriver();" ></td>
			</tr>
			-->
		</table>
		
		
		<div> 
		
		<h2> Update Driver</h2>
		<input type="hidden" id="id" value=<?php echo $id;?> />
		
		<select name="collect_id" id="collect_id" class="col-md-3" > 

            <?php while($row1 = mysqli_fetch_array($result1)):;?>

            <option name= "collector_id" value = "<?php echo $row1['collector_id'];?>"><?php echo $row1['Driver'];?></option>

            <?php endwhile;?>

        </select>
		
		<!--	<input type="hidden" id="collect_id" name= "collect_id" /> -->
		
		<input type="submit" name="update" value="Update" method = "Post"  class="btn btn-success" onclick = "updatedriver();" />
		
		
		</div>
		
		
		<div id= "result" ></div>
		

	
	<script type = "text/javascript">
	

		
	function updatedriver()
	{
		var trans_id = $('#id').val();
		var collector_id = $('#collect_id').val();
				
	$.post('updatedriver.php',{trans_id:trans_id,collector_id:collector_id}, 
	function(data) 
		{
			$('#result').html('Update Successful');
		
	});
	}
	
	
	
	
		</script>
	</body>
	</html>