<?php
   require('config.php');

    if($_SESSION["guest"] == true) {
        $_SESSION['loginMsg'] = "Please login first.";
        header("Location: login.php");
        die();
    } else {
        
        if ($_SESSION['gID'] != 1) {
            if (isset($_SESSION['indexMsg'])) {
                $_SESSION['indexMsg'] .= "<br>You must be an Admin to use the Group page.";
            } else {
                $_SESSION['indexMsg'] = "You must be an Admin to use the Group page.";
            }
            header("Location: index.php");
        }
    }

	if($_SERVER["REQUEST_METHOD"] == "POST") {

        if ($_SESSION['gID'] == 1) {

            //updateAccounts
            if (array_key_exists('updateAccounts', $_POST)) {
                $sql = "select * from User where not User_ID=" . $_SESSION['uID'];
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {

                    if (array_key_exists($row['User_ID'], $_POST['updateAccounts'])) {
                        $gNum = mysqli_real_escape_string($conn, $_POST['updateAccounts'][$row['User_ID']][0]);

                        if ($gNum != $row['User_gID']) {
                            $sql2 = "update User set User_gID='" . $gNum . "' where User_ID=" . $row['User_ID'];
                            $result2 = mysqli_query($conn,$sql2);

                            if ($result2 === false) {

                                if (isset($_SESSION['groupSetMsg'])) {
                                    $_SESSION['groupSetMsg'] .= "<br>Could not update [" . $row['Username'] . "]'s group.";
                                } else {
                                    $_SESSION['groupSetMsg'] = "Could not update [" . $row['Username'] . "]'s group.";
                                }
                            }
                        }
                    }
                }
            }
            
            //updateGroups
            if (array_key_exists('updateGroups', $_POST)) {
                $sql3 = "select * from Groups where Groups_gID>2";
                $result3 = mysqli_query($conn,$sql3);

                while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                    
                    if (array_key_exists($row3['Groups_gID'], $_POST['updateGroups'])) {
                        $gName = mysqli_real_escape_string($conn, $_POST['updateGroups'][$row3['Groups_gID']][0]);

                        if ($gName != mysqli_real_escape_string($conn, $row3['Groups_Name'])) {
                            $sql4 = "update Groups set Groups_Name='" . $gName . "' where Groups_gID=" . $row3['Groups_gID'];
                            $result4 = mysqli_query($conn,$sql4);

                            if ($result4 === false) {

                                if (isset($_SESSION['groupSetMsg'])) {
                                    $_SESSION['groupSetMsg'] .= "<br>Could not update group [" . $row3['Groups_Name'] . "].";
                                } else {
                                    $_SESSION['groupSetMsg'] = "Could not update group [" . $row3['Groups_Name'] . "].";
                                }
                            }
                        }
                    }
                }
            }
            
            //newGroups
            if (array_key_exists('newGroups', $_POST)) {
                
                foreach ($_POST['newGroups'] as $val) {
                    $val2 = mysqli_real_escape_string($conn, $val);
                    $sql5 = "insert into Groups (Groups_Name) values ('" . $val2 . "')";
                    $result5 = mysqli_query($conn,$sql5);
                    
                    if ($result5 === false) {
                        
                        if (isset($_SESSION['groupSetMsg'])) {
                            $_SESSION['groupSetMsg'] .= "<br>Could not create group [" . $val2 . "].";
                        } else {
                            $_SESSION['groupSetMsg'] = "Could not create group [" . $val2 . "].";
                        }
                    }
                }
            }
            
            //delGroups
            if (array_key_exists('delGroups', $_POST)) {
                
                $success = false;
                
                foreach ($_POST['delGroups'] as $val) {
                    $val2 = mysqli_real_escape_string($conn, $val);
                    
                    //Rooms table
                    $sql6 = "update Room set Room_gID=2 where Room_gID=" . $val2;
                    $result6 = mysqli_query($conn,$sql6);
                    
                    if ($result6 === true) {
                        
                        //User table
                        $sql7 = "update User set User_gID=2 where User_gID=" . $val2;
                        $result7 = mysqli_query($conn,$sql7);
                        
                        if ($result7 === true) {
                            
                            //safe to remove
                            $sql8 = "delete from Groups where Groups_gID=" . $val2;
                            $result8 = mysqli_query($conn,$sql8);
                            
                            if ($result8 === true) {
                                $success = true;
                            }
                        }
                    }
                    
                    if ($success === false) {
                        
                        if (isset($_SESSION['groupSetMsg'])) {
                            $_SESSION['groupSetMsg'] .= "<br>Could not delete group [" . $val2 . "].";
                        } else {
                            $_SESSION['groupSetMsg'] = "Could not delete group [" . $val2 . "].";
                        }
                    }
                }
            }
            
            //delAccounts
            if (array_key_exists('delAccounts', $_POST)) {
                
                $success = false;
                
                foreach ($_POST['delAccounts'] as $val) {
                    $val2 = mysqli_real_escape_string($conn, $val);
                    
                    //House_Assignment table
                    $sql9 = "delete from House_Assignment where Assign_User_ID=" . $val2;
                    $result9 = mysqli_query($conn,$sql9);
                    
                    if ($result9 === true) {
                        $sql10 = "delete from User where User_ID=" . $val2;
                        $result10 = mysqli_query($conn,$sql10);

                        if ($result10 === true) {
                            $success = true;
                        }
                    }
                    
                    if ($success === false) {

                        if (isset($_SESSION['groupSetMsg'])) {
                            $_SESSION['groupSetMsg'] .= "<br>Could not delete account [" . $val2 . "].";
                        } else {
                            $_SESSION['groupSetMsg'] = "Could not delete account [" . $val2 . "].";
                        }
                    }
                }
            }
            
            header("Location: groupSettings.php");
        } else { 
            
            if (isset($_SESSION['indexMsg'])) {
                $_SESSION['indexMsg'] .= "<br>You must be an Admin to use the group page.";
            } else {
                $_SESSION['indexMsg'] = "You must be an Admin to use the group page.";
            }
            
            header("Location: index.php");
        }
    } else { 

        if (isset($_SESSION['groupSetMsg'])) {
            $_SESSION['groupSetMsg'] .= "<br>Method must be POST.";
        } else {
            $_SESSION['groupSetMsg'] = "Method must be POST.";
        }
        
        header("Location: groupSettings.php");
    }
?>