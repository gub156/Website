<?php
// Template version: 1.0.1 - 10.02.14
    if(!isset($_SESSION)) session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 1; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("../02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
    <meta name="robots" content="index, follow">
    <title><?php echo KIT_MEDIA;?></title>
    </head>
    <body>
		<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="<?php echo $_SESSION['backFile'];?>index">AST</a></h1></div>
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
		    <h1><?php echo KIT_MEDIA;?></h1>
		    <h3>Logos</h3>
		    <table>
		    <?php //--- Logo 100x100px ---// ?>
			<tr>
			    <td><img src="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/basic/100x100%20trans.png" Height="100px"></td>
			    <td>100px x 100px</td>
			    <td>
				499 Ko<br/>
				14 Ko<br/>
				14 Ko
			    </td>
			    <td>
				Format eps<br/>
				Format png - background transparent<br/>
				Format png - background blanc<br/>
			    </td>
			    <td>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/basic/100x100.eps" download="REFAST_LOGO_100x100">Link</a><br/>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/basic/100x100%20trans.png" download="REFAST_LOGO_100x100_trans">Link</a><br/>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/basic/100x100%20white.png" download="REFAST_LOGO_100x100_white">Link</a>
			    </td>
			</tr>
			<tr>
			    <td><img src="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/+ web/100x100_trans.png" Height="100px"></td>
			    <td>100px x 100px</td>
			    <td>
				521 Ko<br/>
				19 Ko<br/>
				18 Ko
			    </td>
			    <td>
				Format eps<br/>
				Format png - background transparent<br/>
				Format png - background blanc<br/>
			    </td>
			    <td>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/+%20web/100x100.eps" download="REFAST_LOGO_100x100">Link</a><br/>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/+%20web/100x100_trans.png" download="REFAST_LOGO_100x100_trans">Link</a><br/>
				<a href="<?php echo $_SESSION['backFile'];?>05_images/Logos_medias/100x100/+%20web/100x100_white.png" download="REFAST_LOGO_100x100_white">Link</a>
			    </td>
			</tr>
		    </table>
		    <table>
			<tr>
			    <td><a href="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/jpg/REFAST_Julien" download="REFAST_Julien.jpg"><img src="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/REFAST_Julien_small" Height="200px"></a></td><br/>
			    <td><a href="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/jpg/REFAST_David" download="REFAST_David.jpg"><img src="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/REFAST_David_small" Height="200px"></a></td><br/>
			    <td><a href="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/jpg/REFAST_Alain" download="REFAST_Alain.jpg"><img src="<?php echo $_SESSION['backFile'];?>05_images/AboutUs/REFAST_Alain_small" Height="200px"></a></td><br/>
			</tr>
			<tr>
			    <td>Julien Rebetez</td>
			    <td>David Falk</td>
			    <td>Alain Staub</td>
			</tr>
		    </table>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
