<?php
    session_start();
    if(	strtolower($_SESSION['user']) == "gub156" ||
	strtolower($_SESSION['user']) == "alain" ||
	strtolower($_SESSION['user']) == "david" ||
	$_SERVER['REMOTE_ADDR'] == "46.14.157.177" ||
	$_SERVER['REMOTE_ADDR'] == "89.217.159.112" ||
	$_SERVER['REMOTE_ADDR'] == "212.90.210.41")		// FriUp bureau
    {
	include("debugConsol.php");
    }
    if($_SESSION['accesGranted'])
    {
	include("login_ok.php");
	include("latest_news.html");
	//include("miniChat.php");
	include("useful_links.php");
    }
    else
    {
	include("login.php");
	include("latest_news.html");
	include("newsletter.php");
	//include("miniChat.php");
	include("useful_links.php");
    }
?>