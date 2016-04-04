<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");

    $addressError = "preorder";
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
		    <h1><?php echo PREORDER_CONFIRM;?></h1>
	    <?php
	    if($_POST['order_send'])
	    {
	    	$_SESSION['quantity_product'] = $_POST['quantity_product'];
	    //--- En cas d'offre spéciale par la quantité, changer le prix ici ---//
		if($_SESSION['quantity_product'] >= 5)
		{
		    $_SESSION['product']['discountCHF'] = 20;
		}
	    //--- Début du calcul du prix ici ---//
		if($_SESSION['product']['discount']) $totalPiece =  $_SESSION['product']['priceCHF']-($_SESSION['product']['priceCHF']/100*$_SESSION['product']['discountCHF']);
		else $totalPiece = $_SESSION['product']['priceCHF'];
		$_SESSION['total'] = ($_SESSION['product']['priceCHF']-($_SESSION['product']['priceCHF']/100*$_SESSION['product']['discountCHF'])) * $_SESSION['quantity_product'];
		$_SESSION['tva'] = (2.5 * $_SESSION['total']) / 102.5;

		$user_answer = trim(htmlspecialchars($_POST['user_answer']));
		$answer = trim(htmlspecialchars($_POST['answer']));
		if(substr(md5($user_answer),5,10) === $answer)
		{
		    $_SESSION['email'] = htmlspecialchars($_POST['email']);
		    $_SESSION['emailconfirm'] = htmlspecialchars($_POST['emailconfirm']);
		    if($_SESSION['email'] == $_SESSION['emailconfirm'])
		    {
			$_SESSION['date'] = date("Y-m-d");
		    /*** Sauvegarde des informations de l'utilisateur ***/
			$_SESSION['company'] = htmlspecialchars($_POST['company']);
			$_SESSION['title'] = htmlspecialchars($_POST['title']);
			$_SESSION['name'] = htmlspecialchars($_POST['name']);
			$_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
			$_SESSION['street'] = htmlspecialchars($_POST['street']);
			$_SESSION['zip'] = htmlspecialchars($_POST['postcode']);
			$_SESSION['city'] = htmlspecialchars($_POST['city']);
			$_SESSION['countryOrder'] = $_POST['countryOrder'];
			$_SESSION['checkOrder'] = $_POST['checkOrder'];
		    //--- Confirmation que tous les champs soient renseignés ---//
			if( $_SESSION['name'] == "" ||
			    $_SESSION['firstname'] == "" ||
			    $_SESSION['street'] == "" ||
			    $_SESSION['zip'] == "" ||
			    $_SESSION['city'] == "" ||
			    $_SESSION['countryOrder'] == 0 ||
			    $_SESSION['quantity_product'] == 0)
			{
			    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			    $_SESSION['errorField'] = true;     // Erreur des champs non-remplis
			    header('Location: '.$addressError);
			    exit();
			}
			switch($_SESSION['countryOrder'])
			{
			    case 1:	$_SESSION['countryOrder'] = SWISS;
					break;
			    case 2:	$_SESSION['countryOrder'] = LICHTENSTEIN;
					break;
			    case 3:	$_SESSION['countryOrder'] = GERMANY;
					break;
			    case 4:	$_SESSION['countryOrder'] = AUSTRIA;
					break;
			    case 5:	$_SESSION['countryOrder'] = FRANCE;
					break;
			}
			$_SESSION['remark'] = htmlspecialchars($_POST['remark']);
			$_SESSION['articleRef'] = htmlspecialchars($_POST['articleRef']);
		    }
		    else
		    {
		    	$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			$_SESSION['errorEmail'] = true;     // Erreur d'email
			header('Location: ../'.$addressError);
			exit();
		    }
		}
		else
		{
		    $_SESSION['justTryToOrder'] = true; 	// Tentative d'envoi
		    $_SESSION['errorAntispam'] = true;     	// Resultat du calcul erroné
		    header('Location: ../'.$addressError);
		    exit();
		}
	    }
	    if($_SESSION['checkOrder'] == 18)
	    { ?>
		<?php echo PREORDER_CONFIRM_TEXT.'<br/><br/>'; ?>
		<?php echo '<h4>'.PAY_ADDRESS.'</h4>'; ?>
		<?php echo $_SESSION['company']; ?><br/>
		<?php echo $_SESSION['title']; ?><br/>
		<?php echo $_SESSION["name"] . ' ' . $_SESSION["firstname"]; ?><br/>
		<?php echo $_SESSION['street']; ?><br/>
		<?php echo $_SESSION['postcode'].' '.$_SESSION['city']; ?><br/>
		<?php echo $_SESSION['countryOrder']; ?><br/>
		<?php echo $_SESSION['email']; ?><br/><br/>
		<table border="1">
		    <tr>
			<th><?php echo QUANTITY; ?></th>
			<th><?php echo DESCRIPTION; ?></th>
			<th><?php echo PRICE; ?></th>
			<th><?php echo TVA; ?></th>
			<th><?php echo TOTAL; ?></th>
		    </tr>
		    <tr>
			<td><?php echo $_SESSION['quantity_product']; ?></td>
			<td><?php echo $_SESSION['product']['title'].' - '.$_SESSION['product']['genInfos'];?></td>
			<td><?php echo $_SESSION['product']['unitCHF'].number_format($totalPiece, 2, '.', '\'');?></td>
			<td><?php echo $_SESSION['product']['TVA_CHF'].'%';?></td>
			<td><?php echo $_SESSION['product']['unitCHF'].number_format($_SESSION['total'], 2, '.', '\'');?></td>
		    </tr>
		</table>
		<?php echo INCL_TVA_OF.' CHF '.number_format($_SESSION['tva'], 2, '.', '\'');?>
		<br/><br/>
		<?php if($_SESSION['remark'] != "") echo YOUR_REMARK.': '.$_SESSION['remark']; ?>
		<span class="center"><a href="<?php echo $addressError; ?>" class="button" ><?php echo BACK_ORDER;?></a></span>
		<form action="02_scripts/preorderScript.php" method="post">
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
			<p class="center"><input class="button" type="submit" name="confirm_send" value="<?php echo CONFIRM_ORDER;?>" /></p>
		    </div>
		</form>
		<?php
	    }
	    else
	    {
	    	switch($_COOKIE['language'])
		{
		    case "fr":	echo "Cette page n'est plus disponible";
				break;
		    case "de":	echo "Diese Seite ist nicht mehr verfügbar.";
				break;
		}
	    } ?>
	    </div>
	</div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>