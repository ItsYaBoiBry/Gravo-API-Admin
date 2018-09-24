<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();
$var = $_SESSION["name"];
$token = $_SESSION["token"];
$type = $_SESSION["type"];
?>

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
            .nav-pills > li.active > a, .nav-pills > li.active > a:focus {
                color: white;
                background-color: #4BC399;
            }

            .nav-pills > li.active > a:hover {
                background-color: #2d7a5f;
                color:white;
            } 
            .nav-pills > a:hover {
                background-color: #2d7a5f;
                color:white;
            }
        </style>		
    </head>
    <body>
        <div class='container'>
            <div class='whiteBackground'>
                <div class='image' style='text-align:center;'>
                    <!--   <img src='http://ehostingcentre.com/redcampadmin/storage/logo/logo.png' class='logo' style='width: 7%;position:absolute;margin-top:-4%;margin-left:-4%;'> -->
                </div> 
                <p>Dear <?php echo $var; ?></p>
                <br>
                <p>Gravo has received a request from you to RESET / CHANGE your GRAVO Password</p>
                <br/>
                <p>If you need a new password, please click the link / button below.  </p>
                <br/>
                <center>
                    <a href="http://www.ehostingcentre.com/gravo/content/changepassword.php?token=<?php echo $token; ?>&type=<?php echo $type; ?>" style="text-decoration:none; padding:5px; background:lightgrey;color:white;">Reset Password</a>

                </center> 
                <br/>
                <p>Happy Recycling,</p>
                <br/>
                <p>The GRAVO Team</p>
                <br/> 

            </div>
        </div>
    </body>
</html>
