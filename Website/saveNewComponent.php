<?php
require('config.php');

$_SESSION['indexMsg'] = "Component added!";

$id = mysqli_real_escape_string($conn, $_POST['id']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$roomid = mysqli_real_escape_string($conn, $_POST['roomid']);

$sql = "UPDATE Addon SET Addon_Name = '$name', Addon_Description = '$description', Addon_Room_ID = '$roomid' WHERE Addon_ID = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>

