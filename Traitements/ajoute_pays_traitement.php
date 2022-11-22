<?php
	include '../Outils/fonc_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';
	include '../Outils/remplace_accent.php';

	$erreur = true;
	//Formulaire pour renvoyer les infos à la page d'insertion de pays si il y a une erreur
	echo"<form name='form_retour' action='../ajoute_pays.php' method='post'>";

	if(isset($_POST["env"])){
		$erreur = false;

		//Traitements des variables normalement envoyé en POST
		if(!empty($_POST["nom_pays"])){
			$nom_pays = $_POST["nom_pays"];
			echo "<input type='hidden' id='nom_pays' name='nom_pays' value='$nom_pays'>";
		}
		else{
			echo "erreur manque le nom du pays à inserer</br>";
			$erreur = true;
		}

		if(!empty($_POST["annee_creation"])){
			$annee_creation = $_POST["annee_creation"];
			echo "<input type='hidden' id='annee_creation' name='annee_creation' value='$annee_creation'>";
		}
		else{
			echo "erreur manque l'année de création de repos</br>";
			$erreur = true;
		}
		if(!empty($_POST["annee_creation"]) and !is_numeric($_POST["annee_creation"])){
			echo "erreur le champ d'année de création n'est pas un nombre entier</br>";
			$erreur = true;
		}

		if(!empty($_POST["cio"]) and mb_strlen($_POST["cio"]) == 3){
			$cio = $_POST["cio"];
			echo "<input type='hidden' id='cio' name='cio' value='$cio'>";
		}
		else{
			echo "erreur manque le code CIO du pays ou pas le bon nombre de caractère</br>";
			$erreur = true;
		}
		
		if(!empty($_POST["iso"]) and mb_strlen($_POST["iso"]) == 2){
			$iso = $_POST["iso"];
			echo "<input type='hidden' id='iso' name='iso' value='$iso'>";
		}
		else{
			echo "erreur manque le code ISO du pays ou pas le bon nombre de caractère</br>";
			$erreur = true;
		}

		//Test si le pays n'est pas déjà présente
		if($erreur == false){
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$cio = trim(RemplaceAccentsNom(mb_strtoupper($cio)));
			$iso = trim(RemplaceAccentsNom(mb_strtoupper($iso)));
			$nom_pays = trim(RemplaceAccentsNom(mb_strtoupper($nom_pays)));

			$sql_cio = Apostrophe_DOUBLET_SQL($cio);
			$sql_iso = Apostrophe_DOUBLET_SQL($iso);
			$sql_nom_pays = Apostrophe_DOUBLET_SQL($nom_pays);

			$annee_creation = trim($annee_creation);

			$req = "SELECT count(*) as NB from tdf_nation 
					where code_cio = :cio";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":cio",$sql_cio, 3);
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);
			FermerConnexionOCI($conn);

			if($tab[0]["NB"] != 0){
				$erreur = true;
				echo "erreur le pays est déjà présente dans la base ! Changez le code CIO !</br>";
			}
		}

		//Insertion du pays si aucune erreur
		if($erreur == false){
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$req = "INSERT into tdf_nation (code_cio, code_iso, nom, annee_creation, compte_oracle, date_insert)
					VALUES (:cio,
							:iso,
							:nom,
							:annee,
							'PPHP2A_09',
							sysdate)";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":cio",$cio, 3);
			ajouterParamOCI($cur,":iso",$iso, 2);
			ajouterParamOCI($cur,":nom",$nom_pays, 64);
			ajouterParamOCI($cur,":annee",$annee_creation, 3);
			$res = ExecuterRequeteOCI($cur);
			$committed = ValiderTransacOCI($conn);
			FermerConnexionOCI($conn);

			//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
			echo "<input type='hidden' id='error' name='error' value='0'>Le pays est bien insérer !";
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