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

$result = mysqli_query($conn, "SELECT t.id,t.collector_id 'collector_id',t.collection_date,t.collection_address,CONCAT(c.first_name, ' ', c.last_name) 'Driver',r.full_name Recycler,s.status_type'status' FROM transaction t,recycler r, collector c,status s where t.recycler_id=r.id AND t.status_id=s.id and t.collector_id = c.id"); 

$query = "SELECT id,CONCAT(c.first_name, ' ', c.last_name) 'Driver' FROM collector c";

$result1 = mysqli_query($conn, $query);

?>
<html>
 <head>
  <title>Datatables Individual column searching using PHP Ajax Jquery</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
 </head>
 <body>
  <div class="container">
   <h1 align="center">Transaction History</h1>
   <br />
   
   <div class="table-responsive">
    <table id="product_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Transaction ID</th>
       <th>Collection Date</th>
	    <th>Collection Address</th>
       <th>
        <select name="category" id="category" class="form-control">
         <option value="">Driver Search</option>
         <?php 
         while($row = mysqli_fetch_array($result))
         {
          echo '<option value="'.$row["collector_id"].'">'.$row["Driver"].'</option>';
         }
         ?>
        </select>
       </th>
	   
	    <th>Recycler</th>
       <th>Status</th>
	    <th>Details</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </body>
</html>



<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 load_data();

 function load_data(is_category)
 {
  var dataTable = $('#product_data').DataTable({
   "processing":true,
   "serverSide":true,
   "order":[],
   "ajax":{
    url:"fetch.php",
    type:"POST",
    data:{is_category:is_category}
   },
   "columnDefs":[
    {
     "targets":[4],
     "orderable":false,
    },
   ],
  });
 }

 $(document).on('change', '#category', function(){
  var category = $(this).val();
  $('#product_data').DataTable().destroy();
  if(category != '')
  {
   load_data(category);
  }
  else
  {
   load_data();
  }
 });
});
</script>