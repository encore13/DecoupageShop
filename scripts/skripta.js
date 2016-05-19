var s_ms = 60*1000;
var min_ms = s_ms *60;
var h_ms = min_ms * 24;
var dan_ms = h_ms * 7;
var sedmica_ms = h_ms * 30;


function postaviDatum()
{
	var datumi = document.getElementsByClassName("vrijeme");

	/*var dat = new Date();

	Par datuma koji su pogodni za testiranje svih kategorija datuma traženih u zadatku.
	Uvijek će, bez obzira na vrijeme pregledanja ove zadaće od strane asistenta, u odnosu na trenutno vrijeme 
	prikazati vrijeme potrebno za ispis konkretne kategorije (prije par sekundi/minuta/sati/dana/sedmica)
	datumi[0].innerHTML = ispisiPoKategoriji(dat);
	datumi[1].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate(), dat.getHours(), dat.getMinutes()-generisiRandomBroj(2, 59), dat.getSeconds(), dat.getMilliseconds()));
	datumi[2].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate(), dat.getHours()-generisiRandomBroj(1, 23), dat.getMinutes(), dat.getSeconds(), dat.getMilliseconds()));
	datumi[3].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate()-generisiRandomBroj(1,6), dat.getHours(), dat.getMinutes(), dat.getSeconds(), dat.getMilliseconds()));
	datumi[4].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate()- generisiRandomBroj(1, 30), dat.getHours(), dat.getMinutes(), dat.getSeconds(), dat.getMilliseconds()));
	datumi[5].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate()- 15, dat.getHours(), dat.getMinutes(), dat.getSeconds(), dat.getMilliseconds()));
	datumi[6].innerHTML = ispisiPoKategoriji(new Date(dat.getFullYear(), dat.getMonth(), dat.getDate()- 25, dat.getHours(), dat.getMinutes(), dat.getSeconds(), dat.getMilliseconds())); 


	//ostali datumi random generisani
	for(var i = 7; i < datumi.length; i++)
	{
		var dan = generisiRandomBroj(1, 28);
		var mjesec = generisiRandomBroj(1, 12);
		mjesec -= 1;		
		var godina = generisiRandomBroj(2015, 2016);
		var sat = generisiRandomBroj(1, 24);
		var minuta = generisiRandomBroj(1, 60);
		var sekunda = generisiRandomBroj(1, 60);
		datumi[i].innerHTML = ispisiPoKategoriji(new Date(godina, mjesec, dan, sat, minuta, sekunda, 0));
	}*/
	for (var i = 0; i < datumi.length; i++)
		datumi[i].innerHTML = ispisiPoKategoriji(datumi[i].innerHTML);

}


function ispisiPoKategoriji(datum)
{

	var trenutno = new Date();
	//PHP primljeni datum
	datum = datum.trim();
	var dan = datum.substr(0,2);
	dan = Number(dan);
	var mjesec = datum.substr(3,2);
	mjesec = Number(mjesec) - 1;
	var godina = datum.substr(6,4);
	var sati = datum.substr(11,2);
	sati = Number(sati);
	var minute = datum.substr(14,2);
	minute = Number(minute);
	var sekunde = datum.substr(17,2);
	sekunde = Number(sekunde);
	datum = new Date(godina, mjesec, dan, sati, minute, sekunde, 0);
	var razlika = trenutno - datum;
	
	if(razlika < s_ms)
	{
		return "Novost objavljena prije par sekundi.";
	}
	else if (razlika < min_ms)
	{
		var min = Math.floor(razlika/(s_ms));
		return "Novost je objavljena prije " + min + " " + formatirajMinute(min);
	}
	else if (razlika < h_ms)
	{
		var h = Math.floor(razlika/(min_ms));
		return"Novost je objavljena prije " + h + formatirajSate(h);
	}
	else if(razlika < dan_ms)
	{
		var dani = Math.floor(razlika/(h_ms));
		return "Novost je objavljena prije " + dani + formatirajDane(dani);

	}
	else if (razlika < sedmica_ms)
	{
		var sedmice = Math.floor(razlika/(dan_ms));
		return "Novost je objavljena prije " + sedmice + formatirajSedmice(sedmice);
	}
	else
	{
		return formatirajDatum(datum);
	}

}

function generisiRandomBroj(min, max)
{
	return min + Math.floor(Math.random() * (max - min + 1));
}


function formatirajDatum(datum)
{
	var mjesec = datum.getMonth() + 1;
	return "Datum objave: " + datum.getDate() + "." + mjesec+ "." + datum.getFullYear() + ". " + datum.getHours() + ":" + datum.getMinutes() + ":" + datum.getSeconds(); 
}

function formatirajDane(brojDana)
{
	if(brojDana == 1)
		return " dan.";
	return " dana.";
}

function formatirajSate(brojSati)
{
	var pomocni1 = [1, 21];
	var pomocni2 = [2, 3, 4, 22, 23];
	for (var i = 0; i < pomocni1.length; i++)
		if(brojSati == pomocni1[i])
			return " sat."
	
	for(var i = 0; i < pomocni2.length; i++)
		if(brojSati == pomocni2[i])
			return " sata."
	return " sati."
}

function formatirajMinute(brojMinuta)
{
	var pomocni1 = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54];
	var pomocni2 = [1, 21, 31, 41, 51];
	for (var i = 0; i < pomocni1.length; i++)
		if(brojMinuta == pomocni1[i])
			return " minute."
	
	for(var i = 0; i < pomocni2.length; i++)
		if(brojMinuta == pomocni2[i])
			return " minutu."
	return " minuta."
}

function formatirajSedmice(brojSedmica)
{
	if(brojSedmica == 1)
		return " sedmicu."
	return " sedmice."
}

function filtriraj()
{
	var opt = document.getElementById("opcije").selectedIndex;
	var novosti = document.querySelectorAll(".novost");
		
	if(opt == 0)
	{

		refresh(novosti);
		for(var i = 0; i < novosti.length; i++)
		{
				if(novosti[i].querySelector(".vrijeme").innerHTML.indexOf("sekund") < 0
					&& novosti[i].querySelector(".vrijeme").innerHTML.indexOf("minut") <0
					&& novosti[i].querySelector(".vrijeme").innerHTML.indexOf("sat") < 0)
					novosti[i].style.display = "none";
		}
	}
	else if(opt == 1)
	{
		refresh(novosti);
		for(var i = 0; i < novosti.length; i++)
		{
				if(novosti[i].querySelector(".vrijeme").innerHTML.indexOf("sekund") < 0
					&& novosti[i].querySelector(".vrijeme").innerHTML.indexOf("minut") <0
					&& novosti[i].querySelector(".vrijeme").innerHTML.indexOf("sat") < 0
					&& novosti[i].querySelector(".vrijeme").innerHTML.indexOf("dan") < 0)
					novosti[i].style.display = "none";
		}
		
	}
	else if(opt == 2)
	{
		refresh(novosti);
		for(var i = 0; i < novosti.length; i++)
			if(novosti[i].querySelector(".vrijeme").innerHTML.indexOf(":") >= 0)
				novosti[i].style.display = "none";

	}
	else
	{
		refresh(novosti);
	}
	
}

function refresh(novosti)
{
	for(var i = 0; i < novosti.length; i++)
			novosti[i].style.display = "block";	
}

