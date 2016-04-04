<?php

    session_start();
    include("connectMysql.php");

    $addressOK = "thank_you";
    $addressError = "orderConfirm";

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
    //--- Acquisition du nombre de produits ---//
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM product WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
//--- On récupère le nombre de produits dans un tableau ---//
    $_SESSION['numberProduct'] = $userbdd->fetch();

//--- Acquisition du nombre d'accessoires ---//
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM accessories WHERE productLang = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_COOKIE['language'])) or die(print_r($userbdd->errorInfo()));
//--- On récupère le nombre de produits dans un tableau ---//
    $_SESSION['numberAccessorie'] = $userbdd->fetch();

    $userbdd->closeCursor();

    if($_POST['order_send'])
    {
    //--- L'utilisateur doit accepter les CGV ---//
	if(!$_POST['cgv'])
	{
	    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	    $_SESSION['errorCGV'] = true; // Checkbox pas remplie
	    header('Location: ../'.$addressError);
	    exit();
	}
    //--- Si le champ du parrain à été rempli, controler la base de données ---//
	if($_SESSION['godfather'] != "" && $_SESSION['godfather'] != "GFxxx")
	{
	    $date = date("Y-m-d");
	//--- Acquisition des données pour les parrains ---//
	    $userbdd = $bdd->prepare('SELECT GF_Name, sales_BOOK_DE_2, sales_BOOK_DE_3, sales_BOOK_FR, sales_USB2RF FROM Godfathers WHERE GF_Name = ?') or die(print_r($bdd->errorInfo()));
	    $userbdd->execute(array($_SESSION['godfather'])) or die(print_r($userbdd->errorInfo()));
	/*** On récupère les informations de l'utilisateur dans un tableau appelé godfatherData ***/
	    $godfatherData = $userbdd->fetch();
	    $userbdd->closeCursor();
	/*** Enregistrement des achats pour le parrain ***/
	    $godfatherData['sales_BOOK_DE_2'] += $_SESSION['quantity_product'][0];
	    $godfatherData['sales_BOOK_DE_3'] += $_SESSION['quantity_product'][0];
	    $godfatherData['sales_BOOK_FR'] += $_SESSION['quantity_product'][1];
	    $godfatherData['sales_USB2RF'] += $_SESSION['quantity_product'][2];
	    $req = $bdd->prepare('UPDATE Godfathers SET sales_BOOK_DE_2 = :sales_BOOK_DE_2, sales_BOOK_DE_3 = :sales_BOOK_DE_3, sales_BOOK_FR = :sales_BOOK_FR, sales_USB2RF = :sales_USB2RF, lastSale = :lastSale WHERE GF_Name = :GF_Name');
	    $req->execute(array(	'sales_BOOK_DE_2' => $godfatherData['sales_BOOK_DE_2'],
					'sales_BOOK_DE_3' => $godfatherData['sales_BOOK_DE_3'],
					'sales_BOOK_FR' => $godfatherData['sales_BOOK_FR'],
					'sales_USB2RF' => $godfatherData['sales_USB2RF'],
					'lastSale' => $date,
					'GF_Name' => $godfatherData['GF_Name']
				)) or die(print_r($req->errorInfo()));
	    $userbdd->closeCursor();
	}
    /*** Si l'utilisateur s'est inscrit à la newsletter, on enregistre son nom, son email et son titre dans la base de données "newsletter" ***/
	if($_POST['newsletter'])
	{
	    $req = $bdd->prepare('INSERT INTO newsletter(gender, name, email) VALUES(:gender, :name, :email)');
	    $req->execute(array(	'gender' => $_SESSION['title'],
					'name' => $_SESSION['name'],
					'email' => $_SESSION['email'],
				)) or die(print_r($req->errorInfo()));

    // Fin de la requête
	    $req->closeCursor();
	}
    /*** Enregistrement du n° de commande dans le fichier texte ***/
	$billFile = fopen('../06_documents/Finances/bills_number.txt', 'a+');
	if($billFile != FALSE)
	{
	    $content = file('../06_documents/Finances/bills_number.txt');
	    $lastBill = $content[count($content)-1];
	    $newBill = $lastBill + 1;
	    fputs($billFile, "\n" . $newBill);
	    fclose ($billFile);
	}
    /*** Envoi de l'email au client ainsi qu'une copie à sales@refast-swiss.com ***/
	$message_HTML = '
	    <!DOCTYPE html>
	    <html>
		<head>
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
			<meta http-equiv="pragma" content="no-cache">
			<meta http-equiv="cache-control" content="no-cache">
		</head>
		<body>
		    <div style="text-align: left;">
			<table width="100%">
			    <tr>
				<td>
				    <img src="http://www.refast-swiss.com/05_images/Logo/320x240.png" align="left"  alt="REFAST Logo" style="margin: 10px 0 20px 0" height="100px">
				</td>
			    </tr>
			</table>';

	if($_COOKIE['language'] == "de")
	{
	    $message_HTML .= '
		<table width="100%">
		    <tbody>
			<tr>
			    <td>
				<p style="font-weight: bold;font-size: 18px;margin: 20px 0 20px 0">Ihre Bestellung '.$_SESSION['newBill'].'</p>
			    </td>
			</tr>
		    </tbody>
		</table>
		<table width="100%" style="min-height: 150px;position: relative;">
		    <tbody>
			<tr>
			    <td style="padding: 0"></td>
			    <td style="vertical-align: top">
				<p>Rechnungsadresse:</p>
				<p>'.$_SESSION['company'].'</p>
				<p>'.$_SESSION['title'].'</p>
				<p>'.$_SESSION["firstname"] . ' ' . $_SESSION["name"].' </p>
				<p></p>
				<p>'.$_SESSION['street'].'</p>
				<p></p>
				<p>'.$_SESSION['postcode'].' '.$_SESSION['city'].'</p>
				<p>'.$_SESSION['country'].'</p>
				<p></p>
				<p></p>
				<p>E-Mail: '.$_SESSION['email'].'</p>
			    </td>
			    <td style="vertical-align: top">';
			    if($_SESSION['name_2'] != "" || $_SESSION['firstname_2'] != "" || $_SESSION['street_2'] != "" || $_SESSION['city_2'] != "" || $_SESSION['postcode_2'] != "")
			    {
			    $message_HTML .=	'<p>Abweichende Lieferadresse:</p>
				<p>'.$_SESSION['company_2'].'</p>
				<p>'.$_SESSION['title_2'].'</p>
				<p>'.$_SESSION["firstname_2"] . ' ' . $_SESSION["name_2"].' </p>
				<p></p>
				<p>'.$_SESSION['street_2'].'</p>
				<p></p>
				<p>'.$_SESSION['postcode_2'].' '.$_SESSION['city_2'].'</p>
				<p>'.$_SESSION['country_2'].'</p>';
			    }
			    else
			    {	$message_HTML .=	'<p></p>
				<p></p>
				<p></p>
				<p></p>
				<p></p>
				<p></p>
				<p></p>
				<p></p>
				<p></p>';
			    }
			    $message_HTML .=	'</td>
			</tr>
		    </tbody>
		</table>
		<table width="100%" style="margin: 30px 0">
		    <tbody>
			<tr>
			    <td width="50%" style="vertical-align: top">
				<p>Ihr Referenz: '.$_SESSION['yourRef'].'</p>
				<p>Bestelldatum: '.$_SESSION['date'].'</p>
				<p>Shop-Bestell-Nr.: '.$_SESSION['newBill'].'</p>
				<p>Shop-Kunden-ID: '.$_SESSION['clientNumber'].'</p>
				<p></p>
				<p></p>
				<p></p>
				<p>MwSt. Nummer: CHE-330.597.780 MwSt</p>
			    </td>
			</tr>
		    </tbody>
		</table>
		<table width="100%">
		<thead>
		    <tr>
			<td style="margin-right: 10px">Menge</td>
			<td>Beschreibung</td>
			<td style="text-align: right;margin-right: 10px">Preis</td>
			<td style="border-bottom: 1px solid gray;text-align: center;padding:0 10px 0 10px;">MwSt.</td>
			<td style="border-bottom: 1px solid gray;text-align: right;">Total</td>
		    </tr>
		</thead>
		<tbody style="vertical-align: top;">';
		for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
		{
		    if($_SESSION['quantity_product'][$i] > 0)
		    {
			$message_HTML .= '<tr>
			<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
			    <p>'.$_SESSION['qtyProduct'][$i].' '.$_SESSION['product'][$i]['unit'].'</p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;">
			    <b><p>'.$_SESSION['product'][$i]['title'].'</p></b>
			    <p>'.$_SESSION['product'][$i]['genInfos'].'</p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
			    <p>'.number_format($_SESSION['pricePiece'][$i], 2, '.', '\'').'</p>';
			    if($_SESSION['geoZone'] == "Switzerland") if($_SESSION['product'][$i]['discountCHF'] > 0) $message_HTML .= '<p><strike>'.number_format($_SESSION['priceProductBruto'][$i], 2, '.', '\'').'</strike></p>';
			    elseif($_SESSION['geoZone'] == "Germany" || $_SESSION['geoZone'] == "Austria") if($_SESSION['product'][$i]['discountEUR'] > 0) $message_HTML .= '<p><strike>'.number_format($_SESSION['priceProductBruto'][$i], 2, '.', '\'').'</strike></p>';
			    else $message_HTML .= '<p></p>';
			    $message_HTML .= '<p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p>'.$_SESSION['tvaProduct'][$i].'%</p></td>
			<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p>'.$_SESSION['money'].' '.number_format($_SESSION['priceProduct'][$i], 2, '.', '\'').'</p>
			    <p></p>
			</td>
		    </tr>';
		    }
		}
		for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
		{
		    if($_SESSION['quantity_accessorie'][$i] > 0)
		    {
			$message_HTML .= '<tr>
			<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
			    <p>'.$_SESSION['qtyAccessorie'][$i].' '.$_SESSION['accessorie'][$i]['unit'].'</p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;">
			    <b><p>'.$_SESSION['accessorie'][$i]['name'].'</p></b>
			    <p>'.$_SESSION['accessorie'][$i]['genInfos'].'</p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
			    <p>'.number_format($_SESSION['pricePieceAccessorie'][$i], 2, '.', '\'').'</p>';
			    if($_SESSION['geoZone'] == "Switzerland") if($_SESSION['accessorie'][$i]['discountCHF'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceAccessorieBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    elseif($_SESSION['geoZone'] == "Germany" || $_SESSION['geoZone'] == "Austria")
			    if($_SESSION['accessorie'][$i]['discountEUR'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceAccessorieBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    else $message_HTML .= '<p></p>';
			    $message_HTML .= 		'<p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p>'.$_SESSION['tvaAccessorie'][$i].'%</p></td>
			<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p>'.$_SESSION['money'].' '.number_format($_SESSION['priceAccessorie'][$i], 2, '.', '\'').'</p>
			    <p></p>
			</td>
		    </tr>';
		    }
		}
		$message_HTML .= '	<tr>
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
		    <td colspan="4" style="border-top: 1px solid gray;font-weight: bold">Zwischentotal inkl. MwSt</td>
		    <td style="border-top: 1px solid gray;text-align: right;font-weight:bold">'.$_SESSION['money'].' '.number_format($_SESSION['totalBill'], 2, '.', '\'').'</td>
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
		<tr>
		    <td colspan="4"><p></p></td>
		    <td style="text-align: right"><p></p></td>
		</tr>';
		if($_SESSION['flagTVA2_5'])
		{
		$message_HTML .= '
		<tr>
		    <td colspan="4"><p>Inkl. MwSt von '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_2_5'], 2, '.', '\'').' (2.5%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_2_5'], 2, '.', '\'').'</p></td>
		    <td style="text-align: right"><p>&nbsp;</p></td>
		</tr>';
		}
		if($_SESSION['flagTVA8_0'])
		{
		$message_HTML .= '
		<tr>
		    <td colspan="4"><p>Inkl. MwSt von '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_8_0'], 2, '.', '\'').' (8.0%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_8_0'], 2, '.', '\'').'</p></td>
		    <td style="text-align: right"><p>&nbsp;</p></td>
		</tr>';
		}
		$message_HTML .= '
		<tr>
		    <td colspan="4"><p></p></td>
		    <td style="text-align: right"><p></p></td>
		</tr>
		<tr>
		    <td colspan="5"></td>
		</tr>
		<tr>
		    <td colspan="4" style="border-top: 2px solid gray;font-weight: bold;font-size: 14px;">Gesamtsumme inkl. MwSt</td>
		    <td style="border-top: 2px solid gray;border-bottom: 2px solid gray;text-align: right;font-weight: bold;font-size: 14px;">'.$_SESSION['money'].' '.number_format($_SESSION['totalBillShipping'], 2, '.', '\'').'</td>
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
	</table>';
	if($_SESSION['remark'] != "")
	{
	$message_HTML .= '
	    <table width="100%" style="border-top: 1px solid gray">
		<tbody>
		    <tr>
			<td>
			    <p style="margin:20px 0 0 0"><b>Ihre Bemerkungen</b></p>
			    <p style="margin: 0 0 20px 0">'.$_SESSION['remark'].'</p>
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
		</table>';
	}
	    $message_HTML .= '
	    <table width="100%" style="border-collapse: collapse;margin-top: 30px=;">
		<tbody>
		    <tr>
			<td style="border-top: 1px solid gray;margin: 30px 0 0 0">
			    <p><b>REF<font color="#0072CE">AST</font></b></p>
			    <p>Hasenholz 10<br>
			    1735 Giffers</p>
			    <p>E-Mail: <a href="mailto:sales@refast-swiss.com">sales@refast-swiss.com</a></p>
			    <p>Internet: <a href="http://www.refast-swiss.com" target="_top">http://www.refast-swiss.com</a></p>
			</td>
		    </tr>
		</tbody>
	    </table>
	</div>
    </body>
</html>';
}
	else if($_COOKIE['language'] == "fr")
	{
	    $message_HTML .= '
	    <table width="100%">
		<tbody>
		    <tr>
			<td>
			    <p style="font-weight: bold;font-size: 18px;margin: 20px 0 20px 0">Votre commande '.$_SESSION['newBill'].'</p>
			</td>
		    </tr>
		</tbody>
	    </table>
	    <table width="100%" style="min-height: 150px;position: relative;">
		<tbody>
		    <tr>
			<td style="padding: 0"></td>
			<td style="vertical-align: top">
			    <p>Adresse de facturation:</p>
			    <p>'.$_SESSION['company'].'</p>
			    <p>'.$_SESSION['title'].'</p>
			    <p>'.$_SESSION["firstname"] . ' ' . $_SESSION["name"].' </p>
			    <p></p>
			    <p>'.$_SESSION['street'].'</p>
			    <p></p>
			    <p>'.$_SESSION['postcode'].' '.$_SESSION['city'].'</p>
			    <p>'.$_SESSION['country'].'</p>
			    <p></p>
			    <p></p>
			    <p>E-Mail: '.$_SESSION['email'].'</p>
			</td>
			<td style="vertical-align: top">';
			if($_SESSION['name_2'] != "" || $_SESSION['firstname_2'] != "" || $_SESSION['street_2'] != "" || $_SESSION['city_2'] != "" || $_SESSION['postcode_2'] != "")
			{
			    $message_HTML .= '
			    <p>Adresse de livraison:</p>
			    <p>'.$_SESSION['company_2'].'</p>
			    <p>'.$_SESSION['title_2'].'</p>
			    <p>'.$_SESSION["firstname_2"] . ' ' . $_SESSION["name_2"].' </p>
			    <p></p>
			    <p>'.$_SESSION['street_2'].'</p>
			    <p></p>
			    <p>'.$_SESSION['postcode_2'].' '.$_SESSION['city_2'].'</p>
			    <p>'.$_SESSION['country_2'].'</p>';
			}
			else
			{
			    $message_HTML .= '
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>';
			}
			$message_HTML .= '
			</td>
		    </tr>
		</tbody>
	    </table>
	    <table width="100%" style="margin: 30px 0">
		<tbody>
		    <tr>
			<td width="50%" style="vertical-align: top">
			    <p>Votre référence: '.$_SESSION['yourRef'].'</p>
			    <p>Date de la commande: '.$_SESSION['date'].'</p>
			    <p>Commande n°: '.$_SESSION['newBill'].'</p>
			    <p>N° de client (si disponible): '.$_SESSION['clientNumber'].'</p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p>N° TVA: CHE-330.597.780</p>
			</td>
		    </tr>
		</tbody>
	    </table>
	    <table width="100%">
		<thead>
		    <tr>
			<td style="margin-right: 10px">Quantité</td>
			<td>Description</td>
			<td style="text-align: right;margin-right: 10px">Prix</td>
			<td style="border-bottom: 1px solid gray;text-align: center;padding:0 10px 0 10px;">TVA</td>
			<td style="border-bottom: 1px solid gray;text-align: right;">Total</td>
		    </tr>
		</thead>
		<tbody style="vertical-align: top;">';
	    for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
	    {
		if($_SESSION['quantity_product'][$i] > 0)
		{
		    $message_HTML .= '
		    <tr>
			<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
			    <p>'.$_SESSION['qtyProduct'][$i].' '.$_SESSION['product'][$i]['unit'].'</p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;">
			    <b><p>'.$_SESSION['product'][$i]['title'].'</p></b>
			    <p>'.$_SESSION['product'][$i]['genInfos'].'</p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
			    <p>'.number_format($_SESSION['pricePiece'][$i], 2, '.', '\'').'</p>';
			    if($_SESSION['geoZone'] == "Swiss") if($_SESSION['product'][$i]['discountCHF'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceProductBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    elseif($_SESSION['geoZone'] == "Germany" || $_SESSION['geoZone'] == "Austria") if($_SESSION['product'][$i]['discountEUR'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceProductBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    else $message_HTML .= '<p></p>';
			    $message_HTML .= '
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p>'.$_SESSION['tvaProduct'][$i].'%</p></td>
			<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p>'.$_SESSION['money'].' '.number_format($_SESSION['priceProduct'][$i], 2, '.', '\'').'</p>
			    <p></p>
			</td>
		    </tr>';
		}
	    }
	    for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
	    {
		if($_SESSION['quantity_accessorie'][$i] > 0)
		{
		    $message_HTML .= '
		    <tr>
			<td style="vertical-align: top;border-top: 1px solid gray;margin-right: 10px">
			    <p>'.$_SESSION['qtyAccessorie'][$i].' '.$_SESSION['accessorie'][$i]['unit'].'</p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;">
			    <b><p>'.$_SESSION['accessorie'][$i]['name'].'</p></b>
			    <p>'.$_SESSION['accessorie'][$i]['genInfos'].'</p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: right;margin-right: 10px">
			    <p>'.number_format($_SESSION['pricePieceAccessorie'][$i], 2, '.', '\'').'</p>';
			    if($_SESSION['geoZone'] == "Swiss") if($_SESSION['accessorie'][$i]['discountCHF'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceAccessorieBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    elseif($_SESSION['geoZone'] == "Germany" || $_SESSION['geoZone'] == "Austria") if($_SESSION['accessorie'][$i]['discountEUR'] > 0) $message_HTML .= '<p><strike>'. number_format($_SESSION['priceAccessorieBruto'][$i], 2, '.', '\'') .'</strike></p>';
			    else $message_HTML .= '<p></p>';
			    $message_HTML .= '
			    <p></p>
			    <p></p>
			</td>
			<td style="vertical-align: top;border-top: 1px solid gray;text-align: center;"><p>'.$_SESSION['tvaAccessorie'][$i].'%</p></td>
			<td style="text-align: right;vertical-align: top;border-top: 1px solid gray;"><p>'.$_SESSION['money'].' '.number_format($_SESSION['priceAccessorie'][$i], 2, '.', '\'').'</p>
			    <p></p>
			</td>
		    </tr>';
		}
	    }
	    $message_HTML .= '
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
		<td colspan="4" style="border-top: 1px solid gray;font-weight: bold">Total partiel (incl. TVA)</td>
		<td style="border-top: 1px solid gray;text-align: right;font-weight:bold">'.$_SESSION['money'].' '.number_format($_SESSION['totalBill'], 2, '.', '\'').'</td>
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
	    <tr>
		<td colspan="4"><p></p></td>
		<td style="text-align: right"><p></p></td>
	    </tr>';
	    if($_SESSION['flagTVA2_5'])
	    {
	    $message_HTML .= '
	    <tr>
		<td colspan="4"><p>incl. TVA de '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_2_5'], 2, '.', '\'').' (2.5%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_2_5'], 2, '.', '\'').'</p></td>
		<td style="text-align: right"><p>&nbsp;</p></td>
	    </tr>';
	    }
	    if($_SESSION['flagTVA8_0'])
	    {
	    $message_HTML .= '
	    <tr>
		<td colspan="4"><p>incl. TVA de '.$_SESSION['money'].' '.number_format($_SESSION['totalPrice_8_0'], 2, '.', '\'').' (8.0%) = '.$_SESSION['money'].' '.number_format($_SESSION['tva_8_0'], 2, '.', '\'').'</p></td>
		<td style="text-align: right"><p>&nbsp;</p></td>
	    </tr>';
	    }
	    $message_HTML .= '
	    <tr>
		<td colspan="4"><p></p></td>
		<td style="text-align: right"><p></p></td>
	    </tr>
	    <tr>
		<td colspan="5"></td>
	    </tr>
	    <tr>
		<td colspan="4" style="border-top: 2px solid gray;font-weight: bold;font-size: 14px;">Total (incl. TVA)</td>
		<td style="border-top: 2px solid gray;border-bottom: 2px solid gray;text-align: right;font-weight: bold;font-size: 14px;">'.$_SESSION['money'].' '.number_format($_SESSION['totalBillShipping'], 2, '.', '\'').'</td>
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
    </table>';
    if($_SESSION['remark'] != "")
    {
    $message_HTML .= '
    <table width="100%" style="border-top: 1px solid gray">
	<tbody>
	    <tr>
		<td>
		    <p style="margin:20px 0 0 0"><b>Vos remarques</b></p>
		    <p style="margin: 0 0 20px 0">'.$_SESSION['remark'].'</p>
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
    </table>';
    }
    $message_HTML .= '
    <table width="100%" style="border-collapse: collapse;margin-top: 30px=;">
	<tbody>
	    <tr>
		<td style="border-top: 1px solid gray;margin: 30px 0 0 0">
		    <p><b>REF<font color="#0072CE">AST</font></b></p>
		    <p>Hasenholz 10<br>
		    1735 Giffers</p>
		    <p>E-Mail: <a href="mailto:sales@refast-swiss.com">sales@refast-swiss.com</a></p>
		    <p>Internet: <a href="http://www.refast-swiss.com" target="_top">http://www.refast-swiss.com</a></p>
		</td>
	    </tr>
	</tbody>
    </table>
</div>
</body>
</html>';
}

    $message_TXT = ' ';

    $message_HTML = utf8_decode($message_HTML);

    $boundary = "-----=".md5(rand());
    $headers = "From: \"Sales REFAST\"<sales@refast-swiss.com>\n";
    $headers .= "Reply-To: sales@refast-swiss.com\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative;"."\n"." boundary=\"$boundary\""."\n";

    $message = "";
    $message .= "\n"."--".$boundary."\n";
    $message .= "Content-Type:  text/plain\n";
    $message .= "charset=\"ISO-8859-1\"\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= "\n".$message_TXT."\n";

    $message = "\n"."--".$boundary."\n";

    $message .= "Content-Type: text/html; charset=\"ISO-8859-1\""."\n";
    $message .= "Content-Transfer-Encoding: 8bit"."\n";
    $message.= "\n".$message_HTML."\n";

    $message.= "\n"."--".$boundary."--"."\n";

    $object = utf8_decode("Order n° ".$_SESSION['newBill']);

/*** Ecriture des informations de commande dans un document Excel ***/
    if(mail($_SESSION['email'], $object, $message, $headers))
    {
	mail('sales@refast-swiss.com', $object, $message, $headers);

	require_once('../09_libs/Classes/PHPExcel.php');
	require_once('../09_libs/Classes/PHPExcel/Writer/Excel2007.php');
	require_once('../09_libs/Classes/PHPExcel/Writer/HTML.php');
	require_once('../09_libs/Classes/PHPExcel/IOFactory.php');
	require_once('../09_libs/Classes/PHPExcel/Writer/PDF.php');
	require_once('../09_libs/Classes/PHPExcel/Cell.php');
	require_once('../09_libs/Classes/PHPExcel/Cell/DataType.php');

    /*** Error reporting ***/
	error_reporting(E_ALL);
    /*** Include path ***/
	ini_set('include_path', ini_get('include_path').'9_libs/Classes/');

	if($_SESSION['remark'] != "TEST_ORDER")
	{
	/*** Open the Excel file ***/
	    $inputFileName = '/home/www/30398ce4ba8eeca03558674547826581/data/Sales/List_orders2.xlsx';
	/*** Load $inputFileName to a PHPExcel Object  ***/
	    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

		$dataToWrite = array($_SESSION['date'], $_SESSION['newBill'], $_SESSION['clientNumber'], $_SESSION['yourRef'], $_SESSION['company'], $_SESSION['name'], $_SESSION['firstname'], $_SESSION['street'], $_SESSION['postcode'], $_SESSION['city'], $_SESSION['email'], $_SESSION['company_2'], $_SESSION['name_2'], $_SESSION['firstname_2'], $_SESSION['street_2'], $_SESSION['postcode_2'], $_SESSION['city_2'], $_SESSION['remark'], $_SESSION['godfather'], $_SERVER['HTTP_USER_AGENT']);
	// Ajout des produits dans le tableau
		for($i = 0; $i < $_SESSION['numberProduct'][nb]; $i++)
		{
			if($_SESSION['product'][$i]['Available']) array_push($dataToWrite, $_SESSION['quantity_product'][$i], $_SESSION['priceProductBruto'][$i], $_SESSION['discountProduct'][$i], $_SESSION['pricePiece'][$i], $_SESSION['priceProduct'][$i]);
		}
	// Ajout des accessoires dans le tableau
		for($i = 0; $i < $_SESSION['numberAccessorie'][nb]; $i++)
		{
			if($_SESSION['accessorie'][$i]['available']) array_push($dataToWrite, $_SESSION['quantity_accessorie'][$i], $_SESSION['priceAccessorieBruto'][$i], $_SESSION['discountAccessorie'][$i], $_SESSION['pricePieceAccessorie'][$i], $_SESSION['priceAccessorie'][$i]);
		}

		array_push($dataToWrite, $_SESSION['totalPrice_2_5'], $_SESSION['totalPrice_8_0'], $_SESSION['tva_2_5'], $_SESSION['tva_8_0'], $_SESSION['shipping'], $_SESSION['totalBill']);

	    $indexValue_Eval = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, 1)->getValue();
		$fixeLine = 'A';
		foreach($dataToWrite as $dataTemp)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue($fixeLine . $indexValue_Eval, $dataTemp);
			$fixeLine++;
		}
	    $objPHPExcel->getActiveSheet()->SetCellValue('K1', ($indexValue_Eval + 1));
	    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	    $records = '/home/www/30398ce4ba8eeca03558674547826581/data/Sales/List_orders2.xlsx';

	    $objWriter->save($records);
	}
   /*** On supprime toutes les valeurs du formulaire de commande ***/
	unset($_SESSION['date']);
	unset($_SESSION['clientNumber']);
	unset($_SESSION['yourRef']);
	unset($_SESSION['company']);
	unset($_SESSION['title']);
	unset($_SESSION['company_2']);
	unset($_SESSION['title_2']);
	unset($_SESSION['name_2']);
	unset($_SESSION['firstname_2']);
	unset($_SESSION['street_2']);
	unset($_SESSION['postcode_2']);
	unset($_SESSION['city_2']);
	unset($_SESSION['quantity_product']);
	unset($_SESSION['quantity_accessorie']);
	unset($_SESSION['remark']);
	unset($_SESSION['godfather']);
	unset($dataToWrite);
   /*** Fin du script - déroulement avec succès - redirection sur thankyou.php ***/
	$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	$_SESSION['OrderSuccess'] = true;   // Envoi terminé avec succès
	$_SESSION['email_order'] = $email;
	header('Location: ../thank_you');
	exit();
    }
/*** Fin du script - déroulement avec erreur(s) - redirection vers Order.php ***/
    else
    {
	$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	$_SESSION['errorSendingMail'] = true; // Erreur durant l'envoi de l'email
	header('Location: ../confirmOrder');
	exit();
    }
}