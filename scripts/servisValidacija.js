function validacija()
{	
	var kod = document.getElementById("alpha2Code");
	var pozivniBroj = document.getElementById("callingCodes");
	var submitBtn = document.getElementById("submitBtn");

	
	if(kod.value != "" && kod.value.length == 2 && pozivniBroj.value != "" && pozivniBroj.value.length >= 4 &&pozivniBroj.value[0] == "+") 
	{
		
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "https://restcountries.eu/rest/v1/alpha?codes=" + kod.value, true);
		xhttp.send();
		xhttp.onreadystatechange = function() {
  			if (xhttp.readyState  == 4 && xhttp.status == 200){
  			
  				var res = JSON.parse(xhttp.responseText);
  				if(res != "")
  				{
  					
  					var pozivni = pozivniBroj.value.substring(1,4);  					
  					var mreza = pozivniBroj.value.substring(4,6);
  					/* 30 - 59 fiksna telefonija, 60 - 67 mobilna*/
  					var mrezaValid = (Number(mreza) >= 30 && Number(mreza) <= 67);
  					var r = /^[0-9]{6,7}$/;
  					var ostatak = pozivniBroj.value.substring(6, pozivniBroj.value.length);
  					
  					
	  				if (res[0].callingCodes == pozivni && mrezaValid && ostatak.match(r))
	  				{				
	  					kod.style.backgroundColor = 'white';
	  					pozivniBroj.style.backgroundColor = 'white';
	  					submitBtn.disabled = false;				
	  				}
	  				else 
	  				{
	  					submitBtn.disabled = true;				
	  					kod.style.backgroundColor = 'pink';
	  					pozivniBroj.style.backgroundColor = 'pink';	  						  						  
	  				}  		
  				}  				 						

  			}
		}
	}
	else
	{		
		submitBtn.disabled = true;
		kod.style.backgroundColor = 'pink';
	  	pozivniBroj.style.backgroundColor = 'pink';  
	}
	
}