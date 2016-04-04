<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = true; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    // Connexion à la base de données
    include($_SESSION['backFile']."02_scripts/connectMysql.php");
    //Acquisition des données en fonction du nom d'utilisateur
    $userbdd = $bdd->prepare('SELECT title, name, firstname, age, email, phone, street, postcode, city, country, password, register_date, avatar, GFSince, GFName FROM userAccount WHERE pseudo = ?') or die(print_r($bdd->errorInfo()));
    $userbdd->execute(array($_SESSION['user'])) or die(print_r($userbdd->errorInfo()));

    // On récupère les informations de l'utilisateur dans un tableau appelé dataUser.
    $dataUser = $userbdd->fetch();

    $userbdd->closeCursor();

    include($_SESSION['backFile']."02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");

    $_SESSION['title'] = $dataUser['title'];
    $_SESSION['name'] = $dataUser['name'];
    $_SESSION['firstname'] = $dataUser['firstname'];
    $_SESSION['age'] = $dataUser['age'];
    $_SESSION['email'] = $dataUser['email'];
    $_SESSION['phone'] = $dataUser['phone'];
    $_SESSION['street'] = $dataUser['street'];
    $_SESSION['postcode'] = $dataUser['postcode'];
    $_SESSION['city'] = $dataUser['city'];
    $_SESSION['country'] = $dataUser['country'];
    $_SESSION['avatar'] = $dataUser['avatar'];
    $_SESSION['register_date'] = $dataUser['register_date'];
    $_SESSION['GFName'] = $dataUser['GFName'];
    $_SESSION['GFSince'] = $dataUser['GFSince'];
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo MYACCOUNT;?></title>
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
		    <h1><?php echo MYACCOUNT;?></h1>
		    <div class="doubleLine">
			<?php echo '<h3>'.MYINFORMATIONS.'</h3>'; ?><br/>
			<?php
			    if($_SESSION['avatar'] == "")
			    {
				echo'<span class="left"><a href="changeInfo" class="button">'.ADDAVATAR.'</a></span><br/><br/>';
			    }
			    else
			    { ?>
				<table>
				    <tr>
					<td>
					    <span class="left"><?php echo '<img src="05_images/avatars/' . $_SESSION['avatar'] . '" height="100px" alt="Avatar">'; ?></span>
					</td>
					<td>
					    <span class="left"><a href="02_scripts/deletePicture.php" class="button"><?php echo DELETEPICTURE;?></a></span>
					</td>
				    </tr>
				</table>
				<?php
			    } ?>
			<span><?php echo TITLE_SHORT.': '.$_SESSION['title'];?></span><br/>
			<span><?php echo NAME . ': '.$_SESSION['name']; ?></span><br/>
			<span><?php echo FIRSTNAME. ': '.$_SESSION['firstname']; ?></span><br/>
			<span><?php echo AGE. ': '.$_SESSION['age']; ?></span><br/>
			<span><?php echo EMAIL. ': '.$_SESSION['email']; ?></span><br/>
			<span><?php echo PHONE. ': '.$_SESSION['phone']; ?></span><br/>
			<span><?php echo STREET. ': '.$_SESSION['street']; ?></span><br/>
			<span><?php echo POSTCODE. ': '.$_SESSION['postcode']; ?></span><br/>
			<span><?php echo CITY. ': '.$_SESSION['city']; ?></span><br/>
			<span><?php echo COUNTRY. ': '.$_SESSION['country']; ?></span><br/>
			<span><?php echo MEMBERSINCE. ': '.$_SESSION['register_date']; ?></span><br/>
			<?php
			if($_SESSION['GFName'] != "")
			{ ?>
			    <br/>
			    <?php echo '<h3>'.SPONSORING.'</h3>'; ?>
			    <span><?php echo GODFATHER_SINCE. ': '.$_SESSION['GFSince']; ?></span><br/>
			    <span><?php echo GODFATHER_NAME_ACCOUNT. ': '.$_SESSION['GFName']; ?></span><br/><br/><br/>
			    <?php
			}
			else
			{
			    echo '<br/><br/><br/>';
			} ?>

		    </div>
		    <div>
			<a href="changeInfo.php" class="button"><?php echo CHANGEINFO;?></a>
		    </div>
		    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		    <a class="standardLink" href="02_scripts/deleteAccount.php"><?php echo DELETEACCOUNT; ?></a>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
