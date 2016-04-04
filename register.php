<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo REGISTER;?></title>
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
		    <h1><?php echo REGISTER;?></h1>
		    <?php echo MUSTFILLALL; ?>
		    <br /><br />
		    <form id="register" method="post" action="02_scripts/registerInfo.php" enctype="multipart/form-data">
			<div class="form_settings">
			    <p>
				<label for="title"><?php echo "* ".TITLE; ?></label><br/>
				<input type="text" class="bluePolice" id="title" name="title" value="<?php echo $_SESSION['title'];?>" tabindex="1" required>
			    </p>
			    <p>
				<label for="name"><?php echo "* ".NAME; ?></label><br/>
				<input type="text" class="bluePolice" id="name" name="name" value="<?php echo $_SESSION['name'];?>" tabindex="2" required>
			    </p>
                           <p>
				<label for="firstname"><?php echo "* ".FIRSTNAME; ?></label><br/>
				<input type="text" class="bluePolice" id="firstname" name="firstname" value="<?php echo $_SESSION['firstname'];?>" tabindex="3" required>
			    </p>
			    <p>
				<label for="age"><?php echo "* ".AGE; ?></label><br/>
				<input type="date" class="bluePolice" id="age" name="age" value="<?php echo $_SESSION['age'];?>" tabindex="4" required>
				<?php echo FORMAT_DATE;?>
			    </p>
			    <p>
				<label for="email"><?php echo "* ".EMAIL; ?></label><br/>
				<input type="email" class="bluePolice" id="email" name="email" value="<?php echo $_SESSION['email'];?>" tabindex="5" required>
			    </p>
			    <p>
				<label for="phone"><?php echo PHONE; ?></label><br/>
				<input type="tel" class="bluePolice" id="phone" name="phone" value="<?php echo $_SESSION['phone'];?>" tabindex="6">
			    </p>
			    <p>
				<label for="street"><?php echo "* ".STREET; ?></label><br/>
				<input type="text" class="bluePolice" id="street" name="street" value="<?php echo $_SESSION['street'];?>" tabindex="7">
			    </p>
			    <p>
				<label for="postcode"><?php echo "* ".POSTCODE; ?></label><br/>
				<input type="text" class="bluePolice" id="postcode" name="postcode" value="<?php echo $_SESSION['postcode'];?>" tabindex="8">
			    </p>
			    <p>
				<label for="city"><?php echo "* ".CITY; ?></label><br/>
				<input type="text" class="bluePolice" id="city" name="city" value="<?php echo $_SESSION['city'];?>" tabindex="9">
			    </p>
			    <p>
				<label for="country"><?php echo "* ".COUNTRY; ?></label><br/>
				<input type="text" class="bluePolice" id="country" name="country" value="<?php echo $_SESSION['country'];?>" tabindex="10" required>
			    </p>
			    <p>
				<label for="pseudo"><?php echo "* ".PSEUDO; ?></label><br/>
				<input type="text" class="bluePolice" id="pseudo" name="pseudo" value="<?php echo $_SESSION['pseudo'];?>" tabindex="11" required>
			    </p>
			    <p>
				<label for="avatar"><?php echo AVATAR; ?></label><br/>
				<input type="file" id="avatar" name="avatar" tabindex="12">
				<?php echo SPECAVATAR; ?><br/>
			    </p>
			    <p>
				<label for="password"><?php echo "* ".PASSWORD; ?></label><br/>
				<input type="password" class="bluePolice" id="password" name="password" value="<?php echo $_SESSION['password'];?>" tabindex="13" required>
			    </p>
			    <p>
				<label for="passwordconfirm"><?php echo "* ".PASSWORDCONFIRM; ?></label><br/>
				<input type="password" class="bluePolice" id="passwordconfirm" name="passwordconfirm" value="<?php echo $_SESSION['passwordconfirm'];?>" tabindex="14" required>
			    </p>
			    <?php if($_SESSION['beta'])
			    { ?>
			    <p>
				<input type="checkbox" class="bluePolice" id="godfather" name="godfather" <?php if($_SESSION['godfather']) echo 'checked';?> tabindex="15"><label for="godfather"><?php echo BECOME_GODFATHER; ?></label><br/>
			    </p>
			    <?php
			    } ?>
			    <input class="button" type="submit" name="info_submitted" value="<?php echo SEND;?>" >
			</div>
		    </form>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
