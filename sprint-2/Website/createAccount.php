<?php
   require('config.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
        $success = false;
        
		$uname = mysqli_real_escape_string($conn,$_POST['uname']);
		$pass = mysqli_real_escape_string($conn,$_POST['passw1']);
        $pass2 = mysqli_real_escape_string($conn,$_POST['passw2']);

		$sql = "select * from User where Username='$uname'";
		$result = mysqli_query($conn,$sql);

		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		$count = mysqli_num_rows($result);

		if($count == 0) {

            if ($pass === $pass2) {            
                $sql2 = "insert into User (Username, Password) values (" . $uname . ", " . $pass . ")";
                $result2 = mysqli_query($conn,$sql2);
                
                if ($result2) {
                    $sql3 = "select * from User where Username='" . $uname . "'";
                    $result3 = mysqli_query($conn,$sql3);
                    $row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);

                    $_SESSION['user'] = $uname;
                    $_SESSION['pass'] = $row['Password'];
                    $_SESSION['admin'] = $row3['Admin'];
                    $_SESSION['uID'] = $row3['User_ID'];
                    $_SESSION['guest'] = false;
                    $success = true;
                
                    $id = $row3['User_ID'];
                    $sql4 = "select * from House_Assignment where Assign_User_ID = '$id'";
                    $result4 = mysqli_query($conn,$sql4);
                    $row4 = mysqli_fetch_array($result4,MYSQLI_ASSOC);

                    $_SESSION['homes'] = $row4;
                    $_SESSION['home'] = $row4['Assign_House_ID'];
                } else { $_SESSION['createMsg'] = "Could not add account to database."; }
            } else { $_SESSION['createMsg'] = "The password fields did not match."; }
        } else { $_SESSION['createMsg'] = "Another account is already using that username."; }
    } else { $_SESSION['createMsg'] = "Method was not POST."; }

    if ($success) { header('Location: index.php'); }
    else { header('Location: create-account.php'); }
?>