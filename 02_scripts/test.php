    <?php
    session_start();
    $_SESSION['test'] = 0;
    if(isset($_POST['order_send']))
    {
	$addDif_CB = $_SESSION['addDif_CB_client'] = $_POST['addDif_CB'];
	$company = $_SESSION['company_client'] = $_POST['company'];
	$sexe = $_SESSION['sexe_client'] = $_POST['sexe'];
	$name = $_SESSION['name_client'] = htmlspecialchars($_POST['name']);
	$firstname = $_SESSION['firstname_client'] = htmlspecialchars($_POST['firstname']);
	$street = $_SESSION['street_client'] = htmlspecialchars($_POST['street']);
	$city = $_SESSION['city_client'] = htmlspecialchars($_POST['city']);
	$postcode = $_SESSION['postcode_client'] = htmlspecialchars($_POST['postcode']);
	$email = $_SESSION['email_client'] = htmlspecialchars($_POST['email']);
	$emailconfirm = $_SESSION['emailconfirm_client'] = htmlspecialchars($_POST['emailconfirm']);
	$quantity = $_SESSION['quantity_client'] = $_POST['quantity'];
	$remark = $_SESSION['remark_client'] = htmlspecialchars($_POST['remark']);
	$clientNumber = $_SESSION['clientNumber_client'] = htmlspecialchars($_POST['clientNumber']);
	$yourRef = $_SESSION['yourRef_client'] = htmlspecialchars($_POST['yourRef']);
	$company_2 = $_SESSION['company_2_client'] = $_POST['company_2'];
	$sexe_2 = $_SESSION['sexe_2_client'] = $_POST['sexe_2'];
	$name_2 = $_SESSION['name_2_client'] = htmlspecialchars($_POST['name_2']);
	$firstname_2 = $_SESSION['firstname_2_client'] = htmlspecialchars($_POST['firstname_2']);
	$street_2 = $_SESSION['street_2_client'] = htmlspecialchars($_POST['street_2']);
	$city_2 = $_SESSION['city_2_client'] = htmlspecialchars($_POST['city_2']);
	$postcode_2 = $_SESSION['postcode_2_client'] = htmlspecialchars($_POST['postcode_2']);
	
	if($_COOKIE['language'] == "fr")
	{
		if($sexe) $sexe = 'M.';
		else $sexe = 'Mme.';
		if($sexe_2) $sexe_2 = 'M.';
		else $sexe_2 = 'Mme.';
	}
	elseif($_COOKIE['language'] == "de")
	{
		if($sexe) $sexe = 'Herr';
		else $sexe = 'Frau';
		if($sexe_2) $sexe_2 = 'Herr';
		else $sexe_2 = 'Frau';
	}
	// L'utilisateur doit accepter les CGV
	if(!$_POST['cgv'])
	{
	    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	    $_SESSION['errorCGV'] = true; // Checkbox pas remplie
	    header('Location: ../bookOrder');
	    exit();
	}

	// Contrôle de l'antispam
	$user_answer = trim(htmlspecialchars($_POST['user_answer']));
	$answer = trim(htmlspecialchars($_POST['answer']));
	if(substr(md5($user_answer),5,10) === $answer)
	{
	    //Test de la confirmation de l'email
	    if($email != $emailconfirm)
	    {
			$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			$_SESSION['errorEmail'] = true;     // Erreur d'email
			header('Location: ../bookOrder');
			exit();
	    }
	    // Test que tous les champs obligatoires soient renseignés
	    if( $sexe == "" || $name == "" || $firstname == "" || $street == "" || $city == "" || $postcode == "" || $email == "" || $quantity == "")
	    {
			$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			$_SESSION['errorField'] = true;     // Erreur d'email
			header('Location: ../bookOrder');
			exit();
	    }
	    if($addDif_CB)
	    {
			if($name_2 == "" || $firstname_2 == "" || $street_2 == "" || $city_2 == "" || $postcode_2 == "")
			{
				$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
				$_SESSION['errorField'] = true;     // Erreur d'email
				header('Location: ../bookOrder');
				exit();
			}
	    }

/******************************************************** GETTING THE BILL NUMBER FROM TXT DOC *****************************************************/
	    $billFile = fopen('../06_documents/Finances/bills_number.txt', 'a+');
	    if($billFile != FALSE)
	    {
			$content = file('../06_documents/Finances/bills_number.txt');
			$lastBill = $content[count($content)-1];
			$newBill = $lastBill + 1;
			fputs($billFile, "\n" . $newBill);
			fclose ($billFile);
	    }
	    else
	    {
			$_SESSION['test'] = 1;
			header('Location: ../bookOrder');
			exit;
	    }

/******************************************************* WRITTING THE EXCEL FILE FOR CUSTOMER ******************************************************/
		// Emplacement du document //
		$originalDoc = '../06_documents/Finances/templateBill.xlsx';
		$records = '../06_documents/Order_nr_' . $newBill . '.pdf';
		$ISBN = 'ISBN: 978-3-033-04156-1';
		$descriptionBook = 'Formulaire technique';
		$prixUnitaireTexte = 'CHF 55.-';
		
		$prixUnitaire = 55;
		$prixFraisDePort = 0;
		$prixTotal = $prixUnitaire * $quantity;
		$prixTotalMoney = money_format('%i', $prixTotal);
		$prixFraisDePortMoney = money_format('%i', $prixFraisDePort);
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		date_default_timezone_set('Europe/London');

		/** PHPExcel_IOFactory */
		require_once ('../09_libs/Classes/PHPExcel/IOFactory.php');
		
		$objPHPExcel = PHPExcel_IOFactory::load($originalDoc);
		
		$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
		$rendererLibrary = 'dompdf';
		$rendererLibraryPath = '../09_libs/Classes/PHPExcel/Writer/' . $rendererLibrary;
		
		if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath))
		{
			die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');
		}
			
		// Set document properties	
		$objPHPExcel->getProperties()->setCreator("REFAST")
							 ->setLastModifiedBy("J. Rebetez")
							 ->setTitle("Order_confirmation_v1.0")
							 ->setSubject("Order Confirmation")
							 ->setDescription("Webformular for billing")
							 ->setKeywords("")
							 ->setCategory("");
							 
		// Adding customer informations //
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A13', $company);
		$objPHPExcel->getActiveSheet()->setCellValue('A14', $sexe . ' ' . $name . ' ' . $firstname);
		$objPHPExcel->getActiveSheet()->setCellValue('A15', $street);
		$objPHPExcel->getActiveSheet()->setCellValue('A16', $postcode . ' ' . $city);
		
		if($addDif_CB)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('E13', $company_2);
			$objPHPExcel->getActiveSheet()->setCellValue('E14', $sexe_2 . ' ' . $name_2 . ' ' . $firstname_2);
			$objPHPExcel->getActiveSheet()->setCellValue('E15', $street_2);
			$objPHPExcel->getActiveSheet()->setCellValue('E16', $postcode_2 . ' ' . $city_2);
		}
		
		// Adding order informations //
		$objPHPExcel->getActiveSheet()->setCellValue('A19', $quantity);
		$objPHPExcel->getActiveSheet()->setCellValue('B19', $ISBN);
		$objPHPExcel->getActiveSheet()->setCellValue('C19', $descriptionBook);
		$objPHPExcel->getActiveSheet()->setCellValue('F19', $prixUnitaireTexte);
		$objPHPExcel->getActiveSheet()->setCellValue('G19', $prixTotalMoney);
		
		$objPHPExcel->getActiveSheet()->setCellValue('G30', $prixTotalMoney);
		$objPHPExcel->getActiveSheet()->setCellValue('G31', $prixFraisDePortMoney);
		$objPHPExcel->getActiveSheet()->setCellValue('G32', $prixTotalMoney);
		
		// Formating the sheet - Set column widths //
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(9.14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9.71);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9.43);
		
		// Formating the sheet - Set row height //
		$objPHPExcel->getActiveSheet()->getRowDimension('1-40')->setRowHeight(20);
        $objPHPExcel->getActiveSheet()->getRowDimension('18')->setRowHeight(48);       
		
		// Formating the sheet - Set fonts //
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(36);
		
		// Set page orientation and size
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
		$objWriter->setSheetIndex(0);
		$objWriter->save($records);

/***************************************************** END OF THE SCRIPT ***************************************************************************/
			$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			$_SESSION['OrderSuccess'] = true;   // Envoi terminé avec succès
			$_SESSION['email_order'] = $email;
			header('Location: ../thankOrder');
			exit();
	    }
	    else
	    {
			$_SESSION['justTryToOrder'] = true; // Tentative d'envoi
			$_SESSION['errorSendingMail'] = true; // Erreur durant l'envoi de l'email
			header('Location: ../bookOrder');
			exit();
	    }
	}
	else
	{
	    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	    $_SESSION['errorAntispam'] = true;     // Resultat du calcul erroné
	    header('Location: ../bookOrder');
	    exit();
	}
?>