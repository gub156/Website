<?php
// Template version: 1.0.1 - 10.02.14
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    require("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo GODFATHER_TITLE;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index">AST</a></h1></div>
		<?php include("01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<?php include("01_includes/banner.html"); ?>
		<div id="sidebar_container">
		    <?php include("01_includes/sidebar.php"); ?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include("01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo WHAT_IS_A_GODFATHER_TITLE;?></h1>
		    <p><?php echo WHAT_IS_A_GODFATHER_TEXT; ?></p>
		    <h1><?php echo HOW_TO_BECOME_GODFATHER_TITLE; ?></h1>
		    <p><?php echo HOW_TO_BECOME_GODFATHER_TEXT; ?></p>
		    <h1><?php echo REGISTRATION; ?></h1>
		    <form action="godfather.php" method="post">
			<div class="form_settings">
			    <table CELLSPACING="25">
				<tr>
				    <td>
					<select required name="sexe">
					    <option value="E" selected> <?php echo SEXE.' - '.CHOOSE_IT; ?></option>
					    <option value="1"> <?php echo MALE; ?></option>
					    <option value="0"> <?php echo FEMALE; ?></option>
					</select>
				    </td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="name" placeholder="<?php echo NAME; ?>" value="<?php echo $_SESSION['name']; ?>"></td>
				    <td><input class="bluePolice" type="text" name="firstname" placeholder="<?php echo FIRSTNAME; ?>" value="<?php echo $_SESSION['firstname']; ?>"></td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="age" placeholder="<?php echo AGE; ?>" value="<?php echo $_SESSION['age']; ?>"></td>
				    <td><input class="bluePolice" type="text" name="street" placeholder="<?php echo STREET; ?>" value="<?php echo $_SESSION['street']; ?>"></td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="postcode" placeholder="<?php echo POSTCODE; ?>" value="<?php echo $_SESSION['postcode']; ?>"></td>
				    <td><input class="bluePolice" type="text" name="city" placeholder="<?php echo CITY; ?>" value="<?php echo $_SESSION['city']; ?>"></td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="email" placeholder="<?php echo EMAIL; ?>" value="<?php echo $_SESSION['email']; ?>"></td>
				    <td><input class="bluePolice" type="text" name="confirmEmail" placeholder="<?php echo EMAILCONFIRM; ?>" value="<?php echo $_SESSION['email']; ?>"></td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="canton" placeholder="<?php echo CANTON; ?>" value="<?php echo $_SESSION['canton']; ?>"></td>
				    <td>
					<select required name="land">
					    <option value="0" selected> <?php echo COUNTRY.' - '.CHOOSE_IT; ?></option>
					    <option value="CH"> <?php echo SWISS; ?></option>
					    <option value="L"> <?php echo LICHTENSTEIN; ?></option>
					    <option value="AU"> <?php echo AUSTRIA; ?></option>
					    <option value="DE"> <?php echo GERMANY; ?></option>
					</select>
				    </td>
				</tr>
				<tr></tr>
				<tr>
				    <td><input class="bluePolice" type="text" name="pseudo" placeholder="<?php echo PSEUDO; ?>" value="<?php echo $_SESSION['pseudo']; ?>"></td>
				</tr>
				<tr>
				    <td><input class="bluePolice" type="password" name="password" placeholder="<?php echo PASSWORD; ?>" value="<?php echo $_SESSION['password']; ?>"></td>
				    <td><input class="bluePolice" type="password" name="confirmPassword" placeholder="<?php echo PASSWORDCONFIRM; ?>" value="<?php echo $_SESSION['confirmPassword']; ?>"></td>
				</tr>
			    </table>
			    <p class="center"><input class="button" type="submit" name="register_send" value="<?php echo REGISTRATION;?>" /></p>
			</div>
		    </form>
                </div>
            </div>
            <?php include("01_includes/footer.php"); ?>
        </div>
        <?php include("01_includes/javascripts.html"); ?>
    </body>
</html>

<?php
    include("02_scripts/connectMysql.php");

    if(isset($_POST['register_send']))
    {
    //--- Sauvegarde des données utilisateur pour réafficher en cas d'erreur ---//
    	$_SESSION['name'] = htmlspecialchars($_POST['name']);
	$_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
	$_SESSION['age'] = htmlspecialchars($_POST['age']);
	$_SESSION['street'] = htmlspecialchars($_POST['street']);
	$_SESSION['postcode'] = htmlspecialchars($_POST['postcode']);
	$_SESSION['canton'] = htmlspecialchars($_POST['canton']);
	$_SESSION['city'] = htmlspecialchars($_POST['city']);
	$_SESSION['email'] = htmlspecialchars($_POST['email']);
	$_SESSION['confirmEmail'] = htmlspecialchars($_POST['confirmEmail']);
	$_SESSION['pseudo'] = htmlspecialchars($_POST['pseudo']);
	$_SESSION['password'] = htmlspecialchars($_POST['password']);
	$_SESSION['confirmPassword'] = htmlspecialchars($_POST['confirmPassword']);
	$_SESSION['sexe'] = $_POST['sexe'];
	$_SESSION['land'] = $_POST['land'];

	/*** Si tous les champs sont renseignés ***/
    	if(	$_SESSION['name'] != "" &&
		$_SESSION['firstname'] != "" &&
		$_SESSION['age'] != "" &&
		$_SESSION['street'] != "" &&
		$_SESSION['postcode'] != "" &&
		$_SESSION['canton'] != "" &&
		$_SESSION['city'] != "" &&
		$_SESSION['email'] != "" &&
		$_SESSION['confirmEmail'] != "" &&
		$_SESSION['pseudo'] != "" &&
		$_SESSION['password'] != "" &&
		$_SESSION['confirmPassword'] != "" &&
		$_SESSION['sexe'] != "E" &&
		$_SESSION['land'] != "0")
	{
	    /*** On controle que l'adresse email ne corresponde pas à une adresse déjà présente dans la DB ***/
	    $req=$bdd->prepare('SELECT email FROM userAccount')  or die(print_r($bdd->errorInfo()));
	    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
	    // On récupère les emails dans un tableau appelé listEmails.
	    $_SESSION['listEmails'] = $userbdd->fetch();
	    $req->closeCursor();
	    echo "<pre>";
	    print_r($_SESSION['listEmails']);
	    echo "</pre>";
	    echo test1;

	    /*** SI l'adresse email est déjé présente, on affiche un message d'erreur au visiteur ***/
	    if(in_array($_SESSION['email'], $_SESSION['listEmails'])) echo 'Adresse déjà présente';
	    /*** Sinon, on enregistre ces informations dans la base de données. ***/
	    else
	    {
	    	echo test2;
		/*** On enregistre les informations dans la base de données des utilisateurs ***/
		$godfatherName = 'GF_'.strtoupper(substr($_SESSION['name'],0,2)).date("ymd").strtoupper(substr($_SESSION['firstname'],0,2));
		$req = $bdd->prepare('INSERT INTO userAccount(gender, name, firstname, age, email, street, postcode, city, canton, country, pseudo, pseudo_min, password, GFName)
								    VALUES(:sexe, :name, :firstname, :age, :email, :street, :postcode, :city, :canton, :country, :pseudo, :pseudo_min, :password, :godfatherName)');
		$req->execute(array(
				    'sexe' => $_SESSION['sexe'],
				    'name' => $_SESSION['name'],
				    'firstname' => $_SESSION['firstname'],
				    'age' => $_SESSION['age'],
				    'email' => $_SESSION['email'],
				    'street' => $_SESSION['street'],
				    'postcode' => $_SESSION['postcode'],
				    'city' => $_SESSION['city'],
				     'canton' => $_SESSION['canton'],
				    'country' => $_SESSION['land'],
				    'pseudo' => $_SESSION['pseudo'],
				    'pseudo_min' => strtolower($_SESSION['pseudo']),
				    'password' => $_SESSION['password'],
				    'godfatherName' => $godfatherName
				    )) or die(print_r($req->errorInfo()));

		// Fin de la requête
		$req->closeCursor();
		/*** Envoi du mail au visiteur ***/
		$message = "Bonjour, \n\n";
		$message .= "Bienvenue dans le cercles des parrains REFAST.\n\n";
		$message .= "Votre nom de parrain est: ".$godfatherName.".\n\n";
		$message .= "Utilisez et distribuer celui-ci à vos contacts lors d'un achat sur notre site web.\n\n";
		$message .= "CONSEIL: Pour eviter un oubli, nous vous conseillons de faire les commandes de vos client vous-même\n\n";
		$message .= "IMPORTANT: Aucune crédit ne sera fait sur votre compte en cas d'oubli de votre part ou de la part d'un client d'inscrire votre nom de parrain.\n\n\n";
		$message .= "Cordialement,\n\n\n";
		$message .= "Votre team REFAST";

		$To = $_SESSION['email'];

		$Sujet = "Welcome in the club!";

		$headers="From: "."contact@refast-swiss.com"." ";
		$headers .='Reply-To: '."contact@refast-swiss.com"." ";
		$headers .='Content-Type: text/plain; charset="utf-8"'." ";
		$headers .='Content-Transfer-Encoding: 8bit';

		mail($To, $Sujet, $message, $headers);
	    }

	unset($_POST['register_send']);
	}
	else
	{
	    echo test3;
	    echo 'Champ manquant';
	}
    }
?>
