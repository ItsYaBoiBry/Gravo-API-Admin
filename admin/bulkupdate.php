<?php

include 'config.php';
		
$id = $_POST['id'];
$price_quote = $_POST['price_quote'];
$recycler_email = $_POST['recycler_email'];
$query_get_item = "SELECT * FROM bulk_transaction_item WHERE id = $id";


$query_get_transactions = "UPDATE bulk_transaction_item SET price_quote= '$price_quote' , bulk_transaction_status_id=12 WHERE id = $id";
$query_insert_history = "INSERT INTO bulk_transaction_history(bulk_transaction_item_id,history) VALUES ($id,'Gravo has quoted $price_quote for this item.');";

if ($conn) {
           $updateuser = updatetrans($conn, $query_get_transactions);
           $updatehistory = updatetrans($conn, $query_insert_history);
            if ($updateuser) {
            if(updatehistory){
             $getUserDetails = GetTransactions($conn, $query_get_item);
                if ($getUserDetails != []) {
                  echo  "Success";
		  SendEmailAdmin($recycler_email, "Gravo Price Quotation");
                } else {
                   echo  "Unable to retrieve updated user details";
                }
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
   
   $headers = "From: " . "Gravo <recycling@greenravolution.com>" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = "<html>
    <head>
    <title>
        Rescheduling of transaction
    </title>
</head>
<body style='font-family: sans-serif; color: black;'>
<div>
    <div style='text-align: center;'>
        <h1>
        Price Quotation
        </h1>

        <div style='text-align: left; width: 600px; margin:0 auto;'>
            
            <h2>
                Your bulk item has a quote!
            </h2>
            <hr>
            <h3>
                Your item has been quoted by GRAVO. you can choose to accept or reject the quote in the app and schedule a date for collection!
            </h3>
        </div>

    </div>
</div>
</body>
   
</html>";
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}




?>