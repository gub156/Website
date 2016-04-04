<?php
	session_start(); //Démarrage de la session php

        // Connexion à la base de données
	include("connectMysql.php");

        $chemin_destination = '../05_images/avatars/';

        //Acquisition des données en fonction du nom d'utilisateur
	$userbdd = $bdd->prepare('SELECT avatar FROM userAccount WHERE pseudo = ?') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_SESSION['user'])) or die(print_r($userbdd->errorInfo()));

	// On récupère les informations de l'utilisateur dans un tableau appelé dataUser.
	$dataUser = $userbdd->fetch();

	$userbdd->closeCursor();

        $avatar = $dataUser['avatar'];

        $req = $bdd->prepare('DELETE FROM userAccount WHERE pseudo = :pseudo');
        $req->execute(array('pseudo' => $_SESSION['user'])) or die(print_r($req->errorInfo()));
        $userbdd->closeCursor();

        unlink($chemin_destination . $avatar);

        $_SESSION['accesGranted'] = false;
        $_SESSION['AccountHasBeenDeleted'] = true;

        header('Location: ../index.php');
        exit();
?>