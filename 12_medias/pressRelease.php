<?php
// Template version: 1.0.1 - 10.02.14
    if(!isset($_SESSION)) session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 1; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("../02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    include($_SESSION['backFile']."02_scripts/connectMysql.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."01_includes/head.html");

//--- Acquisition des données en fonction du produit ($_SESSION['productID']) et de la langue ($_COOKIE['language']) ---//
    $userbdd = $bdd->prepare(' SELECT ID, publishingDate, language, articleTitle, postingDate, link FROM pressRelease
				ORDER BY publishingDate') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
// On récupère les informations de l'utilisateur dans un tableau appelé article.
    $_SESSION['article'] = $userbdd->fetchAll();
//--- Acquisition du nombre de produits ---//
    $userbdd = $bdd->prepare('SELECT COUNT(*) AS nb FROM pressReview') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
    /*** On récupère le nombre de produits dans un tableau ***/
	$numberArticle = $userbdd->fetch();

    $userbdd->closeCursor();

    $pathArticle = "02_pressRelease/";
?>
    <meta name="robots" content="index, follow">
    <title><?php echo PRESS_RELEASE;?></title>
    </head>
    <body>
		<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="<?php echo $_SESSION['backFile'];?>index">AST</a></h1></div>
		<?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<?php include($_SESSION['backFile']."01_includes/banner.html"); ?>
		<div id="sidebar_container">
		<?php
		    include($_SESSION['backFile']."01_includes/sidebar/medias.php");
		    include($_SESSION['backFile']."01_includes/sidebar.php");
		?>
		</div>
		<div id="content">
		    <div id="textDisplayer">
			<?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
		    </div>
		    <h1><?php echo PRESS_RELEASE;?></h1>
		    <table>
			<tr>
			    <th><?php echo PUBLISHING_DATE; ?></th>
			    <th><?php echo ARTICLE_TITLE;?></th>
			    <th><?php echo LANGUAGE;?></th>
			</tr>
			<?php
			for($i = 0; $i < count($_SESSION['article']); $i++)
			{
			if($w) echo '<tr class="lightBlue">';
			else echo '<tr>'; ?>
			    <td><?php echo date("d.m.y", strtotime($_SESSION['article'][$i]['publishingDate']));?></td>
			    <td><a href="<?php echo 'article.php?link='.urlencode($pathArticle.$_SESSION['article'][$i]['link']).'&article='.urlencode($_SESSION['article'][$i]['articleTitle']);?>"><?php echo $_SESSION['article'][$i]['articleTitle'];?></a></td>
			    <td><?php echo $_SESSION['article'][$i]['language'];?></td>
			</tr>
			<?php	$w =! $w;
			} ?>
		    </table>
		</div>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>
