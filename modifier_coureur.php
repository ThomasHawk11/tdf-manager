<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Modifier coureur</title>
  </head>
  <body>
    <h2>Modification d'un coureur<hr></h2>
<?php

include './Outils/fonc_oracle.php';
include './Outils/util_chap11.php';
include './Outils/info_conn.php';

if ( isset($_GET['id']) && !empty($_GET['id']))
{
	$conn =  OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

	$req = "SELECT tdf_coureur.nom, prenom, annee_naissance, annee_prem, tdf_app_nation.code_cio, code_iso from tdf_coureur 
          join tdf_app_nation using(n_coureur) 
          join tdf_nation on tdf_app_nation.code_cio = tdf_nation.code_cio where n_coureur='".$_GET['id']."'";
	$cur = PreparerRequeteOCI($conn,$req);
	$res = ExecuterRequeteOCI($cur);
	$nb = LireDonneesOCI1($cur,$donnees);
  $_SESSION['N_COUREUR'] = $_GET['id'];
  $nom = $_SESSION['NOM'] = $donnees[0]['NOM'];
  $prenom = $_SESSION['PRENOM'] = $donnees[0]['PRENOM'];
  $anneeNaissance = $_SESSION['ANNEE_NAISSANCE'] = $donnees[0]['ANNEE_NAISSANCE'];
  $anneePrem = $_SESSION['ANNEE_PREM'] = $donnees[0]['ANNEE_PREM'];
  $pays = $_SESSION['CODE_CIO'] = $donnees[0]['CODE_CIO'];
  echo $pays." | ".$nom." ".$prenom.'<hr>';
  FermerConnexionOCI($conn);
?>

<form name="formModifierCoureur" action="./Traitements/modifier_coureur_traitement.php" method="post" onsubmit="return confirm('Voulez-vous modifier ce coureur ?')">
  <p>Champs obligatoire *</p>
        <label>Nom* : </label><?php echo '<input type="text" name="nom" id="nom" value="'.$nom.'" />';?><br/>
        <label>Prénom* : </label><?php echo '<input type="text" name="prenom" id="prenom" value="'.$prenom.'" />';?><br/>
        <label>Année de naissance : </label><select name="annee_naissance">
        <option value="<?=$anneeNaissance;?>" selected><?=$anneeNaissance;?></option>
          <?php
            $year = (int)date('Y');
            $minYear = $year-60;
            for ($year; $minYear <= $year; $year--): ?>
            <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
        </select><br/>
        <label>Année de première : </label><select name="annee_prem">
        <option value="<?=$anneePrem;?>" selected><?=$anneePrem;?></option>
          <?php
            for ($year = (int)date('Y'); 1903 <= $year; $year--): ?>
            <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
        </select><hr>
        <label>Nouvelle nationalié :</label><input type="radio" name="nation" id="nation" value="new"/><br/>
        <label>Correction nationalié :</label><input type="radio" name="nation" id="nation" value="edit"/>
		<?php
      include ("./Liste_deroulantes/pays.php");
      listeDeroulantePays($conn,$pays);
      FermerConnexionOCI($conn);
      echo '<hr>
              <input type="submit" name="inserer" value="Modifier">
          </form>';
    }else{
      echo 'Pas de coureur à ce numéro !';
    }

    ?>

	</body>
</html>


