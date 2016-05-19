<?php
	session_start();
	if(!isset($_SESSION["userLogged"]))
		$_SESSION["userLogged"] = "0";
	
    $action = "";
    

    if(isset($_POST["action"])){
    	$action = $_POST["action"];
    }
	
    function login()
    {
    	$path = "../assets/credentials.txt";
    	$handle = fopen($path, "r");
    	$credens = fread($handle, filesize($path));
    	$index = strpos($credens, ":", 0);
    	$uid = substr($credens, 0, $index);
    	$hshpwd = substr($credens, $index+1);

    	

    	if(isset($_POST["user"]) && isset($_POST["password"]))
    	{
    		$frmuid = $_POST["user"];
    		$frmpwd = $_POST["password"];
    		$hash = md5($frmpwd);
    		if($uid == $frmuid && $hshpwd == $hash)
    		{ 
    		    $_SESSION["userLogged"] = "1";   			
    			$_SESSION["username"] = $_POST["user"];

    		}
    		
    	}    
    	
    }
   	
   	if($action == "login")
   	{
   		
   		login();	
   	}	
   	if($action == "logout")
   	{
   		session_destroy();
   		
   		$_SESSION["userLogged"] = "0";
   	}

   	$niz = $polja = array();
   	

   	function procitajIzCSVa()
	{
		
		$niz = $polja = array();
		$i = 0;
		$handle = fopen("../assets/novosti1.csv", "r");
		if($handle)
		{
			while(($red = fgetcsv($handle)) !== false)
			{
				if(empty($polja))
				{
					$polja = $red;
					continue;
				}
				foreach($red as $kljuc => $vrijednost)
				{
					//vratimo zareze 
					$vrijednost = str_replace('&#44;', ',', $vrijednost);
					$niz[$i][$polja[$kljuc]] = $vrijednost;

				}
				$i++;
			}
			fclose($handle);
		}

		

		return $niz; 


	}

	function porediAbecedno($a, $b)
	{
		return $a["Naslov"] > $b["Naslov"];
	}

	function porediPoDatumu($a, $b)
	{
		$a = strtotime($a["Datum"]);
		$b = strtotime($b["Datum"]);
		return $a < $b;
	}

	$niz = procitajIzCSVa();
	if($action == "sort")
   	{
   		
   		usort($niz, "porediAbecedno");
   	}
   	else usort($niz, "porediPoDatumu");

   
?>

<!DOCTYPE html>
<html>

<head>
	<title>Decoupage shop</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../styles/index.css">
	<script type="text/javascript" src="../scripts/skripta.js"></script>
</head>

<body onload="postaviDatum()">

	<?php if($_SESSION["userLogged"] == "0"){?>

	<form id="logpanel" action="index.php" method="post">
		<input id="user" name="user" type="text" placeholder="Username">
		<input id="pass" name="password" type="password" placeholder="Password">
		<br>
		<input id="login" name="submit" type="submit" value="Login">
		<input type="hidden" value="login" name="action">
	</form>

	<?php } ?>

	<?php if($_SESSION["userLogged"] == "1"){?>
	<form id="logged" action="index.php" method="post">
		<label>Welcome: <?= $_SESSION["username"] ?> </label>
		<input type="submit" name="logout" value="Log out">
		<input type="hidden" value="logout" name="action">
	</form>

	<form id = "unosNovosti" action="unosNovosti.php">
		<input type="submit" name="unesiNovost" value="Dodaj novost">

	</form>
	<?php } ?>


	


	
	<h1>Adisa Lisovac Decoupage</h1>		

	<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
	</ul> 

	
	<div class="slideshow">
		<figure>
			<img src="../images/1.jpg" alt="prva">
		</figure>

		<figure>
			<img src="../images/2.jpg" alt="druga">
		</figure>

		<figure>
			<img src="../images/3.jpg" alt="treca">
		</figure>

		<figure>
			<img src="../images/4.jpg" alt="cetvrta">
		</figure>

		<figure>
			<img src="../images/5.jpg" alt="peta">
		</figure>

		<figure>
			<img src="../images/6.jpg" alt="sesta">
		</figure>

		<figure>
			<img src="../images/7.jpg" alt="sedma">
		</figure>

		<figure>
			<img src="../images/8.jpg" alt="osma">
		</figure>
		
	</div>


	<h2>Novosti</h2>

	<select id="opcije">
		<option>Današnje novosti</option>
		<option>Novosti ove sedmice</option>
		<option>Novosti ovog mjeseca</option>
		<option>Sve novosti</option>
	</select>
	<button onclick="filtriraj()">Filtriraj</button>
	<form id="sortOpcije" method="post" action="index.php">
		<input type="submit" name="abcsort" value="Sortiraj novosti po abecedi">
		<input type="hidden" name="action" value="sort">
	</form>



	<div class="novosti">

			<?php 
			
			 
			for($i = 0; $i < count($niz); $i++)
			{ ?>

			<article class="novost">
				<p class="vrijeme"> <?php print htmlEntities($niz[$i]["Datum"], ENT_QUOTES) ?> </p> 
				<h3> <?php print htmlEntities($niz[$i]["Naslov"], ENT_QUOTES) ?> </h3>
				<p> <?php print htmlEntities($niz[$i]["Sadrzaj"], ENT_QUOTES) ?> </p>
				<img src = " <?php print htmlEntities($niz[$i]["Slika"], ENT_QUOTES) ?> " alt = " <?= $i ?>.alt">		
				
			</article>
			
			<?php } ?>


			
			

			<!--<article class="novost">
				<p class="vrijeme"></p>
				<h3>Naše druženje u Beogradu "uhvaćeno" objektivom ženskog portala Aska.rs</h3>
				
				
				<img id="vecaSlika" src="../images/bgdruzenje.jpg" alt="druzenje">		
				
				<p>
					<a href="http://aska.rs/budite-kreativne-u-svom-domu-napravite-unikatni-sat/">
						Djelić atmosfere pogledajte na ovom linku.
					</a>
				</p>


			</article>

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>IZNENAĐENJEEE!!!</h3>
				<p>
					Prvi put dolazim u HobbyArt Centar Podgorica, radujem se drage moje Crnogorke, vidimo se 09.04.					
				</p>				

				<img src="../images/ob1.jpg" alt="obavijest1">
				<img src="../images/ob2.jpg" alt="obavijest2">

			</article>

			<article class="novost">
				<p class="vrijeme"></p>

				<h3>Održavanje radionice u Beogradu</h3>


				<img src="../images/notice1.jpg" alt="bgradiona">
				<p>
					Drage moje Beograđanke, čast mi je i zadovoljstvo najaviti druženje u Beogradu. Kao prvo moram izraziti svoju neizmjernu zahvalnost mom domaćinu, Sanji Colja, uz čiju inicijativu i gostoprimstvo je moja velika želja da dođem u Beograd ostvarena! Zabavljati ćemo se uz dvije odvojene radionice, sa dvije razlicite tematike: <br>
					1. Antique knjiga/kombinovane tehnike<br>
					2. Radionica čipka					 
				</p>	

				

				
			</article>	


			<article class="novost">
				<p class="vrijeme"></p>

				<h3>Održana radionica u Tešnju</h3>
				
				<p>Sa zadovoljstvom vas obavještavam da je uspješno održana 3. radionica u Tešnju. <img src="../images/radionica.jpg" alt="tesanjradiona"><br>
				Mnogo zabave i stečenog znanja je obilježilo ovo okupljanje. Sve prisutne dame i gospođe su uspješno savladale tehniku rada sa <b><em>aluminijskom folijom</em></b> </p>

				

			</article>	

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Časopis Decoupage!!!</h3>
				<img src="../images/novost3.jpg" alt="casopis">
				<p>Dođoh i ja do svog primjerka novog časopisa Decoupage. Za sve detalje oko nabavke, kontaktirajte me na vama najdraži način.</p>
				
			</article>	

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Decoupage Bosna i Hercegovina, nova grupa na facebooku!</h3>
				<p>
					Za sve zaljubljenike u dekupaž tehniku kreirala sam ovu grupu s ciljem lijepog druženja i razmjene iskustava. Samo ujedinjeni možemo stvarati ljepše, više i napredovati u ovom divnom hobiju koji nas spaja. <br><a href="https://www.facebook.com/groups/456576674537005/?pnref=story">Decoupage Bosna i Hercegovina</a>
				</p>
			</article>

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Obavještenje povodom 8. Marta</h3>

				<img src="../images/mart.jpg" alt="mart">

				<p>
					Dragi kupci, zbog obima posla nažalost više ne primam narudžbe za 8. Mart. Ukoliko još niste odabrali poklone, javite se da vam preporučim kolegice koje također nude divne unikatne ručne radove.
					
				</p>
			</article>


			

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Test novost 8</h3>
				
				
			</article>

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Test novost 9</h3>
			</article>

			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Test novost 10</h3>
			</article>


			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Test novost 11</h3>
			</article>


			<article class="novost">
				<p class="vrijeme"></p>
				<h3>Test novost 12</h3>
			</article> -->
		

	</div>


</body>
</html>