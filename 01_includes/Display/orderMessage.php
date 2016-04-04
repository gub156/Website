<?php
    session_start();

    if($_SESSION['justTryToOrder'])
    {
        if($_SESSION['errorEmail'])
        {
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORMAIL;
            unset($_SESSION['errorEmail']);
        }
        elseif($_SESSION['errorField'])
        {
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORFIELD;
            unset($_SESSION['errorField']);
        }
	elseif($_SESSION['errorFieldShippingAddress'])
	{
	    ?>
	    <div id="textDisplayed_Red"><?php
	    echo ERROR_FIELD_SHIPPING_ADDRESS;
	    unset($_SESSION['errorFieldShippingAddress']);
	}
        elseif($_SESSION['errorCGV'])
        {
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORCGV;
            unset($_SESSION['errorCGV']);
        }
        elseif($_SESSION['errorAntispam'])
        {
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORANTISPAM;
            unset($_SESSION['errorAntispam']);
        }
        elseif($_SESSION['OrderSuccess'])
        {
	    ?><div id="textDisplayed_Green"><?php
            echo ORDERSUCCESS;
            unset($_SESSION['OrderSuccess']);
        }
        elseif($_SESSION['errorSendingMail'])
        {
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORSENDMAIL;
            unset($_SESSION['errorSendingMail']);
        }
	elseif($_SESSION['selectProduct'])
	{
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORPRODUCT;
            unset($_SESSION['selectProduct']);
	}
	elseif($_SESSION['errorGodfather'])
	{
	    ?><div id="textDisplayed_Red"><?php
            echo ERRORGODFATHER;
            unset($_SESSION['errorGodfather']);
	}
        unset($_SESSION['justTryToOrder']);
	?></div><?php
    }