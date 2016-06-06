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

	$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");



	if(isset($_POST["user"]) && isset($_POST["password"]))
	{
		$frmuid = $_POST["user"];
		$frmpwd = $_POST["password"];
		$hash = md5($frmpwd);
		$rezultat = $connection->query("select id, username, password, tipracuna from autori where username='".$frmuid."';");
		if(!$rezultat)
		{
			$greska = $connection->errorInfo();
			print_r($greska);
		}
		else
		{


			foreach ($rezultat as $credens) {

				if($credens['password'] == $hash)
				{
					$_SESSION["IDkorisnika"] = $credens["id"];
					$_SESSION["tipRacuna"] = $credens["tipracuna"];    					
					$_SESSION["userLogged"] = "1";   			
					$_SESSION["username"] = $_POST["user"];
				}

			}

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





$niz = dobaviVijestiIzBaze();

function dobaviVijestiIzBaze()
{
	$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

	$sveNovosti = $connection->query("SELECT v.id, v.naslov, v.sadrzaj, v.linkSlike, FROM_UNIXTIME(UNIX_TIMESTAMP(v.vrijeme), '%d.%m.%Y %H:%i:%s') datum, v.autor, v.moguceKomentarisati, a.id as autor, a.ime, a.prezime 
		FROM vijesti v JOIN autori a
		ON v.autor = a.id");

	$niz=array();
	foreach ($sveNovosti->fetchAll(PDO::FETCH_ASSOC) as $novost) {
		array_push($niz, $novost);
	}
	usort($niz, "porediPoDatumu");
	return $niz;
}





function porediAbecedno($a, $b)
{
	return strtolower($a["naslov"]) > strtolower($b["naslov"]);
}

function porediPoDatumu($a, $b)
{

	$a = strtotime($a["datum"]);
	$b = strtotime($b["datum"]);

	return $a < $b;
}


if($action == "sort")
{   		
	usort($niz, "porediAbecedno");
}



?>

<!DOCTYPE html>
<html>

<head>
	<title>Decoupage shop</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="skripta.js"></script>
	<script type="text/javascript" src="vijestiKorisnika.js"></script>
	
</head>

<body onload="postaviDatum()">

	<?php if($_SESSION["userLogged"] == "0"){?>

	<form id="logpanel" action="index.php" method="post">
		<input id="user" name="user" type="text" placeholder="Username">
		<input id="pass" name="password" type="password" placeholder="Password">
		<br>
		<input id="login" name="submitBtn" type="submit" value="Login" onclick="spremiVijestiLokalno(event)">	
		<input type="hidden" value="login" name="action">
	</form>

	<?php } ?>

	<?php if($_SESSION["userLogged"] == "1"){?>
	<script type="text/javascript" src="notification.js"></script>
	<form id="logged" action="index.php" method="post">
		<label id="welcomeMsg">Dobro došao, <?= $_SESSION["username"] ?> </label>
		<br>
		<input id="btnLogout" type="submit" name="logout" value="Log out" onclick="obrisiStorage(event)">
		<input type="hidden" value="logout" name="action">
		<a href="promjenaSifre.php?idKorisnika=<?php print $_SESSION["IDkorisnika"] ?>">Promijeni šifru</a>
		<button type="button" id="notifikacija"></button>
		<label id="brojNeprocitanih"></label>
		<input type="hidden" id="uid" value="<?= $_SESSION["IDkorisnika"] ?>">
		<br>
	</form>



	<?php if($_SESSION["tipRacuna"] == "admin"){?>
	<form id="autorPanel" action="autorPanel.php">

		<input type="submit" value="Autori">

	</form>
	<?php } ?>
	
	<?php } ?>


	


	
	<h1>Adisa Lisovac Decoupage</h1>		

	<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
		<?php if($_SESSION["userLogged"] == "1"){?>
		<li> 
			<form id="mojeNovosti" method="post" action="sveNovostiAutora.php?id=<?= $_SESSION["IDkorisnika"]?>">
				<input type="submit" value="Moje novosti">
				<input type="hidden" name="action" value="neprocitano">
			</form>

		</li>
		<?php } ?>
	</ul> 

	
	<div class="slideshow">
		<figure>
			<img src="1.jpg" alt="prva">
		</figure>

		<figure>
			<img src="2.jpg" alt="druga">
		</figure>

		<figure>
			<img src="3.jpg" alt="treca">
		</figure>

		<figure>
			<img src="4.jpg" alt="cetvrta">
		</figure>

		<figure>
			<img src="5.jpg" alt="peta">
		</figure>

		<figure>
			<img src="6.jpg" alt="sesta">
		</figure>

		<figure>
			<img src="7.jpg" alt="sedma">
		</figure>

		<figure>
			<img src="8.jpg" alt="osma">
		</figure>
		
	</div>


	<h2>Novosti</h2>

	<div id="newsPanel">

		<select id="opcije">
			<option>Današnje novosti</option>
			<option>Novosti ove sedmice</option>
			<option>Novosti ovog mjeseca</option>
			<option>Sve novosti</option>
		</select>

		<button id="filter" onclick="filtriraj()">Filtriraj</button>

		<?php if($_SESSION["userLogged"] == "1"){?>


		<form id = "unosNovosti" action="unosNovosti.php">
			<input type="submit" name="unesiNovost" value="Dodaj novost">

		</form>
		<?php } ?>
		<br><br>
		<form id="sortOpcije" method="post" action="index.php">
			<input type="submit" name="abcsort" value="Sortiraj novosti po abecedi">
			<input type="hidden" name="action" value="sort">
		</form>
		<br><br>
	</div>
	
	


	
	<div class="novosti">


		
		<?php for($i = 0; $i < count($niz); $i++) { ?>
		<article class="novost">
			
			<?php 

			$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");
			$koment = $connection->query("
				SELECT COUNT(kr.id) broj, COUNT(kd.id) broj2 
				FROM komentarroditelj kr LEFT JOIN komentardijete kd 
				ON kr.id = kd.roditelj 
				WHERE kr.vijest = '".$niz[$i]["id"]."' GROUP BY kr.vijest

				");

			$nizbrojKomentara = array();
			foreach($koment->fetchAll(PDO::FETCH_ASSOC) as $kom)
			{
				array_push($nizbrojKomentara, $kom);
			}

			$broj = 0;

			if(count($nizbrojKomentara) != 0)
				$broj = intval($nizbrojKomentara[0]["broj"]) + intval($nizbrojKomentara[0]["broj2"]);

			?>			


			
			
			<p class="vrijeme"> <?php print htmlEntities($niz[$i]["datum"], ENT_QUOTES) ?> </p>					
			<h3> <?php print htmlEntities($niz[$i]["naslov"], ENT_QUOTES) ?> </h3>
			<img src = " <?php print htmlEntities($niz[$i]["linkSlike"], ENT_QUOTES) ?> " alt = " <?= $niz[$i]["id"] ?>.alt">
			<p> <?php print htmlEntities($niz[$i]["sadrzaj"], ENT_QUOTES) ?> </p>					
			<p class="autor">Autor novosti: <?php print $niz[$i]["ime"]." ".$niz[$i]["prezime"] ?></p>


				
			<a href="detaljiNovosti.php?id=<?php print $niz[$i]["id"] ?>"
			<?php if($_SESSION["userLogged"] == 1 && $niz[$i]["autor"] == $_SESSION["IDkorisnika"]) {?>
			onclick = "smanjiBroj(<?= $niz[$i]["id"] ?>)"
			<?php } ?>
			> Detaljnije </a>
			
			<br>
			<br>	

				<?php if($_SESSION["userLogged"] == 1 && $niz[$i]["autor"] == $_SESSION["IDkorisnika"]) {?>
				
						<label>Broj nepročitanih komentara</label>
						<input type="text" id="brojNeprocitanihKomentaraVijesti<?=$niz[$i]["id"]?>" value="" readonly>
				
				<?php } ?>
			
						
			<input  type="hidden" id="brojKomentaraNaVijest<?=$niz[$i]["id"]?>" value="<?= $broj ?>" >

							

			</article>
			<?php } ?>


		</div>


	</body>
	</html>