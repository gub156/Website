<?php
// Template version: 1.0.1 - 10.02.14
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
    <meta name="robots" content="index, follow">
    <title><?php echo PRESS_MEDIA;?></title>
    </head>
    <body>
		<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
        <div id="main">
            <header>
                <div id="logo"><h1>REF<a href="index">AST</a></h1></div>
                <?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
            </header>
            <div id="site_content">
                <?php include($_SESSION['backFile']."01_includes/banner.html"); ?>
                <div id="sidebar_container">
		<?php
		    include($_SESSION['backFile']."01_includes/sidebar/medias.php");
		    include($_SESSION['backFile']."01_includes/sidebar.php");
		?>
                </div>
                <div id="content">
                   <div id="textDisplayer">
                        <?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
                   </div>
                   <h1><?php echo 'REFAST News';?></h1>
                </div>
            </div>
            <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
        </div>
        <?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
