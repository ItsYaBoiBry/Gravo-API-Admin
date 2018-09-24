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

$result = mysqli_query($conn, "SELECT b.id id,recycler_id,r.full_name fullname,description,image,price_quote FROM bulk_transaction_item b , recycler r 
where r.id = b.recycler_id;"); 

?>

 
<html>
<head>    
    <title>Bulk Items</title>
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
<div align="center"> <h2>Bulk Items</h2></div>
<div> <br/>  <br/<br/> </div>
    <table width='80%' border=0 id= "show_table" align="center">
        <tr bgcolor='#CCCCCC'>
            <th>Bulk ID</th>
            <th>Recycler ID</th>
            <th>Recycler Name</th>
			<th>Description</th>
            <th>Image</th>
			 <th>Update</th>
           
        </tr>
        <?php 
        
        while($res = mysqli_fetch_array($result)) {     

	
		
            echo "<tr>";
			 echo "<td>".$res['id']."</td>";
            echo "<td>".$res['recycler_id']."</td>";
            echo "<td>".$res['fullname']."</td>";
			 echo "<td>".$res['description']."</td>";
         
            echo "<td><img src=".$res['image']." width=\"100\" height=\"100\" /></td>";
          
            echo "<td><a href=\"bulkitemedit.php?id=$res[id]\">Edit</a> | <a href=\"bulkitemdelete.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";        
        }
        ?>
    </table>
</body>
</html>