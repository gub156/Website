<?php

	session_start(); //Démarrage de la session php

	if($_POST['register_newsletter_book'])
    {
		$_SESSION['tryToSubscrNewsletter'] = 1;
		include("../02_scripts/start.php");
		include("../02_scripts/connectMysql.php");

		if($_POST['email'])
		{
			$message_TXT =  'Somebody has been registered for books updates.

							Email: '.$_POST['email'].'
							Language: '.$_COOKIE['language'].'

							Thank you


							Your webmaster';
			$headers = "From: \"Webmaster REFAST\"<webmaster@refast-swiss.com>"."\n";
			$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
			$headers .='Content-Transfer-Encoding: 8bit';
			$message = $message_TXT."\n";
			$object = "New website user registered for book update";

			if(mail('contact@refast-swiss.com', $object, $message, $headers)) setcookie("newBook",1,time() + 365*24*3600, "/", NULL, false, true);
			$_SESSION['SubscrSuccessfulyNewsletter'] = 1;
		}
		else $_SESSION['SubscrSuccessfulyNewsletter'] = 0;
	}
	else
	{
	    setcookie("newBook",1,time() + 365*24*3600, "/", NULL, false, true);
	}
	$path = $_SESSION['old_file'];
	header("Location: ../..$path");
	exit();
?>