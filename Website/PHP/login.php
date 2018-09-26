<?php
$username = $_POST[Username];
$password = $_POST[Password];

$conn=mysqli_connect('127.0.0.1','sthai','root','csi4999test');
    if(!$conn)
    {
        die('server not connected');
    }
    $query = "SELECT * FROM `user_login` WHERE username='$username' and Password='$password'";
    
?>