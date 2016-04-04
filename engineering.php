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
?>
    <meta name="robots" content="index, follow">
    <title><?php echo ENGINEERING;?></title>
    </head>
    <body>
	<?php include("01_includes/feedbackButton.php") ?>
	<?php include_once("analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index">AST</a></h1></div>
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
		    <h1><?php echo ENGINEERING;?></h1>
		    <?php echo ENGINEERING_DESRC; ?>
		    <?php echo
			'<ul>
			    <li><a href="#form">'.SEND_YOUR_PROJECT.'</a></li>
			    <li><a href="contact">'.CONTACT_PAGE.'</a></li>
			    <li><a href="#direct">'.DIRECT_CONTACT.'</a></li>
			</ul>';
		    ?>
		    <h1><?php echo WHAT_WE_ARE_OFFERING; ?></h1>
		    <?php echo WHAT_WE_ARE_OFFERING_DESCR; ?>
		    <span id="form"><h1><?php echo ASK_FOR_AN_OFFER;?></h1></span>

		    <form action="02_scripts/engineeringScript.php" method="post" enctype="multipart/form-data">
			<table>
			    <tr>
				<th><h3><?php echo GENERAL_INFORMATIONS;?></h3></th>
				<th><h3><?php echo PROJECT_INFORMATIONS; ?></h3></th>
			    </tr>
			    <tr>
				<td>
				    <div class="form_settings">
					<p>
					    <label for="company"><?php echo COMPANY; ?></label><br/>
					    <input class="bluePolice" id="company" type="text" name="company" value="<?php echo $_SESSION['company']; ?>" tabindex="1">
					</p>
					<p>
					    <label for="name"><?php echo '*'.NAME; ?></label><br/>
					    <input class="bluePolice" id="name" type="text" name="name" value="<?php echo $_SESSION['name']; ?>" tabindex="2">
					</p>
					<p>
					    <label for="firstname"><?php echo '*'.FIRSTNAME; ?></label><br/>
					    <input class="bluePolice" id="firstname" type="text" name="firstname" value="<?php echo $_SESSION['firstname']; ?>" tabindex="3">
					</p>
					<p>
					    <label for="email"><?php echo '*'.EMAIL; ?></label><br/>
					    <input class="bluePolice" id="email" type="email" name="email" value="<?php echo $_SESSION['email']; ?>" tabindex="4">
					</p>
					<p>
					    <label for="email"><?php echo WEBSITE; ?></label><br/>
					    <input class="bluePolice" id="website" type="url" name="website" value="<?php if($_SESSION['website'] != "") echo $_SESSION['website']; else echo "http://www."; ?>" tabindex="5">
					</p>
					<p>
					    <label for="phone"><?php echo '*'.PHONE; ?></label><br/>
					    <input class="bluePolice" id="phone" type="tel" name="phone" value="<?php echo $_SESSION['phone']; ?>" tabindex="6">
					<p/>
					<p>
					    <label for="mobile"><?php echo MOBILE; ?></label><br/>
					    <input class="bluePolice" id="mobile" type="tel" name="mobile" value="<?php echo $_SESSION['mobile']; ?>" tabindex="7">
					<p/>
				    </div>
				</td>
				<td>
				    <div class="form_settings">
					<p>
					    <label for="projectname"><?php echo '*'.PROJECT_NAME; ?></label><br/>
					    <input class="bluePolice" id="projectname" type="text" name="projectname" value="<?php echo $_SESSION['projectname']; ?>" tabindex="8">
					</p>
					<p>
					    <?php echo '*'.CATEGORY; ?><br/>
					    <div class="form_checkbox">
						<label for="category_private"><?php echo "Private"; ?></label>
						<input class="bluePolice" id="category_private" type="radio" name="category" value="Private" <?php if($_SESSION['category'] == "Private") echo 'checked'; ?> tabindex="9">
						<label for="category_professional"><?php echo "Professional"; ?></label>
						<input class="bluePolice" id="category_professional" type="radio" name="category" value="Professional" <?php if($_SESSION['category'] == "Professional") echo 'checked'; ?> tabindex="10">
					    </div>
					</p>
					<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
					<p>
					    <label for="data"><?php echo DATA. ' (max 10Mo, format zip, rar, pdf)'; ?></label><br/>
					    <input class="bluePolice" id="data" type="file" name="newData" tabindex="11">
					<p/>
					<p>
					    <label for="description"><?php echo '*'.DESCRIPTION; ?></label><br/>
					    <textarea class="bluePolice" id="message" rows="5" cols="50" name="description" tabindex="12"><?php echo $_SESSION['description']; ?></textarea>
					<p/>
				    </div>
				</td>
			    </tr>
			</table>
			<div class="form_settings">
			    <div class="center">
				<p>
				    <label for="antispam"><?php echo ANTISPAM; ?></label><br/>
				    <?php echo $number_1; ?> + <?php echo $number_2; ?> = ?<input type="text" class="bluePolice" id="antispam" name="user_answer" tabindex="13"><input type="hidden" name="answer" value="<?php echo $answer; ?>" />
				</p>
				<p>
				    <span id="direct"><input class="button" type="submit" name="sendProject" value="<?php echo SEND;?>" tabindex="14"></span>
				</p>
			    </div>
			</div>
		    </form>
		    <h1><?php echo DIRECT_CONTACT; ?></h1>
		    <table>
			<tr>
			    <td><img src="05_images/AboutUs/REFAST_Alain_small" alt="REFAST Alain pict"></td>
			    <td><img src="05_images/AboutUs/REFAST_Julien_small" alt="REFAST Julien pict"></td>
			</tr>
			<tr>
			    <td>A. Staub (Deutsch)</td>
			    <td>J. Rebetez (Français)</td>
			</tr>
			<tr>
			    <td>079/487.15.18</td>
			    <td>078/850.50.26</td>
			</tr>
			<tr>
			    <td><a href="mailto:alain.staub@refast-swiss.com">alain.staub@refast-swiss.com</a></td>
			    <td><a href="mailto:julien.rebetez@refast-swiss.com">julien.rebetez@refast-swiss.com</a></td>
			</tr>
		    </table>
		</div>
	    </div>
	    <?php include("01_includes/footer.php"); ?>
	</div>
	<?php
	include("01_includes/javascripts.html");
	include("09_libs/functions.php");
	DataLogger();
	?>
    </body>
</html>