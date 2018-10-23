<?php
    require('config.php');

    $OnOff = mysqli_real_escape_string($conn, $_POST['OnOff']);
    $hID = $_SESSION['home'];

    $sql = "UPDATE Addon SET Addon_State = '$OnOff' WHERE Addon_House_ID = '$hID'";
    $conn->query($sql);

    if ($OnOff == 0) {
        $sql = "SELECT Scene_ID FROM Scenes WHERE House_ID = '$hID'";
        $result = $conn->query($sql);
        $response = array();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sID = $row["Scene_ID"];
                $sql = "UPDATE Scene_Assignment SET IsSet = 0 WHERE Scene_ID = '$sID'";
                $conn->query($sql);
            }
        } else {
            echo "  0 results";
        }
    }

    $conn->close();
?>