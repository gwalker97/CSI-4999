<?php
    require('config.php');

    $sID = mysqli_real_escape_string($conn, $_POST['sID']);

    $sql = "SELECT Addon_ID FROM Scene_Assignment WHERE Scene_ID = '$sID'";

    $result = $conn->query($sql);
    $response = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($response['Addon_ID'] == "")
            {
                $response['Addon_ID'] = $row["Addon_ID"];
            }
            else
            {
                $response['Addon_ID'] = $response['Addon_ID'] . ',' . $row["Addon_ID"];
            }
        }
        echo json_encode($response);
    } else {
        echo "  0 results";
    }

    $conn->close();
?>

