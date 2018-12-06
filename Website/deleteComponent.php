<?php
require('config.php');

$id = mysqli_real_escape_string($conn, $_POST['id']);

$sql = "delete from Scene_Assignment where Addon_ID = " . $id;
$result = mysqli_query($conn,$sql);
$sql2 = "delete from Addon where Addon_ID = " . $id;
$result2 = mysqli_query($conn,$sql2);

if ($result2 === true) {
    if (isset($_SESSION['indexMsg'])) {
        $_SESSION['indexMsg'] .= "<brComponent deleted!";
    } else {
        $_SESSION['indexMsg'] = "Component deleted!";
    }
} else {
    if (isset($_SESSION['indexMsg'])) {
        $_SESSION['indexMsg'] .= "<br>Component could not be deleted!";
    } else {
        $_SESSION['indexMsg'] = "Component could not be deleted!";
    }
}

header("Location: index.php");
?>

