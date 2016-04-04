<?php
/*****************************************************************************************
|
|		Name of the project		:	Website of Refast
|		Name of the file		:	scriptMiniChat.php
|
|		Author					:	J. Rebetez
|		Date					:	30.01.13
|
|		Description of the file	:	This scrip
|		
|		Version					:	1.1
|		Date of the modification:	05.02.2013
------------------------------------------------------------------------------------------
|
|		List of includes		:
|
|-----------------------------------------------------------------------------------------
|
|		27.01.13				:	Creation of this file
|									- J. Rebetez -
|
|		05.02.2013				:	Change the path for the language file (/language/...)
|									by (language/...) who made a bug in IE8
|									- J. Rebetez -
|
|****************************************************************************************/

	// Connexion à la base de données
	include("connectMysql.php");
	
	// Insertion du message à l'aide d'une requête préparée
	$req = $bdd->prepare('INSERT INTO miniChat (pseudo, message, date) VALUES(?, ?, NOW())');
	$req->execute(array($_SESSION['user'], htmlspecialchars($_POST['message'])));
	
	// Fin de la requête
	$req->closeCursor();
	
	// Redirection du visiteur vers la dernière page visitée
	$file = $_SESSION['file'];
	header("Location: ../$file");
?>