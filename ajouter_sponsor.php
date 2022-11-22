<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Ajouter un sponsor</title>
  </head>
  <body>
    <form name="formAjouterCoureur" action="./Traitements/ajouter_sponsor_traitement.php" method="post" onsubmit="return confirm('Voulez-vous ajouter ce sponsor ?')">
        <h2>Ajouter un sponsor</h2><hr>
        <p>Champs obligatoire *</p>
        <?php
            include_once './Outils/info_conn.php';
            include ("./Outils/fonc_oracle.php");
            include ("./Outils/util_chap11.php");
            include ("./Outils/util.php");
            $conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
            include ("./Liste_deroulantes/sponsors_actifs.php");
            listeDeroulanteSponsors($conn);
        ?><br/>
        <label>Nouveau nom* : </label><input type="text" name="nom" id="nom" required><br/>
        <label>Nom abregé : </label><input type="text" name="nom_ab" id="nom_ab" maxlength="3" style="width:100px" oninput="this.value = this.value.toUpperCase()"><br/>
        <label>Année de création : </label><select name="annee" id="annee">
            <option value="" selected>----</option>
            <?php
                for ($year = (int)date('Y'); 1850 <= $year; $year--): ?>
                    <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
        </select>

        <?php
            include ("./Liste_deroulantes/pays.php");
            listeDeroulantePays($conn);
            FermerConnexionOCI($conn);
        ?>

        <hr>
        <input type="submit" name="inserer" value="Insérer">
    </form>
   </body>
</html>