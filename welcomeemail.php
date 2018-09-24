<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();
$var = $_SESSION["name"];
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>
        Welcome to GRAVO!
    </title>
</head>
    <body style="background-color: #ebebeb; font-family: 'Open sans', sans-serif;text-align: start; font-size: 2%;">
<b>
    <div id="page">
        <div class="toppart"
             style="padding-top: 20px;padding-bottom: 20px; background-image: url('http://ehostingcentre.com/gravo/content/welcome_email_img_1.png');background-size: cover; background-position: top;">
            <!--            <div style="float: top;position: absolute;width:100%;z-index:-5;height: auto;"><img-->
            <!--                        src="content/welcome_email_img_1.png" style="width: 100%;"/></div>-->
            <div class="header" style="padding: 10px;background-color: #FFFFFF;">
                <img src="https://greenravolution.com/wp-content/uploads/2018/04/gravo_logo_black.png" height="30px"/>
                <a href="https://facebook.com/gravosg"><img
                            src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/twitter_circle-512.png"
                            width="30px" style="float: right;margin-right: 10px;"/></a>
                <a href="https://facebook.com/gravosg"><img
                            src="https://www.shareicon.net/data/256x256/2016/11/16/854137_round_512x512.png"
                            width="30px"
                            style="float: right;margin-right: 10px;"/></a>
                <a href="https://facebook.com/gravosg"><img
                            src="https://www.androidpolice.com/wp-content/uploads/2017/06/nexus2cee_original_images-Google_xd9f3RQ.png"
                            width="30px" style="float: right;margin-right: 10px;"/></a>
                <a href="https://facebook.com/gravosg"><img
                            src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/facebook_circle-512.png"
                            width="30px" style="float: right;margin-right: 10px;"/></a>
            </div>
            <div class="w3-card"
                 style="margin-bottom:20px; margin-left:20px; margin-right:20px; margin-top:70%; background-color: #FFFFFF;padding: 10px;border-radius: 5px;">
                <p>
                    Hello <?php echo $var; ?>,
                </p>
                <p>
                    Welcome to GRAVO, a mobile platform for your On-Demand Recycling needs.
                </p>
                <p>
                    We are EXCITED to have you join the GRAVO Community! The GRAVO mobile platform APP does great things
                    for
                    you
                    to promote reycling, sharing, learning and earning at the click of a button. Feel free to contact us
                    at
                    <a style="color: #f24b38;text-decoration: none;" href="mailto:recycling@greenravolution.com.sg">recycling@greenravolution.com.sg</a>
                    if you have any
                    questions.
                </p>
                <div style="text-align: center;padding: 20px;">
                    <a href="https://greenravolution.com.sg" class="w3-card"
                       style="border-radius: 10px;background-color: #4fc48d;color: #FFFFFF; text-decoration: none; padding: 10px;">On-demand
                        Recycling</a>
                </div>
            </div>

        </div>

    </div>
    <div id="page">
        <div class="toppart"
             style="padding-top: 20px;padding-bottom: 20px; background-image: url('http://ehostingcentre.com/gravo/content/welcome_email_img_2.png');background-size: cover; background-position: top;background-repeat: repeat-x">
            <!--            <div style="float: top;position: absolute;width:100%;z-index:-5;height: auto;"><img-->
            <!--                        src="content/welcome_email_img_2.png" style="width: 100%;"/></div>-->
            <div class="w3-card"
                 style="margin-bottom:20px; margin-left:20px; margin-right:20px; margin-top:65%; background-color: #FFFFFF;padding: 10px;border-radius: 5px;">

                <p>GRAVO's answer to your everyday need to recycle without the hassle of waiting or the uncertainty
                    of
                    not
                    knowing how much or if your recyclables will be recycled.</p>
                <p>To enjoy GRAVO services, all you have to do is register and login into your account. You can then
                    choose
                    to recycle PAPER, E-WASTE, METAL or dispose of your BIG ITEM. Schedule a time and address where
                    our
                    GRAVO Collectors can help you recycle and they'll be there. Recieve your cash and reward Gravos
                    then
                    start moving up the recycling ladder with your environmental effort.</p>
                <div style="text-align: center;padding: 20px;">
                    <a href="https://greenravolution.com.sg" class="w3-card"
                       style="border-radius: 10px;background-color: #4fc48d;color: #FFFFFF; text-decoration: none; padding: 10px;">Learn
                        More</a>
                </div>

            </div>

        </div>
    </div>
</b>
</body>
</html>
