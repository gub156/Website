<?php
    session_start();
    if($_SESSION['beta'])
    {
	$light = '<a href="'.$_SESSION['backFile'].'../02_scripts/debugScript.php?value=0"><img src="'.$_SESSION['backFile'].'../05_images/green_point.png" alt="Green point"  height="10"></a>';
    }
    else
    {
	$light = '<a href="'.$_SESSION['backFile'].'../02_scripts/debugScript.php?value=1"><img src="'.$_SESSION['backFile'].'../05_images/red_point.png" alt="Red point" height="10"></a>';
    }
?>
<div class="sidebar">
    <h3><?php echo 'Debug Console'; ?></h3>
    <?php
	echo 'Beta: ' . $light . '<br/>';
	echo 'Test var: ' . $_SESSION['test'] . '<br/>';
	echo '<br/>Website version: ' . $_SESSION['websiteVersion'];
	echo '<br/>Last update: ' . $_SESSION['lastUpdateDate'];
	echo '<br/>Your IP: '.$_SERVER['REMOTE_ADDR'];
	echo '<br/>Number users: '.$_SESSION['numberUsers'][nb];
	echo '<br/>backFile: '.$_SESSION['backFile'];
	echo '<br/>Current page: '.$_SESSION['file'];
	echo '<br/>Last page: '.$_SESSION['oldFile'];
    ?>
</div>