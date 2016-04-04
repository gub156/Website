<?php
    session_start();
    $addressError = "preorderconfirm";
    $addressOK = "thank_you";
    include($_SESSION['backFile']."connectMysql.php");
    if($_POST['confirm_send'])
    {
    //--- L'utilisateur doit accepter les CGV ---//
	if(!$_POST['cgv'])
	{
	    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	    $_SESSION['errorCGV'] = true; // Checkbox pas remplie
	    header('Location: ../'.$addressError);
	    exit();
	}
    //--- Si l'utilisateur s'est inscrit à la newsletter, on enregistre son nom, son email et son titre dans la base de données "newsletter" ---//
	if($_POST['newsletter'])
	{
	    $req = $bdd->prepare('INSERT INTO newsletter(gender, name, email) VALUES(:gender, :name, :email)');
	    $req->execute(array('gender' => $_SESSION['title'], 'name' => $_SESSION['name'], 'email' => $_SESSION['email'],)) or die(print_r($req->errorInfo()));
	//--- Fin de la requête ---//
	    $req->closeCursor();
	}
    //--- Enregistrement des informations de la commande dans une base de données ---//
	$req = $bdd->prepare('	INSERT INTO Preorders(product, qty, price, tva, date, company, title, name, firstname, street, city, zip, country, email, remark)
				VALUES(:product, :qty, :price, :tva, :date, :company, :title, :name, :firstname, :street, :city, :zip, :country, :email, :remark)');
	$req->execute(array(	'product' => $_SESSION['articleRef'],
				'qty' => $_SESSION['quantity_product'],
				'price' => $_SESSION['total'],
				'tva' => $_SESSION['tva'],
				'date' => $_SESSION['date'],
				'company' => $_SESSION['company'],
				'title' => $_SESSION['title'],
				'name' => $_SESSION['name'],
				'firstname' => $_SESSION['firstname'],
				'street' => $_SESSION['street'],
				'city' => $_SESSION['city'],
				'zip' => $_SESSION['zip'],
				'country' => $_SESSION['countryOrder'],
				'email' => $_SESSION['email'],
				'remark' => $_SESSION['remark'])) or die(print_r($req->errorInfo()));
    //--- Fin de la requête ---//
	$req->closeCursor();
    //--- Envoi de l'email a REFAST ---//
	$to = "alain.staub@refast-swiss.com";
	$subject = 'New preorder';
	$message = 'Date: '.$_SESSION['date']."\n".'
		    Company: '.$_SESSION['company'].'
		    Title: '.$_SESSION['title'].'
		    Name: '.$_SESSION['name'].'
		    Firstname: '.$_SESSION['firstname'].'
		    Street: '.$_SESSION['street'].'
		    ZIP, City: '.$_SESSION['zip']." ".$_SESSION['city'].'
		    Country: '.$_SESSION['countryOrder']."\n".'
		    Email: '.$_SESSION['email']."\n\n".'
		    Product: '.$_SESSION['articleRef'].'
		    Quantity: '.$_SESSION['quantity_product'].'
		    Price: '.$_SESSION['product']['unitCHF'].number_format($_SESSION['total'], 2, '.', '\'').'
		    TVA: '.$_SESSION['product']['unitCHF'].number_format($_SESSION['tva'], 2, '.', '\'')."\n".'
		    Remark: '.$_SESSION['remark'];
	if(!mail($to, $subject, $message))
	{
	    unset($_SESSION['quantity_product']);
	    unset($_SESSION['articleRef']);
	    unset($_SESSION['total']);
	    unset($_SESSION['tva']);
	    unset($_SESSION['remark']);
	    $_SESSION['checkOrder'] = 0;
	    unset($_POST['order_send']);

	    $_SESSION['justTryToOrder'] = true; // Tentative d'envoi
	    $_SESSION['errorCGV'] = true; // Checkbox pas remplie
	    header('Location: ../'.$addressError);
	    exit();
	}
    }
    header('Location: ../'.$addressOK);
    exit();
?>