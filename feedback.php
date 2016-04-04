<?php
    session_start(); //Démarrage de la session php
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."09_libs/functions.php");
    DataLogger();

    if($_GET['val'] == 1)
    {
	header("Location: 11_dedicatedPages/book");
	exit();
    }
    elseif($_GET['val'] == 2)
    {
	header("Location: _pages_feedback/RDT.php");
	exit();
    }
    else ?> Oooops! It's a wrong QR Code. Please try again <?php
?>
