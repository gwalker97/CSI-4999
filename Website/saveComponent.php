<?php
require('config.php');

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

$result = mysqli_query($conn,$sql);

if ($result === true) {
    if (isset($_SESSION['indexMsg'])) {
        $_SESSION['indexMsg'] .= "<brComponent updated!";
    } else {
        $_SESSION['indexMsg'] = "Component updated!";
    }
} else {
    if (isset($_SESSION['indexMsg'])) {
        $_SESSION['indexMsg'] .= "<br>Component could not be updated!";
    } else {
        $_SESSION['indexMsg'] = "Component could not be updated!";
    }
}

header("Location: index.php");
?>

