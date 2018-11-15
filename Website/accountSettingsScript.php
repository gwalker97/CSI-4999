<?php
require('config.php');

if($_SESSION["guest"] == true) {
    $_SESSION['loginMsg'] = "Please login first.";

    header("Location: login.php");
    die();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = mysqli_real_escape_string($conn,$_POST['uname']);
    $pass = mysqli_real_escape_string($conn,$_POST['passw1']);
    $pass2 = mysqli_real_escape_string($conn,$_POST['passw2']);

    //change username
    if ($_SESSION['user'] != $uname) {
        $sql = "select * from User where Username='$uname'";
        $result = mysqli_query($conn,$sql);
        $count = mysqli_num_rows($result);
        
        if ($count == 0) {
            $sql2 = "update User set Username='$uname' where User_ID=" . $_SESSION['uID'];
            $result2 = mysqli_query($conn,$sql2);
            
            if ($result2 === false) {
                
                if (isset($_SESSION['accountMsg'])) {
                    $_SESSION['accountMsg'] .= "<br>Could not update the username.";
                } else {
                    $_SESSION['accountMsg'] = "Could not update the username.";
                }
            } else { $_SESSION['user'] = $uname; }
            
        } else {
                
            if (isset($_SESSION['accountMsg'])) {
                $_SESSION['accountMsg'] .= "<br>That username is already taken.";
            } else {
                $_SESSION['accountMsg'] = "That username is already taken.";
            }   
        }
    }
    
    //change password
    if ($pass == $pass2 and strlen($pass) != 0) {
        $sql3 = "update User set Password='$pass' where User_ID=" . $_SESSION['uID'];
        $result3 = mysqli_query($conn,$sql3);
        
        if ($result3 === false) {
            
            if (isset($_SESSION['accountMsg'])) {
                $_SESSION['accountMsg'] .= "<br>Could not update the password.";
            } else {
                $_SESSION['accountMsg'] = "Could not update the password.";
            }    
        }
        
    } elseif (strlen($pass) != 0) {
        if (isset($_SESSION['accountMsg'])) {
            $_SESSION['accountMsg'] .= "<br>The password fields did not match.";
        } else {
            $_SESSION['accountMsg'] = "The password fields did not match.";
        }
    }
    
} else { 

    if (isset($_SESSION['accountMsg'])) {
        $_SESSION['accountMsg'] .= "<br>Method was not POST.";
    } else {
        $_SESSION['accountMsg'] = "Method was not POST.";
    }
}

header('Location: accountSettings.php');
?>