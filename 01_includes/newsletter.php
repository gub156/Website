<div class="sidebar">
    <h3><?php echo NEWSLETTER; ?></h3>
    <form action="01_includes/newsletter.php" method="post">
		<div class="form_login">
			<p><input class="bluePolice" type="text" name="gender" placeholder="M., Herr, Prof,..."></p>
			<p><input class="bluePolice" type="text" name="name" placeholder="<?php echo NAME; ?>"></p>
			<p><input class="bluePolice" type="text" name="email" placeholder="<?php echo EMAIL; ?>"></p>
			<p><input class="button" type="submit" value="<?php echo SUBSCRIBE; ?>" name="register_newsletter" /></p>
	    </div>
	</form>
</div>

<?php 

	session_start(); //Démarrage de la session php
	
	if($_POST['register_newsletter'])
    {
		$_SESSION['tryToSubscrNewsletter'] = 1;
		include("../02_scripts/start.php");
		include("../02_scripts/connectMysql.php");
	
		if($_POST['gender'] != "" && $_POST['name'] != "" && $_POST['email'])
		{
			$req = $bdd->prepare('INSERT INTO newsletter(gender, name, email) VALUES(:gender, :name, :email)');
			$req->execute(array('gender' => $_POST['gender'],
								'name' => $_POST['name'],
								'email' => $_POST['email'],
								)) or die(print_r($req->errorInfo()));
		/*** Fin de la requête ***/
			$req->closeCursor();
			$_SESSION['SubscrSuccessfulyNewsletter'] = 1;
		}
		else $_SESSION['SubscrSuccessfulyNewsletter'] = 0;
		$path = $_SESSION['old_file'];
		header("Location: ..$path");
		exit();
	}
?>