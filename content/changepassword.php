<?php
include '../config.php';
$token = $_GET['token'];
$type =$_GET['type'];
$query_get_recycler = "";

if($type=="recycler"){
$query_get_recycler = "SELECT * FROM recycler WHERE forgotPassword = '$token'";
}else if($type == "collector"){
$query_get_recycler = "SELECT * FROM collector WHERE forgotPassword = '$token'";
}

$checkrecycler = getUser($conn, $query_get_recycler);
if ($checkrecycler != []) {
    ?>
    <html>
        <head>
            <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="http://www.w3schools.com/w3css/4/w3.css">
            <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
            <meta charset="UTF-8">
            <title>Change Password for <?php echo "$type"; ?></title>
        </head>
        <body>
            <div>
                <center>
                    <form action="updatepassword.php" method="post" style="margin:0 auto;width:500px; padding:10px;" class="w3-card">
                        <center>
                            <h2>Change your password</h2>
                            <input type="text" name="type" style="display:none;" value="<?php echo "$type"; ?>"/>
                            <input type="text" name="newpassword"/>
                            <input type="text" name="token" value="<?php echo "$token"; ?>" style="display:none;"/>
                            <input type="submit" value="Update Password"/>
                        </center>

                    </form>
                </center>

            </div>
        </body>
    </html>
    <?php
} else {
    ?>
    <html>
        <head>
            <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="http://www.w3schools.com/w3css/4/w3.css">
            <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <link href="http://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
            <meta charset="UTF-8">
            <title>Change Password</title>
        </head>
        <body>
            <div style="margin:20px">
                <center>
                    <h1>Unable to get your details</h1>
                </center>
            </div>
        </body>
    </html>
    <?php
}

function getUser($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));

    $data = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    return $data;
}

function addUser($conn, $query) {
    $resultset = mysqli_query($conn, $query) or die(json_encode($response["error"] = mysqli_error($conn)));
    return $resultset;
}
