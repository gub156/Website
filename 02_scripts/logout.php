<?php
	session_start();
	
	$file = $_SESSION['file'];
	
	
	header("Location: ..$file"); // Redirection sur la page home
	session_unset();
		
	session_destroy();
	exit();
?>