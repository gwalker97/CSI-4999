<?php
    require('config.php');

    $success = false;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $houseName = mysqli_real_escape_string($conn,$_POST['hName']);
        $roomName = mysqli_real_escape_string($conn,$_POST['rName']);
        $hID = $_SESSION['home'];

        $sql = "UPDATE House SET House_Name = '" . $houseName ."' WHERE House_ID = " . $hID . "";
        $result = mysqli_query($conn,$sql);

        $sql2 = "INSERT INTO Room (House_ID, Room_Name) VALUES (" . $hID . ", '" . $roomName . "')";
        $result2 = mysqli_query($conn,$sql2);
        
        $success = true;
        
        if ($result === false) { $_SESSION['houseMsg'] = "Failed to update house name."; $success = false; }
        if ($result2 === false) { $_SESSION['houseMsg'] = "Failed to add room."; $success = false; }
        
    } else { $_SESSION['houseMsg'] = "Method was not POST."; }

    if ($success) { header('Location: index.php'); }
    else { header('Location: set-up-house.php'); }
?>