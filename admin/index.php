
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

$result = mysqli_query($conn, "SELECT * FROM collector ORDER BY id Asc"); 

?>

 
<html>
<head>    
    <title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	  <link rel="stylesheet" href="bootstrap.min.css" />
</head>
 
<body>
   
 
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
	
	
	
</div> -->
<div> <br/>  <br/><br/> </div>
<div align="center"> <h2>Collector</h2></div>
<div> <br/>  <br/<br/> </div>
    <table width='80%' border=0 id= "show_table" align="center">
        <tr bgcolor='#CCCCCC'>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Update</th>
        </tr>
        <?php 
        
        while($res = mysqli_fetch_array($result)) {     

		$userstatus = $res['status'];
		if($userstatus == 1)
		{
			$userstatus='Accepted';
		}
		elseif($userstatus == 2)
		{
			$userstatus='Rejected';
		}
		else
		{
			$userstatus='Pending';
			
		}
		
            echo "<tr>";
			echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
            echo "<td>".$res['email']."</td>";
            echo "<td>".$userstatus."</td>";    
            echo "<td><a href=\"edit.php?email=$res[email]\">Edit</a> | <a href=\"delete.php?email=$res[email]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>  </td>";        
        }
        ?>
    </table>
</body>
</html>