<?php
    session_start();
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta name="description" content="Website of REFAST Company" />
        <meta name="keywords" content="Maintenance page" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="icon" type="image/png" href="../05_images/favicon.png">
        <link rel="shortcut icon" type="image/x-icon" href="../5_images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="00_css/style_maintenance.css" />
        <meta name="robots" content="noindex, follow">
        <title>Maintenance</title>
    </head>
    <body>
		<?php include_once("analyticstracking.php") ?>
        <div id="main">
            <div id="logo"><h1>REF<a href="#">AST</a></h1></div>
            <div id="site_content">
                <img src="05_images/Logo/320x240.png" alt="Logo Refast" height="300px" align="center"/>
                <div id="content">
                    <p></p>
                    <h1>Site under maintenance</h1>
                    <p>Wir arbeiten momentan an unserer Webseite.<br />Besuchen Sie uns bitte später wieder.</p>
                    <hr/>
                    <p>We are actually working to improve our website. <br/> Please visit us later.</p>
                    <hr/>
                    <p>Nous travaillons actuellement à améliorer notre site.<br/> Visitez-nous plus tard.</p>
                    <p><br /></p>
                </div>
            </div>
            <footer>
                <p>&copy; 2013 Refast. All Rights Reserved.</p>
                <?php include($_SESSION['backFile']."02_scripts/Logger.php"); ?>
            </footer>
        </div>
    </body>
</html>
