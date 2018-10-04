<?php
require('config.php');

$id = mysqli_real_escape_string($conn, $_POST['id']);


$sql = "SELECT * FROM Addon WHERE Addon_ID = '$id'";

$result = $conn->query($sql);
$response = array();


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response['state'] = $row["Addon_State"];
    }
    echo json_encode($response);
} else {
    echo "  0 results";
}
$conn->close();
?>

