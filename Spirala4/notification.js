function foo2(id)
{
	var idkorisnika = id;
	
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {

			var json = JSON.parse(xhttp.responseText);	

			for(var i = 0; i < json.length; i++)
			{
				var brojKomentaraIte = Number(json[i].broj) + Number(json[i].broj2);				
				
				if(sessionStorage["brojKomentaraNaVijest"+json[i].vijest] < brojKomentaraIte)
				{
					var brojNeprocitanih = brojKomentaraIte - sessionStorage["brojKomentaraNaVijest"+json[i].vijest];					
					var novost = document.getElementById("brojNeprocitanihKomentaraVijesti"+json[i].vijest);
					novost.innerHTML = "Broj neprocitanih: ";
					var broj = document.getElementById("broj"+json[i].vijest).innerHTML = brojNeprocitanih;
					
				}

			}
		}
	};


	xhttp.open("GET", "notifikacijeServis.php?id="+idkorisnika, true);
	xhttp.send();
	
	
}
function smanjiBroj(id)
{
	var brojNeprocitanih = document.getElementById("broj"+id);
	if(brojNeprocitanih != null)
	{
		brojNeprocitanih = brojNeprocitanih.innerHTML;
		sessionStorage["brojKomentaraNaVijest"+id] = Number(sessionStorage["brojKomentaraNaVijest"+id])+  Number(brojNeprocitanih);
	}
	else
	{
		var broj = document.getElementById("brojNeprocitanihKomentaraVijesti"+id).value;
		sessionStorage["brojKomentaraNaVijest"+id] = Number(sessionStorage["brojKomentaraNaVijest"+id])+  Number(broj);
	}

	
	return true;
	
	
}

function foo()
{
	var ikona = document.getElementById("notifikacija");	
	var idkorisnika = document.getElementById("uid");	

	if(ikona != null && idkorisnika != null)
	{
		var xhttp = new XMLHttpRequest();

		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {

				var json = JSON.parse(xhttp.responseText);


				var ukupnoNeprocitanih = 0;
				for(var i = 0; i < json.length; i++)
				{
					var brojKomentaraIte = Number(json[i].broj) + Number(json[i].broj2);



					if(sessionStorage["brojKomentaraNaVijest"+json[i].vijest] < brojKomentaraIte)
					{
						var brojNeprocitanih = brojKomentaraIte - sessionStorage["brojKomentaraNaVijest"+json[i].vijest];
						ikona.style.backgroundImage = "url('new.jpg')";
						var novost = document.getElementById("brojNeprocitanihKomentaraVijesti"+json[i].vijest);
						var b = document.getElementById("brojNeprocitanih");
						novost.value = brojNeprocitanih;
						ukupnoNeprocitanih += brojNeprocitanih;
						ikona.value = ukupnoNeprocitanih;
						b.innerHTML = ukupnoNeprocitanih;
					}

				}		
			}
		};


		xhttp.open("GET", "notifikacijeServis.php?id="+idkorisnika.value, true);
		xhttp.send();
	}
	

	
}

setInterval(foo, 5000);