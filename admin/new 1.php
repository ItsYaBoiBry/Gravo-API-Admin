<?php

include 'config.php';
		
$id = $_POST['id'];
$price_quote = $_POST['price_quote'];
$recycler_email = $_POST['recycler_email'];
$query_get_item = "SELECT * FROM bulk_transaction_item WHERE id = $id";


$query_get_transactions = "UPDATE bulk_transaction_item SET price_quote= '$price_quote' , bulk_transaction_status_id = 2 WHERE id = $id" ;

if ($conn) {
 
           $updateuser = updatetrans($conn, $query_get_transactions);
            if ($updateuser) {
                $getUserDetails = GetTransactions($conn, $query_get_item);
                if ($getUserDetails != []) {
                  echo  "";
				     SendEmailAdmin($recycler_email, "Gravo Price Quotation");
				     echo '<html><head></head><body><script language="javascript">';
                echo 'alert("Price Quote Updated!");';
                echo 'window.location = "../admin/index.php";';
                echo '</script></body</html>';
                } else {
                   echo  "Unable to retrieve updated user details";
                }
            
} 
}else {
    echo "Unauthorized";
}



function updatetrans($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}

function GetTransactions($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}


function SendEmailAdmin($email, $subject) {
   
   $headers = "From: " . "etechcentre123@gmail.com" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = "<html>
    <head>
        <meta charset='utf-8' />
    	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
    	<title>Reset password successful</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>
        <style>
            .container {
                width: 100%;
                padding: 0;
            }

            .whiteBackground {
                background: white;
                width: 70%;
                padding: 5%;
                margin: auto;
            }

            .centerContent {
                text-align: center;
            }

            .link {
                border: 1px solid;
                padding: 2%;
                text-decoration: none !important;
            }
        </style>
    </head>
    <body >
 			
 			 <p><h2> Welcome!!!!!!!!!! </h2></p>
 			 
 			
			 </body> 
</html>";
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}




?>