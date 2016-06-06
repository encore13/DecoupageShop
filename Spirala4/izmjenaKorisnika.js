function popuniPodacima(){

	
	var korisnik = document.getElementById("user").value;
	
	var kontejner = document.getElementById("podaciOKorisniku");
	kontejner.style.display = "block";

	var ime = document.getElementById("fname");
	var prezime = document.getElementById("lastname");
	var username = document.getElementById("korisnik");
	var iduser = document.getElementById("idOdKorisnika");



	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {

			var json = JSON.parse(xhttp.responseText);
			ime.value= json[0].ime;
			prezime.value = json[0].prezime;
			username.value = json[0].username;
			iduser.value = json[0].id;
		
		}
	};


	xhttp.open("GET", "korisnikServis.php?id="+korisnik, true);
	xhttp.send();

}