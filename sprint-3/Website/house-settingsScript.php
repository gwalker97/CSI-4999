<?php
   require('config.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //houseName
        if (array_key_exists('houseName', $_POST)) {
            $sql = "select * from House where House_ID=" . $_SESSION['home'];
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            
            if ($row['House_Name'] != $_POST['houseName']) {
                $sql = "update House set House_Name='" . $_POST['houseName'] . "' where House_ID=" . $_SESSION['home'];
                $result = mysqli_query($conn,$sql);

                if ($result === false) {
                     $_SESSION['houseSetMsg'] = "Could not update house name.";  
                }
            }
        }

        //updateRoomNames
        if (array_key_exists('updateRooms', $_POST)) {
            $sql2 = "select * from Room where House_ID=" . $_SESSION['home'];
            $result2 = mysqli_query($conn,$sql2);
        
            while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
            
                if (array_key_exists($row2['Room_ID'], $_POST['updateRooms'])) {

                    if ($_POST['updateRooms'][$row2['Room_ID']][0] != $row2['Room_Name']) {
                        $sql3 = "update Room set Room_Name='" . $_POST['updateRooms'][$row2['Room_ID']][0] . "' where Room_ID=" . $row2['Room_ID'];
                        $result3 = mysqli_query($conn,$sql3);

                        if (result3 === false) {
                            $_SESSION['houseSetMsg'] = "Could not update [" . $row['Room_Name'] . "]'s name.";
                            break;
                        }
                    }
                }
            }
        }
        
        //deleteRooms
        if (array_key_exists('delRooms', $_POST)) {
            
            foreach ($_POST['delRooms'] as $val) {
                $sql4 = "delete from Room where Room_ID=" . $val;
                $result4 = mysqli_query($conn,$sql4);

                if ($result4 === false) {
                    $_SESSION['houseSetMsg'] = "Could not remove room ID [" . $val . "].";
                    break;
                }
            }
        }
        
        //createRooms
        if (array_key_exists('newRooms', $_POST)) {
            
            foreach ($_POST['newRooms'] as $val2) {
                $sql5 = "insert into Room(House_ID, Room_Name) values (" . $_SESSION['home'] . ", '" . $val2 . "')";
                $result5 = mysqli_query($conn,$sql5);

                if ($result5 === false) {
                    $_SESSION['houseSetMsg'] = "Could not add room [" . $val2 . "].";
                    break;
                }
            }
        }
                
        header('Location: house-settings.php');
    }
?>