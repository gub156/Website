<?php
    if(!isset($_SESSION)) session_start(); //Démarrage de la session php

// Si changement de langue par l'utilisateur, on réinitialise le cookie de l'utilisateur
// et on recharge la page avec le nouveau fichier de langue.
    if($_GET['switchLang'])
    {
	include('../09_libs/functions.php');
	$lang = $_GET['lang'];
	setcookie('language', $lang, time() + 365*24*3600, "/", NULL, false, true);
	Redirige();
    }
    // Sinon, si la variable cookie existe, on l'utilise pour sélectionner le bon fichier
    // de langue.
    elseif(isset($_COOKIE['language']))
    {
	$CGVlang = $_SESSION['backFile']."01_includes/languages/" . $_COOKIE['language'] . "/CGV.php";
	$impressum = $_SESSION['backFile']."01_includes/languages/" . $_COOKIE['language'] . "/impressum.php";
	$lang = $_SESSION['backFile']."01_includes/languages/" . $_COOKIE['language'] . "/lang_".$_COOKIE['language'].".php";

	include($CGVlang);
	include($impressum);
	include($lang);
    }
// Sinon, on questionne l'ordinateur de l'utilisateur pour connaître sa
// langue. Si elle est prise en charge par le site web, on initialise
// le cookie. Sinon, on fixe la langue en anglais.
    else
    {
	$userLang = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$userLang = strtolower(substr(chop($userLang[0]),0,2));
	if( $userLang == 'fr' OR
	    $userLang == 'de' /*OR
	    $userLang == 'en'*/)
	{
	    setcookie('language', $userLang, time() + 365*24*3600, "/", NULL, false, true);
	    $path = $_SESSION['file'];
	    header("Location: ..$path");
	    exit();
	}
	else
	{
	    setcookie('language', de, time() + 365*24*3600, "/", NULL, false, true);
	    $path = $_SESSION['file'];
	    header("Location: ..$path");
	    exit();
	}
    }
?>