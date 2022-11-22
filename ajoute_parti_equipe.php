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
    <title>Ajouter une participation d'une équipe</title>
  </head>
  <body>
  	<h2>Insertion d'une participation d'une équipe<br></h2>
  	<form name="a" action="./MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
     </form><br>
    <form name="b" action="./ajoute_equipe.php" method="post">
         <input type="submit" value="Ajouter une équipe" style="display:inline-block;"> 
     </form><br>
    <form name="c" action="./ajoute_pays.php" method="post">
         <input type="submit" value="Ajouter un pays" style="display:inline-block;"> 
     </form>
     <hr>
  	<p>Champs obligatoire *</p>
  	<form name="form_ajout_parti_equipe" action="./Traitements/ajouter_parti_equipe_traitement.php" method="post" onsubmit="return confirm('Voulez vous vraiment insérer cette participation ?');">

	  	<label>Année de participation * : </label>
		<select name="annee_parti">
        	<option value="" selected>....</option>
	        <?php
	        	for ($year = (int)date('Y') + 1; (int)date('Y') + 11 >= $year; $year++): 
	        ?>
	        <option value="<?=$year;?>" 
	        	<?php 
	        		if($bool){VerifierSelect ('annee_parti',$year);}
	        	?>>
	        	<?=$year;?>
	        </option>
        	<?php endfor; ?>
	    </select>
		</br>
		<?php
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			include './Liste_deroulantes/sponsors_actifs.php';
			echo "</br>Équipe * : ";
			if(isset($_POST["sponsors"]) && $bool){
				listeDeroulanteSponsors($conn, $_POST["sponsors"]);
			}
			else{
				listeDeroulanteSponsors($conn);
			}

			include './Liste_deroulantes/directeur.php';
			echo "</br></br>Directeur 1 * : ";
			if(isset($_POST["directeur1"]) && $bool){
				listeDeroulanteDirecteur($conn, $_POST["directeur1"], '1');
			}
			else{
				listeDeroulanteDirecteur($conn, '', '1');
			}

			echo "</br></br>Directeur 2 : &nbsp";
			if(isset($_POST["directeur2"]) && $bool){
				listeDeroulanteDirecteur($conn, $_POST["directeur2"], '2');
			}
			else{
				listeDeroulanteDirecteur($conn, '', '2');
			}

			echo "</br></br>Directeur 3 : &nbsp";
			if(isset($_POST["directeur3"]) && $bool){
				listeDeroulanteDirecteur($conn, $_POST["directeur3"], '3');
			}
			else{
				listeDeroulanteDirecteur($conn, '', '3');
			}

			FermerConnexionOCI($conn);
		?>
		<br>
		<br>
		<input type="submit" name="env" value="Insérer">
	</form>
  </body>
</html>