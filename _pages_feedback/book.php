<?php
    session_start(); //Démarrage de la session php
    $_SESSION['dossierRacine'] = 1; //Permet de situer la page dans l'arborescence
    include("../02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta name="description" content="Formelbuch Feedback" />
    <meta name="keywords" content="Feedback, formelbuch, refast" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="../00_css/style_feedback.css" />
    <script type="text/javascript" src="../07_javascript/hideShowElement.js"></script>
    <?php
	if(!isset($_SESSION['page_book']))
	{ ?>
	    <meta http-equiv="content-language" content="en-US" />
	<?php }
	else
	{
	    switch($COOKIE['language'])
	    {
		case "fr":	?><meta http-equiv="content-language" content="fr-CH" /><?php;
				break;
		case "de":	?><meta http-equiv="content-language" content="de-CH" /><?php;
				break;
		case "en":	?><meta http-equiv="content-language" content="en-US" /><?php;
				break;
		default:	?><meta http-equiv="content-language" content="en-US" /><?php;
				break;
	    }
	} ?>
</head>


<title>REFAST - Technical Book Feedback</title>

<body onload="GereControle('chkb_10', 'submit');">
    <div id="main">
	<header>
	    <div id="logo"><h1>REF<a href="../index.php">AST</a></h1></div>
	</header>
	<div id="site_content">
	    <div id="content">
		<h1>Technical Book Feedback</h1>
	    <?php
	    if(!isset($_SESSION['page_book']))
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p>Choose your language.</p>
			<p>
			    <select name="language" size="1">
				<option value="English">English</option>
				<option value="Deutsch" selected>Deutsch</option>
				<option value="Francais">Français</option>
			    </select>
			</p>
			<p><input class="submit" type="submit" name="language_choose" value="Send" /></p>
		    </div>
		</form>
		<?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   1
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 1)
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><?php echo CHOOSECATEGORIE; ?></p>
			<p>
			    <select name="categorie" size="1">
				<option selected value="evaluation"><?php echo EVALUATION; ?></option>
				<option value="mistake"><?php echo MISTAKE; ?></option>
				<option value="divers"><?php echo DIVERS; ?></option>
			    </select>
			</p>
			<p><input class="submit" type="submit" name="language_choose" value="<?php echo SEND;?>" /></p>
		    </div>
		</form> <?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   2
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 2)
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><?php echo STRUCTURBOOK; ?></p>
			<p>
			    <select name="structure" size="1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option selected value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			    </select>
			</p>
			<p><?php echo CONTENTBOOK; ?></p>
			<p>
			    <select name="content" size="1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option selected value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			    </select>
			</p>
			<p><?php echo READABILITYBOOK; ?></p>
			<p>
			    <select name="readability" size="1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option selected value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			    </select>
			</p>
			<p><?php echo DIVERS; ?></p>
			<p><textarea class="contact textarea" rows="5" cols="50" name="divers_evaluation" ></textarea></p>
			<p><input class="submit" type="submit" name="language_choose" value="<?php echo SEND;?>" /></p>
		    </div>
		</form> <?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   3
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 3)
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><?php echo VERSIONBOOK; ?></p>
			<p>
			    <select name="edition" size="1">
				<option selected value="edition1">1</option>
				<option value="edition2">2</option>
				<option value="edition3">3</option>
			    </select>
			</p>
			<p><?php echo PAGEBOOK; ?></p>
			<p><input class="contact" type="text" name="page_book" /></p>
			<p><?php echo ERRORBOOK; ?></p>
			<p><textarea class="contact textarea" rows="5" cols="50" name="divers_error" ></textarea></p>
			<p><input class="submit" type="submit" name="language_choose" value="<?php echo SEND;?>" /></p>
		    </div>
		</form> <?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   4
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 4)
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><?php echo DIVERS; ?></p>
			<p><textarea class="contact textarea" rows="5" cols="50" name="divers" ></textarea></p>
			<p><input class="submit" type="submit" name="language_choose" value="<?php echo SEND;?>" /></p>
		    </div>
		</form> <?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   5
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 5)
	    { ?>
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><?php echo WHATSEXE; ?></p>
			<p>
			    <select name="sexe" size="1">
				<option value="male"><?php echo MALE; ?></option>
				<option value="female"><?php echo FEMALE; ?></option>
			    </select>
			</p>
			<p><?php echo WHATAGE; ?></p><p><input class="contact" type="text" name="age" /></p>
			<p><?php echo PSEUDO; ?></p><p><input class="contact" type="text" name="pseudo" /></p>
			<label style="color: red;"><input style="horizontal-align:middle;" type="checkbox" id="chkb_10" onClick="GereControle('chkb_10', 'submit');"/><br/><?php echo LEGALBOOK;?></label>
			<p><input class="submit" id="submit" type="submit" name="language_choose" value="<?php echo SEND; ?>" /></p>
		    <div>
		</form> <?php
	    }
//--------------------------------------------------------------------------------------//
//	P A G E   6
//--------------------------------------------------------------------------------------//
	    elseif($_SESSION['page_book'] == 6)
	    { ?>
		<p><?php echo THANKFEEDBACK; ?><br /><br /></p><br />
		<form id="feedback" action="../02_scripts/feedback_book.php" method="post">
		    <div class="form_settings">
			<p><input class="submit" id="submit" type="submit" name="language_choose" value="<?php echo BACK; ?>" /></p>
		    </div>
		</form><?php
	    } ?>
	</div>
    </div>
    <?php include($_SESSION['backFile']."01_includes/footer.php");
    include($_SESSION['backFile']."09_libs/functions.php");
    DataLogger();?>
</div>

  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript" src="../04_js/jquery.min.js"></script>
  <script type="text/javascript" src="../04_js/jquery.easing.min.js"></script>
  <script type="text/javascript" src="../04_js/jquery.lavalamp.min.js"></script>
  <script type="text/javascript" src="../04_js/jquery.kwicks-1.5.1.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#images').kwicks({
        max : 600,
        spacing : 2
      });
    });
  </script>
  <script type="text/javascript">
    $(function() {
      $("#lava_menu").lavaLamp({
        fx: "backout",
        speed: 700
      });
    });
  </script>
</body>
</html>
