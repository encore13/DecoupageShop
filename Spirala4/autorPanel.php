<?php
	session_start();
	if(!isset($_SESSION["userLogged"]) || $_SESSION["userLogged"] == "0" || 
		$_SESSION["tipRacuna"] != "admin")
	{
		header("Location: index.php");
		exit;
	}


	$action = "";
	if(isset($_POST["action"]))
		$action = $_POST["action"];

	if($action == "dodajAutora")
	{

		dodajNovogAutora();

	}

	if($action == "obrisiAutora")
	{
		obrisiAutora();
	}

	if($action == "izmijeniAutora")
	{
		izmijeniKorisnika();
	}


	function izmijeniKorisnika()
	{
		if(isset($_POST["idKorisnika"], $_POST["ime"],$_POST["prezime"],$_POST["korisnickoIme"]))
		{
			$id = $_POST["idKorisnika"];
			$id = intval($id);
			$ime = $_POST["ime"];
			$prezime = $_POST["prezime"];
			$username = $_POST["korisnickoIme"];

			


			$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

			$izmijeni = $connection->exec("
					UPDATE autori SET ime = '$ime', prezime = '$prezime', username='$username'
					WHERE id='".$id."'

				");



			echo "<h2>Korisnik izmijenjen</h2>";
		}
			
	}

	function obrisiAutora()
	{
		$id = $_POST["postojeciAutori"];
		$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

		$obrisi = $connection->exec("
				DELETE FROM komentardijete WHERE autor='".$id."'

			");

		$obrisi = $connection->exec("
				DELETE FROM komentarroditelj WHERE autor='".$id."'
			");

		$obrisi = $connection->exec("

				DELETE FROM vijesti WHERE autor='".$id."'
			");

		$obrisi = $connection->exec("
			DELETE FROM autori WHERE id='".$id."'
			");
		echo "<h2>Korisnik obrisan!</h2>";

	}
	function dodajNovogAutora()
	{

			if(isset($_POST["imeAutora"], $_POST["prezimeAutora"], $_POST["usernameAutora"], $_POST["passwordAutora"]))
			{
				$ime = $_POST["imeAutora"];
				$prezime = $_POST["prezimeAutora"];
				$user = $_POST["usernameAutora"];
				$pw = $_POST["passwordAutora"];
				$pw = md5($pw);	
				$tip = "autor";
				
				$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

				$noviAutor =
				$connection->query("
				INSERT INTO autori (id, ime, prezime, username, password, tipRacuna)
				VALUES (NULL, '$ime', '$prezime', '$user', '$pw', '$tip')

				");
				echo "<h2>Novi korisnik uspjesno kreiran!</h2>";
			}
			
	}

?>



<!DOCTYPE html>
<html>
	<head>
		<title>Admin panel</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="index.css">
		<script type="text/javascript" src="izmjenaKorisnika.js"></script>		
	</head>

	<body>
		<ul id="meni">
			<li> <a href="index.php">Naslovna</a> </li>
			<li> <a href="omeni.php">O meni </a> </li>	
			<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
			<li> <a href="kontakt.php"> Kontakt </a> </li>	
		</ul> 
	</body>

	<form id="dodajAutora" method="post" action="autorPanel.php">
		<input type="text" placeholder="Ime autora" name="imeAutora">
		<br>
		<input type="text" placeholder="Prezime autora" name="prezimeAutora">
		<br>
		<input type="text" placeholder="Username" name="usernameAutora">
		<br>
		<input type="text" placeholder="Password" name="passwordAutora">
		<br>
		<input type="submit" value="Dodaj autora">
		<input type="hidden" name="action" value="dodajAutora">
	</form>

	<form id="obrisiAutora" method="post" action="autorPanel.php">
		<select  id="user" name="postojeciAutori">
			<?php 
				$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");
				$rez = $connection->query("SELECT * FROM autori WHERE tipRacuna = 'autor'");

				foreach ($rez as $autor) { ?>
				
				<option value="<?= $autor["id"]?>"> <?php print $autor["ime"]." ".$autor["prezime"] ?> </option>

			<?php } ?>
			
		</select>
		<input type="submit" value="Obriši autora">
		<input type="hidden" name="action" value="obrisiAutora">
	</form>

	<form id="izmijeniKorisnika" method="post" action="autorPanel.php">
		
		<input type="hidden" name="action" value="izmijeniAutora">
		<input type="button" onclick="popuniPodacima()" value="Izmijeni">
		<div id="podaciOKorisniku">

			<input type="text" id="fname" name="ime">
			<br>
			<input type="text" id="lastname" name="prezime">
			<br>
			<input type="text"  id="korisnik" name="korisnickoIme">
			<input type="hidden" id="idOdKorisnika" name="idKorisnika">
			<input type="submit" value="Sačuvaj promjene">	

		</div>

	</form>

</html>