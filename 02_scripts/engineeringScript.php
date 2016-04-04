<?php

    session_start();

    $_SESSION['test'] = 0; // Test
    $pageError = "../engineering";
    $pageOK = "../engineering_thank_you";

    if(isset($_POST['sendProject']))
    {
	$_SESSION['company'] = htmlspecialchars($_POST['company']);
	$_SESSION['name'] = htmlspecialchars($_POST['name']);
	$_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
	$_SESSION['email'] = htmlspecialchars($_POST['email']);
	$_SESSION['website'] = htmlspecialchars($_POST['website']);
	$_SESSION['phone'] = htmlspecialchars($_POST['phone']);
	$_SESSION['mobile'] = htmlspecialchars($_POST['mobile']);
	$_SESSION['category'] = $_POST['category'];
	$_SESSION['projectname'] = htmlspecialchars($_POST['projectname']);
	$_SESSION['description'] = htmlspecialchars($_POST['description']);
	$_SESSION['user_answer'] = htmlspecialchars($_POST['user_answer']);

    //--- Initialisation des variables d'erreur ---//
	$_SESSION['tryToSendAProject'] = true;
	$_SESSION['ServiceSendSuccessfuly'] = false;
	$_SESSION['ObligatoryFields'] = false;
	$_SESSION['UploadedFail'] = false;
	$_SESSION['CaptchaWrong'] = false;
	$_SESSION['formatFileNotSupported'] = false;
	$_SESSION['ErrorFile'] = 0;

    //--- Controle du champ anti-spam ---//
	$user_answer = trim(htmlspecialchars($_POST['user_answer']));
	$answer = trim(htmlspecialchars($_POST['answer']));
	if(substr(md5($user_answer),5,10) === $answer)
	{
	// Controle des champs obligatoires
	    if($_SESSION['category'] == "" || $_SESSION['name'] == "" || $_SESSION['firstname'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['projectname'] == "" || $_SESSION['description'] == "")
	    {
	    	exit; // Test
		$_SESSION['ObligatoryFields'] = true;
		header("Location: ".$pageError);
		exit;
	    }
	// Si tout est en ordre, on crée un dossier pour stocker les informations du contact

	// On choisi encore le dossier de base: privé ou professionnel
	    if($_SESSION['category'] == "Private") $folder = '06_documents/services/Private/';
	    else $folder = '06_documents/services/Professional/';

	// Si l'utilisateur n'a pas entré de nom de companie
	    if($_SESSION['company'] == "")
	    {
		$nameOrder = date('Ymd').'_'.date('His').'_'.$_SESSION['name'].'_'.$_SESSION['firstname'];
	    }
	// Si l'utilisateur à entré un nom de companie
	    else
	    {
		$nameOrder = date('Ymd').'_'.date('His').'_'.$_SESSION['company'].'_'.$_SESSION['name'];
	    }
	    if(mkdir($folder.$nameOrder))
	    {
		$file = fopen($folder.$nameOrder.'/Customer_informations.txt', 'a');
		if($_SESSION['company'] != "") fputs($file, 'Company: '.$_SESSION['company']."\n");
		fputs($file, 'Name: '.$_SESSION['name']."\n");
		fputs($file, 'Firstname: '.$_SESSION['firstname']."\n");
		fputs($file, 'Email: '.$_SESSION['email']."\n");
		fputs($file, 'Website: '.$_SESSION['website']."\n");
		fputs($file, 'Phone '.$_SESSION['phone']."\n");
		fputs($file, 'Mobile: '.$_SESSION['mobile']."\n\n");
		fputs($file, 'Project Name: '.$_SESSION['projectname']."\n");
		fputs($file, 'Description: '.$_SESSION['description']."\n");
		fclose($file);
	    }
	// Création des variables pour l'envoi de l'email
	    $headers ='From: '.$_SESSION['email']."\n";
	    $headers .='Content-Type: text/plain; charset="utf-8"'."\n";
	    $headers .='Content-Transfer-Encoding: 8bit';
	    $to = 'contact@refast-swiss.com';
	    $subject = 'New project - '.$_SESSION['projectname'];
	    $message = "A new project has been sended to us.\n You can find it under: ".$folder.$nameOrder.".\n It's called ".$_SESSION['projectname'].'.';
	// On change le CHMOD du dossier pour pouvoir y enregistrer le document.
	    chmod($folder.$nameOrder, 0777);
	// On sauvegarde ensuite le data de l'utilisateur s'il en a un
	    if(!$_FILES['newData']['error'])
	    {
	    // On controle ensuite l'extension du document
		$extensions_valides = array( 'zip' , 'rar' , 'pdf');
	    //1. strrchr renvoie l'extension avec le point (« . »).
	    //2. substr(chaine,1) ignore le premier caractère de chaine.
	    //3. strtolower met l'extension en minuscules.
		$extension_upload = strtolower(  substr(  strrchr($_FILES['newData']['name'], '.'), 1));
		if(in_array($extension_upload,$extensions_valides) )
		{
		// On déplace le document dans le dossier créé précédemment
		    $result = move_uploaded_file($_FILES['newData']['tmp_name'], $folder.$nameOrder.'/'.$_FILES['newData']['name']);
		    if ($result)
		    {
		    	$_SESSION['tryToSendAProject'] = false;
		    	mail($to, $subject, $message, $headers);
			header("Location: ".$pageOK);
			exit;
		    }
		    else
		    {
		    	$_SESSION['test'] = 1; // Test
		    	$_SESSION['UploadedFail'] = true;
			header("Location: ".$pageError);
			exit;
		    }
		}
		else
		{
		    $_SESSION['test'] = 2; // Test
		    $_SESSION['formatFileNotSupported'] = true;
		    header("Location: ".$pageError);
		    exit;
		}
	    }
	// Pas de fichier téléchargé, mais déroulement sans erreur
	    elseif($_FILES['newData']['error'] == 4)
	    {
	    	$_SESSION['tryToSendAProject'] = false;
	    	mail($to, $subject, $message, $headers);
		header("Location: ".$pageOK);
		exit;
	    }
	// Dans les autres cas d'erreur
	    else
	    {
		$_SESSION['test'] = 3; // Test
		switch($_FILES['newData']['error'])
		{
		// Taille du fichier trop grande (par rapport à upload_max_filesize
		    case 1:	$_SESSION['ErrorFile'] = 1;
				break;
		// Taille du fichier trop grande (par rapport à MAX_FILE_SIZE)
		    case 2:	$_SESSION['ErrorFile'] = 2;
				break;
		// Le fichier n'a pas été complétement téléchargé
		    case 3:	$_SESSION['ErrorFile'] = 3;
				break;
		// Dossier temporaire manquant
		    case 6:	$_SESSION['ErrorFile'] = 4;
				break;
		// Échec de l'écriture du fichier sur le disque
		    case 7:	$_SESSION['ErrorFile'] = 5;
				break;
		// Une extension PHP a arrêté l'envoi de fichier.
		    case 8:	$_SESSION['ErrorFile'] = 6;
				break;
		}
		$to = 'webmaster@refast-swiss.com';
		$subject = "Technical error on the website";
		$message = "Concerned page: services.php\n
			    Error detected: \n
					    - FILE variable: ".$_FILES['newData']['error']."\n
					    - SESSION['ErrorFile']: ".$_SESSION['ErrorFile']."\n
			    User concerned: \n
					    Name: ".$_SESSION['name']."\n
					    Firstname: ".$_SESSION['firstname']."\n
					    Email: ".$_SESSION['email']."\n";
		mail($to, $subject, $message, $headers);
		header("Location: ".$pageError);
		exit;
	    }
	}
	else
	{
	    $_SESSION['test'] = 4; // Test
	    $_SESSION['CaptchaWrong'] = true;
	    header("Location: ".$pageError);
	    exit;
	}
    }
?>