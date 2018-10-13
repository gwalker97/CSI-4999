<?php
require('config.php');

$hID = mysqli_real_escape_string($conn, $_SESSION['home']);

$sql = "SELECT * FROM Temp WHERE House_ID = '$hID'";

$result = $conn->query($sql);
$response = array();


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response['F'] = $row["F"];
        $response['C'] = $row["C"];
    }
    echo json_encode($response);
} else {
    echo "0 results";
}
$conn->close();
?>

