<?php
   require('config.php');

    $success = false;

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$uname = mysqli_real_escape_string($conn,$_POST['uname']);
		$pass = mysqli_real_escape_string($conn,$_POST['passw']); 

		$sql = "select * from User where Username='$uname' and Password='$pass'";
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$count = mysqli_num_rows($result);

		if($count == 1) {

			$_SESSION['user'] = $row['Username'];
			$_SESSION['pass'] = $row['Password'];
            $_SESSION['admin'] = $row['Admin'];
            $_SESSION['uID'] = $row['User_ID'];
			$_SESSION['guest'] = false;
            $success = true;
            
            $id = $row['User_ID'];
            $sql2 = "select * from House_Assignment where Assign_User_ID = '$id'";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
            
            $_SESSION['homes'] = $row2;
            $_SESSION['home'] = $row2['Assign_House_ID'];
            
		} else { $_SESSION['loginMsg'] = "Please re-enter your login info."; }
	} else { $_SESSION['loginMsg'] = "Method was not POST."; }

    if ($success) { header('Location: index.php'); }
    else { header('Location: login.php'); }
?>