<?php
    session_start();
    include_once "../Outils/fonc_oracle.php";
    include_once "../Outils/util_chap11.php";
    include_once '../Outils/info_conn.php';
    include_once '../Outils/util.php';
    include_once '../Outils/remplace_accent.php';
    include_once '../Outils/verificationChamps.php';
    include_once "../Outils/util_chap9.php";

    if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pays']) ){ 
        $_SESSION['nom'] = $_POST['nom'];
        $nom  = preg_replace('/\s+/', ' ',trim(RemplaceAccentsNom(mb_strtoupper(($_POST['nom'])))));
        $_SESSION['prenom'] = $_POST['prenom'];
        $prenom  = preg_replace('/\s+/', ' ',trim(RemplaceAccentsPrenom(ucwords_accent(mb_strtolower($_POST['prenom'])))));
        $annee_prem = $_SESSION['annee_prem'] = $_POST['annee_prem'];
        $annee_naissance = $_SESSION['annee_naissance'] = $_POST['annee_naissance'];
        $pays = $_POST['pays'];
		
        //Modification des chaînes de caractères (double les apostrophes) pour faire des SELECT en SQL
		$nom_sql = Apostrophe_DOUBLET_SQL($nom);
        $prenom_sql = Apostrophe_DOUBLET_SQL($prenom);

        //Vérifie si l'association nom-prénom existe déjà dans la BDD
        $conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
        $sql = "SELECT n_coureur FROM tdf_coureur WHERE nom='".$nom_sql."' and prenom='".$prenom_sql."'";
        $cur = PreparerRequeteOCI($conn,$sql);
        $res = ExecuterRequeteOCI($cur);
        $nb = LireDonneesOCI1($cur,$donnees);
        if(!empty($donnees)){ 
            echo '<hr>Le coureur existe déjà !<hr>';
        }elseif(!($annee_naissance=="") && (!($annee_naissance >= (int)date('Y')-60))){
            echo '<hr>Date de naissance minimum : '.(date('Y')-60).' !<hr>';
        }elseif(!($annee_prem=="") && !($annee_naissance=="") && (!($annee_prem>=$annee_naissance+20))){
            echo '<hr>Le coureur est trop jeune pour avoir fait le Tour de France en '.$annee_prem.' !<hr>';
        }elseif(!CheckNom($nom)){
            echo '<hr>Nom du coureur invalide !<hr>';
        }elseif(!CheckPrenom($prenom)){
            echo '<hr>Prénom du coureur invalide !<hr>';
        }else{
            //Insertion du coureur
            $sql2 = "INSERT INTO tdf_coureur VALUES(inc_n_coureur.nextval,:nom,:prenom,:annee_naissance,:annee_prem, 'PHP2A_09', sysdate)";
            $cur = PreparerRequeteOCI($conn,$sql2);
            ajouterParamOCI($cur,":nom",$nom, 64);
            ajouterParamOCI($cur,":prenom",$prenom, 64);
            ajouterParamOCI($cur,":annee_naissance",$annee_naissance, 4);
            ajouterParamOCI($cur,":annee_prem",$annee_prem, 4);
            $res = ExecuterRequeteOCI($cur);

            //Retourne le numéro du coureur inséré
            $cur = PreparerRequeteOCI($conn,$sql);
            $res = ExecuterRequeteOCI($cur);
            $nb = LireDonneesOCI1($cur,$donnees);
            $n_coureur=$donnees[0]['N_COUREUR'];

            //Insertion dans la table nationalité
            $sql3="INSERT INTO tdf_app_nation(n_coureur,code_cio,annee_debut,compte_oracle,date_insert) VALUES(:n_coureur,:code_cio,:annee_debut,'PHP2A_09',sysdate)";
            $cur= PreparerRequeteOCI($conn,$sql3);
            ajouterParamOCI($cur,":n_coureur",$n_coureur, 6);
            ajouterParamOCI($cur,":code_cio",$pays, 3);
            ajouterParamOCI($cur,":annee_debut",$annee_naissance, 4);
            $res = ExecuterRequeteOCI($cur);

            //Fin de transaction
            $committed = ValiderTransacOCI($conn);
            $res = FermerConnexionOCI($conn);
            session_destroy();
            echo '<hr>Nouveau coureur inséré !<hr>';
        }
    }else{
        echo '<hr>Un champ obligatoire est vide !<hr>';
    }
    echo '<form name="aaaa" action="../ajouter_coureur.php" method="post">
         <input type="submit" value="Retour" style="display:inline-block;"> 
     </form>';
?>