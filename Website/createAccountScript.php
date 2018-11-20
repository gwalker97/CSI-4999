<?php
require('config.php');

$success = false;
$joinHouse = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = mysqli_real_escape_string($conn,$_POST['uname']);
    $pass = mysqli_real_escape_string($conn,$_POST['passw1']);
    $pass2 = mysqli_real_escape_string($conn,$_POST['passw2']);
    $houseCode = mysqli_real_escape_string($conn,$_POST['hCode']);

    //does username already exist?
    $sql = "select * from User where Username='$uname'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    //if joining house, does house code exist?
    if($houseCode != "") {
        $sqlHouseCode = "SELECT * FROM House WHERE House_Code = '" . $houseCode . "'";
        $resultHouseCode = mysqli_query($conn,$sqlHouseCode);
        $rowHouseCode = mysqli_fetch_array($resultHouseCode,MYSQLI_ASSOC);
        $countHouseCode = mysqli_num_rows($resultHouseCode);

        if($countHouseCode > 0) {
            $joinHouse = true;
        }
        else { //redirect with session message set and exit rest of script execution
            $_SESSION['createMsg'] = "The house code provided does not exist.";
            header('Location: create-account.php');
            exit;
        }
    }

    if($count == 0) { //session home and gID not being set properly when joining existing house

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
                $id = $row3['User_ID'];

                if($joinHouse) {
                    $sqlGetHouseID = "SELECT House_ID FROM House WHERE House_Code = '" . $houseCode . "'";
                    $resultGetHouseID = mysqli_query($conn,$sqlGetHouseID);
                    $rowGetHouseID = mysqli_fetch_array($resultGetHouseID, MYSQLI_ASSOC);
                    $houseJoinID = $rowGetHouseID['House_ID'];
                    
                    $_SESSION['home'] = $houseJoinID;
                    
                    $sqlJoinHouse = "INSERT INTO House_Assignment (Assign_House_ID, Assign_User_ID) VALUES (" . $houseJoinID . ", " . $id . ")";
                    $resultJoinHouse = mysqli_query($conn,$sqlJoinHouse);

                    if ($resultJoinHouse === false) { 
                        $_SESSION['createMsg'] = "Joining house failed.";
                        $success = false; 
                    } else {
                        header('Location: index.php');
                        exit;
                    }
                } else {
                    //randomly generated house code
                    $houseCode = substr(md5(mt_rand()), 0, 7);

                    $sql4 = "SELECT * FROM House WHERE House_Code = '" . $houseCode ."'";
                    $result4 = mysqli_query($conn, $sql4);

                    if (!mysqli_num_rows($result4)==0) { //house code already exists
                        $success = false;
                        $_SESSION['createMsg'] = "House creation failed. Please try again.";
                    } else { //house code doesn't exist, perform insert
                        $sql5 = "insert into House (House_Name, House_Code) values ('House Name', '" . $houseCode . "')";
                        $result5 = mysqli_query($conn, $sql5);

                        $newHouseID = $conn->insert_id;
                        $_SESSION['home'] = $newHouseID;

                        $sql6 = "insert into House_Assignment (Assign_House_ID, Assign_User_ID) values (" . $newHouseID . ", " . $id . ")";
                        $result6 = mysqli_query($conn,$sql6);
                        
                        $sql7 = "INSERT INTO Temp (House_ID, F, C, Is_Automated, Target_Temp, Target_Temp_Type) VALUES (" . $newHouseID . ", 0, 0, 0, 0, 'F')";
                        $result7 = mysqli_query($conn,$sql7);

                        $success = true;
                    }

                    if ($result5 === false) { $_SESSION['createMsg'] = "House creation failed."; $success = false; }
                    if ($result6 === false) { $_SESSION['createMsg'] = "House assignment failed."; $success = false; }
                }
            } else { $_SESSION['createMsg'] = "Could not add account to database."; }
        } else { $_SESSION['createMsg'] = "The password fields did not match."; }
    } else { $_SESSION['createMsg'] = "That username is taken."; }
} else { $_SESSION['createMsg'] = "Method was not POST."; }

if ($success) { header('Location: set-up-house.php'); }
else { header('Location: create-account.php'); }
?>