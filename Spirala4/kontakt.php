<!DOCTYPE html>

<html>
<head>
	<title>Kontakt</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="validacija.js"></script>
</head>
<body>

	<ul id="meni">
		<li> <a href="index.php">Naslovna</a> </li>
		<li> <a href="omeni.php">O meni </a> </li>	
		<li> <a href="cjenovnik.php">Cjenovnik</a> </li>
		<li> <a href="kontakt.php"> Kontakt </a> </li>
	</ul>


	<form id="kontaktForma">
		
		<table id="kontaktTabela">
			<tr>
				<td>
					<img class="ico" src="phone.png" alt="telefon">
				</td>

				<td>					
					<label>Kontakt telefon</label>			
					
				</td>				

				<td>
					<input type="text" value="067 110 10 10" readonly>
				</td>
				
			</tr>

			<tr>

				<td>					
					<img class="ico" src="mail.png" alt="mail">
				</td>

				<td>
					<label>Email</label>					
				</td>

				<td>
					<input type="email" value="adisa.lisovac@gmail.com" readonly>
						
					
				</td>
			</tr>

			<tr class="spejs">	
				<td>
					
				</td>			
				<td>
					
				</td>
				<td>
					
				</td>
			</tr>

			<tr class="spejs">
				<td id="exls" colspan="3">
				
					<a href="https://www.facebook.com/profile.php?id=100003902685218">
						<img  class="links" src="face.png" alt="face">
					</a>															
					
				
					<a href="https://www.pinterest.com/adisadeljkic/handmade-by-adisa-decoupage/">
						<img class="links" src="pin.png" alt="pinterest">
					</a>
				
				
					<a href="https://vk.com/">
						<img class="links" src="vk.png" alt="vk">
					</a>				
										
				</td>				
			</tr>

			<tr>
				<td colspan="3">
					<input id="licniPodaci" type="text" placeholder="Ime &amp; Prezime" onkeyup="imePrezimeValid()" required>
				</td>

			</tr>

			<tr>
				<td colspan="3">
					<input id="mail" type="email" placeholder="Email" onkeyup="emailValid()" required>
				</td>
			</tr>

		

			<tr>
				<td colspan="3">
					<input id="kontaktTelefon" type="tel" placeholder="Kontakt telefon" onkeyup="telValid()">
				</td>
			</tr>

			<tr>
				<td colspan="3">
					<input id="kod" type="text" value="16495">
				</td>
			</tr>	

			<tr>
				<td colspan="3">
					<input id="kodbox" type="text" placeholder="Unesite kod odozgo" onkeyup="multipleValid()">
				</td>
			</tr>	

			<tr>
				<td colspan="3">
					<textarea id="koment" placeholder="Vaš komentar?"></textarea>
				</td>				
			</tr>
		

			<tr>
				<td></td>
				<td></td>
				<td>
					<button>Pošalji</button>
				</td>
			</tr>		

		</table>
	</form>

	<div class="spejs">		
	</div>
	<div class="spejs">			
	</div>


	<table id="zadatak">
		<tr><th>Logo</th></tr>	
		<tr>
			<td>
				<div id="logo">
  					<div id="kist"></div>   
  					<label id="text">Decoupage</label>
  					<div id="suza"></div>
				</div>	
			</td>			
		</tr>

		<tr><th>Eksterni linkovi</th></tr>
		
		<tr>
			<td>
				<ul id="eksterniLinkovi">
					<li><a href="https://www.facebook.com/groups/456576674537005/?fref=ts">Decoupage Bosna i Hercegovina facebook page</a></li>
					<li><a href="https://www.facebook.com/Adisa-Lisovac-Decoupage-shopp-759441707448322/?fref=ts">Adisa Lisovac Decoupage shop</a></li>
					<li><a href="https://www.facebook.com/profile.php?id=100003902685218">Adisa Lisovac Dekupaž profil</a></li>
					
					<li><a href="https://www.pinterest.com/adisadeljkic/handmade-by-adisa-decoupage/">Pinterest page</a></li>
					<li><a href="https://twitter.com/dekupaz12">Časopis Dekupaž</a></li>
				</ul>		
			</td>
		</tr>
			
		
	</table>

</body>
</html>