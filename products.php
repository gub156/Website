<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
    include("02_scripts/connectMysql.php");

 //Acquisition des données pour les produits - Si l'utiliateur est en mode beta, on récupère tous les produits.
    if($_SESSION['beta'])
    {
	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.supplier, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.authors, pr.edition, pr.unit, pr.linkButton,
					    pr.picturePath, pr.available, pr.specialOfferText, pr.webPage, pr.shippingConditions, pr.textColor, pr.amazon, pr.linkAmazon,
					    pl.priceCHF, pl.discount, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.TVA_EUR, pl.unitEUR, pl.discountCHF, pl.discountEUR
				    FROM product AS pr
				    INNER JOIN priceList as pl
				    ON pr.articleNumber = pl.artNumber
				    WHERE pr.productLang = ?
				    ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé product.
	$_SESSION['product'] = $userbdd->fetchAll();
    /*** Acquisition du nombre de produits ***/
	$userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM product WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
    /*** On récupère le nombre de produits dans un tableau ***/
	$numberProduct = $userbdd->fetch();
    }
    else // Sinon, on ne récupère que les produits valables.
    {
    	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.supplier, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.authors, pr.edition, pr.unit, pr.linkButton,
					    pr.picturePath, pr.available, pr.specialOfferText, pr.webPage, pr.shippingConditions, pr.textColor, pr.amazon, pr.linkAmazon,
					    pl.priceCHF, pl.discount, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.TVA_EUR, pl.unitEUR, pl.discountCHF, pl.discountEUR
				    FROM product AS pr
				    INNER JOIN priceList as pl
				    ON pr.articleNumber = pl.artNumber
				    WHERE pr.productLang = ? AND pr.available = ?
				    ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'], 1)) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé product.
	$_SESSION['product'] = $userbdd->fetchAll();
    /*** Acquisition du nombre de produits ***/
	$userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM product WHERE productLang = ? AND available = ?') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'], 1)) or die(print_r($userbdd->errorInfo()));
    /*** On récupère le nombre de produits dans un tableau ***/
	$numberProduct = $userbdd->fetch();
    }
    $userbdd->closeCursor();
?>

	<meta name="robots" content="index, follow">
	<title><?php echo PRODUCTLINK;?></title>
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
		    <?php // ICI PEUT ETRE AFFICHE UN MESSAGE A L'UTILISATEUR ?>
		    <h1><?php echo PRODUCTLINK;?></h1>
		    <!-- Affichage des produits -->
			<?php
			for($i = 0; $i < $numberProduct[nb]; $i++)
			{
			    if($_SESSION['product'][$i]['available'] || $_SESSION['beta'])
			    { ?>
				<table>
				    <tr>
					<td class="largeur100Pixels">
					    <a href="<?php echo $_SESSION['product'][$i]['webPage'] ?>"><img class="resize" src="<?php echo $_SESSION['product'][$i]['picturePath'] ?>" alt="<?php echo $_SESSION['product'][$i]['altPicturePath'] ?>"></a>
					</td>
					<td>
					    <?php
					    echo '<b>'.$_SESSION['product'][$i]['title'].'</b><br/><br/>';
					    echo $_SESSION['product'][$i]['authors'].'<br/>';
					    echo $_SESSION['product'][$i]['edition'].'<br/>';
					    echo $_SESSION['product'][$i]['language'].'<br/>';
					    echo '<br/>'.ART_NUMBER.$_SESSION['product'][$i]['articleNumber'].'<br/>';
					    ?>
					    <br/><br/>
					    <a href="<?php echo $_SESSION['product'][$i]['webPage'] ?>"><input class="button" type="button" value="<?php echo INFOS;?>"></a>
					</td>
					<td class="price">
					    <?php
					    if($_SESSION['product'][$i]['discount'] != 0)
					    {
						echo '<del>'.$_SESSION['product'][$i]['unitCHF'].number_format($_SESSION['product'][$i]['priceCHF'], 2, '.', '\'').' / '.
								$_SESSION['product'][$i]['unitEUR'].number_format($_SESSION['product'][$i]['priceEUR'], 2, '.', '\'').'</del><br/>';
						echo FROM .' '.$_SESSION['product'][$i]['unitCHF'].number_format($_SESSION['product'][$i]['priceCHF']-($_SESSION['product'][$i]['priceCHF']/100*$_SESSION['product'][$i]['discountCHF']), 2, '.', '\'').' / '.
					    			$_SESSION['product'][$i]['unitEUR'].number_format($_SESSION['product'][$i]['priceEUR']-($_SESSION['product'][$i]['priceEUR']/100*$_SESSION['product'][$i]['discountEUR']), 2, '.', '\'').'<br/>';
					    }
					    else echo FROM .' '.$_SESSION['product'][$i]['unitCHF'].number_format($_SESSION['product'][$i]['priceCHF'], 2, '.', '\'').' / '.
								$_SESSION['product'][$i]['unitEUR'].number_format($_SESSION['product'][$i]['priceEUR'], 2, '.', '\'').'<br/>';
					    echo '<br/>';
					    if($_SESSION['product'][$i]['specialOfferText'] != "") echo '<br/><span class="specialOffer">'.$_SESSION['product'][$i]['specialOfferText'].'</span><br/>';
					    ?>
					    <br/><br/>
					    <?php
					    if($_SESSION['product'][$i]['available'])
					    { ?>
						<a href="<?php echo $_SESSION['product'][$i]['linkButton']; ?>"><input class="button" type="button" value="<?php echo ORDER_DIRECT;?>"></a><br/>
						<?php 	if($_SESSION['product'][$i]['supplier']){?><a href="whoBuy"><input class="button" type="button" value="<?php echo DISTRIBUTORS;?>"></a><br/><?php }
							if($_SESSION['product'][$i]['amazon']) {?> <a href="<?php echo $_SESSION['product'][$i]['linkAmazon']; ?>" onclick="window.open(this.href); return false;"><input class="button" type="button" value="<?php echo Amazon;?>"></a><?php }
					    }
					    else echo COMMING_SOON; ?>
					</td>
				    </tr>
				    <tr>
					<td colspan="3" ><font color="<?php echo $_SESSION['product'][$i]['textColor'];?> "><?php echo $_SESSION['product'][$i]['shippingConditions']; ?></font></td>
				    </tr>
				</table>
				<hr>
				<?php
			    }
			}
			?>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php
	include("01_includes/javascripts.html");
	include("09_libs/functions.php");
	DataLogger();
	?>
    </body>
</html>