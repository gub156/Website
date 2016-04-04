<div class="sidebar">
    <h3><?php echo LOGINTITLE;?></h3>
    <form id="login" action="<?php echo $_SESSION['backFile'];?>02_scripts/login.php" method="post">
	<div class="form_login">
	    <p><input class="pseudo" type="text" name="Pseudo" placeholder="Pseudo" /></p>
	    <p><input class="password" type="password" name="password" placeholder="*****" /></p>
	    <p><input class="button" type="submit" name="contact_submitted" value="<?php echo SEND;?>" /></p>
	    <p><a class="standardLink" href="<?php echo $_SESSION['backFile'];?>passwordForgotten" ><?php echo PASS_FORGET;?></a></p>
	    <p><a class="standardLink" href="<?php echo $_SESSION['backFile'];?>register" ><?php echo REGISTER;?></a></p>
	</div>
    </form>
</div>


