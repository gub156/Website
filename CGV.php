<?php
    session_start(); //Démarrage de la session php
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo CGV;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
	    </header>
	    <div id="site_content">
		<div id="content">
		    <p><?php echo TEXT_CGV;?></p>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html");
	include("09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
