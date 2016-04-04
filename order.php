<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $displayOrderForm = true; // true = affiche le formulaire de commande / false = pas de formulaire visible
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/connectMysql.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");

    $number_1 = rand(1, 9);
    $number_2 = rand(1, 9);
    $answer = substr(md5($number_1+$number_2),5,10);

//Acquisition des données pour les produits - Si l'utiliateur est en mode beta, on récupère tous les produits.
    if($_SESSION['beta'])
    {
	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.unit, pr.picturePath, pr.available, pr.qtyDiscount,
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
    	$userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.genInfos, pr.language, pr.unit, pr.picturePath, pr.available, pr.preorder, pr.qtyDiscount,
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

//Acquisition des prix
    $userbdd = $bdd->prepare('SELECT artNumber, priceCHF, discountCHF, priceEUR, discountEUR FROM priceList') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
// On récupère les informations de l'utilisateur dans un tableau appelé dataProduct.
    $_SESSION['priceList'] = $userbdd->fetchAll();

    $userbdd->closeCursor();

?>
	<meta name="robots" content="index, follow">
	<title><?php echo ORDER;?></title>
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
		    <?php
		if($displayOrderForm)
		{ ?>
		    <h1><?php echo ORDER;?></h1>
		    <?php // ICI PEUT ETRE AFFICHE UN MESSAGE A L'UTILISATEUR ?>
		    <p><?php echo FILL_THE_FORM;?></p>
		    <form action="02_scripts/confirmOrderScript.php" method="post">
			<div class="form_settings">
			    <table class="centerTable">
				<tr>
				    <th><?php echo BILLING_ADDRESS; ?></th>
				    <th><?php echo ADDR_DIFF; ?></th>
				</tr>
				<tr>
				    <td>
					<p>
					    <label for="clientNumber"><?php echo CLIENT_NUMBER; ?></label><br/>
					    <input class="bluePolice" id="clientNumber" type="text" name="clientNumber" placeholder="Cxxxx" value="<?php echo $_SESSION['clientNumber'];?>" autofocus>
					</p>
					<p>
					    <label for="yourRef"><?php echo YOUR_REF; ?></label><br/>
					    <input class="bluePolice" id="yourRef" type="text" name="yourRef" value="<?php echo $_SESSION['yourRef'];?>">
					</p>
					<p>
					    <label for="company"><?php echo COMPANY; ?></label><br/>
					    <input class="bluePolice" id="company" type="text" name="company" value="<?php echo $_SESSION['company'];?>">
					<p>
					    <label for="title"><?php echo TITLE; ?></label><br/>
					    <input class="bluePolice" id="title" type="text" name="title" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['title'];else echo $_SESSION['title'];?>">
					</p>
					<p>
					    <label for="name"><?php echo "* ".NAME; ?></label><br/>
					    <input class="bluePolice" id="name" type="text" name="name" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['name'];else echo $_SESSION['name'];?>">
					</p>
					<p>
					    <label for="firstname"><?php echo "* ".FIRSTNAME; ?></label><br/>
					    <input class="bluePolice" id="firstname" type="text" name="firstname" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['firstname'];else echo $_SESSION['firstname'];?>" >
					</p>
					<p>
					    <label for="street"><?php echo "* ".STREET; ?></label><br/>
					    <input class="bluePolice" id="street" type="text" name="street" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['street'];else echo $_SESSION['street'];?>" >
					</p>
					<p>
					    <label for="postcode"><?php echo "* ".POSTCODE; ?></label><br/>
					    <input class="bluePolice" id="postcode" type="text" name="postcode" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['postcode'];else echo $_SESSION['postcode'];?>" >
					</p>
					<p>
					    <label for="city"><?php echo "* ".CITY; ?></label><br/>
					    <input class="bluePolice" id="city" type="text" name="city" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['city'];else echo $_SESSION['city'];?>" >
					</p>
					<p>
					    <label for="countryOrder"><?php echo "* ".COUNTRY; ?></label><br/>
					    <select name="countryOrder" id="countryOrder">
						<option value=0 selected></option>
						<option value=1 <?php if($_SESSION['countryOrder'] == 1) echo 'selected'; ?>><?php echo SWISS; ?></option>
						<option value=2 <?php if($_SESSION['countryOrder'] == 2) echo 'selected'; ?>><?php echo LICHTENSTEIN; ?></option>
						<option value=3 <?php if($_SESSION['countryOrder'] == 3) echo 'selected'; ?>><?php echo GERMANY; ?></option>
						<option value=4 <?php if($_SESSION['countryOrder'] == 4) echo 'selected'; ?>><?php echo AUSTRIA; ?></option>
						<option value=5 <?php if($_SESSION['countryOrder'] == 5) echo 'selected'; ?>><?php echo FRANCE; ?></option>
					    </select>
					</p>
					<p>
					    <label for="email"><?php echo "* ".EMAIL; ?></label><br/>
					    <input class="bluePolice" id="email" type="email" name="email" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['email'];else echo $_SESSION['email'];?>" >
					</p>
					<p>
					    <label for="emailconfirm"><?php echo "* ".EMAILCONFIRM; ?></label><br/>
					    <input class="bluePolice" id="emailconfirm" type="email" name="emailconfirm" value="<?php if($_SESSION['accesGranted'])echo $_SESSION['email'];else echo $_SESSION['emailconfirm'];?>" >
					</p>
				    </td>
				    <td>
					<p>
					    <label for="company_2"><?php echo COMPANY; ?></label><br/>
					    <input class="bluePolice" id="company_2" type="text" name="company_2" value="<?php echo $_SESSION['company_2'];?>">
					</p>
					<p>
					    <label for="title_2"><?php echo TITLE; ?></label><br/>
					    <input class="bluePolice" id="title_2" type="text" name="title_2" value="<?php echo $_SESSION['title_2'];?>">
					</p>
					<p>
					    <label for="name_2"><?php echo "* ".NAME; ?></label><br/>
					    <input type="text" id="name_2" class="bluePolice" name="name_2" value="<?php echo $_SESSION['name_2'];?>">
					</p>
					<p>
					    <label for="firstname_2"><?php echo "* ".FIRSTNAME; ?></label><br/>
					    <input class="bluePolice" id="firstname_2" type="text" name="firstname_2" value="<?php echo $_SESSION['firstname_2'];?>">
					</p>
					<p>
					    <label for="street_2"><?php echo "* ".STREET; ?></label><br/>
					    <input class="bluePolice" id="street_2" type="text" name="street_2" value="<?php echo $_SESSION['street_2'];?>">
					</p>
					<p>
					    <label for="postcode_2"><?php echo "* ".POSTCODE; ?></label><br/>
					    <input class="bluePolice" id="postcode_2" type="text" name="postcode_2" value="<?php echo $_SESSION['postcode_2'];?>">
					</p>
					<p>
					    <label for="city_2"><?php echo "* ".CITY; ?></label><br/>
					    <input class="bluePolice" id="city_2" type="text" name="city_2" value="<?php echo $_SESSION['city_2'];?>">
					</p>
					<p>
					    <label for="countryOrder_2"><?php echo "* ".COUNTRY; ?></label><br/>
					    <select name="countryOrder_2" id="countryOrder_2">
						<option value=0 selected></option>
						<option value=1 <?php if($_SESSION['countryOrder_2'] == 1) echo 'selected'; ?>><?php echo SWISS; ?></option>
						<option value=2 <?php if($_SESSION['countryOrder_2'] == 2) echo 'selected'; ?>><?php echo LICHTENSTEIN; ?></option>
						<option value=3 <?php if($_SESSION['countryOrder_2'] == 3) echo 'selected'; ?>><?php echo GERMANY; ?></option>
						<option value=4 <?php if($_SESSION['countryOrder_2'] == 4) echo 'selected'; ?>><?php echo AUSTRIA; ?></option>
						<option value=5 <?php if($_SESSION['countryOrder_2'] == 5) echo 'selected'; ?>><?php echo FRANCE; ?></option>
					    </select>
					</p>
				    </td>
				</tr>
			    </table>
			    <?php echo '<b>'.QUANTITY . "*</b>"; ?><br/>
			    <table class="centerTable">
			<?php
			for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
			{
			    if(($_SESSION['product'][$i]['available'] && !$_SESSION['product'][$i]['preorder']) || $_SESSION['beta'])
			    { ?>
				<tr>
				    <td class="largeur80Pixels">
					<a href="<?php echo $_SESSION['product'][$i]['picturePath']; ?>"><img src="<?php echo $_SESSION['product'][$i]['picturePath']; ?>" alt="<?php echo $_SESSION['product'][$i]['Title']; ?>"width="80%"></a>
				    </td>
				    <td class="largeur120Pixels">
				    <?php
					echo  '<b>'.$_SESSION['product'][$i]['title'].'</b>'.'<br/>'.
						$_SESSION['product'][$i]['genInfos'].'<br/>'.'<br/>'.
						$_SESSION['product'][$i]['unitCHF'].$_SESSION['product'][$i]['priceCHF'].' / '.
						$_SESSION['product'][$i]['unitEUR'].$_SESSION['product'][$i]['priceEUR'];
				    ?>
				    </td>
				    <td>
					<input class="bluePolice" type="number" name="quantity_product[]" placeholder="<?php echo QUANTITY; ?>" value="<?php if($_SESSION['quantity_product'][$i] > 0) echo $_SESSION['quantity_product'][$i]; ?>" min="0"><br/>
					<input type="hidden" name="articleRef[]" value="<?php echo $_SESSION['product'][$i]['articleNumber']?>">
				    </td>
				</tr>
				<tr><td colspan="3" class="bluePolice"><?php echo $_SESSION['product'][$i]['qtyDiscount'].'<br/><br/>';?></td>
				</tr>
				<?php
			    }
			} ?>
				</table>
				<?php echo '<b>'.ACCESSORIES.'</b>'; ?>
				<table class="centerTable">
			    <?php
			    for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
			    {
				if($_SESSION['accessorie'][$i]['available'] || $_SESSION['beta'])
				{ ?>
				    <tr>
					<td class="largeur80Pixels">
					    <a href="<?php echo $_SESSION['accessorie'][$i]['picturePath']; ?>"><img src="<?php echo $_SESSION['accessorie'][$i]['picturePath']; ?>" alt="<?php echo $_SESSION['accessorie'][$i]['name']; ?>"width="80%"></a>
					</td>
					<td class="largeur120Pixels">
					<?php
					    echo   '<b>'.$_SESSION['accessorie'][$i]['name'].'</b>'.'<br/>'.
						    $_SESSION['accessorie'][$i]['genInfos'].'<br/>'.'<br/>'.
						$_SESSION['accessorie'][$i]['unitCHF'].$_SESSION['accessorie'][$i]['priceCHF'].' / '.
						$_SESSION['accessorie'][$i]['unitEUR'].$_SESSION['accessorie'][$i]['priceEUR']; ?>
					</td>
					<td>
					    <input class="bluePolice" type="number" name="quantity_accessorie[]" placeholder="<?php echo QUANTITY; ?>" value="<?php if($_SESSION['quantity_accessorie'][$i] > 0) echo $_SESSION['quantity_accessorie'][$i]; ?>" min="0"><br/>
					</td>
				    </tr>
				    <?php
				}
			    } ?>
				</table>
				<p class="center">
				    <label for="remark"><?php echo REMARKS; ?></label><br/>
				    <textarea class="bluePolice" id="remark" rows="5" name="remark"><?php echo $_SESSION['remark'];?></textarea>
				</p>
			    <?php if($_SESSION['beta'])
			    { ?>
				<p class="center">
				    <label for="godfather"><?php echo GODFATHER_NAME; ?></label><br/>
				    <input class="bluePolice" id="godfather" type="text" name="godfather" placeholder="GFxxx" value="<?php echo $_SESSION['godfather'];?>">
				</p>
				<?php
			    } ?>
				<p class="center"><label for="user_answer"><?php echo ANTISPAM; ?></label></p>
				<p class="center">
				    <label for="user_answer"><?php echo $number_1; ?> + <?php echo $number_2; ?> = ?</label><br/>
				    <input type="text" id="user_answer" class="bluePolice" name="user_answer" /><input type="hidden" name="answer" value="<?php echo $answer; ?>" >
				</p>
				<p class="center"><input class="button" type="submit" name="order_send" value="<?php echo ORDER;?>" ></p>
			    </div>
			</form>
		    <?php
		    }
		    else
		    {
			echo ORDER_SOON_AVAILABLE;
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