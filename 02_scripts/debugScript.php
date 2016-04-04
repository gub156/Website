<?php
	session_start();
	if($_GET['value'] == 0) $_SESSION['beta'] = false;
	elseif($_GET['value'] == 1) $_SESSION['beta'] = true;

	$path = $_SESSION['file'];
        header("Location: ..$path");
?>