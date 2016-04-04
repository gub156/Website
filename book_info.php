<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['pageWithGetValue'] = true; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
// Connexion à la base de données
    include("02_scripts/connectMysql.php");
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
    include("09_libs/functions.php");

    $_SESSION['articleNumber'] = $_GET['product'];

//Acquisition des données en fonction du produit ($_SESSION['productID']) et de la langue ($_COOKIE['language'])
    $userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.carrousel, pr.language, pr.linkButton, pr.shippingConditions, pr.authors, pr.edition, pr.pages, pr.description, pr.ISBN, pr.picturePath, pr.specialOfferText, pr.available, sampled, samplePath,
				pl.priceCHF, pl.discount, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.TVA_EUR, pl.unitEUR, pl.discountCHF, pl.discountEUR
				FROM product AS pr
				INNER JOIN priceList as pl
				ON pr.articleNumber = pl.artNumber
				WHERE pr.articleNumber = ? AND pr.productLang = ?
				ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_SESSION['articleNumber'], $_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
// On récupère les informations de l'utilisateur dans un tableau appelé product.
    $dataProduct = $userbdd->fetch();

    $userbdd->closeCursor();
?>
    <meta name="robots" content="index, follow">
    <title><?php echo TECHNICAL_BOOK;?></title>
	<script type="text/javascript" src="11_flash/swfobject.js"></script>
	<script type="text/javascript">
		swfobject.registerObject("myFlashContent", "9.0.0", "expressInstall.swf");
	</script>
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
		<?php
	// Affichage de la banner ou d'une image si le périphérique ne supporte pas flash
		if($dataProduct['carrousel']) includeBanner($_SESSION['articleNumber']);
		?>
		<div id="sidebar_container">
		    <?php include("01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include("01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo TECHNICAL_BOOK;?></h1>
		    <!-- Informations du produit -->
			<?php // ICI PEUT ETRE AFFICHE UN MESSAGE A L'UTILISATEUR ?>
		    <table>
			<tr>
			    <td></td>
			    <td> <img src="<?php echo $dataProduct['picturePath'];?>" align="center" height="300px" alt="photo flyer de"/></td>
			    <td>
			    <?php
				echo "<h3>" . $dataProduct['title'] . "</h3><br/>";
				echo $dataProduct['edition'] . "<br/><br/>";
				echo $dataProduct['authors'] . "<br/><br/>";
				echo $dataProduct['language'] . "<br/><br/>";
				echo $dataProduct['pages'] . "<br/><br/>";
				echo '<span style="color: green;">'.$dataProduct['shippingConditions']. "</span><br/><br/>";
				echo 'ISBN: ' . $dataProduct['ISBN'];
			    ?>
			    </td>
			    <td>
			    <?php
				if($dataProduct['available'])
				{
				    if($dataProduct['discount'])
				    {
					echo '<del>'.$dataProduct['unitCHF'].number_format($dataProduct['priceCHF'], 2, '.', '\'').' / '.
						$dataProduct['unitEUR'].number_format($dataProduct['priceEUR'], 2, '.', '\'').'</del><br/>';
					echo FROM .' '.$dataProduct['unitCHF'].number_format($dataProduct['priceCHF']-(($dataProduct['priceCHF']/100)*$dataProduct['discountCHF']), 2, '.', '\'').' / '.
						$dataProduct['unitEUR'].number_format($dataProduct['priceEUR']-($dataProduct['priceEUR']/100*$dataProduct['discountEUR']), 2, '.', '\'').'<br/>';
				    }
				    else echo FROM .' '.$dataProduct['unitCHF'].number_format($dataProduct['priceCHF'], 2, '.', '\'').' / '.
						$dataProduct['unitEUR'].number_format($dataProduct['priceEUR'], 2, '.', '\'').'<br/>';
				    echo '<br/>';
				    if($dataProduct['specialOfferText'] != "") echo '<br/><span class="specialOffer">'.$dataProduct['specialOfferText'].'</span><br/>';
				    ?>
				    <br/><br/>
				    <?php
				}
				else echo COMMING_SOON; ?>
			    </td>
			</tr>
		    </table>
		    <br/>
		    <?php
		// Description du produit
		    echo $dataProduct['description'];
		// Exemple du produit, échantillon,...
		    if($dataProduct['sampled'])
		    {
			if($_SESSION['browser'] != "iPod, iPhone & iPad")
			{ ?>
			    <div class="center">
				<a href="<?php echo $dataProduct['samplePath'];?>" onclick="window.open(this.href); return false;"><input class="button" type="button" value="<?php echo SEEBOOK;?>"></a>
			    </div>
			    <?php
			}
			else
			{ ?>
			    <div class="center">
				<a href="<?php echo $dataProduct['SamplePathNoFlash'];?>" onclick="window.open(this.href); return false;"><input class="button" type="button" value="<?php echo DOWNLOAD;?>"></a>
			    </div>
			    <?php
			}
			}?>
		    <br/><br/>

		    <?php
		// Disponibilité du produit
		    if($dataProduct['available'])
		    { ?>
			<div class="center">
			    <a href="<?php echo $dataProduct['linkButton'];?>"><input class="button" type="button" value="<?php echo ORDER;?>"></a>
			</div><br/><br/>
		    <?php
		    }?>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php
	include("01_includes/javascripts.html");
	DataLogger();
	?>
    </body>
</html>

