<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>fonctions d'accés aux bases de données</title>
  </head>
  <body>
<?php
	include '../Outils/fonc_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';
	include '../Outils/util.php';

	//Vérifie si le coureur peut être supprimé de la BDD
	function CheckSuppression($n_coureur, $donnees, $nbLignes){
		if(isset($n_coureur) && !empty($n_coureur)
		&& isset($donnees)){

			for($i = 0; $i < $nbLignes; $i++){
				if($donnees[$i]['N_COUREUR'] == $n_coureur)
				return "disabled";
			}
		}
		return "";
	}

	//Fonction d'affichage pour la table TDF_COUREUR et TDF_APP_NATION
	function AfficherDonnee_Table_Coureur_Nation($tab,$nbLignes,$conn){
		if(isset($tab) && isset($nbLignes) && isset($conn)){
			//Récup des numéros de coureur des participant au TDF
			$req = "SELECT distinct n_coureur as N_COUREUR
			FROM tdf_parti_coureur
			ORDER BY N_COUREUR ASC";

			$cur = PreparerRequeteOCI($conn,$req);
			$res = ExecuterRequeteOCI($cur);
			$nb = LireDonneesOCI1($cur,$donnees);

			if ($nbLignes > 0) {
				echo "<table border=\"1\">\n";
				echo "<tr>\n";

				foreach ($tab[0] as $key => $val){ //En-tête des colonnes
					if($key != "N_COUREUR"){
						echo "<th>$key</th>\n";
					}
				}
				echo "<th>MODIFIER</th>\n<th>SUPPRIMER</th>\n<th>INFO COUREUR</th>\n";
				echo "</tr>\n";

				//echo $nbLignes;
				for ($i = 0; $i < $nbLignes; $i++){ // balayage de toutes les lignes
				echo "<tr>\n";

				foreach ($tab[$i] as $key => $val){ // lecture des enregistrements de chaque colonne
					if($key != "N_COUREUR"){
						echo "<td>$val</td>\n";
					}
					else{
						//Bouton modifier
						echo "<td align='center'><form name='modif' action='./modifier_coureur.php' method='get'>
						<input type='submit' name='env' value='Modifier'>
						<input type='hidden' name='id' value='$val'/></form></td>";

						//Bouton supprimmer
						echo "<td align='center'><button name='$val' id='$val'".CheckSuppression($val,$donnees,$nb)." onclick='fctSuppr($val)'>Supprimer</td>";

						//Bouton info générales
						echo "<td align='center'><form name='info' action='./infos_generales.php' method='get'>
						<input type='submit' name='env' value='Info'>
						<input type='hidden' name='id' value='$val'/></form></td>";
					}
				}
				echo "</tr>\n";
				}
				echo "</table>\n";
			} 
			else {
				echo "Pas de ligne<br />\n";
			}
		}
		else {
			echo "Variables non-définis<br />\n";
		}
	}

	if (!empty($_GET['ordre']) && isset($_GET['ordre'])
	 && !empty($_GET['type']) && isset($_GET['type'])){

		//$conn = OuvrirConnexionOCI('ETU000', 'XXX','localhost:1521/xe');
		$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'], $infoConn['instanceOCI']);

		$ordre2 = $_GET['ordre'];
		$type2 = $_GET['type'];

		//Requête SQL de récupération des infos des coureurs selon l'énoncé
		$req = "SELECT tdf_coureur.nom as NOM, prenom as PRENOM, annee_naissance as ANNEE, nvl(tdf_nation.nom, 'N/D') as PAYS, tdf_coureur.n_coureur as N_COUREUR
				FROM tdf_coureur
				LEFT JOIN tdf_app_nation on tdf_coureur.n_coureur = tdf_app_nation.n_coureur
				LEFT JOIN tdf_nation on tdf_app_nation.code_cio = tdf_nation.code_cio
				WHERE annee_fin is null

				union

				SELECT tdf_coureur.nom as NOM, prenom as PRENOM, annee_naissance as ANNEE, nvl(tdf_nation.nom, 'N/D') as PAYS, tdf_coureur.n_coureur as N_COUREUR
				FROM tdf_coureur
				JOIN tdf_app_nation on tdf_coureur.n_coureur = tdf_app_nation.n_coureur
				JOIN tdf_nation on tdf_app_nation.code_cio = tdf_nation.code_cio
				WHERE tdf_coureur.n_coureur in 
                (
                    select n_coureur from tdf_app_nation
                    group by n_coureur
                    having count(*) < 2
                )
               and annee_fin is not null
               ORDER by ".$type2." ".$ordre2."";
               


		$cur = PreparerRequeteOCI($conn,$req);
		$res = ExecuterRequeteOCI($cur);
		$nb = LireDonneesOCI1($cur,$donnees);

		//AfficherTab($donnees);
		AfficherDonnee_Table_Coureur_Nation($donnees,$nb,$conn);

		FermerConnexionOCI($conn);
	}
	else{
		//Même chose que au dessus pour de la sécurité en cas d'erreurs
		$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'], $infoConn['instanceOCI']);
		
		$req = "SELECT tdf_coureur.nom as NOM, prenom as PRENOM, annee_naissance as ANNEE, nvl(tdf_nation.nom, 'N/D') as PAYS, tdf_coureur.n_coureur as N_COUREUR
				FROM tdf_coureur
				LEFT JOIN tdf_app_nation on tdf_coureur.n_coureur = tdf_app_nation.n_coureur
				LEFT JOIN tdf_nation on tdf_app_nation.code_cio = tdf_nation.code_cio
				WHERE annee_fin is null

				union

				SELECT tdf_coureur.nom as NOM, prenom as PRENOM, annee_naissance as ANNEE, nvl(tdf_nation.nom, 'N/D') as PAYS, tdf_coureur.n_coureur as N_COUREUR
				FROM tdf_coureur
				JOIN tdf_app_nation on tdf_coureur.n_coureur = tdf_app_nation.n_coureur
				JOIN tdf_nation on tdf_app_nation.code_cio = tdf_nation.code_cio
				WHERE tdf_coureur.n_coureur in 
                (
                    select n_coureur from tdf_app_nation
                    group by n_coureur
                    having count(*) < 2
                )
               and annee_fin is not null
               ORDER by NOM ASC";

		$cur = PreparerRequeteOCI($conn,$req);
		$res = ExecuterRequeteOCI($cur);
		$nb = LireDonneesOCI1($cur,$donnees);

		//AfficherTab($donnees);
		AfficherDonnee_Table_Coureur_Nation($donnees,$nb,$conn);

		FermerConnexionOCI($conn);
	}
?>