<?php
    require('config.php');

    $sN = mysqli_real_escape_string($conn, $_POST['sN']);
    $sC = mysqli_real_escape_string($conn, $_POST['sC']);
    $sA = mysqli_real_escape_string($conn, $_POST['sA']);
    $sS = mysqli_real_escape_string($conn, $_POST['sS']);
    $sE = mysqli_real_escape_string($conn, $_POST['sE']);
    $aID = mysqli_real_escape_string($conn, $_POST['aID']);
    $hID = $_SESSION['home'];

    $sql = "INSERT INTO Scenes (House_ID, Scene_Name, Start_Time, End_Time, Is_Automated, Scene_Order, Scene_Color) VALUES
    ('$hID', '$sN', '$sS', '$sE', '$sA', 0, '$sC')";
    $conn->query($sql);
    $sID = $conn->insert_id;

    $addonIDs = explode(',', $aID);

    foreach ($addonIDs as $value) {
        $sql = "INSERT INTO Scene_Assignment (Scene_ID, Addon_ID, IsSet) VALUES ('$sID', '$value', 0)";
        $conn->query($sql);
    }

    $conn->close();
?>