<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include_once("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="index, follow">
    <title><?php echo ABOUTUSLINK;?></title>
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
	    <a class="nowandthen">
		<img src="05_images/AboutUs/REFAST_Founders_1" alt="REFAST Founders_1">
		<img src="05_images/AboutUs/REFAST_Founders_2" alt="REFAST Founders_2">
	    </a>
	    <div id="sidebar_container">
		<?php include("01_includes/sidebar.php"); ?>
	    </div>
	    <div id="content">
		<div id="textDisplayer">
		    <?php include("01_includes/Display/texts.php");?>
		</div>
		<h1><?php echo ABOUTUSLINK;?></h1>
		<?php echo PRESENTUS;?>
		<?php
		    if($_COOKIE['language'] == "de")
		    { ?>
			<iframe src='http://embed.verite.co/timeline/?source=0ApDy0VyN2c5sdEY0MzFEWmdvMHY2YUdYY2g3WWR0eVE&font=Bevan-PotanoSans&maptype=toner&lang=de&start_at_end=true&height=650' width='100%' height='650' frameborder='0'></iframe>
			<?php
		    }
		    elseif($_COOKIE['language'] == "fr")
		    { ?>
			<iframe src='http://embed.verite.co/timeline/?source=0ApDy0VyN2c5sdFZrOXFrSlY2OXVYazlzLUozWjg2NWc&font=Bevan-PotanoSans&maptype=toner&lang=fr&start_at_end=true&height=650' width='100%' height='650' frameborder='0'></iframe>
			<?php
		    }
		    else
		    { ?>
			<iframe src='http://embed.verite.co/timeline/?source=0ApDy0VyN2c5sdDY2aUFoZjdLb1NEQmlhZk5LbV9jZnc&font=Bevan-PotanoSans&maptype=toner&lang=en&start_at_end=true&height=650' width='100%' height='650' frameborder='0'></iframe>
			<?php
		    } ?>
		    <h2><?php echo FOUNDERS;?></h2>
		    <table id="founders">
			<tr>
			    <td width="223" align="center">
				<a class="nowandthen_founders">
				    <img src="05_images/AboutUs/REFAST_Julien_1" width="150" alt="REFAST Julien_1">
				    <img src="05_images/AboutUs/REFAST_Julien_2" width="150" alt="REFAST Julien_2">
				</a>
			    </td>
			    <td width="223" align="center">
				<a class="nowandthen_founders">
				    <img src="05_images/AboutUs/REFAST_David_1" width="150" alt="REFAST David_1">
				    <img src="05_images/AboutUs/REFAST_David_2" width="150" alt="REFAST David_2">
				</a>
			    </td>
			    <td width="223" align="center">
				<a class="nowandthen_founders">
				    <img src="05_images/AboutUs/REFAST_Alain_1" width="150" alt="REFAST Alain_1">
				    <img src="05_images/AboutUs/REFAST_Alain_2" width="150" alt="REFAST Alain_2">
				</a>
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td></td>
			    <td></td>
			</tr>
			<tr>
			    <td>
				<?php echo JULIEN;?>
			    </td>
			    <td>
				<?php echo DAVID;?>
			    </td>
			    <td>
				<?php echo ALAIN;?>
			    </td>
			</tr>
		    </table>
		<?php if($_SESSION['beta'])
		{ ?>
		    <h2><?php echo MAIN_PARTNERS;?></h2>
		    <table>
			<tr>
			    <td width="50%" align="center">
				<a class="nowandthen_founders">
				    <img src="05_images/AboutUs/REFAST_Cecile_1" width="150" alt="REFAST Cecile_1">
				    <img src="05_images/AboutUs/REFAST_Cecile_2" width="150" alt="REFAST Cecile_2">
				</a>
			    </td>
			    <td width="50%" align="center">
				<a class="nowandthen_founders">
				    <img src="05_images/AboutUs/Romina_1" width="150" alt="REFAST Romina_1">
				    <img src="05_images/AboutUs/Romina_2" width="150" alt="REFAST Romina_2">
				</a>
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td></td>
			</tr>
			<tr>
			    <td>
				<?php echo CECILE;?>
			    </td>
			    <td>
				<?php echo ROMINA;?>
			    </td>
			</tr>
		    </table>
		    <p><?php echo THANKS_TO_THE_OTHER_HELPFULL_PEOPLE. "Thomas Reichmuth, Michel Neuhaus, Marc Staub, Christoph Reusser".THANK_TO_OUR_FAMILY;?></p>
		    <?php
		} ?>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html");
	include("09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
