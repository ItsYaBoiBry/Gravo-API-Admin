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
	
          
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
        <!--   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
		 <link rel="stylesheet" href="gravo/bootstrap-3.3.7-dist/css/bootstrap.min.css" /> -->
		 
		  <link rel="stylesheet" href="bootstrap.min.css" />
           <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  

         <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />   
   <script src="filterDropDown.js"></script>
    <script src="filterDropDown.min.js"></script>
	
	
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
<div> </div>
<div align="center"> <h2>Transaction History</h2></div>
<div> <br/>  <br/<br/> </div>
 
 <div class="table-responsive">  
 <table id="show_data" width="90%" align= "center" class="table table-striped table-bordered table-hover">  
   <thead>
        <tr bgcolor='#4CAF50'>
            <th>Transaction ID</th>
            <th>Collection Date</th>
            <th>Collection Address</th>
			   <th>Driver </th>
			<th>Recycler</th>
            <th>Status</th>
			 <th>Details</th>
           
        </tr>
		</thead>
		<tbody>
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
		</tbody>
    </table>
	</div>
</body>
</html>
<script>
$(document).ready(function() {
    var mesa = $('#show_data').DataTable( { 
        responsive: true,
     

        initComplete: function () { // After DataTable initialized
            this.api().columns([2]).every( function () {
            /* use of [2] for third column.  Leave blank - columns() - for all. 
            Multiples? Use columns[0,1]) for first and second, e.g. */
                 var column = this; 
                 var select = $('<select><option value=""/></select>')
                      .appendTo( $('.datatable .dropdown .third').empty() ) /* for multiples use .appendTo( $(column.header()).empty() ) or .appendTo( $(column.footer()).empty() ) */
                      .on( 'change', function () {
                           var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                           );
                      /* 
                      Above - appendTo, in this case, uses my customized classes instead of doing one of one of the following: 
                      .appendTo($(column.header()).empty()) or .appendTo($(column.footer()).empty()) 
        UPDATE November 1: However, if you want multiples, you would want to use .appendTo($(column.header()).empty()) 
        for thead element or .appendTo($(column.footer()).empty())  for tfoot element. You would have to create a tfoot 
        element with row for the latter.  If you have a thead element and want to see titles, etc., but still want the 
        menus at the top, then you can inline style the tfoot element with style="display:footer-header-group;" and 
        that will place the menus at the top.  If you code your markup of the tfoot below the thead, that works out nicely. 
                     
                      */
                      column
                           .search( val ? '^'+val+'$' : '', true, false )
                           .draw();
                      } );
                 column.data().unique().sort().each( function ( d, j ) {
                      select.append( '<option value="'+d+'">'+d+'</option>' )
                 } );
            } ); // this.api function
        } //initComplete function 

    } );
} );
</script>


<!-- <script>  
 
// $(document).ready(function(){  
  //    $('#show_data').DataTable();  
	 
 //});  
 
 $(document).ready(function() {
    $('#show_data').DataTable( {
        initComplete: function () {
           this.api().columns('.select-filter').every( function () 
 {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );


 </script>  -->