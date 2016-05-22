<?php 
	session_start();
	if(!isset($_SESSION["userLogged"]) || $_SESSION["userLogged"] == "0")
	{
		header("Location: index.php");
		exit;
	}
	date_default_timezone_set('Europe/Sarajevo');

	

	function upisiUCSV()
	{
		$path = "novosti1.csv";
		$file = fopen($path, "a");
		$naslov = $_POST["naslov"];		
		$sadrzaj = $_POST["sadrzaj"];
		$link = $_POST["linkSlike"];
		
		//da ne poremeti format csva ukoliko korisnik unese novi red u textboxe
		$naslov = str_replace(PHP_EOL, ' ', $naslov);
		$sadrzaj = str_replace(PHP_EOL, ' ', $sadrzaj);
		//isto za zareze
		$naslov = str_replace(',', '&#44;', $naslov);
		$sadrzaj = str_replace(',', '&#44;', $sadrzaj);


		$datum = date("d.m.Y H:i:s");
		$text = $naslov.",".$sadrzaj.",".$link.",".$datum.PHP_EOL;
		fwrite($file, $text);
		fclose($file);
	}



	if(isset($_POST["sacuvaj"]))
	{
		upisiUCSV();
		
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
			<input type="submit" id = "submitBtn" name = "sacuvaj" value="Sačuvaj novost" disabled="true">
			<br><br>
			
			

		</form>
	
</body>
</html>