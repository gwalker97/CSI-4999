<?php
require('config.php');

$id = mysqli_real_escape_string($conn, $_POST['id']);

$sql = "delete from Scene_Assignment where Addon_ID = " . $id;
$result = mysqli_query($conn,$sql);
$sql2 = "delete from Addon where Addon_ID = " . $id;
$result2 = mysqli_query($conn,$sql2);

if ($result2 === true) {
    $_SESSION['indexMsg'] = "Component deleted!";
} else {
    $_SESSION['indexMsg'] = "Component could not be deleted!";
}
/*
if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
*/
$conn->close();
?>

