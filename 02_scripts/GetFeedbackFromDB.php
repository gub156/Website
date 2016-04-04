<?php
	session_start(); //Démarrage de la session php
	include("connectMysql.php"); // Connexion à la base de données
	
	//Acquisition des données en fonction du nom d'utilisateur
	$FBbdd = $bdd->prepare('SELECT id, date, language, construction, content, readability, 
								   divers, sexe, age, pseudo FROM feedback_book_evaluation WHERE Display = ?')
								   or die(print_r($bdd->errorInfo()));
	$FBbdd->execute(array(1)) or die(print_r($FBbdd->errorInfo()));
	
	// On récupère les informations de l'utilisateur dans un tableau appelé dataUser.
	$_SESSION['Feedbacks'] = $FBbdd->fetch();
	
	$FBbdd->closeCursor();

?>