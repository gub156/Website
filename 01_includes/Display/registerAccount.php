<?php
    session_start();

    // On a tenté de créer un compte
    if($_SESSION['TryToRegister'])
    {
        if($_SESSION['AccountHasBeenCreated']) // Les infos ont effectivement été changées.
        {
			?><div id="textDisplayed_Green"><?php
            echo ACCOUNTCREATED;
            unset($_SESSION['AccountHasBeenCreated']);
        }
        else // Le compte n'a pas été crée. ==> Affichage de l'erreur
        {
			?><div id="textDisplayed_Red"><?php
            if($_SESSION['PasswordNotSame']) // Mots de passe pas identiques
            {
                echo ERRORPASSWORD;
                unset($_SESSION['PasswordNotSame']);
            }
            elseif($_SESSION['NotAllFieldsAreFilled']) // Un ou plusieurs champs n'ont pas été remplis
            {
                echo MUSTFILLALL;
                unset($_SESSION['NotAllFieldsAreFilled']);
            }
            elseif($_SESSION['PictureTooBig'])	// L'image uploadée est trop grosse
            {
                echo PICTURETOOBIG;
                unset($_SESSION['PictureTooBig']);
            }
            elseif($_SESSION['WrongFormatImage'])
            {
                echo FORMATPICTURE;
                unset($_SESSION['WrongFormatImage']);
            }
            elseif($_SESSION['FileNotUploaded'])
            {
                echo FILENOTUPLOADED;
                unset($_SESSION['FileNotUploaded']);
            }
        }
        unset($_SESSION['TryToRegister']);
		?></div><?php
    }
    elseif($_SESSION['AccountHasBeenDeleted'])
    {
		?><div id="textDisplayed_Green"><?php
        echo ACCOUNTDELETED;
        unset($_SESSION['AccountHasBeenDeleted']);
		?></div><?php
    }
?>


