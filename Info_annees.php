<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Info Années</title>
  </head>
  <body>
  	<form name="formInfoAnnée" action="./Traitements/Info_annees_traitement.php" method="post">
  		<?php $_POST['annee_selectionnee']='';?>
  		<h2>Info sur une Année<hr></h2>
  		<label>Selection d'une année : </label><select name="annee_selectionnee">
  			<?php 
  			$year = (int)date('Y'); //Ajoute dans une liste déroulante toutes les année du tour de france.
  			$minYear = $year - 48;
  			for ($year; $minYear <= $year; $year--): ?> 
            <option value="<?=$year;?>"><?=$year;?></option>
            <?php endfor; ?>
  		</select><br/>
      <br/>
  		<hr>
      <br/>
      <input type="submit" name="Envoyer">
    </form><br>
    <form name="a" action="./MENU.php" method="post">
         <input type="submit" value="Menu" style="display:inline-block;"> 
     </form>
  </body>
</html>