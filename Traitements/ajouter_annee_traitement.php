<?php
	include '../Outils/fonc_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';

	$erreur = true;
	//Formulaire pour renvoyer les infos à la page d'insertion d'année si il y a une erreur durant le traitement
	echo"<form name='form_retour' action='../ajoute_annee.php' method='post'>";

	if(isset($_POST["env"])){
		$erreur = false;

		//Traitements des variables normalement envoyé en POST
		if(!empty($_POST["annee"])){
			$annee = $_POST["annee"];
			echo "<input type='hidden' id='annee' name='annee' value='$annee'>";
		}
		else{
			echo "erreur manque l'année à inserer</br>";
			$erreur = true;
		}
		if(!empty($_POST["annee"]) and !is_numeric($_POST["annee"]) or (int)$annee <1903){
			echo "erreur le champ année n'est pas un nombre entier positif supérieur ou égal à 1903</br>";
			$erreur = true;
		}

		if(!empty($_POST["jour_repos"])){
			$jour_repos = $_POST["jour_repos"];
			echo "<input type='hidden' id='jour_repos' name='jour_repos' value='$jour_repos'>";
		}
		else{
			echo "erreur manque le nombre de jours de repos</br>";
			$erreur = true;
		}
		if(!empty($_POST["jour_repos"]) and !is_numeric($_POST["jour_repos"]) or (int)$jour_repos <0){
			echo "erreur le champ des jours de repos n'est pas un nombre entier positif</br>";
			$erreur = true;
		}

		if(!empty($_POST["nb_coureur"])){
			$nb_coureur = $_POST["nb_coureur"];
			echo "<input type='hidden' id='nb_coureur' name='nb_coureur' value='$nb_coureur'>";
		}
		else{
			echo "erreur manque le nombre de coureur par équipe</br>";
			$erreur = true;
		}
		if(!empty($_POST["nb_coureur"]) and !is_numeric($_POST["nb_coureur"]) or (int)$nb_coureur <0){
			echo "erreur le champ des nombre de coureur n'est pas un nombre entier positif</br>";
			$erreur = true;
		}

		//Test si l'année n'est pas déjà présente dans la BDD
		if($erreur == false){
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$req = "SELECT  count(*) as NB from tdf_annee
					where annee = :annee";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":annee",$annee, 4);
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);
			FermerConnexionOCI($conn);

			if($tab[0]["NB"] != 0){
				$erreur = true;
				echo "erreur l'année est déjà présente dans la base !</br>";
			}
		}

		//Insertion de l'année si aucune erreur
		if($erreur == false){
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$req = "INSERT into tdf_annee (annee, jour_repos, nb_coureurs_par_equipe, compte_oracle, date_insert)
					VALUES (:annee,
							:jour_repos,
							:nb_coureur,
							'PPHP2A_09',
							sysdate)";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":annee",$annee, 4);
			ajouterParamOCI($cur,":jour_repos",$jour_repos, 2);
			ajouterParamOCI($cur,":nb_coureur",$nb_coureur, 2);
			$res = ExecuterRequeteOCI($cur);
			$committed = ValiderTransacOCI($conn);
			FermerConnexionOCI($conn);

			//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
			echo "<input type='hidden' id='error' name='error' value='0'>L'année est bien insérer !";
		}
	}
	if ($erreur == true){
		//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
		echo "<input type='hidden' id='error' name='error' value='1'>";
	}
	//Bouton qui envoie un formulaire à la page de base
	echo "<hr>
	<input type='submit' name='env' value='Retour'>
  	</form>";
?>