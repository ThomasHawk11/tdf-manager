<?php
    include_once "../Outils/fonc_oracle.php";
    include_once "../Outils/util_chap11.php";
    include_once '../Outils/info_conn.php';
    include_once '../Outils/util.php';
    include_once '../Outils/remplace_accent.php';
    include_once '../Outils/verificationChamps.php';

    if(isset($_POST['nom']) && !empty($_POST['nom'])){
        $id=explode(":",$_POST['sponsors']);
        $nEquipe=$id[0];
        $nSponsor=$id[1];
        $new_nSponsor = $nSponsor+1;
        $nom=RemplaceAccentsNom(mb_strtoupper($_POST['nom']));
        $nomAb=RemplaceAccentsNom(mb_strtoupper($_POST['nom_ab']));
        $annee=$_POST['annee'];
        $pays=$_POST['pays'];

        //Vérification des mêmes caractéristiques d'un sponsor
        $conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
        $sql = "SELECT n_equipe FROM tdf_sponsor WHERE nom='".$nom."'and na_sponsor='".$nomAb."' and code_cio='".$pays."'";
        $cur = PreparerRequeteOCI($conn,$sql);
        $res = ExecuterRequeteOCI($cur);
        $nb = LireDonneesOCI1($cur,$donnees);
        if(!empty($donnees)){ 
            echo '<hr>Ce sponsor est déjà actif !<hr><br/>';
        }else{
            //Insertion d'un nouveau sponsor
            $sql2 = "INSERT INTO tdf_sponsor VALUES(:n_equipe,:n_sponsor,:nom,:na_sponsor,:code_cio,:annee_sponsor, 'PHP2A_09', sysdate)";
            $cur = PreparerRequeteOCI($conn,$sql2);
            ajouterParamOCI($cur,":n_equipe",$nEquipe, 3);
            ajouterParamOCI($cur,":n_sponsor",$new_nSponsor, 3);
            ajouterParamOCI($cur,":nom",$nom, 64);
            ajouterParamOCI($cur,":na_sponsor",$nomAb, 3);
            ajouterParamOCI($cur,":code_cio",$pays, 3);
            ajouterParamOCI($cur,":annee_sponsor",$annee, 4);
            $res = ExecuterRequeteOCI($cur);
            $committed = ValiderTransacOCI($conn);
            $res = FermerConnexionOCI($conn);
            print("Nouveau sponsor ajouté !<br/>");
        }
    }else{
        echo 'Il manque un nom de sponsor !<br/>';
    }
    echo '<hr>';
    echo '<a href="../ajouter_sponsor.php"><input type="button" value="Retour"/></a>';
?>
