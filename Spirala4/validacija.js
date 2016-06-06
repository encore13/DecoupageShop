function imePrezimeValid()
{
	var imePrezime = document.getElementById("licniPodaci");
	var regex = /^[a-zA-Z\s]*$/;

	if(imePrezime.length == 0 || !imePrezime.value.match(regex))
		imePrezime.style.background = "pink";
	else
		imePrezime.style.background = "white";
}


function emailValid()
{
	var regex =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var email = document.getElementById("mail");

	if(email.value.length == 0  || !email.value.match(regex))
		email.style.background = "pink";
	else
		email.style.background = "white";
}

function telValid()
{
	var regex = /^[0-9]+$/;
	var telefon = document.getElementById("kontaktTelefon");
	if(!telefon.value.match(regex))
		telefon.style.background = "pink";
	else
		telefon.style.background = "white";
}

function multipleValid()
{
	var kod = document.getElementById("kod");
	var kodbox = document.getElementById("kodbox");
	if(kodbox.value != kod.value)
		kodbox.style.background= "pink";
	else
		kodbox.style.background = "white";

	


}

