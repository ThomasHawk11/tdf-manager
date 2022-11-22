<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Ajouter un coureur</title>
  </head>
  <body>
    
        <h2>Insertion d'un coureur<hr></h2>
        <form name="a" action="./MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
     </form>
        <p>Champs obligatoire *</p>
      <form name="formAjouterCoureur" action="./Traitements/ajouter_coureur_traitement.php" method="post">
        <label>Nom* : </label><input type="text" name="nom" id="nom" value="<?php if (isset($_SESSION['nom'])){echo $_SESSION['nom'];}?>"><br/>
        <label>Prénom* : </label><input type="text" name="prenom" id="prenom" value="<?php if (isset($_SESSION['prenom'])){echo $_SESSION['prenom'];}?>"><br/>
        <label>Année de naissance : </label><select name="annee_naissance">
          <option value="<?php if (isset($_SESSION['annee_naissance'])){echo $_SESSION['annee_naissance'];}?>" selected><?php if (isset($_SESSION['annee_naissance'])){echo $_SESSION['annee_naissance'];}else{echo '....';}?></option>
          <?php
            $year = (int)date('Y');
            $minYear = $year-60;
            for ($year; $minYear <= $year; $year--): ?>
            <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
        </select><br/>
        <label>Année de première : </label><select name="annee_prem">
          <option value="<?php if (isset($_SESSION['annee_prem'])){echo $_SESSION['annee_prem'];}?>" selected><?php if (isset($_SESSION['annee_prem'])){echo $_SESSION['annee_prem'];}else{echo '....';}?></option>
          <?php
            for ($year = (int)date('Y'); 1903 <= $year; $year--): ?>
            <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
        </select>
      
        <?php
            include ("./Outils/pays.php");
            listeDeroulantePays($conn);
        ?>
        <hr>
        <input type="submit" name="inserer" value="Insérer">
    </form>
   </body>
</html>