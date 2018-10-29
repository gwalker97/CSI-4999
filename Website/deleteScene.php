<?php
    require('config.php');

    $sID = mysqli_real_escape_string($conn, $_POST['sID']);

    $sql = "DELETE FROM Scene_Assignment WHERE Scene_ID = '$sID'";
    $conn->query($sql);

    $sql = "DELETE FROM Scenes WHERE Scene_ID = '$sID'";
    $conn->query($sql);

    $conn->close();
?>