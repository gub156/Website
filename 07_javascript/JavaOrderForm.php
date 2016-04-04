
<script type="text/javascript">

var port = 5.95;
var tva = 0.025;


function calculerPrix(champQte)
{
	var champPrix = champQte.form.elements[champQte.name + "prix"];
	var champPrixUnite = champQte.form.elements[champQte.name + "unite"];
	var prixUnite = parseFloat(champPrixUnite.value);
	var qte = champQte.value;
	var prix = prixUnite * qte;
	
	if (qte == "") qte = 0;
	else if (isNaN(qte)) qte = 0;
	else qte = Math.floor(qte);
	
	if(qte < 0) qte = 0;

	champPrix.value  = formatPrix(prix);

	calculerTotal(champQte.form);
}


function calculerPrixPort(prixPort, qte)
{
	if(prixPort.value == "B")
	{
		if(qte.value < 4)
		{
			champPort.value = formatPrix(7.00);
		}
		else
		{
			champPort.value = formatPrix(9.00);
		}
	}
	else
	{
		if(qte.value < 4)
		{
			champPort.value = formatPrix(11.00);
		}
		else
		{
			champPort.value = formatPrix(15.00);
		}
	}
}

function
calculerTotal (form) {

var champTva = form.elements['tva'];
var champPort = form.elements['port'];
var champTotal = form.elements['total'];
champPort.value
= formatPrix(port);

var total = 0;
for (var i in form.elements) {
if (
//form.elements[i].name
i.toLowerCase().indexOf("prix") != -1)
total += parseFloat(form.elements[i].value);
}
total += port;
var tvaCalc = tva * total;
tvaCalc = Math.round(tvaCalc*100)/100.0;

champTva.value
= formatPrix(tvaCalc);
total += tvaCalc;

champTotal.value = formatPrix(total);
}

function formatPrix (n) {
n = Math.round(n*100)/100.0;
var
str = ""+n+"";

var i = str.indexOf(".");
if (i == -1) str += ".00";
else if (i == str.length-2) str += "0";
return str;
}

// -->
</script>
