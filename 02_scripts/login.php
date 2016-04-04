<?php
    session_start();
    $_SESSION['user'] = strtolower($_POST['Pseudo']);

// Si le nom d'utilisateur n'a pas été renseigné, on quitte le script
    if($_SESSION['user'] == "")
    {
	$_SESSION['accesGranted'] = false;	// Bloque l'accès aux zones réservées.
	$_SESSION['justTryToLog'] = true;	// Indique que la tentative de login vient
											// d'être faite.
        $file = $_SESSION['file'];			// Redirige l'utilisateur sur la dernière
        header("Location: ..$file");		// page visitée.
        exit();
    }

// Connexion à la base de données
    include("connectMysql.php");

//Récupération du mot de passe en rapport au nom d'utilisateur entré
    $req = $bdd->prepare('SELECT password FROM userAccount WHERE pseudo_min = ?');
    $req->execute(array($_SESSION['user'])) or die(print_r($req->errorInfo()));

    $password = $req->fetch();
    $req->closeCursor();

//Si le password stocké dans la base de données correspond au password
//entré, on autorise l'accès. Sinon, on affiche un message à l'utilisateur.
    if($password['password'] == $_POST['password'])
    {
        $_SESSION['accesGranted'] = true;    // Autorise l'accès aux zones réservées.
        $_SESSION['justTryToLog'] = true;    // Indique que la tentative de login vient d'être faite.
    //Acquisition des données en fonction du nom d'utilisateur
	$userbdd = $bdd->prepare('SELECT title, name, firstname, age, email, street, postcode, city, country, password, register_date, avatar, pseudo FROM userAccount WHERE pseudo_min = ?') or die(print_r($bdd->errorInfo()));
	$userbdd->execute(array($_SESSION['user'])) or die(print_r($userbdd->errorInfo()));

    //date("d/m/Y", strtotime($date1))
    // On récupère les informations de l'utilisateur dans un tableau appelé dataUser.
	$dataUser = $userbdd->fetch();

    /*** Acquisition du nombre d'utilisateurs enregistrés ***/
	$userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM userAccount') or die(print_r($bdd->errorInfo()));
	$userbdd->execute() or die(print_r($userbdd->errorInfo()));
    /*** On récupère le nombre de produits dans un tableau ***/
	$_SESSION['numberUsers'] = $userbdd->fetch();

	$userbdd->closeCursor();

        $_SESSION['title'] = $dataUser['title'];
        $_SESSION['name'] = $dataUser['name'];
        $_SESSION['firstname'] = $dataUser['firstname'];
        $_SESSION['age'] = DATE_FORMAT($dataUser['age'], "%d.%m.%y");
        $_SESSION['email'] = $dataUser['email'];
        $_SESSION['street'] = $dataUser['street'];
        $_SESSION['postcode'] = $dataUser['postcode'];
        $_SESSION['city'] = $dataUser['city'];
        $_SESSION['country'] = $dataUser['country'];
        $_SESSION['register_date'] = $dataUser['register_date'];
        $_SESSION['user'] = $dataUser['pseudo'];

        if( $_SESSION['user'] == "alain" OR
            $_SESSION['user'] == "david" OR
            $_SESSION['user'] == "Gub156")
            {
                $_SESSION['beta'] = true;
                $_SESSION['maintenance'] = false;
            }

        if($_SESSION['file'] == "/loginPage.php")
        {
            $file = $_SESSION['pageToDisplayAfterLogin'];		// Redirige l'utilisateur sur la dernière
            header("Location: ..$file");						// page visitée.
            exit();
        }
        $file = $_SESSION['file'];			// Redirige l'utilisateur sur la dernière
        header("Location: ..$file");		// page visitée.
        exit();
    }
    elseif($_POST['contact_submitted'])
    {
        $_SESSION['accesGranted'] = false;	// Bloque l'accès aux zones réservées.
        $_SESSION['justTryToLog'] = true;	// Indique que la tentative de login vient
											// d'être faite.
        $file = $_SESSION['file'];			// Redirige l'utilisateur sur la dernière
        header("Location: ..$file");		// page visitée.
        exit();
    }
?>