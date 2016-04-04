<?php
session_start();

function Redirige()
{
    $pos = strpos($_SESSION['URI'], '?');
    if($pos === false)
    {
	header("Location: ".$_SESSION['URI']);
	exit();
    }
    else
    {
    	$path = substr($_SESSION['file'], 0, -4);
	$variables = substr($_SESSION['URI'], $pos);
	$path .= $variables;
	header("Location: ".$path);
	exit();
    }
//--- Elimine les 4 derniers caractères des la chaîne (.php) ---//
    if($_SESSION['pageWithGetValue'])
    {
	if($_SESSION['articleNumber'] != "")
	{
	    $path = substr($_SESSION['file'], 0, -4);
	    $path .= '?product=';
	    $path .= $_SESSION['articleNumber'];
	}
    }
    else
    {
	if($_SESSION['file'] == "/08_errors/error_404.php") $path = substr($_SESSION['old_file'], 0, -4);
	else $path = substr($_SESSION['file'], 0, -4);
    }
    header("Location: ..$path");
    exit();
}

function DataLogger()
{/*
    require_once($_SESSION['backFile'].'09_libs/Classes/PHPExcel.php');
    require_once($_SESSION['backFile'].'09_libs/Classes/PHPExcel/Writer/Excel2007.php');
    require_once($_SESSION['backFile'].'09_libs/Classes/PHPExcel/IOFactory.php');
    include($_SESSION['backFile'].'02_scripts/connectMysql.php');

//--- Ici, on filtre les utilisateurs / IP que l'on ne veut pas logger ---//
    if(	strtolower($_SESSION['user']) == "gub156" ||
	strtolower($_SESSION['user']) == "alain" ||
	strtolower($_SESSION['user']) == "david" ||
	$_SERVER['REMOTE_ADDR'] == "46.14.157.177" ||
	$_SERVER['REMOTE_ADDR'] == "81.62.174.173" ||
	$_SERVER['REMOTE_ADDR'] == "199.64.72.253" ||
	$_SERVER['REMOTE_ADDR'] == "89.217.159.112" ||	// Grubenweg 6
	$_SERVER['REMOTE_ADDR'] == "89.217.147.238" ||	// Grubenweg 6
	$_SERVER['REMOTE_ADDR'] == "212.90.210.41")		// FRIUP Bureau
    {
    //--- Dans ce cas, on n'enregistre pas de données ---//
    }
    else
    {
    // Récupération de l'adresse IP, troncage de celle-ci (suppression des .), puis, conversion de l'IP en chiffre
	$dotted = preg_split( "/[.]+/", $_SERVER["REMOTE_ADDR"]);
	$ip = (double) ($dotted[0]*16777216)+($dotted[1]*65536)+($dotted[2]*256)+($dotted[3]);

    //Récupération du pays en fonction de l'adresse IP ($ip)
	$req = $bdd->prepare('SELECT COUNTRY_NAME FROM COUNTRY_NAME WHERE :value BETWEEN IP_FROM AND IP_TO');
	$req->execute(array(':value' => $ip)) or die(print_r($req->errorInfo()));

	$country = $req->fetch();
	$req->closeCursor();

    // Error reporting //
	error_reporting(E_ALL);
    // Include path //
	ini_set('include_path', ini_get('include_path').'9_libs/Classes/');

    // Open the Excel file //
	$inputFileName = '/home/www/30398ce4ba8eeca03558674547826581/data/Stats/DataLogger.xlsx';

    // Load $inputFileName to a PHPExcel Object  //
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

	$date = date("Y.m.d");
	$time = date("H:i:s");
	if(!isset($_SESSION['typeOfDevice']))
	{
	    $detectStartChar = strpos($_SERVER['HTTP_USER_AGENT'], '(')+1;
	    $detectSemicolonChar = strpos($_SERVER['HTTP_USER_AGENT'], ';')-$detectStartChar;
	    $_SESSION['typeOfDevice'] = substr($_SERVER['HTTP_USER_AGENT'], $detectStartChar, $detectSemicolonChar);
	}

	$browser = get_browser(null, true);
	$indexValue_Eval = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 1)->getValue();
	$flagEmailOverflow = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, 1)->getValue();
	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $indexValue_Eval, $date);
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $indexValue_Eval, $time);
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $indexValue_Eval, $_SERVER["REMOTE_ADDR"]);
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $indexValue_Eval, $country['COUNTRY_NAME']);
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $indexValue_Eval, $_SESSION['file']);
	if(!isset($_SERVER['HTTP_REFERER'])) $objPHPExcel->getActiveSheet()->SetCellValue('F' . $indexValue_Eval, 'Not available');
	else $objPHPExcel->getActiveSheet()->SetCellValue('F' . $indexValue_Eval, $_SERVER['HTTP_REFERER']);
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $indexValue_Eval, $browser['platform']);
	$objPHPExcel->getActiveSheet()->SetCellValue('H' . $indexValue_Eval, $_SESSION['typeOfDevice']);
	$objPHPExcel->getActiveSheet()->SetCellValue('I' . $indexValue_Eval, $_COOKIE['language']);

	$objPHPExcel->getActiveSheet()->SetCellValue('D1', ($indexValue_Eval + 1));
    //--- Si le nombre de lignes du fichier dépasse un certain quota, un mail est envoyé au webmaster ---//
    //--- pour "vider" le fichier de log, car celui-ci ralenti le chargement de chaque page web. ---//
	if($indexValue_Eval >= 2000 && $flagEmailOverflow == "")
	{
	    mail('webmaster@refast-swiss.com', 'Dataloger is over 2\'000 lines long', 'Please clear out the excel document to accelerate the website');
	    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Email sent');
	}

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$records = '/home/www/30398ce4ba8eeca03558674547826581/data/Stats/DataLogger.xlsx';
	$objWriter->save($records);
    }*/
}

function IPAddress2IPNumber($dotted)
{
    $dotted = preg_split( "/[.]+/", $dotted);

    $ip = (double) ($dotted[0]*16777216)+($dotted[1]*65536)+($dotted[2]*256)+($dotted[3]);

    return $ip;
}

function includeBanner($nameBanner)
{
    if($_SESSION['typeOfDevice'] != "iPhone")
    {
	?>
	<div class="slider">
	    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
		width="950" height="633" id="dewslider4" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="06_documents/Banner/dewslider.swf?xml=06_documents/Banner/<?php echo $nameBanner; ?>/dewslider_<?php echo $_COOKIE['language']; ?>.xml" />
		<param name="quality" value="high" /><embed src="06_documents/Banner/dewslider.swf?xml=06_documents/Banner/<?php echo $nameBanner; ?>/dewslider_<?php echo $_COOKIE['language']; ?>.xml" quality="high"
			width="950" height="633" name="dewslider4" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	    </object>
	</div>
	<?php
    }
    else
    {
    	?><img src="05_images/products/<?php echo $nameBanner."_noFlash"; ?>" align="center" alt="Show the book"/><?php
    }
}