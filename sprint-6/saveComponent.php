<?php
require('config.php');

$_SESSION['indexMsg'] = "Component updated!";

$id = mysqli_real_escape_string($conn, $_POST['id']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$roomid = mysqli_real_escape_string($conn, $_POST['roomid']);
$gID = mysqli_real_escape_string($conn, $_POST['gID']);

if ($_SESSION['gID'] == 1) {
    $sql = "UPDATE Addon SET Addon_Name = '$name', Addon_Description = '$description', Addon_Room_ID = '$roomid', Addon_Group_ID = '$gID' WHERE Addon_ID = '$id'";
} else {
    $sql = "UPDATE Addon SET Addon_Name = '$name', Addon_Description = '$description', Addon_Room_ID = '$roomid' WHERE Addon_ID = '$id'";
}

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>

