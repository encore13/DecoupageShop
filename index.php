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
    	$path = "credentials.txt";
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
		$handle = fopen("novosti1.csv", "r");
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
		return strtolower($a["Naslov"]) > strtolower($b["Naslov"]);
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
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="skripta.js"></script>
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
			<label id="welcomeMsg">Dobro došao, <?= $_SESSION["username"] ?> </label>
			<br>
			<input id="btnLogout" type="submit" name="logout" value="Log out">
			<input type="hidden" value="logout" name="action">
			<br>
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

			<?php 
			
			 
			for($i = 0; $i < count($niz); $i++)
			{ ?>

			<article class="novost">
				<p class="vrijeme"> <?php print htmlEntities($niz[$i]["Datum"], ENT_QUOTES) ?> </p> 
				
				<h3> <?php print htmlEntities($niz[$i]["Naslov"], ENT_QUOTES) ?> </h3>
				<img src = " <?php print htmlEntities($niz[$i]["Slika"], ENT_QUOTES) ?> " alt = " <?= $i ?>.alt">
				<p> <?php print htmlEntities($niz[$i]["Sadrzaj"], ENT_QUOTES) ?> </p>
						
				
			</article>
			
			<?php } ?>		
		

	</div>


</body>
</html>