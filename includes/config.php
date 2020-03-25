<?php
    ob_start();
    session_start();
    
    $timezone = date_default_timezone_set("Asia/Calcutta");

    $con = mysqli_connect("localhost","root","","mstream");
    
    if(mysqli_connect_errno()){
        echo "Failed To Connect :" . mysqli_connect_errno();
    }
?>