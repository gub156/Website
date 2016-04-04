   <?php
    session_start();
  /*  $url = ('05_images/Logos/'.$_GET['dwld']);
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'. basename($url) .'";');
	@readfile($url) OR die();*/
	
	
	$fichier = $_GET['dwld'];
	$chemin = '05_images/Logos/' . $fichier;

	header('Content-disposition: attachment; filename="' . $fichier . '"');
	header('Content-Type: application/force-download');
	header("Content-Type: application/octet-stream" );
	header("Content-Type: application/download" ); 
	header("Content-Transfer-Encoding: $type\n");
	header('Content-Length: '. filesize($chemin));
	header('Pragma: no-cache');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	readfile($chemin);
	
	$_SESSION['test'] = $chemin;
?>