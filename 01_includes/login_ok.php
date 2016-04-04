<?php
    session_start();
    // Connexion à la base de données
	include($_SESSION['backFile']."02_scripts/connectMysql.php");
    //Acquisition des données en fonction du nom d'utilisateur
    $userbdd = $bdd->prepare('SELECT name, firstname, age, email, street, postcode, city, country, password, register_date, avatar FROM userAccount WHERE pseudo = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_SESSION['user'])) or die(print_r($userbdd->errorInfo()));

    // On récupère les informations de l'utilisateur dans un tableau appelé dataUser.
    $dataUser = $userbdd->fetch();

    $userbdd->closeCursor();
?>
<div class="sidebar">
    <h3><?php echo WELCOMELOGIN. $_SESSION['user'];?></h3>
    <div class="doubleLine">
	<?php
	    if($dataUser['avatar'] != "")
	    {
		echo '<img src="'.$_SESSION['backFile'].'05_images/avatars/' . $dataUser['avatar'] . '" height="100px" align="center" alt="Avatar" >';
	    }
	?>
	<br />
	<a class="standardLink" href="<?php echo $_SESSION['backFile'];?>myAccount" ><?php echo MYACCOUNT; ?></a><br />
	<a class="standardLink" href="<?php echo $_SESSION['backFile'];?>../02_scripts/logout" ><?php echo DISCONNECTME; ?></a>
    </div>
</div>