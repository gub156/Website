function GereControle(Controleur, Controle) 
{
	var objControleur = document.getElementById(Controleur);
	var objControle = document.getElementById(Controle);
	
	objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
}