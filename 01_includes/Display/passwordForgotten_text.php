<?php
    session_start();

	if($_SESSION['tryToRecoverPassword'])
	{
		
		if($_SESSION['mailSuccessfulySended'])
		{
			?><div id="textDisplayed_Green"><?php
			echo MAIL_WITH_NEW_PASS_HAS_BEEN_SEND;
			unset($_SESSION['mailSuccessfulySended']);
		}
		elseif($_SESSION['errorDuringSendingPasswordMail'])
		{
			?><div id="textDisplayed_Red"><?php
			echo ERROR_DURING_SENDING_MAIL;
			unset($_SESSION['errorDuringSendingPasswordMail']);
		}
		elseif($_SESSION['noEmailFoundInDB'])
		{
			?><div id="textDisplayed_Red"><?php
			echo NO_SUCH_EMAIL_IN_DB;
			unset($_SESSION['noEmailFoundInDB']);
		}
		elseif($_SESSION['emailAddressNotFilled'])
		{
			?><div id="textDisplayed_Red"><?php
			echo FILL_THE_EMAIL_FIELD;
			unset($_SESSION['emailAddressNotFilled']);
		}
		unset($_SESSION['tryToRecoverPassword']);
		?></div><?php
	}
?>


