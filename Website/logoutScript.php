<?php
	require('config.php');

    $sql3 = "UPDATE Addon AS A INNER JOIN (select * from Room where House_ID=" . $_SESSION['home'] . ") as B
                            ON B.Room_ID= A.Addon_Room_ID SET A.Addon_State = '0.00';";
    $result3 = mysqli_query($conn,$sql3);

	session_destroy();
	header( 'Location: login.php');
	exit();
?>