<?php

session_start();
if(!isset($_SESSION["userLogged"]) || $_SESSION["userLogged"] == "0")
{
	header("Location: index.php");
	exit;
}


$idKorisnika = $_GET["idKorisnika"];

$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

$rez = $connection->query("
	SELECT password FROM autori WHERE id = '".$idKorisnika."'

	");



$hashStareSifre="";
foreach ($rez as $hashSifre) {
	$hashStareSifre = $hashSifre["password"];
}



if(isset($_POST["trenutna"], $_POST["nova"], $_POST["novaPonovo"]))
{
		if(md5($_POST["trenutna"]) == $hashStareSifre && $_POST["nova"] == $_POST["novaPonovo"])
		{
			$novaSifra = md5($_POST["nova"]);

			$promjena = $connection->exec("
				UPDATE autori SET password = '$novaSifra' WHERE id = '".$idKorisnika."'
			");

			print ("<h2>Šifra uspješno promijenjena</h2>");
		}
		else print ("<h2>Greška!(u nedostatku volje za validacijom) Ili se novi passwordi ne podudaraju ili niste unijeli dobar trenutni password :) </h2>");


}

	
?>



<!DOCTYPE html>
<html>

<head>
	<title>Promjena šifre</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="skripta.js"></script>
</head>

<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
</ul> 

<form id="promjeniSifru" method="post" action="promjenaSifre.php?idKorisnika=<?= $_SESSION["IDkorisnika"]?>">

	<input type="password" name="trenutna" placeholder="Trenutna šifra">
	<input type="password" name="nova" placeholder="Nova šifra">
	<input type="password" name="novaPonovo" placeholder="Ponovi šifru">
	<input type="submit" value="Potvrdi">

</form>

</html>