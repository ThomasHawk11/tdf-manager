<?php
	include '../Outils/fonc_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';
	include '../Outils/util.php';
	include '../Outils/remplace_accent.php';

	//Fonction qui vérifie si un directeur présent dans la base ne participe pas à une année du TDF
	function testDirecteur($conn, $num_d, $annee){
		if($num_d != ""){
			$req = "SELECT count(*) as NB from tdf_parti_equipe
					where annee = :annee
					and (n_pre_directeur = :dir or n_sec_directeur = :dir or n_troi_directeur = :dir)";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":dir",$num_d, 5);
			ajouterParamOCI($cur,":annee",$annee, 5);
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);

			if($tab[0]["NB"] != 0){
				return true;
			}
		}
		return false;
	}

	//Fonction qui test si un champ est ne possède pas de valeur inutilisable dans notre contexte
	// d'ajout d'une participation d'équipe
	function directeurVide($num_d){
		if($num_d == ""){
			return NULL;
		}
		return $num_d;
	}

	//Fonction qui renvoie un tableau trier où sont rangé les directeur de 1 à 3
	// Dans le cas où certain sont NULL on les place à la fin du tableau pour faciliter l'insertion dans la BDD
	function rangeDirecteur($dir_1, $dir_2, $dir_3){
		$tab[0] = $dir_1;
		$tab[1] = $dir_2;
		$tab[2] = $dir_3;

		for($i = 0 ; $i<3 ; $i++){
			if($tab[0] == NULL and $tab[1] != NULL){
				$temp = $tab[0];
				$tab[0] = $tab[1];
				$tab[1] = $temp;
			}
			if($tab[1] == NULL and $tab[2] != NULL){
				$temp = $tab[1];
				$tab[1] = $tab[2];
				$tab[2] = $temp;
			}
		}
		return $tab;
	}

	$erreur = true;

	//Formulaire pour renvoyer les infos à la page d'insertion de participation d'équipe si il y a une erreur
	echo"<form name='form_retour' action='../ajoute_parti_equipe.php' method='post'>";

	if(isset($_POST["env"])){
		$erreur = false;

		//Traitements des variables normalement envoyé en POST
		if(isset($_POST["annee_parti"]) and $_POST["annee_parti"] != ""){
			$annee_parti = $_POST['annee_parti'];
			echo "<input type='hidden' id='annee_parti' name='annee_parti' value='$annee_parti'>";
		}
		else{
			echo "erreur manque l'année de participation </br>";
			$erreur = true;
		}

		//Traitement de l'équipe active choisie
		if(isset($_POST["sponsors"]) and $_POST["sponsors"] != ""){
			$sponsors = $_POST['sponsors'];
			echo "<input type='hidden' id='sponsors' name='sponsors' value='$sponsors'>";
		}
		else{
			echo "erreur manque l'équipe active qui doit participer </br>";
			$erreur = true;
		}

		//Traitement du/des directeur(s)
		if(isset($_POST["directeur1"]) and isset($_POST["directeur2"]) and isset($_POST["directeur3"])){
			$dir1 = $_POST["directeur1"];
			$dir2 = $_POST["directeur2"];
			$dir3 = $_POST["directeur3"];

			if($dir1 == "" and $dir2 == "" and $dir3 == ""){
				echo "erreur manque les directeurs</br>";
				$erreur = true;
			}
			else if(($dir1 == $dir2 and $dir1 != "" and $dir2 != "")
			or ($dir2 == $dir3 and $dir2 != "" and $dir3 != "")
			or ($dir1 == $dir3 and $dir1 != "" and $dir3 != "")){
				echo "erreur un même directeur est choisis plusieurs fois</br>";
				$erreur = true;
			}
		}
		else{
			echo "erreur manque les directeurs</br>";
			$erreur = true;
		}
			


		//Vérifie que les directeurs ne participe déjà pas à un TDF de la même année
		if($erreur == false){
			
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$erreur = testDirecteur($conn, $dir1, $annee_parti) || testDirecteur($conn, $dir2, $annee_parti) || testDirecteur($conn, $dir3, $annee_parti);
			
			FermerConnexionOCI($conn);

			if($erreur){
				echo "Un des directeur participe déjà au TDF de l'année choisie</br>";
			}
		}

		//Test si l'équipe ne participe pas déjà au TDF de cette année
		if($erreur == false){
			$tableau = explode(":", $sponsors);
			$n_equipe = $tableau[0];
			$n_sponsor = $tableau[1];

			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
			$req = "SELECT count(*) as NB from tdf_parti_equipe
					where annee = :annee and n_equipe = :n_equipe";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":annee",$annee_parti, 4);
			ajouterParamOCI($cur,":n_equipe",$n_equipe, 5);
			
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);
			FermerConnexionOCI($conn);

			if($tab[0]["NB"] != 0){
				$erreur = true;
				echo "L'équipe participe déjà au TDF de l'année choisie !</br>";
			}
		}

		//Insertion de la participation dans la table tdf_parti_equipe et de l'année dans tdf_annee au besoin
		if($erreur == false){

			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
			//Test pour savoir la présence de l'année sinon insertion de l'année dans la table tdf_annee
			$req = "SELECT count(*) as NB from tdf_annee 
					where annee = :annee";
			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":annee",$annee_parti, 4);
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);
			
			//Insertion par défaut d'une nouvelle année avec les critères d'un TDF selon l'énoncé et
			// en moyenne se que l'on peut voir dans la BDD (8 coureurs par équipe et 2 jour de repos)
			if($tab[0]["NB"] == 0){
				$req = "INSERT INTO tdf_annee (annee, jour_repos, nb_coureurs_par_equipe, compte_oracle, date_insert)
				VALUES (:annee,
						2,
						8,
						'PPHP2A_09',
						sysdate)";
				$cur = PreparerRequeteOCI($conn,$req);
				ajouterParamOCI($cur,":annee",$annee_parti, 4);
				$res = ExecuterRequeteOCI($cur);
			}

			//Insertion de la participation de l'équipe à l'année choisie
			$req = "INSERT into tdf_parti_equipe (annee, n_equipe, n_sponsor, n_pre_directeur, n_sec_directeur, n_troi_directeur, compte_oracle, date_insert)
			values (:annee,
					:n_equipe,
					:n_sponsor,
					:n_pre_directeur,
					:n_sec_directeur,
					:n_troi_directeur,
					'PPHP2A_09',
					sysdate)";

			$cur = PreparerRequeteOCI($conn,$req);

			$tab_directeur = rangeDirecteur(directeurVide($dir1), directeurVide($dir2), directeurVide($dir3));

			ajouterParamOCI($cur,":annee",$annee_parti, 4);
			ajouterParamOCI($cur,":n_equipe",$n_equipe, 5);
			ajouterParamOCI($cur,":n_sponsor",$n_sponsor, 5);
			ajouterParamOCI($cur,":n_pre_directeur",$tab_directeur[0], 5);
			ajouterParamOCI($cur,":n_sec_directeur",$tab_directeur[1], 5);
			ajouterParamOCI($cur,":n_troi_directeur",$tab_directeur[2], 5);

			$res = ExecuterRequeteOCI($cur);
			$committed = ValiderTransacOCI($conn);
			FermerConnexionOCI($conn);
			//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
			//0 = non et 1 = oui
			echo "<input type='hidden' id='error' name='error' value='0'>Cette équipe participera au Tour de France ".$annee_parti." !";

		}
	}

	//En cas d'erreur avec les données du formulaire envoyées
	if ($erreur == true){
		//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
		//0 = non et 1 = oui
		echo "<input type='hidden' id='error' name='error' value='1'>";

		if(isset($_POST["directeur1"]) and $_POST["directeur1"] != ""){
			echo "<input type='hidden' id='directeur1' name='directeur1' value='".$_POST["directeur1"]."'>";
		}
		if(isset($_POST["directeur2"]) and $_POST["directeur2"] != ""){
			echo "<input type='hidden' id='directeur2' name='directeur2' value='".$_POST["directeur2"]."'>";
		}
		if(isset($_POST["directeur3"]) and $_POST["directeur3"] != ""){
			echo "<input type='hidden' id='directeur3' name='directeur3' value='".$_POST["directeur3"]."'>";
		}
	}

	//Bouton qui envoie un formulaire à la page de base avec les données utiles
	echo "<hr>
	<input type='submit' name='env' value='Retour'>
  	</form>";
?>