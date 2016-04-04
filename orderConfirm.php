<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/connectMysql.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");

    $_SESSION['addressOK'] = "";
    $_SESSION['addressError'] = "order";

//Acquisition des données pour les produits - Si l'utiliateur est en mode beta, on récupère tous les produits.
    if($_SESSION['beta'])
    {
	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.unit, pr.picturePath, pr.available,
					    pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				    FROM product AS pr
				    INNER JOIN priceList as pl
				    ON pr.articleNumber = pl.artNumber
				    WHERE pr.productLang = ?
				    ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé product.
	$_SESSION['product'] = $userbdd->fetchAll();
    //Acquisition des données pour les accessoires
	$userbdd = $bdd->prepare(' SELECT  ac.accessorieNumber, ac.name, ac.genInfos, ac.unit, ac.picturePath, ac.available,
					    pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				    FROM accessories AS ac
				    INNER JOIN priceList as pl
				    ON ac.artNumber = pl.artNumber
				    WHERE productLang = ?
				    ORDER BY accessorieNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé accessorie.
	$_SESSION['accessorie'] = $userbdd->fetchAll();
    }
    else // Sinon, on ne récupère que les produits valables.
    {
    	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.unit, pr.picturePath, pr.available,
					    pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				    FROM product AS pr
				    INNER JOIN priceList as pl
				    ON pr.articleNumber = pl.artNumber
				    WHERE pr.productLang = ? AND pr.available = ?
				    ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'], 1)) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé product.
	$_SESSION['product'] = $userbdd->fetchAll();
    //Acquisition des données pour les accessoires
	$userbdd = $bdd->prepare(' SELECT  ac.accessorieNumber, ac.name, ac.genInfos, ac.unit, ac.picturePath, ac.available,
					    pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				    FROM accessories AS ac
				    INNER JOIN priceList as pl
				    ON ac.artNumber = pl.artNumber
				    WHERE productLang = ? AND ac.available = ?
				    ORDER BY accessorieNumber') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_COOKIE['language'], 1)) or die(print_r($userbdd->errorInfo()));
    // On récupère les informations de l'utilisateur dans un tableau appelé accessorie.
	$_SESSION['accessorie'] = $userbdd->fetchAll();
    }

/*** Acquisition du nombre de produits ***/
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM product WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
/*** On récupère le nombre de produits dans un tableau ***/
    $_SESSION['numberProduct'] = $userbdd->fetch();

/*** Acquisition du nombre d'accessoires ***/
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM accessories WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
/*** On récupère le nombre de produits dans un tableau ***/
    $_SESSION['numberAccessorie'] = $userbdd->fetch();

    $userbdd->closeCursor();

//--- Correspondance du nombre stocké dans $_SESSION['country'] en texte ---//
	switch($_SESSION['countryOrder'])
	{
	    case 1:	$_SESSION['country'] = SWISS;
			break;
	    case 2:	$_SESSION['country'] = LICHTENSTEIN;
			break;
	    case 3:	$_SESSION['country'] = GERMANY;
			break;
	    case 4:	$_SESSION['country'] = AUSTRIA;
			break;
	    case 5:	$_SESSION['country'] = FRANCE;
			break;
	}
	switch($_SESSION['countryOrder_2'])
	{
	    case 1:	$_SESSION['country_2'] = SWISS;
			break;
	    case 2:	$_SESSION['country_2'] = LICHTENSTEIN;
			break;
	    case 3:	$_SESSION['country_2'] = GERMANY;
			break;
	    case 4:	$_SESSION['country_2'] = AUSTRIA;
			break;
	    case 5:	$_SESSION['country'] = FRANCE;
			break;
	}

?>
	<meta name="robots" content="index, follow">
	<title><?php echo CONFIRM_ORDER;?></title>
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
			<div id="sidebar_container">
				<?php include("01_includes/sidebar.php"); ?>
			</div>
			<div id="content">
				<div id="textDisplayer">
					<?php include("01_includes/Display/texts.php");?>
				</div>
				<h1><?php echo TITLE_ORDER_CONFIRM.$_SESSION['newBill']; ?></h1>
				<?php if(isset($_SESSION['quantity_product']))
				{ ?>
				<style type="text/css">
					body td {
						font-size: 11px;
						font-family: arial,helvetica,sans-serif;
					}
					table {
						text-align: left;
						border-collapse: collapse;
					}
					p	{
						line-height: 130%;
						padding: 0;
						margin: 0;
					}

					checkout_page {
						color: #000000;
					}
				</style>
				<div style="text-align: left;">
					<table style="min-height: 150px;position: relative;">
						<tbody>
							<tr>
								<td style="padding: 0"></td>
								<td style="vertical-align: top">
									<p><?php echo PAY_ADDRESS; ?></p>
									<p><?php echo $_SESSION['company']; ?></p>
									<p><?php echo $_SESSION['title']; ?></p>
									<p><?php echo $_SESSION["name"] . ' ' . $_SESSION["firstname"]; ?></p>
									<p></p>
									<p><?php echo $_SESSION['street']; ?></p>
									<p></p>
									<p><?php echo $_SESSION['postcode'].' '.$_SESSION['city']; ?></p>
									<p><?php echo $_SESSION['country']; ?></p>
									<p></p>
									<p></p>
									<p>E-Mail: <?php echo $_SESSION['email']; ?></p>
								</td>
								<td style="vertical-align: top">
									<?php
									if($_SESSION['name_2'] != "" || $_SESSION['firstname_2'] != "" || $_SESSION['street_2'] != "" || $_SESSION['city_2'] != "" || $_SESSION['postcode_2'] != "")
									{ ?>
										<p><?php echo SHIPPING_ADDRESS; ?></p>
										<p><?php echo $_SESSION['company_2']; ?></p>
										<p><?php echo $_SESSION['title_2']; ?></p>
										<p><?php echo $_SESSION["name_2"] . ' ' . $_SESSION["firstname_2"]; ?></p>
										<p></p>
										<p><?php echo $_SESSION['street_2']; ?></p>
										<p></p>
										<p><?php echo $_SESSION['postcode_2'].' '.$_SESSION['city_2']; ?></p>
										<p><?php echo $_SESSION['country_2']; ?></p> <?php
									}
									else
									{ ?>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<p></p>
										<?php
									} ?>
								</td>
							</tr>
						</tbody>
					</table>
					<table width="100%" style="margin: 30px 0">
						<tbody>
							<tr>
								<td width="50%" style="vertical-align: top">
									<p><?php echo YOUR_REF.': '.$_SESSION['yourRef']; ?></p>
									<p><?php echo ORDER_DATE.' '.$_SESSION['date']; ?></p>
									<p><?php echo ORDER_NUMBER.' '.$_SESSION['newBill']; ?></p>
									<p><?php echo CLIENT_NUMBER.' '.$_SESSION['clientNumber']; ?></p>
									<p></p>
									<p></p>
									<p></p>
									<p><?php echo TVA_NUMBER; ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<table width="100%">
						<thead>
							<tr>
								<td style="margin-right: 10px"><?php echo QUANTITY; ?></td>
								<td><?php echo DESCRIPTION; ?></td>
								<td style="text-align: right;margin-right: 10px"><?php echo PRICE; ?></td>
								<td style="border-bottom: 1px solid gray;text-align: center;padding:0 10px 0 10px;"><?php echo TVA; ?></td>
								<td style="border-bottom: 1px solid gray;text-align: right;"><?php echo TOTAL; ?></td>
							</tr>
						</thead>
						<tbody style="vertical-align: top;"><?php
							for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
							{
								if($_SESSION['quantity_product'][$i] > 0)
								{ ?>
									<tr>
										<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
											<p><?php echo $_SESSION['qtyProduct'][$i].' '.$_SESSION['product'][$i]['unit']; ?></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;">
											<b><p><?php echo $_SESSION['product'][$i]['title']; ?></p></b>
											<p><?php echo $_SESSION['product'][$i]['genInfos']; ?></p>
											<p></p>
											<p></p>
											<p></p>
											<p></p>
											<p></p>
											<p>
											<p></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
											<p><?php echo number_format($_SESSION['pricePiece'][$i], 2, '.', '\''); ?></p><?php
											if($_SESSION['product'][$i]['discountCHF'] > 0 || $_SESSION['product'][$i]['discountEUR'] > 0 || ($_SESSION['isBookDiscountActive'] && strpos($_SESSION['product'][$i]['articleNumber'], "TB") === 0))
											{ ?>
												<p><strike><?php echo number_format($_SESSION['priceProductBruto'][$i], 2, '.', '\''); ?></strike></p>
												<?php
											}
											else
											{?>
												<p></p>
												<?php
											} ?>
											<p></p>
											<p></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p><?php echo $_SESSION['tvaProduct'][$i]; ?>%</p></td>
										<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p><?php echo $_SESSION['money'].' '.number_format($_SESSION['priceProduct'][$i], 2, '.', '\''); ?></p>
											<p></p>
										</td>
									</tr><?php
								}
							}
							for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
							{
								if($_SESSION['quantity_accessorie'][$i] > 0)
								{ ?>
									<tr>
										<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
											<p><?php echo $_SESSION['qtyAccessorie'][$i].' '.$_SESSION['accessorie'][$i]['unit']; ?></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;">
											<b><p><?php echo $_SESSION['accessorie'][$i]['name']; ?></p></b>
											<p><?php echo $_SESSION['accessorie'][$i]['genInfos']; ?></p>
											<p></p>
											<p></p>
											<p></p>
											<p></p>
											<p></p>
											<p>
											<p></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
											<p><?php echo number_format($_SESSION['pricePieceAccessorie'][$i], 2, '.', '\''); ?></p><?php
											if($_SESSION['accessorie'][$i]['discountCHF'] > 0 || $_SESSION['accessorie'][$i]['discountEUR'] > 0)
											{ ?>
												<p><strike><?php echo number_format($_SESSION['priceAccessorieBruto'][$i], 2, '.', '\''); ?></strike></p>
												<?php
											}
											else
											{?>
												<p></p>
												<?php
											} ?>
											<p></p>
											<p></p>
										</td>
										<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p><?php echo $_SESSION['tvaAccessorie'][$i]; ?>%</p></td>
										<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p><?php echo $_SESSION['money'].' '.number_format($_SESSION['priceAccessorie'][$i], 2, '.', '\'');?></p>
											<p></p>
										</td>
									</tr><?php
								}
							} ?>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align: right;margin-right: 10px"></td>
								<td style="vertical-align: top;text-align: center;"></td>
								<td style="text-align: right;"></td>
							</tr>
							<tr>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td colspan="4" style="border-top: 1px solid gray;font-weight: bold"><?php echo PARTIAL_TOTAL; ?></td>
								<td style="border-top: 1px solid gray;text-align: right;font-weight:bold"><?php echo $_SESSION['money'].' '.number_format($_SESSION['totalBill'], 2, '.', '\''); ?></td>
							</tr>
							<tr>
								<td colspan="4"><p></p></td>
								<td style="text-align: right"><p></p></td>
							</tr>
							<tr>
								<td colspan="4"><p></p></td>
								<td style="text-align: right"><p></p></td>
							</tr>
							<tr>
								<td colspan="4"><p></p></td>
								<td style="text-align: right"><p></p></td>
							</tr>
							<?php if($_SESSION['flagTVA2_5'])
							{ ?>
							<tr>
								<td colspan="4"><p></p></td>
								<td style="text-align: right"><p></p></td>
							</tr>
							<tr>
								<td colspan="4"><p><?php echo INCL_TVA_OF.' '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_2_5'], 2, '.', '\'').' (2.5%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_2_5'], 2, '.', '\''); ?></p></td>
								<td style="text-align: right"><p>&nbsp;</p></td>
							</tr>
							<?php
							}
							if($_SESSION['flagTVA8_0'])
							{ ?>
							<tr>
								<td colspan="4"><p><?php echo INCL_TVA_OF.' '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_8_0'], 2, '.', '\'').' (8.0%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_8_0'], 2, '.', '\''); ?></p></td>
								<td style="text-align: right"><p>&nbsp;</p></td>
							</tr>
							<tr>
								<td colspan="4"><p></p></td>
								<td style="text-align: right"><p></p></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="5"></td>
							</tr>
							<tr>
								<td colspan="4" style="border-top: 2px solid gray;font-weight: bold;font-size: 14px;"><?php echo TOTAL_WITH_TVA; ?></td>
								<td style="border-top: 2px solid gray;border-bottom: 2px solid gray;text-align: right;font-weight: bold;font-size: 14px;"><?php echo $_SESSION['money'].' '.number_format($_SESSION['totalBillShipping'], 2, '.', '\''); ?></td>
							</tr>

							<tr>
								<td colspan="4"></td>
								<td style="text-align: right"></td>
							</tr>
							<tr>
								<td colspan="4"></td>
								<td style="text-align: right"></td>
							</tr>
						</tbody>
					</table>
					<table width="100%" style="border-top: 1px solid gray">
						<tbody>
							<tr>
								<td>
									<p style="margin:20px 0 0 0"><b><?php if($_SESSION['remark'] != "") echo YOUR_REMARK; ?></b></p>
									<p style="margin: 0 0 20px 0"><?php echo $_SESSION['remark']; ?></p>
								</td>
							</tr>
							<tr>
								<td>
									<p style="margin-top: 0px"></p>
									<p style="margin: 0 0 20px 0"></p>
									<p style="margin-top: 0px"> </p>
									<p style="margin-top: 0px"></p>
									<p style="margin-top: 0px"></p>
									<p style="margin-top: 0px"></p>
									<p style="margin: 0 0 20px 0"></p>
								</td>
							</tr>
							<tr>
								<td>
									<p></p>
									<p></p>
									<p></p>
									<p></p>
									<p></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<span class="center"><a href="<?php echo $_SESSION['addressError']; ?>" class="button" ><?php echo BACK_ORDER;?></a></span>
				<form action="02_scripts/orderScript.php" method="post">
				    <div class="form_settings">
					<span class="center">
					    <p><?php echo NEWSLETTER_SUBSCR; ?></p><br/>
					    <input type="checkbox" name="newsletter" >
					</span>
					<span class="center">
					    <p><?php echo CGVBOX; ?></p><br/>
					    <input type="checkbox" name="cgv" >
					</span>
					<br /><br />
					<p class="center"><input class="button" type="submit" name="order_send" value="<?php echo CONFIRM_ORDER;?>" /></p>
				    </div>
				</form><?php
			    }
			    else
			    {
			    	echo 'Page plus disponible';
			    } ?>
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