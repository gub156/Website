<?php
    session_start(); ?>

    <nav>
	<div id="language_box">
	    <a href="<?php echo $_SESSION['backFile'];?>02_scripts/chooseLanguage.php?lang=de&amp;switchLang=true"><img src="<?php echo $_SESSION['backFile'];?>05_images/de.png" alt="lang_de"/></a>
	    <a href="<?php echo $_SESSION['backFile'];?>02_scripts/chooseLanguage.php?lang=fr&amp;switchLang=true"><img src="<?php echo $_SESSION['backFile'];?>05_images/fr.png" alt="lang_fr"/></a>
	    <?php if($_SESSION['beta'])echo '<a href="'.$_SESSION['backFile'].'02_scripts/chooseLanguage.php?lang=en&amp;switchLang=true"><img src="'.$_SESSION['backFile'].'05_images/en.png" alt="lang_en"/></a>'; ?>
	</div>
	<ul class="lavaLampWithImage" id="lava_menu">
	    <li<?php if($_SESSION['file'] == "/index.php") echo ' class="current"';?>><a href="<?php echo $_SESSION['backFile'];?>index"><?php echo HOMELINK;?></a></li>
	    <li<?php if($_SESSION['file'] == "/engineering.php") echo ' class="current"';?>><a href="<?php echo $_SESSION['backFile'];?>engineering"><?php echo ENGINEERING;?></a></li>
	    <li<?php if($_SESSION['file'] == "/products.php" ||
			$_SESSION['file'] == "/book_info.php" ||
			$_SESSION['file'] == "/order.php" ||
			$_SESSION['file'] == "/orderConfirm.php" ||
			$_SESSION['file'] == "/thank_you.php") echo ' class="current"';?>><a href="<?php echo $_SESSION['backFile'];?>products"><?php echo PRODUCTLINK;?></a></li>
	    <li<?php if($_SESSION['file'] == "/about_us.php") echo ' class="current"';?>><a href="<?php echo $_SESSION['backFile'];?>about_us"><?php echo ABOUTUSLINK;?></a></li>
	    <li<?php if($_SESSION['file'] == "/contact.php") echo ' class="current"';?>><a href="<?php echo $_SESSION['backFile'];?>contact"><?php echo CONTACTUSLINK;?></a></li>
	</ul>
    </nav>
?>