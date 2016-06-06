function spremiVijestiLokalno(evt)
{

	
	evt.preventDefault();

	var user = document.getElementById("user").value;


	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {

			var json = JSON.parse(xhttp.responseText);
			sessionStorage.setItem("idLoginovanogKorisnika", json[0].id);



			var xhttp2 = new XMLHttpRequest();
			xhttp2.onreadystatechange =function()
			{
				if(xhttp2.readyState == 4 && xhttp2.status == 200){
					var json2 = JSON.parse(xhttp2.responseText);
					for(var i = 0; i < json2.length; i++)
					{						
						sessionStorage.setItem("brojKomentaraNaVijest"+json2[i].vijest,
								Number(json2[i].broj) + Number(json2[i].broj2)

							);
					}
					sbmt();

				}
			}



			xhttp2.open("GET", "notifikacijeServis.php?id="+ json[0].id);
			xhttp2.send();

			
					
		}
	};


	xhttp.open("GET", "servis.php?user="+user, true);
	xhttp.send();	

}

function obrisiStorage(evt)
{
	evt.preventDefault();
	sessionStorage.clear();
	sbmt2();
}


function sbmt()
{
	document.getElementById("logpanel").submit();
}

function sbmt2()
{
	document.getElementById("logged").submit();
}