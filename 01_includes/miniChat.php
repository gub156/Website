<div class="sidebar">
    <h3><?php echo GOLDEN_BOOK; ?></h3>
    <h4><?php echo LEAVE_YOUR_IMPRESS; ?></h4>
    <div class="news-container">
	<?php
	    if($_SESSION['accesGranted'])
	    { ?>
		<form action="../02_scripts/scriptMiniChat.php" method="post" align="center">
		    <div class="form_settings">
			<p>
			    <br /><textarea rows="4" cols="20" type="text" name="message" id="message" ></textarea><br /><br />
			    <input class="button" type="submit" value="Send" />
			</p>
		    </div>
		</form>
		<?php
	    }
	// Connexion à la base de données
	if($_SESSION['dossierRacine'])
	{
	    require("02_scripts/connectMysql.php");
	}
	else
	{
	    require("../02_scripts/connectMysql.php");
	}
	// Récupération des 10 derniers messages
	$reponse = $bdd->query('SELECT pseudo, message FROM miniChat ORDER BY id DESC LIMIT 0, 5');

	// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
	while($donnees = $reponse->fetch())
	{
	    echo '<p><strong>' .htmlspecialchars($donnees['pseudo']).'</strong>: ' .htmlspecialchars($donnees['message']).' </p>';
	}
	$reponse->closeCursor();
?>
	</div>
</div>

