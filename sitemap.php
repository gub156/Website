<?php
    // TODO: Master data update
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo SITEMAP;?></title>
    </head>
    <body>
	<?php include($_SESSION['backFile']."01_includes/feedbackButton.php") ?>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index">AST</a></h1></div>
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
		    <h1><?php echo SITEMAP;?></h1>
		    <ul>
			<li><a class="standardLink" href="index"><?php echo HOMELINK;?></a></li>
			<li><a class="standardLink" href="about_us"><?php echo ABOUTUSLINK;?></a></li>
			<li><a class="standardLink" href="engineering"><?php echo ENGINEERING;?></a></li>
			<li><a class="standardLink" href="products"><?php echo PRODUCTLINK;?></a></li>
			<li><ul><li><a class="standardLink" href="book_info?product=TB03DE-RE"><?php echo TECHNICAL_BOOK.'(de)';?></a></li></ul></li>
			<li><a class="standardLink" href="contact"><?php echo CONTACTUSLINK;?></a></li>
			<li><a class="standardLink" href="CGV"><?php echo CGV;?></a></li>
			<li><a class="standardLink" href="impressum"><?php echo IMPRESSUM;?></a></li>
		    </ul>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html"); ?>
    </body>
</html>
