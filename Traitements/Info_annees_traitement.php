<?php
    include_once "../Outils/fonc_oracle.php";
    include_once "../Outils/util_chap11.php";
    include_once '../Outils/info_conn.php';
    include_once '../Outils/util.php';

    $conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

    function AfficherDonnee_Ferdi($tab,$nbLignes,$conn){
		if(isset($tab) && isset($nbLignes) && isset($conn)){
			if ($nbLignes > 0) {
				echo "<table border=\"1\">\n";
				echo "<tr>\n";

				foreach ($tab[0] as $key => $val){ //En-tête des colonnes
					echo "<th>$key</th>\n";
				}
				echo "</tr>\n";

				//echo $nbLignes;
				for ($i = 0; $i < $nbLignes; $i++){ // balayage de toutes les lignes
				echo "<tr>\n";

				foreach ($tab[$i] as $key => $val){ // lecture des enregistrements de chaque colonne
						echo "<td>$val</td>\n";
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

    if(!empty($_POST['annee_selectionnee']) ){
    	$annee_selectionnee = $_POST['annee_selectionnee'];
    	$sql = "SELECT '".$annee_selectionnee."' FROM tdf_annee";
    	$cur = PreparerRequeteOCI($conn,$sql);
    	$res = ExecuterRequeteOCI($cur);
    	$nb = LireDonneesOCI1($cur,$donnees);
    }


    echo'<h2>Classement Général :</h2>';
    $sql = "SELECT rang_arrivee, n_coureur as NUMERO_COUREUR, nom as NOM_COUREUR, prenom as PRENOM_COUREUR, code_pays as PAYS, equipe, temps as TEMPS_SECONDE from tdf_classements_generaux where annee = 
			:annee and n_coureur not in
			(
    			select n_coureur from tdf_abandon where c_typeaban like 'DO' 
    			union
    			select n_coureur from tdf_abandon where c_typeaban like 'EX'
			)
			order by rang_arrivee";
    $cur = PreparerRequeteOCI($conn, $sql);
    ajouterParamOCI($cur,":annee",$annee_selectionnee, 4);
    $res = ExecuterRequeteOCI($cur);
    $nb = LireDonneesOCI1($cur, $donnees);
    AfficherDonnee_Ferdi($donnees, $nb, $conn); //Crée un tableau du classement général à partir d'une requête qui trie les coureurs en fonction de leurs rang_arrivee et qui enlève les tricheurs.

    echo'<hr>';

    echo'<h2>Les étapes et leur gagnant respectif :</h2>';
    $sql = "SELECT n_etape, n_coureur , nom as NOM_COUREUR, prenom as PRENOM_COUREUR, total_seconde as TEMPS_SECONDE from tdf_temps 
			join tdf_coureur using (n_coureur)
			where rang_arrivee = 1 and annee = :annee
			order by n_etape";
    $cur = PreparerRequeteOCI($conn, $sql);
    ajouterParamOCI($cur,":annee",$annee_selectionnee, 4);
    $res = ExecuterRequeteOCI($cur);
    $nb = LireDonneesOCI1($cur, $donnees);
    AfficherDonnee_Ferdi($donnees, $nb, $conn); //Crée un tableau des étapes et de leur gagnant.

    echo'<hr>';

    echo'<h2>Les participants et leur sponsor :</h2>';
    $sql = "SELECT n_coureur, tdf_coureur.nom as NOM_COUREUR, tdf_coureur.prenom as PRENOM_COUREUR , n_sponsor, tdf_sponsor.nom as NOM_SPONSOR from tdf_parti_coureur 
			join tdf_sponsor using (n_equipe, n_sponsor)
			join tdf_coureur using (n_coureur)
			where annee = :annee
			order by tdf_coureur.nom";
    $cur = PreparerRequeteOCI($conn, $sql);
    ajouterParamOCI($cur,":annee",$annee_selectionnee, 4);
    $res = ExecuterRequeteOCI($cur);
    $nb = LireDonneesOCI1($cur, $donnees);
    AfficherDonnee_Ferdi($donnees, $nb, $conn); //Créer un tableau des participants du tour de France de l'année voulue et de leur sponsor.

    echo'<hr>';

    echo'<h2>Les abandons :</h2>';
    $sql = "SELECT n_coureur, nom as NOM, prenom as PRENOM, libelle as LIBELLE from tdf_abandon 
			join tdf_typeaban using (c_typeaban)
			join tdf_coureur using (n_coureur)
			where annee = :annee
			order by nom";
    $cur = PreparerRequeteOCI($conn, $sql);
    ajouterParamOCI($cur,":annee",$annee_selectionnee, 4);
    $res = ExecuterRequeteOCI($cur);
    $nb = LireDonneesOCI1($cur, $donnees);
    AfficherDonnee_Ferdi($donnees, $nb, $conn); //Créer un tableau de tout les abandons d'une année.


   	FermerConnexionOCI($conn);

    echo'<hr>';

    echo'<form method = "POST" action ="../Info_annees.php">
    		<input type = "submit" value = "Retour"/>
    	</form><br>
        <form name="a" action="../MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
        </form>';


    //afficherTab("bonjour");
?>