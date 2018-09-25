<?php
	session_start();

	define('DB_SERVER', '127.0.0.1');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'password');
	define('DB_DATABASE', 'SeniorProject');
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	if(!$conn) {
		die("Connection failed : " . mysqli_connect_error());
	}

	if (!isset($_SESSION["guest"])) { $_SESSION["guest"] = true; }
?>
