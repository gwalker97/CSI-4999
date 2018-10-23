<?php
    require('config.php');

    $sN = mysqli_real_escape_string($conn, $_POST['sN']); //scene name
    $sC = mysqli_real_escape_string($conn, $_POST['sC']); //scene color
    $sA = mysqli_real_escape_string($conn, $_POST['sA']); //scene is automated
    $sS = mysqli_real_escape_string($conn, $_POST['sS']); //scene start
    $sE = mysqli_real_escape_string($conn, $_POST['sE']); //scene end
    $aID = mysqli_real_escape_string($conn, $_POST['aID']); //addonids
    $sID = mysqli_real_escape_string($conn, $_POST['sID']);
    $hID = $_SESSION['home'];

    $sql = "UPDATE Scenes SET Scene_Name = '$sN', Scene_Color = '$sC', Is_Automated = '$sA', Start_Time = '$sS', End_Time = '$sE' WHERE Scene_ID = '$sID'";
    $conn->query($sql);

    $sql = "DELETE FROM Scene_Assignment WHERE Scene_ID = '$sID'";
    $conn->query($sql);

    $addonIDs = explode(',', $aID);

    foreach ($addonIDs as $value) {
        $sql = "INSERT INTO Scene_Assignment (Scene_ID, Addon_ID, IsSet) VALUES ('$sID', '$value', 0)";
        $conn->query($sql);
    }

    $conn->close();
?>