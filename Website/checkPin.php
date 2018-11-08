<?php
    require('config.php');

    $hID = mysqli_real_escape_string($conn, $_POST['hID']);
    $pin = mysqli_real_escape_string($conn, $_POST['pin']);

    $sql = "SELECT * FROM Addon WHERE Addon_Host_ID = '$hID' AND Addon_Pin = '$pin'";

    $result = $conn->query($sql);
    $response = array();

    if ($result->num_rows > 0) {
        $response = "Pin Taken!";
        echo json_encode($response);
    } else {
        $response = "Pin Available!";
        echo json_encode($response);
    }

    $conn->close();
?>

