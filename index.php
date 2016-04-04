<?php
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
    <title><?php echo HOMELINK_HEADER;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
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
		    <h1><?php echo HOMELINK;?></h1>
			<div class="center">
			<?php
			if($_COOKIE['language'] == "de")
			{
			    echo '<h3>Formelbuch - Bestellung der französischen Version</h3>';
			    echo 'Die erste französische Version vom Formelbuch steht für eine bestellung bereit.<br/><br/>';
			    echo '<a href="order"><img src="05_images/products/Formelbuch/TB01FR-RE/flyer_french" alt="flyer livre français" width="80%"></a>';
			}
			elseif($_COOKIE['language'] == "fr")
			{
			    echo '<h3>Formulaire technique - La version française est en vente</h3>';
			    echo 'La première édition de la version française de notre formulaire technique est dès maintenant disponible à la vente.<br/><br/>';
			    echo '<a href="order"><img src="05_images/products/Formelbuch/TB01FR-RE/flyer_french" alt="couverture livre français" width="80%"></a>';
			}
			echo '</div>'; ?>
		    <div class="center">
		    <?php
			if($_COOKIE['language'] == "fr")
			{
			    echo '<h3>Nous avons été incubés</h3><img src="05_images/Logo/friup_logo" alt="Logo FriUp REFAST" width="200px">';
			    echo '<br/><br/>Depuis le 14 Octobre, nous sommes officiellement incubés au sein de FRIUP.<br/>Durant deux ans, FRIUP nous met donc à disposition des locaux et une infrastructure pour pouvoir travailler dans des conditions optimales.<br/>
				    En plus de cela, un coach nous soutiendra durant ce parcours dans différents secteurs.<br/><br/>
				    Nous nous réjouissons de travailler en collaboration avec FRIUP et sommes certains de faire un grand pas en avant avec leur soutien.<br/><br/>
				    <br/><a href="http://www.friup.ch/de/2014/11/fri-up-incube-refast-2/">Lire l\'article sur le site de FRIUP.</a><br/><br/><br/>
				    Votre Team REFAST';
			}
			elseif($_COOKIE['language'] == "de")
			{
			    echo '<h3>Wir wurden aufgenommen</h3><img src="05_images/Logo/friup_logo" alt="Logo FriUp REFAST" width="200px">';
			    echo '<br/><br/>Seit dem 14.10.2014 dürfen wir während zwei Jahren auf die Unterstützung von FRIUP zählen.<br/>';
			    echo 'Nach einer 15 minütigen Präsentation über unser Projekt REFAST und einer 15 minütigen Stellungnahme gegenüber Fragen von FRIUP, teilte uns das Komitee die Aufnahme mit.<br/>';
			    echo '<br/>Wir werden begleitet und unterstützt unsere Ziele zu erreichen, dazu werden uns Büroräume in Murten zur Verfügung gestellt.<br/><br/>';
			    echo '<a href="http://www.friup.ch/de/2014/11/fri-up-incube-refast-2/">Lesen Sie der Artikel auf der Webseite von FRIUP.</a><br/><br/><br/>';
			    echo 'Ihre REFAST-Team';
			}
			?>
		    </div>
		    <hr>
		    <?php // ICI PEUT ETRE AFFICHE UN MESSAGE A L'UTILISATEUR ?>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php
	    include($_SESSION['backFile']."01_includes/javascripts.html");
	    include($_SESSION['backFile']."09_libs/functions.php");
	    DataLogger();
	?>
    </body>
</html>