<?php
    require('config.php');

    $sID = mysqli_real_escape_string($conn, $_POST['sID']);


    $sql = "SELECT Scene_Name, Scene_Color, Is_Automated, Start_Time, End_Time FROM Scenes WHERE Scene_ID = '$sID'";

    $result = $conn->query($sql);
    $response = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $response['Scene_Name'] = $row["Scene_Name"];
            $response['Scene_Color'] = $row["Scene_Color"];
            $response['Is_Automated'] = $row["Is_Automated"];
            $response['Start_Time'] = $row["Start_Time"];
            $response['End_Time'] = $row["End_Time"];
        }
        echo json_encode($response);
    } else {
        echo "  0 results";
    }

    $conn->close();
?>

