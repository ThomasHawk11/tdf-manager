<?php
	include '../Outils/fonc_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';
	include '../Outils/util.php';
	include '../Outils/remplace_accent.php';
	$erreur = true;
	//Formulaire pour renvoyer les infos à la page d'insertion d'équipe si il y a une erreur
	echo"<form name='form_retour' action='../ajoute_equipe.php' method='post'>";

	if(isset($_POST["env"])){
		$erreur = false;

		//Traitements des variables normalement envoyé en POST
		if(!empty($_POST["nom_sponsor"])){
			$nom_sponsor = $_POST["nom_sponsor"];
			echo "<input type='hidden' id='nom_sponsor' name='nom_sponsor' value='$nom_sponsor'>";
		}
		else{
			echo "erreur manque le nom du sponsor</br>";
			$erreur = true;
		}
		if(!empty($_POST["na_sponsor"]) && mb_strlen($_POST["na_sponsor"]) <= 3){
			$na_sponsor = $_POST['na_sponsor'];
			echo "<input type='hidden' id='na_sponsor' name='na_sponsor' value='$na_sponsor'>";
		}
		else{
			echo "erreur manque l'abréviation ou trop longue</br>";
			$erreur = true;
		}
		if(!empty($_POST["annee_creation"])){
			$annee_creation = $_POST['annee_creation'];
			echo "<input type='hidden' id='annee_creation' name='annee_creation' value='$annee_creation'>";
		}
		else{
			echo "erreur manque l'année de creation </br>";
			$erreur = true;
		}
		if(!empty($_POST["pays"])){
			$pays = $_POST['pays'];
			echo "<input type='hidden' id='pays' name='pays' value='$pays'>";
		}
		else{
			echo "erreur manque le pays </br>";
			$erreur = true;
		}

		//Vérifie que l'équipe n'est pas déjà dans la BDD
		if($erreur == false){
			//Mise en forme des données à mettre dans la BDD selon l'énoncer

			$na_sponsor = trim(RemplaceAccentsNom(mb_strtoupper($na_sponsor)));
			$nom_sponsor = trim(RemplaceAccentsNom(mb_strtoupper($nom_sponsor)));

			$sql_nom = Apostrophe_DOUBLET_SQL($nom_sponsor);
			$sql_na = Apostrophe_DOUBLET_SQL($na_sponsor);

			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

			$req = "SELECT count(*) as NB from tdf_sponsor join
			(
				select n_equipe,max(n_sponsor) as n_sponsor from tdf_sponsor 
				join tdf_equipe using(n_equipe) where annee_disparition is null
				group by n_equipe
			)
			using(n_equipe,n_sponsor)
			where nom = :nom and na_sponsor = :na";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":nom",$nom_sponsor, 128);
			ajouterParamOCI($cur,":na",$na_sponsor, 3);
			$res = ExecuterRequeteOCI($cur);
			$nbLignes = LireDonneesOCI1($cur,$tab);
			FermerConnexionOCI($conn);

			if($tab[0]["NB"] != 0){
				$erreur = true;
				echo "L'équipe existe déjà et est active !</br>";
			}
		}
		
		//Insertion de l'équipe si aucune erreur
		if($erreur == false){
			$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
			$req = "INSERT into tdf_equipe (n_equipe, annee_creation, annee_disparition) 
			VALUES ((SELECT max(n_equipe) as MAX_NUM from tdf_equipe) + 1,
					:annee,
					NULL)";

			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":annee",$annee_creation, 4);
			$res = ExecuterRequeteOCI($cur);


			$req = "INSERT into tdf_sponsor (n_equipe, n_sponsor, code_cio, nom, na_sponsor, annee_sponsor, compte_oracle, date_insert)
			VALUES ((SELECT max(n_equipe) as MAX_NUM from tdf_equipe),
					'1',
					:cio,
					:nom,
					:na,
					:annee,
					'PPHP2A_09',
					sysdate)";
			$cur = PreparerRequeteOCI($conn,$req);
			ajouterParamOCI($cur,":nom",$nom_sponsor, 64);
			ajouterParamOCI($cur,":na",$na_sponsor, 3);
			ajouterParamOCI($cur,":cio",$pays, 3);
			ajouterParamOCI($cur,":annee",$annee_creation, 4);
			$res = ExecuterRequeteOCI($cur);
			$committed = ValiderTransacOCI($conn);
			FermerConnexionOCI($conn);
			//Valeur caché qui va indiquer si oui ou non on remet les données dans les inputs du formulaire d'ajout d'équipe
			echo "<input type='hidden' id='error' name='error' value='0'>L'équipe est bien insérée !";

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