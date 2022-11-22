<?php 
session_start();
print_r ($_SESSION);
include_once "../Outils/fonc_oracle.php";
include_once "../Outils/util_chap11.php";
include_once '../Outils/info_conn.php';
include_once '../Outils/util.php';
include_once '../Outils/remplace_accent.php';
include_once '../Outils/verificationChamps.php';
include_once "../Outils/util_chap9.php";

$nom  = preg_replace('/\s+/', ' ',trim(RemplaceAccentsNom(mb_strtoupper(($_POST['nom'])))));
$prenom  = preg_replace('/\s+/', ' ',trim(RemplaceAccentsPrenom(ucwords_accent(mb_strtolower($_POST['prenom'])))));
$annee_naissance = $_POST['annee_naissance'];
$annee_prem = $_POST['annee_prem'];
$pays = $_POST['pays'];
if(isset($_POST['nation'])){
    $nation = $_POST['nation'];
}else{
    $nation='';
}

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
    if(($nom != $_SESSION['NOM'] || $prenom != $_SESSION['PRENOM']) 
                                 || ($annee_naissance != $_SESSION['ANNEE_NAISSANCE']) 
                                 || ($annee_prem != $_SESSION['ANNEE_PREM']) 
                                 || ($pays != $_SESSION['CODE_CIO'])){
        $conn =  OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
        $sql = "UPDATE tdf_coureur SET";
        if($nom != $_SESSION['NOM'])
            $sql .= " nom ='".$nom."'";
        if($prenom != $_SESSION['PRENOM'])
            $sql .= " prenom ='".$prenom."'";
        if($annee_naissance != $_SESSION['ANNEE_NAISSANCE'])
            $sql .= " annee_naissance ='".$annee_naissance."'";
        if($annee_prem != $_SESSION['ANNEE_PREM'])
            $sql .= " annee_prem ='".$annee_prem."'";
        if($pays != $_SESSION['CODE_CIO']){
            //s'il s'agit d'une corrrection de la nationalité du coureur
            if($_POST['nation']=='edit'){
                $sqlNat = "UPDATE tdf_app_nation SET code_cio='".$pays."' WHERE n_coureur='".$_SESSION['N_COUREUR']."' and annee_fin is null";
                echo $sqlNat;
                $cur = PreparerRequeteOCI($conn,$sqlNat);
                $res = ExecuterRequeteOCI($cur);
            }
            //s'il s'agit d'une nouvelle nationalité
            if($_POST['nation']=='new'){
                //Fin de la nationalité actuelle
                $sqlNat = "UPDATE tdf_app_nation SET annee_fin = to_char(sysdate,'YYYY') WHERE n_coureur='".$_SESSION['N_COUREUR']."' and annee_fin is not null";
                $cur = PreparerRequeteOCI($conn,$sqlNat);
                $res = ExecuterRequeteOCI($cur);
                //Nouvelle nationalité
                $sqlNewNat = "INSERT INTO tdf_app_nation(n_coureur,code_cio,annee_debut,compte_oracle,date_insert) VALUES (:n_coureur,:code_cio, to_char(sysdate,'YYYY'),'PPHP2A_09', sysdate )";
                $cur = PreparerRequeteOCI($conn,$sqlNewNat);
                ajouterParamOCI($cur,":n_coureur",$_SESSION['N_COUREUR'], 5);
                ajouterParamOCI($cur,":code_cio",$pays, 3);
                $res = ExecuterRequeteOCI($cur);
            }
        }
        //Dans le cas où il y a eu des modifications
        if($sql !=  "UPDATE tdf_coureur SET"){
            $sql .= " WHERE n_coureur='".$_SESSION['N_COUREUR']."'";
            echo $sql;
            $cur = PreparerRequeteOCI($conn,$sql);
	        $res = ExecuterRequeteOCI($cur);
        }
        ValiderTransacOCI($conn);
        FermerConnexionOCI($conn);
        echo 'Coureur modifié !';
    }else{
        echo 'Aucune modification apportée !';
    }
}
session_destroy();
echo '<hr>';
echo '<form name="a" action="../modifier_coureur.php?id='.$_SESSION['N_COUREUR'].'" method="post">
        <input type="submit" value="Menu" style="display:inline-block;"> 
    </form>';
?>