<?php
session_start(); //Démarrage de la session php

$_SESSION['maintenance'] = false; // Website en mode "utilisation normale"
//$_SESSION['maintenance'] = true; // Website en mode "Mise à jour"

// Mode maintenance
// Explications du fonctionnement:
// -------------------------------

// $_SESSION['maintenance'] = true 		==> 	Site en mode maintenance
// $_SESSION['maintenance'] = false		==>	Site en mode normal

// $_SESSION['accesMaintenance'] = true	==>	Accès au site autorisé
// $_SESSION['accesMaintenance'] = false 	==>	Accès non-autorisé

// $_SESSION['NotYetInitialized'] = true	==>	Redirection déjà effectuée
// $_SESSION['NotYetInitialized'] = false	==>	Redirection de l'utilisateur


// On teste la variable pour connaître l'état du site web (normal ou maintenance)
if($_SESSION['maintenance'])
{
    // On contrôle que l'accès soit bien autorisé (accesMaintenance = true)
    if($_SESSION['accesMaintenance']);
    elseif($_GET['update'])	// Variable initialisée dans l'adresse
    {
	$_SESSION['accesMaintenance'] = true; // Accès autorisé au site web
	header("Location: index"); // Redirection sur la page home
	exit();
    }
    // Redirection aussi si l'adresse URL est modifiée
    elseif($_SESSION['file'] != "/maintenance.php")
    {
	$_SESSION['NotYetInitialized'] = true;
	header("Location: maintenance"); // Redirection sur la page home
	exit();
    }
}
elseif($_SESSION['file'] == "/maintenance.php")
{
    header("Location: index");
    exit();
}
?>