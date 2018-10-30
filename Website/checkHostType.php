<?php
    require('config.php');

    $hID = mysqli_real_escape_string($conn, $_POST['hID']);

    $sql = "SELECT Host_Model FROM Hosts WHERE Host_ID = '$hID'";

    $result = $conn->query($sql);
    $response = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $response['model'] = $row["Host_Model"];
        }
        echo json_encode($response);
    } else {
        echo "  0 results";
    }

    $conn->close();
?>

