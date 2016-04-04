<?php
    session_start(); //Démarrage de la session php

//--- Défini la profondeur de la page (dossier-mère, sous-dossier, sous-sous-dossier ---//
    switch($_SESSION['dossierRacine'])
    {
	case 0:		$_SESSION['backFile'] = "";
			break;
	case 1:		$_SESSION['backFile'] = "../";
			break;
	case 2:		$_SESSION['backFile'] = "../../";
			break;
    }
//--- Enregistrement de la page quittée ainsi que la nouvelle page visitée ---//
    if(isset($_SERVER['PHP_SELF']))
    {
    	if(empty($_SESSION['old_file']) && empty($_SESSION['file']))
	{
	    $_SESSION['old_file'] = $_SESSION['file'] = $_SERVER['PHP_SELF'];
	}
	else
	{
	    $_SESSION['old_file'] = $_SESSION['file'];
	    $_SESSION['file'] = $_SERVER['PHP_SELF'];
	}
	$_SESSION['URI'] = $_SERVER['REQUEST_URI'];
    }
    else
    {
    	$_SESSION['old_file'] = $_SESSION['file'] = "/index.php";
    }

//--- Permet d'afficher la page désirée ou de rediriger l'utilisateur s'il est enregistré ou non. ---//
    if($pageWithLogin)
    {
        if(!$_SESSION['accesGranted'])
        {
            header("Location: loginPage.php");
            exit();
        }
    }

//--- Détection du navigateur une seule fois durant la session ---//
    if($_SESSION['checkIEBrowser'] != true)
    {
	if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') == true))
	{
	    if($_COOKIE['language'] == "fr") echo '<p>Vous utilisez un navigateur non compatible avec ce site web. Pourquoi ne pas passer à un navigateur standardisé comme <a href="https://www.google.fr/chrome/">Google Chrome</a> ou <a https://www.mozilla.org/fr/firefox/new/>Firefox</a>?</p>';
	    else if($_COOKIE['language'] == "de") echo '<p>Sie verwenden für diese Webseite einen nicht kompatiblen Web-Browser. Wechseln Sie ganz einfach zu einem standardisierten Web-Browser wie <a href="https://www.google.fr/chrome/">Google Chrome</a> oder <a https://www.mozilla.org/fr/firefox/new/>Firefox</a>.</p>';
	}
	$_SESSION['checkIEBrowser'] = true;
    }
?>