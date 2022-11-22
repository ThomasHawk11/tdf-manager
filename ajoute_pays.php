<?php 
	include './Outils/util_chap9.php';
	
	if(isset($_POST["error"]) && $_POST["error"] == 1){
		$bool = true;
	}
	else{
		$bool = false;
	}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Ajouter un pays</title>
  </head>
  <body>
  	<h2>Insertion d'un pays participant au Tour de France<hr></h2>
  	<p>Champs obligatoire *</p>
  	<form name="form_ajout_annee" action="./Traitements/ajoute_pays_traitement.php" method="post" onsubmit="return confirm('Voulez vous vraiment insérer ce pays ?');">
  		<label for='nom_pays'>Nom du Pays * : </label><input type="text" name="nom_pays" id="nom_pays" 
		value="<?php if($bool){verifierText("nom_pays");} ?>"><br> 
		<br> 
		<label for='annee_creation'>Année de création * : </label><input type="tel" name="annee_creation" id="annee_creation" 
		value="<?php if($bool){verifierText("annee_creation");} ?>" maxlength="4" size="4"><br>
		<br>
		<label for='cio'>Code CIO (3 caractères) * : </label><input type="text" name="cio" id="cio" 
		value="<?php if($bool){verifierText("cio");} ?>" maxlength="3" size="3"><br>
		<br>
		<label for='iso'>Code ISO (2 caractères) * : </label><input type="text" name="iso" id="iso" 
		value="<?php if($bool){verifierText("iso");} ?>" maxlength="2" size="2"><br>
		<br><br>
		<input type="submit" name="env" value="Insérer">
	</form>
  </body>
</html>