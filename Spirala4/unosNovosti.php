<?php 
	session_start();
	if(!isset($_SESSION["userLogged"]) || $_SESSION["userLogged"] == "0")
	{
		header("Location: index.php");
		exit;
	}
	date_default_timezone_set('Europe/Sarajevo');

	


	function dodajNovost()
	{
		date_default_timezone_set('Europe/Sarajevo');
		$moguceKomentarisati = 0;
		if(isset($_POST["canComment"]))
			$moguceKomentarisati = 1;
		$naslov = $_POST["naslov"];		
		$sadrzaj = $_POST["sadrzaj"];
		$link = $_POST["linkSlike"];

		
		
		$id = intval($_SESSION["IDkorisnika"]);

		$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

		$novaVijest = 
		$connection->query("
				INSERT INTO vijesti (id, naslov, sadrzaj, linkSlike, vrijeme, autor, moguceKomentarisati)
				VALUES (NULL, '$naslov', '$sadrzaj', '$link', CURRENT_TIMESTAMP, '$id', '$moguceKomentarisati')

			");
		if(!$novaVijest)
			print ("greska");			

	}

	
	if(isset($_POST["sacuvaj"]))
	{
		
		dodajNovost();		
		header ("Location: index.php");

	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Unos novosti</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="servisValidacija.js"></script>
</head>
<body>

	<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
	</ul> 

		


		<form id="frmNovosti" method="post" action="unosNovosti.php">
			<br>
			<input class="novostTxtBox" type="text" name = "naslov" placeholder="Naslov novosti" required>
			<br><br>
			<textarea id="txtAreaSadrzaj" name = "sadrzaj" placeholder="Sadržaj novosti..." required ></textarea> 
			<br><br>
			<input  class="novostTxtBox" type="text" name="linkSlike" placeholder="Link slike" required>
			<br><br>
			<input class="novostTxtBox" type="text" id="alpha2Code" placeholder="Dvoslovni kod države" name="kod" onblur="validacija()">
			<br><br>
			<input class="novostTxtBox" type="text" id="callingCodes" placeholder="Tel: +387XXYYYYYY(Y)" name="telBroj" onblur="validacija()">
			<br><br>
			<input type="checkbox" name="canComment" value="mogucnost">Mogućnost komentarisanja?
			<br><br>
			<input type="submit" id = "submitBtn" name = "sacuvaj" value="Sačuvaj novost" disabled="true">
			<br><br>
			
			

		</form>
	
</body>
</html>