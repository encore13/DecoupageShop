<?php
	session_start();
	$idAutora = $_GET["id"];

	$connection = new PDO("mysql:dbname=decoupageshop;host=127.11.116.130;charset=utf8", "spirala4", "spirala4");
	$rez = $connection->query("
			SELECT v.id, v.naslov, v.sadrzaj, v.linkSlike, FROM_UNIXTIME(UNIX_TIMESTAMP(v.vrijeme), '%d.%m.%Y %H:%i:%s') datum, v.autor, v.moguceKomentarisati, a.id as autornovosti, a.ime, a.prezime  
			FROM vijesti v JOIN autori a
			ON v.autor = a.id
			WHERE autor = ' ".$_GET["id"]."'


		");



	$niz=array();
		foreach ($rez->fetchAll(PDO::FETCH_ASSOC) as $novost) {
			array_push($niz, $novost);
		}

	

	
?>


<!DOCTYPE html>
<html>

<head>
	<title>Sve novosti autora</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="skripta.js"></script>
	<?php if($_SESSION["userLogged"] == "1") { ?>
		<script type="text/javascript" src="notification.js"></script>
	<?php } ?>

</head>

<body <?php if($_SESSION["userLogged"] == "1"){ ?> onload="foo2(<?= $_SESSION["IDkorisnika"] ?>)" <?php } ?> >

	<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
	</ul>

	<h2>Sve novosti - Autor: <?php print $niz[0]["ime"]." ".$niz[0]["prezime"] ?></h2>
	<div class="novosti">			
		
			<?php for($i = 0; $i < count($niz); $i++) { ?>
						<article class="novost">
						<p class="vrijeme"> <?php print htmlEntities($niz[$i]["datum"], ENT_QUOTES) ?> </p>

						<h3> <?php print htmlEntities($niz[$i]["naslov"], ENT_QUOTES) ?> </h3>
						
						<img src = " <?php print htmlEntities($niz[$i]["linkSlike"], ENT_QUOTES) ?> " alt = " <?= $niz[$i]["id"] ?>.alt">
						<p> <?php print htmlEntities($niz[$i]["sadrzaj"], ENT_QUOTES) ?> </p>
						<br>
						<p class="autor"> Autor novosti: <?php print $niz[$i]["ime"]." ".$niz[$i]["prezime"] ?></p>

						<a href="detaljiNovosti.php?id=<?php print $niz[$i]["id"] ?>" onclick="return smanjiBroj(<?php print $niz[$i]["id"] ?>)"> Detaljnije </a>	

						<?php if($_SESSION["userLogged"] == "1" && $_SESSION["IDkorisnika"] == $niz[$i]["autornovosti"]){ ?>
						<label id="brojNeprocitanihKomentaraVijesti<?php print $niz[$i]["id"] ?>"> </label>	
						<div id="broj<?php print $niz[$i]["id"] ?>"></div>
						<?php } ?>	

						</article>
				<?php } ?>			

	</div>

</body>

</html>