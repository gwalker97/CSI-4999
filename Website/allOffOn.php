<?php
    require('config.php');

    $OnOff = mysqli_real_escape_string($conn, $_POST['OnOff']);
    $hID = $_SESSION['home'];

    $sql = "UPDATE Addon SET Addon_State = '$OnOff' WHERE Addon_House_ID = '$hID'";
    $conn->query($sql);

    $conn->close();
?>