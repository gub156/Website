<?php
    session_start();

//--- On a tenté d'envoyer un service ---//
    if($_SESSION['tryToSendAProject'])
    {
	?><div id="textDisplayed_Red"><?php
    //--- Champs obligatoires pas remplis ---//
	if($_SESSION['ObligatoryFields'])
	{
	    echo MUSTFILLALL;
	    unset($_SESSION['ObligatoryFields']);
	}
    //--- L'upload d'un fichier a échoué ---//
	elseif($_SESSION['UploadedFail'])
	{
	    echo ERROR_DURING_UPLOAD;
	    unset($_SESSION['UploadedFail']);
	}
    //--- Le captcha entré est erronné ---//
	elseif($_SESSION['CaptchaWrong'])	// L'image uploadée est trop grosse
	{
	    echo ERRORANTISPAM;
	    unset($_SESSION['CaptchaWrong']);
	}
    //--- Si l'extension du fichier uploadé n'est pas prise en charge ---//
	else if($_SESSION['formatFileNotSupported'])
	{
	    echo UNSUPPORTED_EXTENSION_FILE;
	    unset($_SESSION['formatFileNotSupported']);
	}
    //--- Un problème est survenu par rapport au fichier en cours d'upload ---//
	elseif($_SESSION['ErrorFile'] != 0)
	{
	    switch($_SESSION['ErrorFile'])
	    {
	    //--- Taille du fichier trop grande (par rapport à upload_max_filesize ---//
	    	case 1:
	    //--- Taille du fichier trop grande (par rapport à MAX_FILE_SIZE) ---//
		case 2:	    echo SIZE_OF_FILE_TOO_BIG;
			    break;
	    //--- Le fichier n'a pas été complétement téléchargé ---//
		case 3:
	    //--- Dossier temporaire manquant ---//
		case 4:
	    //--- Échec de l'écriture du fichier sur le disque ---//
		case 5:
	    //--- Une extension PHP a arrêté l'envoi de fichier. ---//
		case 6:
		default:    echo TECHNICAL_ERROR;
			    break;
	    }
	    unset($_SESSION['ErrorFile']);
	}
	unset($_SESSION['tryToSendAProject']);
	?></div><?php
    }
?>


