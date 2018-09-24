	<?php 

	include 'config.php';

	$query = "SELECT t.id 'id',`collector_id`,`status_id` ,status_type 'status' FROM `transaction` t , status s where t.status_id=s.id and t.status_id=6 and t.collector_id is Null"; // status_id= 6--driver is not assigned
	$gettotalcollector = "select * from collector ";
	$query_cron = "SELECT collector_id,last_tran_count FROM cron_tran ORDER BY id DESC LIMIT 1"; // get the last tran in cron_tran
	$query_max_collector = "SELECT id FROM collector ORDER BY id DESC LIMIT 1"; // get the max id from collector
	//

	if ($conn) 
	{
		
		$get_cron_trans = Getcollector($conn, $query_cron);
		$cron_driver_count =0;
		$cron_driver_id  =0;//$cron_driver_id  =1
		//$i=0;
		$get_max_collector = Getcollector($conn, $query_max_collector); //get the max collector_id; to calc i value
		$driver_id = $get_max_collector[0]['id'];
		echo "Max driver :". $driver_id ;
			if (!empty($get_cron_trans))
				{
				 $cron_driver_id = $get_cron_trans[0]['collector_id'];
				$cron_driver_count = $get_cron_trans[0]['last_tran_count'];
				echo "Last driver assigned =".$cron_driver_id;
				echo "Last driver count =".$cron_driver_count;
			
						
						if($cron_driver_count < 5)
						{
							
							$count=5-$cron_driver_count;
						echo "Count :".$count;
					
							echo "Hello";
							 $update_cron_query = "update transaction set collector_id=$cron_driver_id,status_id=7  where status_id=6 and collector_id is null Limit $count";
								$result_cron = mysqli_query($conn, $update_cron_query);
							$updated_withcron=mysqli_affected_rows($conn);
							echo "Updated remaining cron count :".$updated_withcron;
						
						}
				}
					
					//	
						
	  $get_tot_trans = Getcollector($conn, $query);
	if (!empty($get_tot_trans)) 
	{
		$tran_awaiting_driver=mysqli_affected_rows($conn);
			while($tran_awaiting_driver >0)
			{
				
			if($tran_awaiting_driver  >= 1)
			{
				 $totalcollectorquery = Getcollector($conn, $gettotalcollector); // find number of drivers
				 $totalcollector=mysqli_affected_rows($conn);
				
					echo "Tranaction Drivers Not assigned= " . $tran_awaiting_driver ;

					echo nl2br("\n   No of Drivers = ") . $totalcollector ;
					
					
					if ($totalcollector >= 1)
					{
					
						if($driver_id == $cron_driver_id) // driver id= 3 default = 0
						{
							$i=0;
							echo "i=".$i;
						}
						else if($driver_id > $cron_driver_id)
						{
							$i=$cron_driver_id ;//+1;
							echo  nl2br("\n  *************").$i;
							echo  nl2br("\n  else if cron < 3 i=").$i;
							echo nl2br("\n  else if driver id:=").$driver_id;
							echo nl2br("\n  else if cron_driver_id:=").$cron_driver_id;
						}
						else 
						{
							$i=0;
							echo "else i=".$i;
						}
						echo "Hi ";
									for ($i;$i<$totalcollector;$i++)
									{
										echo "\Hi from for:";
										//if($i=
						
											$id = $totalcollectorquery[$i]['id'];
											$tran_id = $get_tot_trans[$i]['id'];
											$status = $get_tot_trans[$i]['status']; //A driver is assigned
											$status = 'A driver is assigned';
											$update_query = "update transaction set collector_id=$id,status_id=7  where status_id=6 and collector_id is null Limit 5";
											$result = mysqli_query($conn, $update_query);
					
						
											if($result)
											{
												 $norowsupdated=mysqli_affected_rows($conn);
												
												echo nl2br("\n  Updated ID:").$id;
											
												echo nl2br("   No of rows Updated:").$norowsupdated;
												
												if($norowsupdated>0)
												{
												$query_cron_insert="INSERT INTO cron_tran ( time,collector_id, last_tran_count) VALUES (CURRENT_TIMESTAMP(),$id,$norowsupdated)";
												$insert_cron = mysqli_query($conn, $query_cron_insert) or die(json_encode($response["error"] = mysqli_error($conn)));
												
												}
												
									
											
											}
						
									}
					
					
					
					}
					else // less than 1 driver;
						{
						//
						}
				
				
			
				
			}
			else
				{
					
							echo "No Tranaction Pending";
							return;
						
				}
			
			echo "Exiting while";
			  $get_tot_trans = Getcollector($conn, $query);
			$tran_awaiting_driver=mysqli_affected_rows($conn);
				$cron_driver_id  =0;
			} //end while;
			
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
