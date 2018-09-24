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
  
include_once("config.php");

$result = mysqli_query($conn, "SELECT t.id,t.collection_date,t.collection_address,CONCAT(c.first_name, ' ', c.last_name) 'Driver',r.full_name Recycler,s.status_type'status' FROM transaction t,recycler r, collector c,status s where t.recycler_id=r.id AND t.status_id=s.id and t.collector_id = c.id"); 
$query = "SELECT id,CONCAT(c.first_name, ' ', c.last_name) 'Driver' FROM collector c";
$result1 = mysqli_query($conn, $query);
?>

 
<html>
<head>    
    <title>Transaction History</title>
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
<!--
<div class="content">
  	<!-- notification message 
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
<!--
    <!-- logged in user information 
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
	
</div> 
-->
<div> <br/>  <br/<br/> </div>
<div align="left"> <h2>Transaction Details</h2>
<div align ="right"> <input type="text" placeholder="Search.."> </div>
<div> <br/>  <br/<br/> 
<div>



 </div></div>
 
 <div align ="center">  <label for="y">Select the Driver:</label>
   <select id="driver" name="driver"    >

            <?php while($row1 = mysqli_fetch_array($result1)):;?>
            <option ><?php echo $row1['Driver'];?></option>
            <?php endwhile;?>
  </select>
 
 </div>


    <table width='80%' border=0 id= "show_table" align="center">
        <tr bgcolor='#CCCCCC'>
            <th>Transaction ID</th>
            <th>Collection Date</th>
            <th>Collection Address</th>
			 <th>Driver </th>
			<th>Recycler</th>
            <th>Status</th>
			 <th>Details</th>
           
        </tr>
        <?php 
        
        while($res = mysqli_fetch_array($result)) {     

	
		
            echo "<tr>";
			 echo "<td>".$res['id']."</td>";
            echo "<td>".$res['collection_date']."</td>";
            echo "<td>".$res['collection_address']."</td>";
			 echo "<td>".$res['Driver']."</td>";
			 echo "<td>".$res['Recycler']."</td>";
			 echo "<td>".$res['status']."</td>";
         
            echo "<td><a href=\"tran_detail.php?id=$res[id]\">Details</a> </td>";        
        }
        ?>
    </table>
</body>
</html>



 <script>  
 $(document).ready(function(){  
      $('#driver').change(function(){  
           var driver_id = $(this).val();  
           $.ajax({  
                url:"load_data.php",  
                method:"GET",  
                data:{driver_id:driver_id},  
                success:function(data){  
                     $('#show_table').html(data);  
                }  
           });  
      });  
 });  
 </script> 