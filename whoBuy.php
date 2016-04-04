<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 0; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."02_scripts/connectMysql.php");
    include($_SESSION['backFile']."01_includes/head.html");

//Acquisition des données pour les produits
    $userbdd = $bdd->prepare('SELECT distributor, email, website, address FROM outlets ORDER BY distributor') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
// On récupère les informations de l'utilisateur dans un tableau appelé dataProduct.
    $_SESSION['outlets'] = $userbdd->fetchAll();
?>
    <meta name="robots" content="index, follow">
    <title><?php echo DISTRIBUTORS;?></title>
    </head>
    <body>
	<?php include($_SESSION['backFile']."01_includes/feedbackButton.php") ?>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="index">AST</a></h1></div>
		<?php include($_SESSION['backFile']."01_includes/menu.php"); ?>
	    </header>
	    <div id="site_content">
		<div id="textDisplayer">
		    <?php include($_SESSION['backFile']."01_includes/Display/texts.php");?>
		</div>
		<h1><?php echo DISTRIBUTORS;?></h1>
	    <?php // ICI PEUT ETRE AFFICHE UN MESSAGE A L'UTILISATEUR ?>
		<div class="fluid-wrapper">
		    <iframe src="https://mapsengine.google.com/map/u/0/embed?mid=zCLvzZpmA4CQ.kEkTEincNIBo" width="900" height="480"></iframe>
		</div>
		<table class="blue">
		    <tr>
			<th><?php echo DISTRIBUTORS; ?></th>
			<th><?php echo EMAIL_TEXT; ?></th>
			<th><?php echo WEBSITE; ?></th>
			<th><?php echo ADDRESS; ?></th>
		    </tr>
	    <?php
		$i = 0;
		foreach($_SESSION['outlets'] as &$tempData)
		{
		    if($i) echo '<tr class="lightBlue">';
		    else echo '<tr>';
			echo '<td>'.$tempData['distributor'].'</td>';
			echo '<td>'.$tempData['email'].'</td>';
			echo '<td><a href="http://'. $tempData['website'].'" onclick="window.open(this.href); return false;">'.$tempData['website'].'</a></td>';
			echo '<td>'.$tempData['address'].'</td>';
		    echo '</tr>';
		    $i =! $i;
		} ?>
		</table>
	    </div>
	    <?php include($_SESSION['backFile']."01_includes/footer.php"); ?>
	</div>
	<?php include($_SESSION['backFile']."01_includes/javascripts.html");
	include($_SESSION['backFile']."09_libs/functions.php");
	DataLogger();?>
    </body>
</html>