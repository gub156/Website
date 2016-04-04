<?php
    session_start();

// Connexion à la base de données
    include("connectMysql.php");

// Enregistrement des entrées de l'utilisateur dans des variables temporaires
    $_SESSION['title'] = htmlspecialchars($_POST['title']);
    $_SESSION['name'] = htmlspecialchars($_POST['name']);
    $_SESSION['firstname'] = htmlspecialchars($_POST['firstname']);
    $_SESSION['age'] = htmlspecialchars($_POST['age']);
    $_SESSION['email'] = htmlspecialchars($_POST['email']);
    $_SESSION['phone'] = htmlspecialchars($_POST['phone']);
    $_SESSION['street'] = htmlspecialchars($_POST['street']);
    $_SESSION['postcode'] = htmlspecialchars($_POST['postcode']);
    $_SESSION['city'] = htmlspecialchars($_POST['city']);
    $_SESSION['country'] = htmlspecialchars($_POST['country']);
    $_SESSION['pseudo'] = htmlspecialchars($_POST['pseudo']);
    $_SESSION['pseudoMin'] = strtolower($_SESSION['pseudo']);
    $_SESSION['password'] = htmlspecialchars($_POST['password']);
    $_SESSION['passwordconfirm'] = htmlspecialchars($_POST['passwordconfirm']);
    $_SESSION['godfather'] = $_POST['godfather'];

// Initialisation des variables d'erreur
    $_SESSION['PasswordNotSame'] = false;
    $_SESSION['NotAllFieldsAreFilled'] = false;
    $_SESSION['AccountHasBeenCreated'] = false;
    $_SESSION['PictureTooBig'] = false;
    $_SESSION['FILENOTUPLOADED'] = false;

// Autres initialisations
    $maxsize = 500000;
    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
    $chemin_destination = '../05_images/avatars/';

// Permettra d'afficher un message à l'utilisateur
    $_SESSION['TryToRegister'] = true;

// On contrôle tout dabord les mots de passe.
// - Ils doivent être semblable
// - Doivent être remplis
    if($_SESSION['password'] == "" || ($_SESSION['password'] != $_SESSION['passwordconfirm']))
    {
	$_SESSION['PasswordNotSame'] = true;
	header('Location: ../register.php');
	exit();
    }

// On contrôle ensuite que tous les champs obligatoires soient bien remplis.
    if(	$_SESSION['title'] == "" OR
	$_SESSION['name'] == "" OR
	$_SESSION['firstname'] == "" OR
	$_SESSION['age'] == "" OR
	$_SESSION['email'] == "" OR
	$_SESSION['street'] == "" OR
	$_SESSION['postcode'] == "" OR
	$_SESSION['country'] == "" OR
	$_SESSION['pseudo'] == "")
    {
	$_SESSION['NotAllFieldsAreFilled'] = true;
	header('Location: ../register.php');
	exit();
    }

// On teste maintenant la partie avatar.
// On commence par contrôler qu'un fichier est bien disponible
    if($_FILES['avatar']['error'] == 0)
    {
    // On contrôle ensuite que la taille du fichier uploadé ne dépasse pas la limite
	if($_FILES['avatar']['size'] > $maxsize)
	{
	    $_SESSION['PictureTooBig'] = true;
	    header('Location: ../register.php');
	    exit();
	}
    // Contrôle du format de l'image envoyée
    //1. strrchr renvoie l'extension avec le point (« . »).
    //2. substr(chaine,1) ignore le premier caractère de chaine.
    //3. strtolower met l'extension en minuscules.
	$extension_upload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.')  ,1));
	if ( in_array($extension_upload,$extensions_valides) )
	{
	    $extension = strtolower(strrchr($_FILES['avatar']['name'], '.'));	// Donne uniquement l'extension (.png)
	    $filename = $_SESSION['name'] . '_' . $_SESSION['firstname'] . '_' . $_SESSION['pseudo']; // Donne uniquement le nouveau nom de l'avatar (nom_prénom_pseudo)
	    $path = strtolower($filename . $extension);				// Donne le nom du fichier (nom_prénom_pseudo.xxx)
	    $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin_destination . $path);
	    if (!$resultat)
	    {
		$_SESSION['FileNotUploaded'] = true;
		header('Location: ../register.php');
		exit();
	    }
	}
	else
	{
	    $_SESSION['WrongFormatImage'] = true;
	    header('Location: ../register.php');
	    exit();
	}
    }
    elseif($_FILES['avatar']['error'] != 4)
    {
	$_SESSION['FileNotUploaded'] = true;
	header('Location: ../register.php');
	exit();
    }
// Si le client désire devenir parrain, on lui attribue un nom, puis on lui enverra un email en fin d'inscription
    $date = date("Y-m-d");
    if($_SESSION['godfather'])
    {
	$godfatherName = 'GF_'.strtoupper(substr($_SESSION['name'],0,2)).date("ymd").strtoupper(substr($_SESSION['firstname'],0,2));
	$GFSince = date("Y-m-d");
    }
    else $godfatherName = NULL;
// Si les tests ci-dessus ont fonctionnés, on enregistre les données dans la DB.
    $req = $bdd->prepare('INSERT INTO userAccount(title, name, firstname, age, email, phone,  street, postcode, city, country, pseudo, pseudo_min, password, avatar, register_date, GFSince, GFName)
			    VALUES(:title, :name, :firstname, :age, :email, :phone, :street, :postcode, :city, :country, :pseudo, :pseudo_min, :password, :avatar, :register_date, :gfsince, :godfather)');
    $req->execute(array(
			'title' => $_SESSION['title'],
			'name' => $_SESSION['name'],
			'firstname' => $_SESSION['firstname'],
			'age' => $_SESSION['age'],
			'email' => $_SESSION['email'],
			'phone' => $_SESSION['phone'],
			'street' => $_SESSION['street'],
			'postcode' => $_SESSION['postcode'],
			'city' => $_SESSION['city'],
			'country' => $_SESSION['country'],
			'pseudo' => $_SESSION['pseudo'],
			'pseudo_min' => $_SESSION['pseudoMin'],
			'password' => $_SESSION['password'],
			'avatar' => $path,
			'register_date' => $date,
			'gfsince' => $_SESSION['GFSince'],
			'godfather' => $godfatherName
			)) or die(print_r($req->errorInfo()));

// Fin de la requête
    $req->closeCursor();

    $_SESSION['AccountHasBeenCreated'] = true;
    $_SESSION['user'] = $_SESSION['pseudo'];
    $_SESSION['accesGranted'] = true;
// Envoi du mail au visiteur s'il s'est inscrit en tant que parrain
    if($_SESSION['godfather'])
    {
    	$message = "Bonjour, \n\n";
	$message .= "Bienvenue dans le cercles des parrains REFAST.\n\n";
	$message .= "Votre nom de parrain est: ".$godfatherName.".\n\n";
	$message .= "Utilisez et distribuer celui-ci à vos contacts lors d'un achat sur notre site web.\n\n";
	$message .= "IMPORTANT: Aucune crédit ne sera fait sur votre compte en cas d'oubli de votre part ou de la part d'un client d'inscrire votre nom de parrain.\n\n";
	$message .= "CONSEIL: Pour eviter un oubli, nous vous conseillons de faire les commandes de vos client vous-même\n\n\n";
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
    header('Location: ../myAccount.php');
    exit();

?>