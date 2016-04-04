<?php
    session_start();

    // Connexion à la base de données
    include("connectMysql.php");

    // Enregistrement des variables dans des variables temporaires
    $title = htmlspecialchars($_POST['title']);
    $name = htmlspecialchars($_POST['name']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $age = htmlspecialchars($_POST['age']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $street = htmlspecialchars($_POST['street']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $city = htmlspecialchars($_POST['city']);
    $country = htmlspecialchars($_POST['country']);
    $passwordconfirm = htmlspecialchars($_POST['passwordconfirm']);
    $password = htmlspecialchars($_POST['password']);
    $_SESSION['name_picture'] = $FILES['avatar_new']['name'];

    // Initialisation des variables d'erreurs
    $dataChanged = 0;
    $_SESSION['PictureTooBig'] = false;
    $_SESSION['FileNotUploaded'] = false;
    $_SESSION['WrongFormatImage'] = false;
    $_SESSION['infoTryToChange'] = false;
    $_SESSION['infoHasBeenChanged'] = false;

    // Autres variables destinées à l'enregistrement de l'avatar
    $maxsize = 500000;
    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
    $chemin_destination = '../05_images/avatars/';
    // Contrôle du format de l'image envoyée
    //1. strrchr renvoie l'extension avec le point (« . »).
    //2. substr(chaine,1) ignore le premier caractère de chaine.
    //3. strtolower met l'extension en minuscules.
    $extension_upload = strtolower(substr(strrchr($_FILES['avatar_new']['name'], '.')  ,1));

    if($_POST['title'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET title = :title WHERE pseudo = :pseudo');
	$req->execute(array('title' => htmlspecialchars($_POST['title']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;	// On change d'état cette variable pour le test de fin de connexion à la db.
    }
    if($_POST['name'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET name = :name WHERE pseudo = :pseudo');
	$req->execute(array('name' => htmlspecialchars($_POST['name']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;	// On change d'état cette variable pour le test de fin de connexion à la db.
    }
    else
    {
	$name = $_SESSION['name'];
    }
    if($_POST['firstname'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET firstname = :firstname WHERE pseudo = :pseudo');
	$req->execute(array('firstname' => htmlspecialchars($_POST['firstname']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    else
    {
	$firstname = $_SESSION['firstname'];
    }
    if($_POST['age'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET age = :age WHERE pseudo = :pseudo');
	$req->execute(array('age' => htmlspecialchars($_POST['age']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['email'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET email = :email WHERE pseudo = :pseudo');
	$req->execute(array('email' => htmlspecialchars($_POST['email']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['phone'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET phone = :phone WHERE pseudo = :pseudo');
	$req->execute(array('phone' => htmlspecialchars($_POST['phone']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['street'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET street = :street WHERE pseudo = :pseudo');
	$req->execute(array('street' => htmlspecialchars($_POST['street']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['postcode'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET postcode = :postcode WHERE pseudo = :pseudo');
	$req->execute(array('postcode' => htmlspecialchars($_POST['postcode']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['city'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET city = :city WHERE pseudo = :pseudo');
	$req->execute(array('city' => htmlspecialchars($_POST['city']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($_POST['country'] != "")
    {
	$req = $bdd->prepare('UPDATE userAccount SET country = :country WHERE pseudo = :pseudo');
	$req->execute(array('country' => htmlspecialchars($_POST['country']),
			    'pseudo' => $_SESSION['user']
			    )) or die(print_r($req->errorInfo()));
	$dataChanged = 1;
    }
    if($password != "")
    {
	// Compare les deux mots de passe. S'il y a une différence, renvoie l'utilisateur
	// sur la page myAccount et affiche un message.
	if($password != $passwordconfirm)
	{
	    $_SESSION['infoTryToChange'] = true;
	    $_SESSION['infoHasBeenChanged'] = false;
	    $_SESSION['PasswordNotSame'] = true;
	}
	else
	{
	    $req = $bdd->prepare('UPDATE userAccount SET password = :password WHERE pseudo = :pseudo');
	    $req->execute(array('password' => htmlspecialchars($password),
				'pseudo' => $_SESSION['user']
				)) or die(print_r($req->errorInfo()));
	    $dataChanged = 1;
	}
    }
    if($_FILES['avatar_new']['error'] == 0)
    {
	// On contrôle ensuite que la taille du fichier uploadé ne dépasse pas la limite
	if($_FILES['avatar_new']['size'] > $maxsize)
	{
	    $_SESSION['infoTryToChange'] = true;
	    $_SESSION['PictureTooBig'] = true;
	    $_SESSION['infoHasBeenChanged'] = false;
	}
	elseif(in_array($extension_upload,$extensions_valides))
	{
	    $extension = strtolower(strrchr($_FILES['avatar_new']['name'], '.'));	 // Donne uniquement l'extension (.png)
	    $filename = strtolower($name . '_' . $firstname . '_' . $_SESSION['user']); 	 // Donne uniquement le nouveau nom de l'avatar (nom_prénom_pseudo)
	    $path = $filename . $extension;				                // Donne le nom du fichier (nom_prénom_pseudo.xxx)
	    $resultat = move_uploaded_file($_FILES['avatar_new']['tmp_name'], $chemin_destination . $path);
	    if (!$resultat)
	    {
		$_SESSION['infoTryToChange'] = true;
		$_SESSION['FileNotUploaded'] = true;
		$_SESSION['infoHasBeenChanged'] = false;
	    }
	    else
	    {
		$req = $bdd->prepare('UPDATE userAccount SET avatar = :avatar WHERE pseudo = :pseudo');
		$req->execute(array('avatar' => $path,
				    'pseudo' => $_SESSION['user']
				    )) or die(print_r($req->errorInfo()));
		$dataChanged = 1;
	    }
	}
	else
	{
	    $_SESSION['infoTryToChange'] = true;
	    $_SESSION['WrongFormatImage'] = true;
	    $_SESSION['infoHasBeenChanged'] = false;
	}
    }
    elseif($_FILES['avatar_new']['error'] != 4)
    {
	$_SESSION['infoTryToChange'] = true;
	$_SESSION['FileNotUploaded'] = true;
	$_SESSION['infoHasBeenChanged'] = false;
    }
    // Fin de la requête: si un changement a eu lieu, on termine la requête.
    if($dataChanged == 1)
    {
	$req->closeCursor();
	$dataChanged = 0;
	// Pour le retour à la page myAccount affichera un message.
	$_SESSION['infoTryToChange'] = true;
	$_SESSION['infoHasBeenChanged'] = true;
    }
    header('Location: ../myAccount.php');
    exit();
?>