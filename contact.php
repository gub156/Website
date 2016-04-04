<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include("02_scripts/maintenance.php");
    include("02_scripts/chooseLanguage.php");
    include("01_includes/head.html");

    $number_1 = rand(1, 9);
    $number_2 = rand(1, 9);
    $answer = substr(md5($number_1+$number_2),5,10);
    $typeAddress = $_GET['typeAddress'];
?>
	<meta name="robots" content="noindex, nofollow">
	<title>Contact</title>
    </head>
    <body>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	<header>
	    <div id="logo"><h1>REF<a href="index.php">AST</a></h1></div>
	    <?php include("01_includes/menu.php"); ?>
	</header>
	<div id="site_content">
	    <?php include("01_includes/banner.html"); ?>
	    <div id="sidebar_container">
		<?php
		    include("01_includes/sidebar.php");
		?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include("01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo CONTACTUSLINK;?></h1>
		    <form id="contact" action="contact.php" method="post">
			<div class="form_settings">
			    <label for="address"><?php echo REASON_OF_THE_MESSAGE; ?></label><br/>
			    <select name="address" id="address" autofocus tabindex="1">
				<option value="sales@refast-swiss.com" <?php if($typeAddress == 1 || $_SESSION['to'] == "sales@refast-swiss.com") echo "selected";?>><?php echo GENERAL_SALES;?></option>
				<option value="webmaster@refast-swiss.com" <?php if($typeAddress == 2 || $_SESSION['to'] == "webmaster@refast-swiss.com") echo "selected";?>><?php echo GENERAL_WEBSITE;?></option>
				<option value="contact@refast-swiss.com" <?php if($typeAddress == 3 || $_SESSION['to'] == "contact@refast-swiss.com") echo "selected";?>><?php echo GENERAL_REQUEST;?></option>
				<option value="support@refast-swiss.com" <?php if($typeAddress == 4 || $_SESSION['to'] == "support@refast-swiss.com") echo "selected";?>><?php echo TECHNICAL_SUPPORT; ?></option>
			    </select>
			    <br/><br/>
			    <label for="product"><?php echo CONCERNED_PRODUCT; ?></label><br/>
			    <select name="product" id="product" tabindex="2">
				<option value="None" ><?php echo NONE_MASC; ?></option>
				<option value="Book DE" ><?php echo BOOKDE; ?></option>
				<option value="Book FR" ><?php echo BOOKFR; ?></option>
				<option value="USB2RF" ><?php echo USB2RF; ?></option>
				<option value="Consulting" ><?php echo CONSULTING.' / '.SERVICES; ?></option>
			    </select>
			    <br/><br/>
			    <p>
				<label for="name"><?php echo NAME.", ".FIRSTNAME; ?></label><br/>
				<input class="bluePolice" id="name" type="text" name="your_name" value="<?php echo $_SESSION['yourname']; ?>" tabindex="2">
			    </p>
			    <p>
				<label for="email"><?php echo EMAIL; ?></label><br/>
				<input class="bluePolice" id="email" type="text" name="your_email" value="<?php echo $_SESSION['youremail']; ?>" tabindex="3">
			    </p>
			    <p>
				<label for="message"><?php echo MESSAGE; ?></label><br/>
				<textarea class="bluePolice" id="message" rows="5" cols="50" name="your_message" tabindex="4"><?php echo $_SESSION['yourmessage']; ?></textarea>
			    <p/>
			    <p>
				<label for="antispam"><?php echo ANTISPAM; ?></label><br/>
				<?php echo $number_1; ?> + <?php echo $number_2; ?> = ?<input type="text" class="bluePolice" id="antispam" name="user_answer" tabindex="5"><input type="hidden" name="answer" value="<?php echo $answer; ?>" />
			    </p>
			    <input class="button" type="submit" name="contact_submitted" value="<?php echo SEND;?>" tabindex="6">
			</div>
		    </form>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php include("01_includes/javascripts.html");
	include("09_libs/functions.php");
	DataLogger();?>
    </body>
</html>

<?php
    if(isset($_POST['contact_submitted']))
    {
	$_SESSION['to'] = $_POST['address'];
	$subject = 'Somebody contact us';
	function email_is_valid($email)
	{
	    return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$email);
	}
	if (!email_is_valid($to))
	{
	    echo '<p style="color: red;">You must set-up a valid (to) email address before this contact page will work.</p>';
	}
	$_SESSION['youremail'] = trim(htmlspecialchars($_POST['your_email']));
	$_SESSION['yourname'] = stripslashes(strip_tags($_POST['your_name']));
	$_SESSION['yourmessage'] = stripslashes(strip_tags($_POST['your_message']));
	$contact_name = "Name: ".$_SESSION['yourname']."\n"."\n";
	$product = "Concerned product: ".$_POST['product']."\n"."\n";
	$message_text = "Message: ".$_SESSION['yourmessage']."\n"."\n"."\n"."\n";
	$serverName = 'Server name: '.$_SERVER['SERVER_NAME']."\n";
	$browser = 'Type of browser: '.$_SERVER['HTTP_USER_AGENT']."\n";
	$IPAddress = 'IP Address: '.$_SERVER['REMOTE_ADDR']."\n";
	$addrHost = 'Address host: '.$_SERVER['REMOTE_HOST']."\n";

	$user_answer = trim(htmlspecialchars($_POST['user_answer']));
	$answer = trim(htmlspecialchars($_POST['answer']));
	$message = $contact_name.$product.$message_text.$serverName.$IPAddress.$addrHost.$browser;
	$headers = "From: ".$_SESSION['youremail'];
	if (email_is_valid($_SESSION['youremail']) && $_SESSION['yourname'] != "" && $_SESSION['yourmessage'] != "" && substr(md5($user_answer),5,10) === $answer)
	{
	    mail($_SESSION['to'],$subject,$message,$headers);
	    $_SESSION['yourname'] = '';
	    $_SESSION['youremail'] = '';
	    $_SESSION['yourmessage'] = '';
	    $_SESSION['contactMailHasBeenSend'] = true;
	}
	else
	{
	    $_SESSION['contactMailHasNotBeenSend'] = true;
	}
	header("Location: contact.php");
	exit;
    }
?>