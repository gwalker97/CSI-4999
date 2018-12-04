<?php
   require('config.php');

    if($_SESSION["guest"] == true) {
        $_SESSION['loginMsg'] = "Please login first.";
        
        header("Location: login.php");
        die();
    }

	if($_SERVER["REQUEST_METHOD"] == "POST") {

        //houseName
        if (array_key_exists('houseName', $_POST)) {
            $hName = mysqli_real_escape_string($conn, $_POST['houseName']);
            $sql = "select * from House where House_ID=" . $_SESSION['home'];
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

            if (mysqli_real_escape_string($conn, $row['House_Name']) != $hName) {

                if ($_SESSION['gID'] == 1) {
                    $sql = "update House set House_Name='" . $hName . "' where House_ID=" . $_SESSION['home'];
                    $result = mysqli_query($conn,$sql);

                    if ($result === false) {
                        if (isset($_SESSION['houseSetMsg'])) {
                            $_SESSION['houseSetMsg'] .= "<br>Could not update house name.";
                        } else {
                            $_SESSION['houseSetMsg'] = "Could not update house name.";
                        }
                    }
                } else { 

                    if (isset($_SESSION['houseSetMsg'])) {
                        $_SESSION['houseSetMsg'] .= "<br>Only admins can change the house name.";
                    } else {
                        $_SESSION['houseSetMsg'] = "Only admins can change the house name.";
                    }
                }
            }
        }

        //updateRoomNames
        if (array_key_exists('updateRooms', $_POST)) {
            $sql2 = "select * from Room where House_ID=" . $_SESSION['home'];
            $result2 = mysqli_query($conn,$sql2);

            while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {

                if (array_key_exists($row2['Room_ID'], $_POST['updateRooms'])) {
                    $rName = mysqli_real_escape_string($conn, $_POST['updateRooms'][$row2['Room_ID']][0]);
                    $rNum = mysqli_real_escape_string($conn, $_POST['updateRooms'][$row2['Room_ID']][1]);

                    if ($rName != mysqli_real_escape_string($conn, $row2['Room_Name']) or $rNum != $row2['Room_gID']) {

                        if ($_SESSION['gID'] == 1 or ($_SESSION['gID'] == $row2['Room_gID'] and $_SESSION['gID'] != 2)) {

                            if ($_SESSION['gID'] == 1) {
                                
                                $sql3 = "update Room set Room_Name='" . $rName . "', Room_gID='" . $rNum . "' where Room_ID=" . $row2['Room_ID'];
                                
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
                                    $_SESSION['houseSetMsg'] .= "<br>Could not change [" . $row2['Room_Name'] . "].";
                                } else {
                                    $_SESSION['houseSetMsg'] = "Could not change [" . $row2['Room_Name'] . "].";
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

        //deleteRooms
        if (array_key_exists('delRooms', $_POST)) {

            if ($_SESSION['gID'] == 1) {
                
                foreach ($_POST['delRooms'] as $val) {
                    $val2 = mysqli_real_escape_string($conn, $val);
                    
                    if (array_key_exists('confirmedRooms', $_POST) && array_key_exists($val2, $_POST['confirmedRooms'])) {
                        $conf = true;
                    }
                    $sql4 = "select Scenes.Scene_Name, B.Scene_ID, B.Addon_ID, B.Addon_Room_ID, B.Addon_Name from (select Scene_Assignment.Scene_ID, A.Addon_ID, A.Addon_Room_ID, A.Addon_Name from (select Addon_ID, Addon_Room_ID, Addon_Name from Addon where Addon_Room_ID=" . $val2 . ") as A join Scene_Assignment on A.Addon_ID = Scene_Assignment.Addon_ID ) as B join Scenes on Scenes.Scene_ID = B.Scene_ID";
                    $result4 = mysqli_query($conn,$sql4);
                    
                    if (!isset($sct)) { $sct = ""; }
                    if (!isset($at)) { $at = ""; }
                    
                    while ($row4 = mysqli_fetch_array($result4,MYSQLI_ASSOC)) {
                         
                        if ($conf) {
                            $sql5 = "delete from Scene_Assignment where Addon_ID=" . $row4['Addon_ID'];
                            $result5 = mysqli_query($conn,$sql5);
                        } else {
                            $sct .= "     " . $row4['Addon_Name'] . " will be removed from " . $row4['Scene_Name'] . ".<br>";
                        }
                    }
                    
                    $sql4 = "select Addon_ID, Addon_Room_ID, Addon_Name from Addon where Addon_Room_ID=" . $val2;
                    $result4 = mysqli_query($conn,$sql4);
                    
                    while ($row4 = mysqli_fetch_array($result4,MYSQLI_ASSOC)) {
                        
                        if ($conf) {
                            $sql6 = "delete from Addon where Addon_ID=" . $row4['Addon_ID'];
                            $result6 = mysqli_query($conn,$sql6);
                        } else {
                            $at .= "     " . $row4['Addon_Name'] . " will be deleted.<br>";
                            $_SESSION['confirmed'][$val2] = true;
                        }
                    }
                    
                    if (!isset($row4) || $conf) {
                        $sql7 = "delete from Room where Room_ID=" . $val2;
                        $result7 = mysqli_query($conn,$sql7);

                        if ($result7 === false) {

                            if (isset($_SESSION['houseSetMsg'])) {
                                $_SESSION['houseSetMsg'] .= "<br>Could not delete room [" . $val2 . "].";
                            } else {
                                $_SESSION['houseSetMsg'] = "Could not delete room [" . $val2 . "].";
                            }
                        }
                    }
                }
                
                if ($at != "") {
                    
                    if ($sct != "") {
                        $_SESSION['houseSetConfMsg'][0] = $sct;
                    }

                    $_SESSION['houseSetConfMsg'][1] = $at;
                }

            } else {
                if (isset($_SESSION['houseSetMsg'])) {
                    $_SESSION['houseSetMsg'] .= "<br>You cannot delete rooms.";
                } else {
                    $_SESSION['houseSetMsg'] = "You cannot delete rooms.";
                }
            }
        }

        //createRooms
        if (array_key_exists('newRooms', $_POST)) {

            if ($_SESSION['gID'] == 1) {
                
                foreach ($_POST['newRooms'] as $val3) {
                    $val4 = mysqli_real_escape_string($conn, $val3);
                    $sql8 = "insert into Room(House_ID, Room_Name) values (" . $_SESSION['home'] . ", '" . $val4 . "')";
                    $result8 = mysqli_query($conn,$sql8);

                    if ($result8 === false) {
                        if (isset($_SESSION['houseSetMsg'])) {
                            $_SESSION['houseSetMsg'] .= "<br>Could not create [" . $val4 ."].";
                        } else {
                            $_SESSION['houseSetMsg'] = "Could not create [" . $val4 ."].";
                        }
                        break;
                    }
                }
                
            } else {
                if (isset($_SESSION['houseSetMsg'])) {
                    $_SESSION['houseSetMsg'] .= "<br>You cannot create rooms.";
                } else {
                    $_SESSION['houseSetMsg'] = "You cannot create rooms.";
                }
            }
        }

    }

    header('Location: house-settings.php');
?>