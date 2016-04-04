<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = true; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");
?>
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo CHANGEINFO;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
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
		    <h1><?php echo CHANGEINFO;?></h1>
		    <?php echo MUSTNOTCHANGEALL; ?>
		    <br/><br/>
		    <form id="register" method="post" action="02_scripts/changeInfo.php" enctype="multipart/form-data">
			<div class="form_settings">
			    <p>
				<label for="title"><?php echo TITLE; ?></label><br/>
				<input type="text" class="bluePolice" id="title" name="title" tabindex="1" autofocus>
			    </p>
			    <p>
				<label for="name"><?php echo NAME; ?></label><br/>
				<input type="text" class="bluePolice" id="name" name="name" tabindex="2">
			    </p>
			    <p>
				<label for="firstname"><?php echo FIRSTNAME; ?></label><br/>
				<input type="text" class="bluePolice" id="firstname" name="firstname" tabindex="3">
			    </p>
			    <p>
				<label for="age"><?php echo AGE; ?></label><br/>
				<input type="date" class="bluePolice" id="age" name="age" tabindex="4">
				<?php echo FORMAT_DATE; ?>
			    </p>
			    <p>
				<label for="email"><?php echo EMAIL; ?></label><br/>
				<input type="email" class="bluePolice" id="email" name="email" tabindex="5">
			    </p>
			    <p>
				<label for="phone"><?php echo PHONE; ?></label><br/>
				<input type="tel" class="bluePolice" id="phone" name="phone" tabindex="6">
			    </p>
			    <p>
				<label for="street"><?php echo STREET; ?></label><br/>
				<input type="text" class="bluePolice" id="street" name="street" tabindex="7">
			    </p>
			    <p>
				<label for="postcode"><?php echo POSTCODE; ?></label><br/>
				<input type="text" class="bluePolice" id="postcode" name="postcode" tabindex="8">
			    </p>
			    <p>
				<label for="city"><?php echo CITY; ?></label><br/>
				<input type="text" class="bluePolice" id="city" name="city" tabindex="9">
			    </p>
			    <p>
				<label for="country"><?php echo COUNTRY; ?></label><br/>
				<input type="text" class="bluePolice" id="country" name="country" tabindex="10">
			    </p>
			    <p>
				<label for="avatar"><?php echo AVATAR; ?></label><br>
				<input type="file" class="bluePolice" id="avatar" name="avatar_new" tabindex="11">
				<?php echo SPECAVATAR; ?>
			    </p>
			    <br/>
			    <p>
				<label for="password"><?php echo PASSWORD; ?></label><br>
				<input type="password" class="bluePolice" id="password" name="password" tabindex="12">
			    </p>
			    <p>
				<label for="passwordconfirm"><?php echo PASSWORDCONFIRM; ?></label><br>
				<input type="password" class="bluePolice" id="passwordconfirm" name="passwordconfirm" tabindex="13">
			    </p>
			    <br/>
			    <input class="button" type="submit" name="info_submitted" value="<?php echo SEND;?>" >
			</div>
		    </form>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html"); ?>
    </body>
</html>
