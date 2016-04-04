<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = true; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo DWNLOADLINK;?></title>
    </head>
    <body>
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
		    <h1><?php echo DWNLOADLINK;?></h1>
		    <h3><?php echo TUTOEAGLE; ?></h3><br />
		    <p><?php echo TUTOEAGLEDESCR; ?></p>
		    <table>
			<tr>
			    <td>Version française</td>
			    <td>1.1MB</td>
			    <td><a href="06_documents/downloads/Tuto_Eagle_fr.pdf" onclick="window.open(this.href); return false;"><img src="05_images/Logos/logo_pdf.png" alt="logo pdf"></a></td>
			</tr>
			<tr>
			    <td>Deutsche Version</td>
			    <td>Nicht vorhanden</td>
			    <td><img src="05_images/Logos/logo_pdf.png" alt="logo pdf"></td>
			</tr>
		    </table>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html"); ?>
    </body>
</html>
