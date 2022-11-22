<?php 
	include './Outils/util_chap9.php';
	//Booléen pour savoir si je dois préremplir/présélectionner les champs du formulaire
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
    <title>Ajouter une année</title>
  </head>
  <body>
  	<h2>Insertion d'une année du Tour de France<br></h2>
  	<form name="a" action="./MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
     </form><hr>
  	<p>Champs obligatoire *</p>
  	<form name="form_ajout_annee" action="./Traitements/ajouter_annee_traitement.php" method="post" onsubmit="return confirm('Voulez vous vraiment insérer cette année ?');">
  		<label for='nom_sponsor'>Année * : </label><input type="tel" name="annee" id="annee" 
		value="<?php if($bool){verifierText("annee");} ?>" maxlength="4" size="4"><br>
		<label for='jour_repos'>Nombre de jour de repos * : </label><input type="tel" name="jour_repos" id="jour_repos" 
		value="<?php if($bool){verifierText("jour_repos");} ?>" maxlength="2" size="2"><br>
		<label for='nb_coureur'>Nombre de coureurs par équipe * : </label><input type="tel" name="nb_coureur" id="nb_coureur" 
		value="<?php if($bool){verifierText("nb_coureur");} ?>" maxlength="2" size="2"><br>
		<br><br>
		<input type="submit" name="env" value="Insérer">
	</form>
  </body>
</html>