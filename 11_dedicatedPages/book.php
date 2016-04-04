<?php
    session_start(); //Démarrage de la session php
    $pageWithLogin = false; // true = login nécessaire / false = accès à tous
    $_SESSION['dossierRacine'] = 1; //Permet de situer la page dans l'arborescence
    $_SESSION['pageWithGetValue'] = false; // Cette page reçoit une variable $_GET en paramètre. Nécessaire lors d'une redirection!!!
    include("../02_scripts/start.php");
    include($_SESSION['backFile']."02_scripts/maintenance.php");
    require($_SESSION['backFile']."02_scripts/chooseLanguage.php");
    include($_SESSION['backFile']."02_scripts/connectMysql.php");
    include($_SESSION['backFile']."01_includes/head.html");

//--- Acquisition des données pour les produits ---//
    $userbdd = $bdd->prepare('SELECT ID, date, chapter, reportNumber, reportNumberLink, failureLocation_1, failureLocationLink_1, failureDescription, failureDescriptionLink FROM errata_'.$_COOKIE['language'].' ORDER BY ID') or die(print_r($bdd->errorInfo()));
    $userbdd->execute() or die(print_r($userbdd->errorInfo()));
//--- On récupère les informations de l'utilisateur dans un tableau appelé dataProduct ---//
    $_SESSION['errata'] = $userbdd->fetchAll();
?>
    <meta name="robots" content="index, follow">
    <title><?php echo HOMELINK;?></title>
    </head>
    <body>
	<?php include($_SESSION['backFile']."01_includes/feedbackButton.php") ?>
	<?php include_once($_SESSION['backFile']."analyticstracking.php") ?>
	<div id="main">
	    <header>
		<div id="logo"><h1>REF<a href="../index">AST</a></h1></div>
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
		    <h1><?php echo TECHNICAL_BOOK;?></h1>
		    <h3>Feedback</h3>
		    <?php echo DID_YOU_FIND_AN_ERROR; ?>
		    <div class="center">
			<a href="<?php echo $_SESSION['backFile'];?>_pages_feedback/book.php" class="button"><?php echo FEEDBACK; ?></a>
		    </div>
		    <h3><?php echo ERRATA; ?></h3>
		    <?php echo ERRATA_TEXT; ?>
		    <table class="blue">
			<tr>
			    <th><?php echo DATE; ?></th>
			    <th><?php echo CONCERNED_CHAPTER; ?></th>
			    <th><?php echo REPORT_NUMBER; ?></th>
			    <th><?php echo FAILURE_LOCATION; ?></th>
			    <th><?php echo FAILURE_DESCRIPTION; ?></th>
			</tr>
		    <?php
			$w = 0;
			for($i = 0; $i < count($_SESSION['errata']); $i++)
			{
			    if($w) echo '<tr class="lightBlue">';
			    else echo '<tr>';
			    echo '<td class="width15pc">'.$_SESSION['errata'][$i]['date'].'</td>';
			    echo '<td class="width15pc">'.$_SESSION['errata'][$i]['chapter'].'</td>';
			    if($_SESSION['errata'][$i]['reportNumberLink'] == "") echo '<td class="width20pc">'.$_SESSION['errata'][$i]['reportNumber'].'</td>';
			    else echo '<td class="width20pc"><a href="../'.$_SESSION['errata'][$i]['reportNumberLink'].'" target="_blank">'.$_SESSION['errata'][$i]['reportNumber'].'</a></td>';
			    if($_SESSION['errata'][$i]['failureLocationLink_1'] == "") echo '<td class="width20pc">'.$_SESSION['errata'][$i]['failureLocation_1'].'</td>';
			    else echo '<td class="width20pc"><a href="../'.$_SESSION['errata'][$i]['failureLocationLink_1'].'" target="_blank">'.$_SESSION['errata'][$i]['failureLocation_1'].'</a></td>';
			    if($_SESSION['errata'][$i]['failureDescriptionLink'] == "") echo '<td>'.$_SESSION['errata'][$i]['failureDescription'].'</td>';
			    else echo '<td><a href="../'.$_SESSION['errata'][$i]['failureDescriptionLink'].'" target="_blank">'.$_SESSION['errata'][$i]['failureDescription'].'</a></td>';
			    echo '</tr>';
			    $w =! $w;
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