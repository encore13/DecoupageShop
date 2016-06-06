<?php
session_start();
if(!isset($_SESSION["userLogged"]))
		$_SESSION["userLogged"] = "0";

$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");

$id = $_GET["id"];
$action = "";
if(isset($_POST["action"]))
	$action = $_POST["action"];


$rez = $connection->query("SELECT v.id, v.naslov, v.sadrzaj, v.linkSlike, FROM_UNIXTIME(UNIX_TIMESTAMP(v.vrijeme), '%d.%m.%Y %H:%i:%s') datum, v.autor, v.moguceKomentarisati, a.ime, a.prezime 
	FROM vijesti v JOIN autori a
	ON v.autor = a.id
	WHERE v.id = ' ".$_GET["id"]."'");



if($action == "promjenaMogucnostiKomentarisanja")
{
		
	$vijest = $connection->query("
			SELECT moguceKomentarisati FROM vijesti WHERE id='".$id."'

		");	

	$daLiJeMoguce = "";
	foreach ($vijest as $mogucnost) {
		$daLiJeMoguce = $mogucnost["moguceKomentarisati"];
	}

	$daLiJeMoguce = intval($daLiJeMoguce);

	echo $daLiJeMoguce;
	if($daLiJeMoguce == 1)
	{

		$brisanjeSubkomentara = $connection->exec(" DELETE FROM komentardijete 
		WHERE roditelj IN 
		(SELECT id FROM komentarroditelj WHERE vijest ='".$id."') "

		);

		$brisanjeKomentara = $connection->exec(
			" DELETE FROM komentarroditelj
				WHERE vijest ='".$id."'"

		);

		$nemoguce = $connection->exec("
				UPDATE vijesti SET moguceKomentarisati = 0 WHERE  id='".$id."'
			");
	}
	else
	{
		$moguce = $connection->exec("
				UPDATE vijesti SET moguceKomentarisati = 1 WHERE  id='".$id."'
			");
	}

	header("Location: detaljiNovosti.php?id=".$id);
}

if($action == "brisanjeNovosti")
{
	
	$brisanjeSubkomentara = $connection->exec(" DELETE FROM komentardijete 
		WHERE roditelj IN 
		(SELECT id FROM komentarroditelj WHERE vijest ='".$id."') "

		);

	$brisanjeKomentara = $connection->exec(
			" DELETE FROM komentarroditelj
				WHERE vijest ='".$id."'"

		);

	$brisanjeVijesti = $connection->exec(" DELETE FROM vijesti WHERE id='".$id."'"

		);

	header("Location: index.php");
	exit;	

}

if($action == "brisanjeKomentara")
{
	$idkomentara = $_POST["komentarZaBrisati"];

	$brisanjeSubKomentara = $connection->exec("
			DELETE FROM komentardijete WHERE roditelj = '".$idkomentara."'
		");

	$brisanjeKomentara = $connection-> exec("
			DELETE from komentarroditelj WHERE id = '".$idkomentara."'
		");

}

if($action == "brisanjeSubKomentara")
{

	$idsubkomentara = $_POST["podkomentarZaBrisati"];

	$brisanjesubkomentara = $connection->exec("
			DELETE FROM komentardijete WHERE id = '".$idsubkomentara."'
		");

}


if($action == "dodajKomentar")
{
	if(isset($_SESSION["IDkorisnika"]))
		$idKorisnika = intval($_SESSION["IDkorisnika"]);			
	else 
		$idKorisnika = 6;



	if(isset($_POST["sadrzajKomentara"]))
	{
		$sadrzaj = $_POST["sadrzajKomentara"];

		$noviKomentar = 
		$connection->query("
			INSERT INTO komentarroditelj (id, tekst, vrijeme, autor, vijest)
			VALUES (NULL, '$sadrzaj', CURRENT_TIMESTAMP, '$idKorisnika', '$id')

			");


	}	

}


if($action == "dodajOdgovor")
{

	if(isset($_POST["sadrzajOdgovora"], $_POST["roditeljskiKomentar"]))
	{
		$sadrzajOdgovora = $_POST["sadrzajOdgovora"];
		if(isset($_SESSION["IDkorisnika"]))	
			$idKorisnika = intval($_SESSION["IDkorisnika"]);
		else
			$idKorisnika = 6;

		$roditeljKom = intval($_POST["roditeljskiKomentar"]);
		$noviOdgovorNaKomentar =
		$connection->query("
			INSERT INTO komentardijete (id, tekst, vrijeme, autor, roditelj)
			VALUES (NULL, '$sadrzajOdgovora', CURRENT_TIMESTAMP, '$idKorisnika', '$roditeljKom' )


			");



	}
	else echo "fail";

}




$komentari = $connection->query("SELECT k.id, k.tekst, FROM_UNIXTIME(UNIX_TIMESTAMP(k.vrijeme), '%d.%m.%Y 	  %H:%i:%s') datum, a.username
	FROM komentarroditelj k JOIN autori a
	ON k.autor = a.id
	WHERE k.vijest =' ".$_GET["id"]."'" );




	?>



	<!DOCTYPE html>
	<html>

	<head>
		<title>Detalji novosti</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="index.css">
		<script type="text/javascript" src="skripta.js"></script>

	</head>

	<body>

		<ul id="meni">
			<li> <a href="index.php">Naslovna</a> </li>
			<li> <a href="omeni.php">O meni </a> </li>	
			<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
			<li> <a href="kontakt.php"> Kontakt </a> </li>
		</ul> 

		<div class="novostDetaljno">
			<?php 	foreach ($rez as $vijest) { ?>

			<article class="novost">
				<p class="vrijeme"> <?php print htmlEntities($vijest["datum"], ENT_QUOTES) ?> </p>
				<?php if( $_SESSION["userLogged"] == "1") { 
					 if($_SESSION["tipRacuna"] == "admin") { ?>
					<form id="brisanjeVijesti" method="post" action="detaljiNovosti.php?id=<?= $vijest["id"] ?>">
						<input type="hidden" name="action" value="brisanjeNovosti">
						<input type="submit" value="Obriši novost">						
					</form>
				<?php } }?>
				<?php if( $_SESSION["userLogged"] == "1") { 
					 if($_SESSION["tipRacuna"] == "admin") { ?>
					<form id="komentariOnOff" method="post" action="detaljiNovosti.php?id=<?= $vijest["id"] ?>">
						<input type="hidden" name="action" value="promjenaMogucnostiKomentarisanja">
						<input type="submit" value="Omogući/onemogući komentarisanje">
												
					</form>
				<?php } }?>
				<h3> <?php print htmlEntities($vijest["naslov"], ENT_QUOTES) ?> </h3>

				<img src = " <?php print htmlEntities($vijest["linkSlike"], ENT_QUOTES) ?> " alt = " <?= $vijest["id"] ?>.alt">
				<p> <?php print htmlEntities($vijest["sadrzaj"], ENT_QUOTES) ?> </p>
				<br>
				<a href="sveNovostiAutora.php?id=<?php print $vijest["autor"] ?>">Autor novosti: <?php print $vijest["ime"]." ".$vijest["prezime"] ?></a>
				
			</article>	


			<?php } ?>

			<?php if($vijest["moguceKomentarisati"] == 1) { ?>



			<h2>Komentari</h2>
			<div id="komentariWrapper">
				<?php foreach ($komentari as $komentar) { ?>


				<article id="kom">


					<p class="vrijeme"> <?php print htmlEntities($komentar["datum"], ENT_QUOTES) ?> </p>
					<br>
					<?php if( $_SESSION["userLogged"] == "1") { 
					 if($_SESSION["tipRacuna"] == "admin") { ?>
						<form id="brisanjeKomentara" method="post" action="detaljiNovosti.php?id=<?= $vijest["id"] ?>">
							<input type="hidden" name="action" value="brisanjeKomentara">
							<input type="submit" value="Obriši komentar">
							<input type="hidden" name="komentarZaBrisati" value="<?= $komentar["id"]?>" >						
						</form>
					<?php } }?>
					<p> <?php print htmlEntities($komentar["tekst"], ENT_QUOTES) ?> </p>
					<br>
					<p> Korisničko ime autora komentara: <?php print htmlEntities($komentar["username"], ENT_QUOTES)?> </p>
					<br>

					<input type="button" value="Odgovori" id="replyBtn" onclick="odgovor(<?php print $komentar["id"] ?>)">
					<div class="odgovori" id="odgovor<?php print $komentar["id"] ?>">
						<form id="unosOdgovora" action="detaljiNovosti.php?id=<?= $id ?>" method="post">

							<textarea id="sadrzajOdgovora" name="sadrzajOdgovora" placeholder="Vaš komentar..."></textarea>
							<input type="submit" value="Komentariši" id="saveReplyBtn">
							<input type="hidden" name="roditeljskiKomentar" value="<?= $komentar["id"] ?>">
							<input type="hidden" name="action" value="dodajOdgovor">

						</form>
					</div>
				</article>

				<div id="odgovoriWrapper">
				<?php 

				$subkomentari = $connection->query("
					SELECT kd.id, kd.tekst, FROM_UNIXTIME(UNIX_TIMESTAMP(kd.vrijeme), '%d.%m.%Y 	  %H:%i:%s') datum, a.username
					FROM komentardijete kd JOIN autori a
					ON kd.autor = a.id
					WHERE kd.roditelj = '".$komentar["id"]."'				
					");

					foreach ($subkomentari as $podkomentar) { ?>

					<article id="komentOdgovori">
						<p class="vrijeme"> <?php print htmlEntities($podkomentar["datum"], ENT_QUOTES) ?> </p>
						<?php if( $_SESSION["userLogged"] == "1") { 
						 if($_SESSION["tipRacuna"] == "admin") { ?>
							<form id="brisanjeSubKomentara" method="post" action="detaljiNovosti.php?id=<?= $vijest["id"] ?>">
								<input type="hidden" name="action" value="brisanjeSubKomentara">
								<input type="submit" value="Obriši podkomentar">
								<input type="hidden" name="podkomentarZaBrisati" value="<?= $podkomentar["id"]?>" >						
							</form>
						<?php } }?>
						
						<p id="sadrzajOdgovora"> <?php print htmlEntities($podkomentar["tekst"], ENT_QUOTES) ?> </p>
						
						<p id="autorOdgovora"> Autor: <?php print htmlEntities($podkomentar["username"], ENT_QUOTES)?> </p>
						
					</article>

					<?php }

					?>
				</div>			
				<?php } ?> 


				<br><br>
				
			</div>

				<div id="komentar">
					<form id="unosKomentara" action="detaljiNovosti.php?id=<?= $id ?>" method="post">
						<textarea id="sadrzajKomentara" name="sadrzajKomentara" placeholder="Vaš komentar..."></textarea>
						<br>
						<input type="submit" value="Komentariši">
						<input type="hidden" name = "action" value="dodajKomentar">
					</form>	
				</div>

			</div>
			<?php } else {  
				print "<h2>Autor nije omogućio ostavljanje komentara!</h2>";
				 } 


			?>


			

	</body>



</html>