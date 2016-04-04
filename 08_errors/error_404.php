<?php
    if(!isset($_SESSION)) session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    require("../02_scripts/chooseLanguage.php");
    $_SESSION['file'] = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="icon" type="image/png" href="../05_images/favicon.png">
	<link rel="shortcut icon" type="image/x-icon" href="../05_images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="../00_css/style_maintenance.css" />
	<meta name="robots" content="noindex, follow">
	<title>Oooops! It's a 404 error...</title>
    </head>
    <body>
	<div id="main">
	    <div id="logo"><h1>REF<a href="../index">AST</a></h1></div>
	    <div id="site_content">
		<img src="../05_images/Logo/320x240.png" alt="Logo Refast" height="300px"/>
		<div id="content">
		    <p></p>
		    <h1>Oooops!</h1>
		    <p>This page doesn't exist or doesn't exist anymore.</p>
		    <a href="../index">Home Page</a><br/><br/><br/>
		</div>
	    </div>
	    <footer>
		<p><a class="standardLink" href="../index"><?php echo HOMELINK;?></a> | <a class="standardLink" href="../about_us"><?php echo ABOUTUSLINK;?></a> | <a class="standardLink" href="../contact"><?php echo CONTACTUSLINK;?></a>
		    <a class="standardLink" href="../Sitemap"><?php echo SITEMAP;?></a> |
		    <a class="standardLink" href="../CGV"><?php echo CGV;?></a> |
		    <a class="standardLink" href="../impressum"><?php echo IMPRESSUM;?></a>
		    <br/><br/><br/>
		    &copy; 2013 REFAST. All Rights Reserved.
		</p>
	    </footer>
	</div>
    </body>
</html>
<?php
    include("../09_libs/functions.php");
    DataLogger();
?>
