<?php
    session_start();

    if($_SESSION['contactMailHasBeenSend'])
	{
		?><div id="textDisplayed_Green"><?php
		echo MESSSEND;
		unset($_SESSION['contactMailHasBeenSend']);
		?></div><?php
	}
	elseif($_SESSION['contactMailHasNotBeenSend'])
	{
		?><div id="textDisplayed_Red"><?php
		echo MESS_NO_SEND;
		unset($_SESSION['contactMailHasNotBeenSend']);
		?></div><?php
	}
?>


