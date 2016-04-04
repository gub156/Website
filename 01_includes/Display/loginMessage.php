<?php
    session_start();

    if($_SESSION['justTryToLog'])
    {
        if($_SESSION['accesGranted'])
        {
			?><div id="textDisplayed_Green"><?php
            echo ACCESGRANTED;
        }
        else
        {
			?><div id="textDisplayed_Red"><?php
            echo WRONGLOGIN;
        }
        unset($_SESSION['justTryToLog']);
		?></div><?php
    }