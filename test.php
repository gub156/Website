<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $displayOrderForm = true; // true = affiche le formulaire de commande / false = pas de formulaire visible
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="index, follow">
    <title><?php echo 'Test';?></title>
    </head>
        <?php include_once("analyticstracking.php") ?>
        <div id="main">
            <header>
                <div id="logo"><h1>REF<a href="index">AST</a></h1></div>
                <?php include("01_includes/menu.php"); ?>
            </header>
            <div id="site_content">
		<div id="sidebar_container">
		    <?php include("01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include("01_includes/Display/texts.php");?>
		    </div>
		    <img src="05_images/testInProgress.png" width="50%">

		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html"); ?>
    </body>
    </html>