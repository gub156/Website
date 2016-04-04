<?php
// Template version: 1.0.1 - 10.02.14
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    require("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo GODFATHER_TITLE;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index">AST</a></h1></div>
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
		    <h1><?php echo WHAT_IS_A_GODFATHER_TITLE;?></h1>
		    <p><?php echo WHAT_IS_A_GODFATHER_TEXT; ?></p>
		    <h1><?php echo HOW_TO_BECOME_GODFATHER_TITLE; ?></h1>
		    <p><?php echo HOW_TO_BECOME_GODFATHER_TEXT; ?></p>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html"); ?>
    </body>
</html>