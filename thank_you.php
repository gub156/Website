<?php
    session_start(); //Démarrage de la session php
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
        <meta name="robots" content="noindex, nofollow">
        <title><?php echo THANKYOU;?></title>
    </head>
    <body>
	<?php include($_SESSION['backFile']."01_includes/feedbackButton.php") ?>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
		<?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<?php include($_SESSION['backFile']."01_includes/banner.html"); ?>
		<div id="sidebar_container">
		    <?php include($_SESSION['backFile']."01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo THANKYOU;?></h1>
		    <p><?php echo MAIL_HAS_BEEN_SEND_WITH_CONFIRMATION . $_SESSION['email'] . MAIL_HAS_BEEN_SEND_WITH_CONFIRMATION_2; ?></p>
		    <br/><br/><br/>
		    <div class="center">
			<img src="05_images/general/thankyou" alt="Thank you img" width="95%">
		    </div>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html"); ?>
    </body>
</html>
<?php
    unset($_SESSION['email_order']);
?>
