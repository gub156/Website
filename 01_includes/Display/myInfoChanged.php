<?php
    session_start();

    // Les informations personnelles ont été tentées de changer
    if($_SESSION['infoTryToChange'])
    {
        if($_SESSION['infoHasBeenChanged']) // Les infos ont effectivement été changées.
        {
			?><div id="textDisplayed_Green"><?php
            echo INFOCHANGEDWITHSUCCES;
            unset($_SESSION['infoHasBeenChanged']);
        }
        else // Les infos n'ont pas été changées.
        {
			?><div id="textDisplayed_Red"><?php
            if($_SESSION['PasswordNotSame']) // Type d'erreur.
            {
                echo ERRORPASSWORD;
                unset($_SESSION['PasswordNotSame']);
            }
            elseif($_SESSION['PictureTooBig'])
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
        unset($_SESSION['infoTryToChange']);
		?></div><?php
    }
?>


