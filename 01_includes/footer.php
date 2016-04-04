<?php session_start(); ?>
<footer>
    <p><a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>index"><?php echo HOMELINK;?></a> | <a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>about_us"><?php echo ABOUTUSLINK;?></a> | <a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>contact"><?php echo CONTACTUSLINK;?></a></p>
    <table>
	<tr>
	    <td width="25%">
		<div class="fb-like" data-href="https://www.facebook.com/pages/Refast/585082378180713?fref=ts" data-width="10" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
	    </td>
	    <td width="25%">
		<script src="//platform.linkedin.com/in.js" type="text/javascript">
		<?php
		switch($_COOKIE['language'])
		{
		    case "fr":	?>lang: fr_FR<?php;
				break;
		    case "de":	?>lang: de_DE<?php;
				break;
		    case "en": 	?>lang: en_US<?php;
				break;
		    default:	?>lang: en_US<?php;
		}?>
		</script>
		<script type="IN/Share" data-url="www.refast-swiss.com" data-counter="right"></script>
	    </td>
	    <td width="25%">
		<div class="g-plusone" data-annotation="inline" data-width="200" data-href="http://plus.google.com/101426367354753269900"></div>
	    </td>
	    <td width="25%">
		<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a>
	    </td>
	</tr>
    </table>
    <p>
	&copy; 2013 REFAST. All Rights Reserved. |
	<a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>sitemap.php"><?php echo SITEMAP;?></a> |
	<a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>CGV"><?php echo CGV;?></a> |
	<a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>impressum"><?php echo IMPRESSUM;?></a>
	<?php if($_SESSION['beta'])
	{?>
	    | <a class="standardLink" href="<?php echo $_SESSION['backFile']; ?>medias"><?php echo PRESS_MEDIA;?></a>
	    <?php
	}?>
    </p>
</footer>
