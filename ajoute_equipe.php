<?php 
	include './Outils/util_chap9.php';
	include './Outils/fonc_oracle.php';
	include './Outils/info_conn.php';
	include './Outils/util_chap11.php';
	
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
    <title>Ajouter une équipe</title>
  </head>
  <body>
  	<h2>Insertion d'une équipe</h2><br>
  	<form name="a" action="./MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
     </form>
     <hr>
  	<p>Champs obligatoire *</p>
  	<form name="form_ajout_equipe" action="./Traitements/ajouter_equipe_traitement.php" method="post" onsubmit="return confirm('Voulez vous vraiment insérer cette équipe ?');">
	  	<label for='nom_sponsor'>Nom du sponsor * : </label><input type="text" name="nom_sponsor" id="nom_sponsor" 
		value="<?php if($bool){verifierText("nom_sponsor");} ?>"><br>
	  	<label for='na_sponsor'>Abréviation du sponsor (3 caractères max) * : </label><input type="text" name="na_sponsor" id="na_sponsor" maxlength="3" size="3"  
		value="<?php if($bool){verifierText("na_sponsor");} ?>"><br>
	  	<label>Année de création * : </label>
		  	<select name="annee_creation">
		          <option value="" selected>....</option>
		          <?php
		            for ($year = (int)date('Y'); 1903 <= $year; $year--): ?>
		            <option value="<?=$year;?>" <?php if($bool){VerifierSelect ('annee_creation',$year);}?>><?=$year;?></option>
		            <?php endfor; ?>
		    </select><br>Pays * : 
		<?php
			include './Liste_deroulantes/pays.php';
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
			if(isset($_POST["pays"]) && $bool){
				listeDeroulantePays($conn, $_POST["pays"]);
			}
			else{
				listeDeroulantePays($conn);
			}
			FermerConnexionOCI($conn);
		?>
		<br><br>
		<input type="submit" name="env" value="Insérer">
	</form>
  </body>
</html>
