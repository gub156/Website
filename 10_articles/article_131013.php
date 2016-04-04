<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 1;
    include("../02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");
?>
    <meta name="robots" content="index, follow">
    <title><?php echo ARTICLE_131013_TITLE;?></title>
    </head>
    <body>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="../index.php">AST</a></h1></div>
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
		    <h1><?php echo ARTICLE_131013_TITLE;?></h1>
		    <?php echo ARTICLE_131013_CONTENT;?>
					<br/><br/><br/><br/>
		    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
			    codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
			    width="670" height="447" id="dewslider4" align="middle">
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="movie" value="../06_documents/Banner/dewslider.swf?xml=../06_documents/Banner/Articles/article_131013.xml" />
			<param name="quality" value="high" /><embed src="../06_documents/Banner/dewslider.swf?xml=../06_documents/Banner/Articles/article_131013.xml" quality="high"
				width="670" height="447" name="dewslider4" align="middle" allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		    </object>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html"); ?>
    </body>
</html>
