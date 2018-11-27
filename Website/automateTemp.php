<?php
    require('config.php');

    $isA = mysqli_real_escape_string($conn, $_POST['isA']);
    $aT = mysqli_real_escape_string($conn, $_POST['aT']);
    $aTT = mysqli_real_escape_string($conn, $_POST['aTT']);
    $hID = $_SESSION['home'];

    $sql = "";
    if ($isA == 0) {
        $sql = "UPDATE Temp SET Is_Automated = 0 WHERE House_ID = '$hID'";
    }
    else {
        $sql = "UPDATE Temp SET Is_Automated = 1, Target_Temp = '$aT', Target_Temp_Type = '$aTT' WHERE House_ID = '$hID'";
    }
    $conn->query($sql);
    $conn->close();
?>