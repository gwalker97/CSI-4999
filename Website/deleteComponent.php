<?php
require('config.php');

$_SESSION['indexMsg'] = "Component deleted!";

$id = mysqli_real_escape_string($conn, $_POST['id']);

$sql = "DELETE from Addon WHERE Addon_ID = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>

