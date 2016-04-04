<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");

    if($_SESSION['old_file'] != "/loginPage.php")
    {
	$_SESSION['pageToDisplayAfterLogin'] = $_SESSION['old_file'];
    }
?>
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo LOGINPAGE;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
		<?php include("01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<?php include("01_includes/banner.html"); ?>
		<div id="sidebar_container">
		    <?php include("01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include("01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo LOGINPAGE;?></h1>
		    <div id="textDisplayed_Red">
			<?php echo TOSEETHISPAGE; ?>
		    </div>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html"); ?>
    </body>
</html>
