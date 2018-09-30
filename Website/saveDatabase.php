<?php
require('config.php');

$id = mysqli_real_escape_string($conn, $_POST['id']);
$state = mysqli_real_escape_string($conn, $_POST['state']);

$sql = "UPDATE Addon SET Addon_State = '$state' WHERE Addon_ID = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>

