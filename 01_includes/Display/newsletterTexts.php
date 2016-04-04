<?php
    session_start();

    // On a tenté de s'inscrire à la newsletter
    if($_SESSION['tryToSubscrNewsletter'])
    {
		if($_SESSION['SubscrSuccessfulyNewsletter'])
		{
			?><div id="textDisplayed_Green"><?php
			echo SUBSCRIBE_SUCCESSFULY_TO_THE_NEWSLETTER;
		}
		else
		{
			?><div id="textDisplayed_Red"><?php
			echo ERROR_DURING_TRYING_TO_SUBSCRIBE_TO_THE_NEWSLETTER;
            
		}
		unset($_SESSION['SubscrSuccessfulyNewsletter']);
		?></div><?php
    }
    unset($_SESSION['tryToSubscrNewsletter']);
?>


