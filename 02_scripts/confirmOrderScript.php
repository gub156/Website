<?php

    session_start();
    include("connectMysql.php");

    $addressError = $_SESSION['oldFile'];
    $addressOK = "orderConfirm";

    if($_POST['order_send'])
    {
	$_SESSION['date'] = date("d.m.Y");
    //--- Sauvegarde des informations de l'utilisateur ---//
    	$_SESSION['clientNumber'] = htmlspecialchars($_POST['clientNumber']);
	$_SESSION['yourRef'] = htmlspecialchars($_POST['yourRef']);
	$_SESSION['company'] = htmlspecialchars($_POST['company']);
	$_SESSION['title'] = htmlspecialchars($_POST['title']);
	$_SESSION['name'] = htmlspecialchars($_POST['name']);
	$_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
	$_SESSION['street'] = htmlspecialchars($_POST['street']);
	$_SESSION['postcode'] = htmlspecialchars($_POST['postcode']);
	$_SESSION['city'] = htmlspecialchars($_POST['city']);
	$_SESSION['countryOrder'] = $_POST['countryOrder'];
	$_SESSION['email'] = htmlspecialchars($_POST['email']);
	$_SESSION['emailconfirm'] = htmlspecialchars($_POST['emailconfirm']);

	$_SESSION['company_2'] = htmlspecialchars($_POST['company_2']);
	$_SESSION['title_2'] = htmlspecialchars($_POST['title_2']);
	$_SESSION['name_2'] = htmlspecialchars($_POST['name_2']);
	$_SESSION['firstname_2'] = htmlspecialchars($_POST['firstname_2']);
	$_SESSION['street_2'] = htmlspecialchars($_POST['street_2']);
	$_SESSION['postcode_2'] = htmlspecialchars($_POST['postcode_2']);
	$_SESSION['city_2'] = htmlspecialchars($_POST['city_2']);
	$_SESSION['countryOrder_2'] = $_POST['countryOrder_2'];

	for($i = 0;$i < $_SESSION['numberProduct'][nb]; $i++)
	{
	    $_SESSION['quantity_product'][$i] = $_POST['quantity_product'][$i];
	    $_SESSION['articleRef'][$i] = $_POST['articleRef'][$i];
	}
	for($i = 0;$i < $_SESSION['numberAccessorie'][nb]; $i++) $_SESSION['quantity_accessorie'][$i] = $_POST['quantity_accessorie'][$i];
	$_SESSION['remark'] = htmlspecialchars($_POST['remark']);
	$_SESSION['godfather'] = htmlspecialchars($_POST['godfather']);
    /*** Contole de l'antispam ***/
	$user_answer = trim(htmlspecialchars($_POST['user_answer']));
	$answer = trim(htmlspecialchars($_POST['answer']));
	if(substr(md5($user_answer),5,10) === $answer)
	{
	/*** Test de la confirmation de l'email ***/
	    if($_SESSION['email'] != $_SESSION['emailconfirm'])
	    {
		$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
		$_SESSION['errorEmail'] = true;     // Erreur d'email
		header('Location: ../'.$addressError);
		exit();
	    }
	/*** Test que tous les champs obligatoires soient renseignés ***/
	    if( $_SESSION['name'] == "" ||
		$_SESSION['firstname'] == "" ||
		$_SESSION['street'] == "" ||
		$_SESSION['city'] == "" ||
		$_SESSION['postcode'] == "" ||
		$_SESSION['countryOrder'] == 0 ||
		$_SESSION['email'] == "")
	    {
		$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
		$_SESSION['errorField'] = true;     // Erreur des champs non-remplis
		header('Location: ../'.$addressError);
		exit();
	    }
	/*** Si l'adresse de facturation est différente de l'adresse de livraison, on controle que tous les champs soient remplis ***/
	    if( $_SESSION['name_2'] != "" ||
		$_SESSION['firstname_2'] != "" ||
		$_SESSION['street_2'] != "" ||
		$_SESSION['city_2'] != "" ||
		$_SESSION['countryOrder_2'] != 0 ||
		$_SESSION['postcode_2'] != "")
	    {
		if( $_SESSION['name_2'] == "" ||
		    $_SESSION['firstname_2'] == "" ||
		    $_SESSION['street_2'] == "" ||
		    $_SESSION['city_2'] == "" ||
		    $_SESSION['countryOrder_2'] == 0 ||
		    $_SESSION['postcode_2'] == "")
		{
		    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
		    $_SESSION['errorFieldShippingAddress'] = true;     // Erreur des champs non-remplis
		    header('Location: ../'.$addressError);
		    exit();
		}
	    }
	/*** Controle qu'un article au moins soit sélectionné ***/
	    $nextStep = 0;
	    for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
	    {
		if($_SESSION['quantity_product'][$i] != 0) $nextStep = 1;
	    }
	    if($nextStep == 0)
	    {
		$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
		$_SESSION['selectProduct'] = true;  // Erreur de sélection de produits
		header('Location: ../'.$addressError);
		exit();
	    }
	/*** Si le champ du parrain à été rempli, controler la base de données ***/
	    if($_SESSION['godfather'] != "" && $_SESSION['godfather'] != "GFxxx")
	    {
	    /*** Acquisition des données pour les produits ***/
		$userbdd = $bdd->prepare('SELECT GF_Name, sales_BOOK_DE_2, sales_BOOK_DE_3, sales_BOOK_FR, sales_USB2RF FROM Godfathers WHERE GF_Name = ?') or die(print_r($bdd->errorInfo()));
		$userbdd->execute(array($_SESSION['godfather'])) or die(print_r($userbdd->errorInfo()));
	    /*** On récupère les informations de l'utilisateur dans un tableau appelé godfatherData ***/
		$godfatherData = $userbdd->fetch();
		$userbdd->closeCursor();
	    /*** On controle que le parrain entré par l'utilisateur corresponde bien à un parrain enregistré ***/
		if($_SESSION['godfather'] != $godfatherData['GF_Name'])
		{
		    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
		    $_SESSION['errorGodfather'] = true; // Erreur du nom de parrain
		    header('Location: ../'.$addressError);
		    exit();
		}
	    }
	/*** Obtention d'un numéro de commande ***/
	    $billFile = fopen('../06_documents/Finances/bills_number.txt', 'a+');
	    if($billFile != FALSE)
	    {
            $content = file('../06_documents/Finances/bills_number.txt');
            $lastBill = $content[count($content)-1];
            // Controle de l'année du numéro
            if(strpos($lastBill, strval(date(y))) === 2)
                $_SESSION['newBill'] = $lastBill++;
            else
                $_SESSION['newBill'] = "50" . strval(date(y)) . "1000";
        //    fputs($billFile, "\n" . $_SESSION['newBill']);
            fclose ($billFile);
	    }
	//--- On défini la monnaie en fonction de l'adresse (Pays) ---//
	    switch($_SESSION['countryOrder'])
	    {
		case 1:
		case 2:	$_SESSION['geoZone'] = "Swiss";
			$_SESSION['money'] = $_SESSION['product'][0]['unitCHF'];
			break;
		case 3:
		case 4:
		case 5:	$_SESSION['geoZone'] = "Europa";
			$_SESSION['money'] = $_SESSION['product'][0]['unitEUR'];
	    }
	//--- Calcule de la facture ---//
	    $_SESSION['totalPrice_0_0'] = 0;
	    $_SESSION['totalPrice_2_5'] = 0;
	    $_SESSION['totalPrice_8_0'] = 0;
	    $_SESSION['tva_0_0'] = 0;
	    $_SESSION['tva_2_5'] = 0;
	    $_SESSION['tva_8_0'] = 0;
	    $_SESSION['flagTVA0_0'] = false;
	    $_SESSION['flagTVA2_5'] = false;
	    $_SESSION['flagTVA8_0'] = false;
	    $_SESSION['totalBooks'] = 0;
	    $_SESSION['bookDiscount'] = 0;
	//--- Check de la quantité de livres commandés: si > 10, alors on fait un rabais de x % ---//
	    for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
	    {
	    	if($_SESSION['quantity_product'][$i] > 0 && ($_SESSION['product'][$i]['articleNumber'] == "TB03DE-RE" || $_SESSION['product'][$i]['articleNumber'] == "TB01FR-RE"))
		{
		    $_SESSION['totalBooks'] += $_SESSION['quantity_product'][$i];
		}
	    }
	    if($_SESSION['totalBooks'] >= 10)
	    {
		$_SESSION['bookDiscount'] = 10;
		$_SESSION['isBookDiscountActive'] = 1;
	    }
	    else $_SESSION['isBookDiscountActive'] = 0;
	    $_SESSION['test'] = $_SESSION['totalBooks'];
	    for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
	    {
		if($_SESSION['quantity_product'][$i] > 0)
		{
		    if($_SESSION['geoZone'] == "Swiss")
		    {
		    	if(strpos($_SESSION['product'][$i]['articleNumber'], "TB") === 0) $_SESSION['discountProduct'][$i] = $_SESSION['product'][$i]['discountCHF'] + $_SESSION['bookDiscount'];
			else $_SESSION['discountProduct'][$i] = $_SESSION['product'][$i]['discountCHF'];
			$_SESSION['pricePiece'][$i] = $_SESSION['product'][$i]['priceCHF'] - (($_SESSION['product'][$i]['priceCHF'] / 100) * $_SESSION['discountProduct'][$i]);
			$_SESSION['priceProductBruto'][$i] = $_SESSION['product'][$i]['priceCHF'];
			$_SESSION['qtyProduct'][$i] = $_SESSION['quantity_product'][$i];
			$_SESSION['priceProduct'][$i] = $_SESSION['pricePiece'][$i] * $_SESSION['qtyProduct'][$i];
			$_SESSION['tvaProduct'][$i] = $_SESSION['product'][$i]['TVA_CHF'];
			$_SESSION['amountProductTVA'] = $_SESSION['quantity_product'][$i] * ($_SESSION['pricePiece'][$i] - (($_SESSION['pricePiece'][$i] / (100 + $_SESSION['tvaProduct'][$i])) * 100));
		    }
		    elseif($_SESSION['geoZone'] == "Europa")
		    {
		    	if(strpos($_SESSION['product'][$i]['articleNumber'], "TB") === 0) $_SESSION['discountProduct'][$i] = $_SESSION['product'][$i]['discountEUR'] + $_SESSION['bookDiscount'];
			else $_SESSION['discountProduct'][$i] = $_SESSION['product'][$i]['discountEUR'];
		    	$_SESSION['pricePiece'][$i] = $_SESSION['product'][$i]['priceEUR'] - (($_SESSION['product'][$i]['priceEUR'] / 100) * $_SESSION['discountProduct'][$i]);
			$_SESSION['priceProductBruto'][$i] = $_SESSION['product'][$i]['priceEUR'];
			$_SESSION['qtyProduct'][$i] = $_SESSION['quantity_product'][$i];
			$_SESSION['priceProduct'][$i] = $_SESSION['pricePiece'][$i] * $_SESSION['qtyProduct'][$i];
			$_SESSION['discountProduct'][$i] = $_SESSION['product'][$i]['discountEUR'];
			$_SESSION['tvaProduct'][$i] = $_SESSION['product'][$i]['TVA_EUR'];
			$_SESSION['amountProductTVA'] = $_SESSION['quantity_product'][$i] * ($_SESSION['pricePiece'][$i] - (($_SESSION['pricePiece'][$i] / (100 + $_SESSION['tvaProduct'][$i])) * 100));

		    }
		    if($_SESSION['tvaProduct'][$i] == 0)
		    {
			$_SESSION['totalPrice_0_0'] += $_SESSION['priceProduct'][$i];
			$_SESSION['tva_0_0'] += $_SESSION['amountProductTVA'];
			$_SESSION['flagTVA0_0'] = true;
		    }
		    elseif($_SESSION['tvaProduct'][$i] == 2.5)
		    {
			$_SESSION['totalPrice_2_5'] += $_SESSION['priceProduct'][$i];
			$_SESSION['tva_2_5'] += $_SESSION['amountProductTVA'];
			$_SESSION['flagTVA2_5'] = true;
		    }
		    elseif($_SESSION['tvaProduct'][$i] == 8.0)
		    {
			$_SESSION['totalPrice_8_0'] += $_SESSION['priceProduct'][$i];
			$_SESSION['tva_8_0'] += $_SESSION['amountProductTVA'];
			$_SESSION['flagTVA8_0'] = true;
		    }
		}
	    }
	    for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
	    {
		if($_SESSION['quantity_accessorie'][$i] > 0)
		{
		    if($_SESSION['geoZone'] == "Swiss")
		    {
			$_SESSION['pricePieceAccessorie'][$i] = $_SESSION['accessorie'][$i]['priceCHF'] - (($_SESSION['accessorie'][$i]['priceCHF'] / 100) * $_SESSION['accessorie'][$i]['discountCHF']);
			$_SESSION['priceAccessorieBruto'][$i] = $_SESSION['accessorie'][$i]['priceCHF'];
			$_SESSION['qtyAccessorie'][$i] = $_SESSION['quantity_accessorie'][$i];
			$_SESSION['priceAccessorie'][$i] = $_SESSION['pricePieceAccessorie'][$i] * $_SESSION['qtyAccessorie'][$i];
			$_SESSION['discountAccessorie'][$i] = $_SESSION['accessorie'][$i]['discountCHF'];
			$_SESSION['tvaAccessorie'][$i] = $_SESSION['accessorie'][$i]['TVA_CHF'];
			$_SESSION['amountAccessorieTVA'] = $_SESSION['quantity_accessorie'][$i] * ($_SESSION['pricePieceAccessorie'][$i] - (($_SESSION['pricePieceAccessorie'][$i] / (100 + $_SESSION['tvaAccessorie'][$i])) * 100));
		    }
		    elseif($_SESSION['geoZone'] == "Europa")
		    {
		    	$_SESSION['pricePieceAccessorie'][$i] = $_SESSION['accessorie'][$i]['priceEUR'] - (($_SESSION['accessorie'][$i]['priceEUR'] / 100) * $_SESSION['accessorie'][$i]['discountEUR']);
			$_SESSION['priceAccessorieBruto'][$i] = $_SESSION['accessorie'][$i]['priceEUR'];
			$_SESSION['qtyAccessorie'][$i] = $_SESSION['quantity_accessorie'][$i];
			$_SESSION['priceAccessorie'][$i] = $_SESSION['pricePieceAccessorie'][$i] * $_SESSION['qtyAccessorie'][$i];
			$_SESSION['discountAccessorie'][$i] = $_SESSION['accessorie'][$i]['discountEUR'];
			$_SESSION['tvaAccessorie'][$i] = $_SESSION['accessorie'][$i]['TVA_EUR'];
			$_SESSION['amountAccessorieTVA'] = $_SESSION['quantity_accessorie'][$i] * ($_SESSION['pricePieceAccessorie'][$i] - (($_SESSION['pricePieceAccessorie'][$i] / (100 + $_SESSION['tvaAccessorie'][$i])) * 100));
		    }
		    if($_SESSION['tvaAccessorie'][$i] == 0)
		    {
			$_SESSION['tva_0_0'] += $_SESSION['amountAccessorieTVA'];
			$_SESSION['totalPrice_0_0'] += $_SESSION['priceAccessorie'][$i];
			$_SESSION['flagTVA0_0'] = true;
		    }
		    elseif($_SESSION['tvaAccessorie'][$i] == 2.5)
		    {
			$_SESSION['tva_2_5'] += $_SESSION['amountAccessorieTVA'];
			$_SESSION['totalPrice_2_5'] += $_SESSION['priceAccessorie'][$i];
			$_SESSION['flagTVA2_5'] = true;
		    }
		    elseif($_SESSION['tvaAccessorie'][$i] == 8.0)
		    {
			$_SESSION['tva_8_0'] += $_SESSION['amountAccessorieTVA'];
			$_SESSION['totalPrice_8_0'] += $_SESSION['priceAccessorie'][$i];
			$_SESSION['flagTVA8_0'] = true;
		    }
		}
	    }
	    $_SESSION['shipping'] = 0;

	    $_SESSION['totalBill'] = $_SESSION['totalPrice_0_0'] + $_SESSION['totalPrice_2_5'] + $_SESSION['totalPrice_8_0'];
	    $_SESSION['totalBillShipping'] = $_SESSION['totalBill'] + $_SESSION['shipping'];
	/*** Fin du script - déroulement avec succès - redirection sur OrderConfirm ***/
	    header('Location: ../'.$addressOK);
	    exit();
	}
	else
	{
	    $_SESSION['justTryToOrder'] = true; 	// Tentative d'envoi
	    $_SESSION['errorAntispam'] = true;     	// Resultat du calcul erroné
	    header('Location: ../'.$addressError);
	    exit();
	}
    }