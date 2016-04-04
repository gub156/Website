<?php
session_start(); //Démarrage de la session php

if(!isset($_SESSION['page_book']))
{
	$_SESSION['language_feedback'] = $_POST['language'];
	switch($_SESSION['language_feedback'])
	{
		case "Francais":	setcookie('language', 'fr', time() + 365*24*3600, "/", NULL, false, true);
							break;
		case "Deutsch":		setcookie('language', 'de', time() + 365*24*3600, "/", NULL, false, true);
							break;
		case "English":		setcookie('language', 'en', time() + 365*24*3600, "/", NULL, false, true);
							break;
	}
	$_SESSION['page_book'] = 1;
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   1 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 1)
{
	$_SESSION['categorie'] = $_POST['categorie'];
	switch($_SESSION['categorie'])
	{
		case "evaluation":	$_SESSION['page_book'] = 2;
							break;
		case "mistake":		$_SESSION['page_book'] = 3;
							break;	
		case "divers":		$_SESSION['page_book'] = 4;
	}
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   2 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 2)
{
	$_SESSION['structure'] = $_POST['structure'];
	$_SESSION['content'] = $_POST['content'];
	$_SESSION['readability'] = $_POST['readability'];
	$_SESSION['divers_evaluation'] = htmlspecialchars($_POST['divers_evaluation']);
    $_SESSION['page_book'] = 5;
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   3 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 3)
{
	$_SESSION['edition'] = $_POST['edition'];
	$_SESSION['page_book_error'] = htmlspecialchars($_POST['page_book']);
	$_SESSION['divers_error'] = htmlspecialchars($_POST['divers_error']);
    $_SESSION['page_book'] = 5;
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   4 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 4)
{
	$_SESSION['divers'] = htmlspecialchars($_POST['divers']);
    $_SESSION['page_book'] = 5;
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   5 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 5)
{
	require_once('../09_libs/Classes/PHPExcel.php');
    require_once('../09_libs/Classes/PHPExcel/Writer/Excel2007.php');
    require_once('../09_libs/Classes/PHPExcel/Writer/HTML.php');
    require_once('../09_libs/Classes/PHPExcel/IOFactory.php');
    require_once('../09_libs/Classes/PHPExcel/Writer/PDF.php');
    require_once('../09_libs/Classes/PHPExcel/Cell.php');
    require_once('../09_libs/Classes/PHPExcel/Cell/DataType.php');
	
	// Error reporting //
    error_reporting(E_ALL);
	
	// Include path //
    ini_set('include_path', ini_get('include_path').'9_libs/Classes/');
	
	// Open the Excel file //
	chmod("../../data/00_Feedbacks/List_feedbacks.xlsx", 0777);
    $inputFileName = '../../data/00_Feedbacks/List_feedbacks.xlsx';

    // Load $inputFileName to a PHPExcel Object  //
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			
	$date = date("d.m.Y");
	$to = 'contact@refast-swiss.com,';
	$subject = 'Somebody contact us';
	$message = "Product: Book" . "\n" . "Type of feedback: ". $_SESSION['categorie'];
	$headers = "From: ". $_SERVER["REMOTE_ADDR"];
	
	$_SESSION['sexe'] = $_POST['sexe'];
	$_SESSION['age'] = htmlspecialchars($_POST['age']);
	$_SESSION['pseudo'] = htmlspecialchars($_POST['pseudo']);
	if($_SESSION['categorie'] == "evaluation")
	{				
		$objPHPExcel->setActiveSheetIndex(0);
		$indexValue_Eval = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, 1)->getValue();
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $indexValue_Eval, $date);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $indexValue_Eval, $_SESSION['language_feedback']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $indexValue_Eval, $_SESSION['structure']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $indexValue_Eval, $_SESSION['content']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $indexValue_Eval, $_SESSION['readability']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $indexValue_Eval, $_SESSION['divers_evaluation']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $indexValue_Eval, $_SESSION['sexe']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $indexValue_Eval, $_SESSION['age']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $indexValue_Eval, $_SESSION['pseudo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J' . $indexValue_Eval, $_SERVER["REMOTE_ADDR"]);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', ($indexValue_Eval + 1));
	}
	elseif($_SESSION['categorie'] == "mistake")
	{		
		$objPHPExcel->setActiveSheetIndex(1);
		$indexValue_Mistake = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, 1)->getValue();
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $indexValue_Mistake, $date);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $indexValue_Mistake, $_SESSION['language_feedback']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $indexValue_Mistake, $_SESSION['edition']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $indexValue_Mistake, $_SESSION['page_book_error']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $indexValue_Mistake, $_SESSION['divers_error']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $indexValue_Mistake, $_SESSION['sexe']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $indexValue_Mistake, $_SESSION['age']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $indexValue_Mistake, $_SESSION['pseudo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $indexValue_Mistake, $_SERVER["REMOTE_ADDR"]);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', ($indexValue_Mistake + 1));
	}
	elseif($_SESSION['categorie'] == "divers")
	{
		$objPHPExcel->setActiveSheetIndex(2);
		$indexValue_Divers = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, 1)->getValue();
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $indexValue_Divers, $date);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $indexValue_Divers, $_SESSION['language_feedback']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $indexValue_Divers, $_SESSION['divers']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $indexValue_Divers, $_SESSION['sexe']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $indexValue_Divers, $_SESSION['age']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $indexValue_Divers, $_SESSION['pseudo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $indexValue_Divers, $_SERVER["REMOTE_ADDR"]);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', ($indexValue_Divers + 1));
	}
	
	$objPHPExcel->setActiveSheetIndex(0);
	// Save as Excel File //
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $records = '../../data/00_Feedbacks/List_feedbacks.xlsx';
    $objWriter->save($records);
	
    $_SESSION['page_book'] = 6;
   // mail($to,$subject,$message,$headers);
	header("Location: ../_pages_feedback/book.php");
	exit();
}
//--------------------------------------------------------------------------------------//
//									P A G E   6 
//--------------------------------------------------------------------------------------//
elseif($_SESSION['page_book'] == 6)
{
	header("Location: ../../index.php");
	exit();
}
?>