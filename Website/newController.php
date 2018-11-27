<?php
    require('config.php');

    $cN = mysqli_real_escape_string($conn, $_POST['cN']);
    $cM = mysqli_real_escape_string($conn, $_POST['cM']);

    $sql = "INSERT INTO Hosts (Host_Name, Host_Model, Host_MAC) VALUES
    ('$cN', 'ESP', '$cM')";
    
	$conn->query($sql);

    $conn->close();

    $_SESSION['indexMsg'] = "Controller Saved!";

?>
