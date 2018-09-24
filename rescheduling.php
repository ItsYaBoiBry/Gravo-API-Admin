<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 7/8/2018
 * Time: 12:44 PM
 */
$transactioncode = $_SESSION["transactioncode"];
$collectionaddress = $_SESSION["transactionaddress"];
$collectionpostal = $_SESSION["transactionpostal"];
$collectionuser = $_SESSION["transactionuser"];
$collectionnumber = $_SESSION["transactionnumber"];
$transactiontime = $_SESSION["transactiontime"];
$transactiondate = $_SESSION["transactiondate"];
$collectionweight = $_SESSION["transactionweight"];
$collectionprice = $_SESSION["transactionprice"];

?>
<!DOCTYPE html>
<html>
<head>
    <title>
        Rescheduling of transaction
    </title>
</head>
<body style="font-family: sans-serif; color:black;">
<div>
    <div style="text-align: center;">
        <h1>
            Transaction Re-schedule
        </h1>

        <div style="text-align: left; width: 600px; margin:0 auto;">
            <h2>
                Particulars
            </h2>
            <hr>
            <p>
                <b style="float: left;">Name: </b><span style="float: right;"><?php echo $collectionuser ?></span>
            </p><br>
            <p>
                <b style="float: left;">Transaction Code: </b><span
                        style="float: right;"><?php echo $transactioncode ?></span>
            </p><br>
            <p>
                <b style="float: left;">Address: </b><span style="float: right;"><?php echo $collectionaddress ?></span>
            </p><br>
            <hr>
            <h2>
                Details
            </h2>
            <hr>
            <p>
                <b style="float: left;">Total Weight/Quantity: </b><span
                        style="float: right;"><?php echo $collectionweight ?></span>
            </p><br>
            <p>
                <b style="float: left;">Total Price: </b><span
                        style="float: right;"><?php echo $collectionprice ?></span>
            </p><br>
            <hr>
            <h3>
                Transaction date has been requested to be Re-scheduled to<br> <b
                        style="color: red;"><?php echo $transactiondate; ?> (<?php echo $transactiontime ?>)</b>
            </h3>
        </div>


    </div>
</div>
</body>
<footer>
    Sent on <?php echo date("d-m-Y"); ?>
</footer>
</html>
