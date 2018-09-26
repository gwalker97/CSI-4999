<?php
   require('config.php');

    $success = false;

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$uname = mysqli_real_escape_string($conn,$_POST['uname']);
		$pass = mysqli_real_escape_string($conn,$_POST['passw1']);
        $pass2 = mysqli_real_escape_string($conn,$_POST['passw2']);

		$sql = "select * from User where Username='$uname'";
		$result = mysqli_query($conn,$sql);

		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

		$count = mysqli_num_rows($result);

		if($count == 0) {

            if ($pass === $pass2) {            
                $sql2 = "insert into User (Username, Password) values ('" . $uname . "', '" . $pass . "')";
                $result2 = mysqli_query($conn,$sql2);
                
                if ($result2 === true) {
                    $sql3 = "select * from User where Username='" . $uname . "'";
                    $result3 = mysqli_query($conn,$sql3);
                    $row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);

                    $_SESSION['user'] = $uname;
                    $_SESSION['pass'] = $row['Password'];
                    $_SESSION['admin'] = $row3['Admin'];
                    $_SESSION['uID'] = $row3['User_ID'];
                    $_SESSION['guest'] = false;
                    $_SESSION['home'] = 1;
                    $success = true;
                    $id = $row3['User_ID'];
                    
                    $sql4 = "insert into House_Assignment (Assign_House_ID, Assign_User_ID) values (1, " . $id . ")";
                    $result4 = mysqli_query($conn,$sql4);
                    
                    if ($result4 === false) { $_SESSION['indexMsg'] = "House assignment failed."; }
                } else { $_SESSION['createMsg'] = "Could not add account to database."; }
            } else { $_SESSION['createMsg'] = "The password fields did not match."; }
        } else { $_SESSION['createMsg'] = "That username is taken."; }
    } else { $_SESSION['createMsg'] = "Method was not POST."; }

    if ($success) { header('Location: index.php'); }
    else { header('Location: create-account.php'); }
?>