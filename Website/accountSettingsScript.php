<?php
   require('config.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
        print(var_dump($_POST));
    }
?>