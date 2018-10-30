<?php
   require('config.php');

    if($_SESSION["guest"] == true) {
        $_SESSION['loginMsg'] = "Please login first.";
        header("Location: login.php");
        die();
    }

	if($_SERVER["REQUEST_METHOD"] == "POST") {
        print(var_dump($_POST));
        
        if ($_SESSION['gID'] == 1) {
        
            //updateAccounts
            if (array_key_exists('updateAccounts', $_POST)) {
                $sql = "select * from User";
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    $uName = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_ID']][0]);
                    $gNum = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_ID']][1]);

                    if ($uName == mysqli_real_escape_string($conn, $row['Username']) and $gNum != $row['User_gID']) {
                        $sql2 = "update User set User_gID='" . $gNum . "' where Username=" . $row['Username'];
                        $result2 = mysqli_query($conn,$sql2);

                        if ($result2 === false) {

                            if (isset($_SESSION['groupSetMsg'])) {
                                $_SESSION['groupSetMsg'] .= "<br>Could not update [" . $row['Username'] . "'s group.";
                            } else {
                                $_SESSION['groupSetMsg'] = "Could not update [" . $row['Username'] . "'s group.";
                            }
                        }
                    }
                }
            }
            
            //updateGroups
            if (array_key_exists('updateGroups', $_POST)) {
                $sql3 = "select * from Groups";
                $result3 = mysqli_query($conn,$sql3);

                while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                    $uName = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_ID']][0]);
                    $gNum = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_gID']][1]);

                    if ($uName == mysqli_real_escape_string($conn, $row['Username']) and $gNum != $row['User_gID']) {
                        $sql2 = "update User set User_gID='" . $gNum . "' where Username=" . $row['Username'];
                        $result2 = mysqli_query($conn,$sql2);

                        if ($result2 === false) {

                            if (isset($_SESSION['groupSetMsg'])) {
                                $_SESSION['groupSetMsg'] .= "<br>Could not update [" . $row['Username'] . "'s group.";
                            } else {
                                $_SESSION['groupSetMsg'] = "Could not update [" . $row['Username'] . "'s group.";
                            }
                        }
                    }
                }
            }
            
        } else { 
            
            if (isset($_SESSION['indexMsg'])) {
                $_SESSION['indexMsg'] .= "<br>You must be an Admin to use the group page.";
            } else {
                $_SESSION['indexMsg'] = "You must be an Admin to use the group page.";
            }
            
            header("Location: index.php");
            
        }
/*        
        //update Accounts
        if (array_key_exists('updateAccounts', $_POST)) {
            $sql = "select * from User";
            $result = mysqli_query($conn,$sql);
        
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {

                if (array_key_exists($row['User_ID'], $_POST['updateAccounts'])) {
                    $rName = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_ID']][0]);
                    $rNum = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_gID']][1]);

                    if ($rName != mysqli_real_escape_string($conn, $row['Username']) or $rNum != $row['User_gID']) {

                        if ($_SESSION['gID'] == 1 or ($_SESSION['uID'] == $row['User_ID'])) {
                            
                            if ($_SESSION['gID'] == 1) {
                                
                                $sql = "update Room set Room_Name='" . $rName . "', Room_gID='" . $rNum . "' where Room_ID=" . $row2['Room_ID'];
                            } else {
                                $sql3 = "update Room set Room_Name='" . $rName . "' where Room_ID=" . $row2['Room_ID'];
                                
                                if ($rNum != $row2['Room_gID']) {
                                    
                                    if (isset($_SESSION['houseSetMsg'])) {
                                        $_SESSION['houseSetMsg'] .= "<br>You cannot change [" . $rName . "]'s group.";
                                    } else {
                                        $_SESSION['houseSetMsg'] = "You cannot change [" . $rName . "]'s group.";
                                    }
                                }
                            }
                            $result3 = mysqli_query($conn,$sql3);

                            if (result3 === false) {
                                
                                if (isset($_SESSION['houseSetMsg'])) {
                                    $_SESSION['houseSetMsg'] .= "<br>Could not change [" . $row2['Room_Name'] . "]'s name.";
                                } else {
                                    $_SESSION['houseSetMsg'] = "Could not change [" . $row2['Room_Name'] . "]'s name.";
                                }
                            }
                        } else {
                            
                            if (isset($_SESSION['houseSetMsg'])) {
                                $_SESSION['houseSetMsg'] .= "<br>You cannot edit [" . $row2['Room_Name'] . "].";
                            } else {
                                $_SESSION['houseSetMsg'] = "You cannot edit [" . $row2['Room_Name'] . "].";
                            }
                        }
                    }  
                }
            }
        }
*/
    }
?>