<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."02_scripts/connectMysql.php");
    include($_SESSION['backFile']."01_includes/head.html");

//--- Récupération des informations du produit concerné, ainsi que des accessoires ---//
    $userbdd = $bdd->prepare(' SELECT  pr.productNumber, pr.articleNumber, pr.title, pr.genInfos, pr.picturePath, pr.altPicturePath, pr.specialOfferText, pr.webPage,
					pl.discount, pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				FROM product AS pr
				INNER JOIN priceList as pl
				ON pr.articleNumber = pl.artNumber
				WHERE pr.productLang = ? AND pr.productNumber = ?
				ORDER BY productNumber') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'], 3)) or die(print_r($userbdd->errorInfo()));
//--- On récupère les informations de l'utilisateur dans un tableau appelé product. ---//
    $_SESSION['product'] = $userbdd->fetch();
    $_SESSION['numberProduct'][nb] = 1;
//--- Acquisition des données pour les accessoires ---//
    $userbdd = $bdd->prepare(' SELECT  ac.accessorieNumber, ac.name, ac.genInfos, ac.unit, ac.picturePath, ac.available,
					pl.priceCHF, pl.discountCHF, pl.TVA_CHF, pl.unitCHF, pl.priceEUR, pl.discountEUR, pl.TVA_EUR, pl.unitEUR
				FROM accessories AS ac
				INNER JOIN priceList as pl
				ON ac.artNumber = pl.artNumber
				WHERE productLang = ? AND ac.available = ?
				ORDER BY accessorieNumber') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'], 1)) or die(print_r($userbdd->errorInfo()));
//--- On récupère les informations de l'utilisateur dans un tableau appelé accessorie ---//
    $_SESSION['accessorie'] = $userbdd->fetchAll();

//--- Acquisition du nombre d'accessoires ---//
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM accessories WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
//--- On récupère le nombre de produits dans un tableau ---//
    $_SESSION['numberAccessorie'] = $userbdd->fetch();

//--- Acquisition des prix ---//
    $userbdd = $bdd->prepare('SELECT artNumber, priceCHF, discountCHF, priceEUR, discountEUR FROM priceList') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
//--- On récupère les informations de l'utilisateur dans un tableau appelé priceList ---//
    $_SESSION['priceList'] = $userbdd->fetchAll();
//--- Fin de la reqete Sql ---//
    $userbdd->closeCursor();

    $number_1 = rand(1, 9);
    $number_2 = rand(1, 9);
    $answer = substr(md5($number_1+$number_2),5,10);
?>
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo PREORDER;?></title>
    </head>
    <body>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	<header>
	    <div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
	    <?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
	</header>
	<div id="site_content">
	    <?php include($_SESSION['backFile']."01_includes/banner.html"); ?>
	    <div id="sidebar_container">
		<?php
		    include($_SESSION['backFile']."01_includes/sidebar.php");
		?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo PREORDER;?></h1>
		    <form action="preorderconfirm.php" method="post">
			<div class="form_settings">
			    <table class="centerTable">
				<tr>
				    <td class="largeur80Pixels">
					<a href="<?php echo $_SESSION['product']['picturePath']; ?>"><img src="<?php echo $_SESSION['product']['picturePath']; ?>" alt="<?php echo $_SESSION['product']['Title']; ?>"width="80%"></a>
				    </td>
				    <td class="largeur120Pixels">
				    <?php
					    echo '<b>'.$_SESSION['product']['title'].'</b><br/>';
					    echo $_SESSION['product']['genInfos'].'<br/><br/>';
					    if($_SESSION['product']['discount'] != 0)
					    {
						echo '<del>'.$_SESSION['product']['unitCHF'].number_format($_SESSION['product']['priceCHF'], 2, '.', '\'').'</del><br/>';
						echo FROM .' '.$_SESSION['product']['unitCHF'].number_format($_SESSION['product']['priceCHF']-($_SESSION['product']['priceCHF']/100*$_SESSION['product']['discountCHF']), 2, '.', '\'').'<br/>';
					    }
					    else echo FROM .' '.$_SESSION['product']['unitCHF'].number_format($_SESSION['product']['priceCHF'], 2, '.', '\'').' / '.
								$_SESSION['product']['unitEUR'].number_format($_SESSION['product']['priceEUR'], 2, '.', '\'').'<br/>';
					    echo '<br/>';
					    if($_SESSION['product']['specialOfferText'] != "") echo '<br/><span class="specialOffer">'.$_SESSION['product']['specialOfferText'].'</span><br/>';
					    ?>
				    </td>
				    <td>
					<input class="bluePolice" type="number" name="quantity_product" placeholder="<?php echo QUANTITY; ?>" value="<?php echo $_SESSION['quantity_product']; ?>"><br/>
					<input type="hidden" name="articleRef" value="<?php echo $_SESSION['product']['articleNumber']?>"><br/>
					<a href="<?php echo $_SESSION['product']['webPage'] ?>" class="button"><?php echo INFOS;?></a>
				    </td>
				</tr>
			    </table>
			    <?php echo PRICE_FOR_MORE_PRODUCT; ?>
			    <table class="centerTable">
				<tr>
				    <td>
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
				</tr>
			    </table>
			    <p class="center">
				<label for="remark"><?php echo REMARKS; ?></label><br/>
				<textarea class="bluePolice" id="remark" rows="5" name="remark"><?php echo $_SESSION['remark'];?></textarea>
			    </p>
			    <p class="center"><label for="user_answer"><?php echo ANTISPAM; ?></label></p>
			    <p class="center">
				<label for="user_answer"><?php echo $number_1; ?> + <?php echo $number_2; ?> = ?</label><br/>
				<input type="text" id="user_answer" class="bluePolice" name="user_answer" /><input type="hidden" name="answer" value="<?php echo $answer; ?>" >
				<input type="hidden" name="checkOrder" value="18" >
			    </p>
			    <p class="center"><input class="button" type="submit" name="order_send" value="<?php echo ORDER;?>" ></p>
			</div>
		    </form>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>