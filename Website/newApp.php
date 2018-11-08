<?php
    require('config.php');

    $aN = mysqli_real_escape_string($conn, $_POST['aN']);
    $aD = mysqli_real_escape_string($conn, $_POST['aD']);
    $aT = mysqli_real_escape_string($conn, $_POST['aT']);
    $aR = mysqli_real_escape_string($conn, $_POST['aR']);
    $aH = mysqli_real_escape_string($conn, $_POST['aH']);
    $aP = mysqli_real_escape_string($conn, $_POST['aP']);
    $hID = $_SESSION['home'];

    $sql = "INSERT INTO Addon (Addon_House_ID, Addon_Room_ID, Addon_Host_ID, Addon_Name, Addon_Description, Addon_Pin, Addon_Type) VALUES
    ('$hID', '$aR', '$aH', '$aN', '$aD', '$aP', '$aT')";
    
	$conn->query($sql);

    $conn->close();
?>
