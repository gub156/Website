<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include_once("02_scripts/start.php");
    include_once($_SESSION['backFile']."02_scripts/maintenance.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include_once($_SESSION['backFile']."01_includes/head.html");
?>
	<meta name="robots" content="index, follow">
	<title><?php echo PASS_FORGET;?></title>
    </head>
    <body>
	<?php include($_SESSION['backFile']."01_includes/feedbackButton.php") ?>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
		<?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<?php include($_SESSION['backFile']."01_includes/banner.html"); ?>
		<div id="sidebar_container">
		    <?php include($_SESSION['backFile']."01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo PASS_FORGET;?></h1>
		    <p><?php echo CLIC_TO_SEND_MAIL_RESET_PASS; ?></p>
		    <form action="passwordForgotten.php" method="post">
			<div class="form_settings">
			    <p><input class="bluePolice" type="text" name="email" placeholder="<?php echo EMAIL;?>" ></p>
			    <p><input class="button" type="submit" name="contact_submitted" value="<?php echo SEND_NEW_PASSWORD;?>" ></p>
			</div>
		    </form>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html"); ?>
    </body>
</html>


<?php
// Si formulaire a été envoyé
if($_POST['contact_submitted'])
{
	include($_SESSION['backFile']."02_scripts/connectMysql.php");

	$_SESSION['tryToRecoverPassword'] = true;

	// Sauvegarde de l'adresse email dans une variable temporaire
	$mailAddress = htmlspecialchars($_POST['email']);
	$_SESSION['test'] = $mailAddress;
	// Champ email a été rempli?
	if($mailAddress != "")
	{
		//Récupération du mot de passe en rapport à l'adresse email entrée
		$req = $bdd->prepare('SELECT password FROM userAccount WHERE email = ?');
		$req->execute(array($mailAddress)) or die(print_r($req->errorInfo()));
		$password = $req->fetch();
		$req->closeCursor();

		// Une correspondance dans la base de données a été trouvée avec l'email entré par l'utilisateur
		if($password != "")
		{
			// Génération d'un nouveau mot de passe
			$newPass = uniqid();
			// Update du mot de passe dans la base de données
			$req = $bdd->prepare('UPDATE userAccount SET password = :password WHERE email = :email');
            $req->execute(array('password' => $newPass, 'email' => $mailAddress)) or die(print_r($req->errorInfo()));

			// Envoi du mail contenant le nouveau mot de passe à l'utilisateur
			$emailWM = "webmaster@refast-swiss.com";
			$headers = "From :" . $emailWM . "\n";
			$headers = 'Content-type: text/html; charset=iso-8859-1'."\n";
			$subject = PASS_FORGET_MAIL;
			$message = MESSAGE_PASS_FORGOTTEN;
			$message .= "<b>$newPass</b>";
			$message .= MESSAGE_PASS_FORGOTTEN_1;
			if(mail($mailAddress, $subject, $message, $headers))
			{
				$_SESSION['mailSuccessfulySended'] = true;
				header('Location: index.php');
				exit();
			}
			else // Si une erreur est survenue durant l'envoi du mail
			{
				$_SESSION['errorDuringSendingPasswordMail'] = true;
				header('Location: passwordForgotten.php');
				exit();
			}
		}
		else // Si l'adresse entrée par l'utilisateur n'a pas été trouvée dans la DB
		{
			$_SESSION['noEmailFoundInDB'] = true;
			header('Location: passwordForgotten.php');
			exit();
		}
	}
	else // Si le champ d'email n'a pas été rempli
	{
		$_SESSION['emailAddressNotFilled'] = true;
		header('Location: passwordForgotten.php');
		exit();
	}
}