<?php
session_start();
if(!$_SESSION['beta'])
{
	define('TEXT_CGV', "Actuellement indisponible");
}
else
{
	define('TEXT_CGV', "
		<h1>CGV - Conditions générales de vente</h1>
		Conditions générales de vente de l'entreprise REFAST, appelé ci-dessous fournisseur.<br/>
		Etat au 27 janvier 2014<br/><br/>
		<h3>Validité des CGV</h3>
		Les conditions générales de vente sont fondées sur le droit suisse et sont valables à l'intérieur de la Suisse, si les parties les reconnaissent catégoriquement ou tacitement.
		Des changements et clauses annexes sont effectifs uniquement s'ils sont confirmés par écrit par le fournisseur.<br/><br/>
		
		<h3>Prix et conditions de paiement</h3>
		
		Tous les prix s'entendent en francs suisse. Des changements de prix restent réservés.<br/><br/>
		Le client est tenu de payer dans les 30 jours après livraison.<br/><br/>
		
		<h3>Livraison</h3>
		Le récepteur porte tous les frais d'expédition occasionnels sur une nouvelle livraison, si ce dernier a transmis de fausses informations lors de la commande.<br/><br/>
		
		<h3>Exécution du contrat</h3>
		
		<h3>Garantie</h3>
		
		<h3>Réserve de propriété</h3>
		
		<h3>Validité</h3>
		Des erreurs, informations erronées et des changements de prix restent réservés.<br/><br/>
		
		<h3>For juridique</h3>
		Le for juridique se trouve à Fribourg, Suisse. Le Droit suisse est applicable.
		
		
		
		
		");
}
?>