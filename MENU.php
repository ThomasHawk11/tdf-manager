<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>MENU GÉNÉRAL</title>
   
  </head>
  <body>
    <div style="text-align:center">
        <h2>MENU GÉNÉRAL<hr></h2>
    </div>
 
 <?php 
     include_once "Outils/fonc_oracle.php";
	   include_once "Outils/util_chap11.php";
	   include_once 'Outils/info_conn.php';
	   include_once 'Outils/util.php';
 ?>
       <br>
       <br>
       <!--   passer d’une page à une autre -->
       <div style="text-align:center">
       <form name="a" action="./affich_COUR_NAT.htm" method="get">
         <input type="submit" value="Afficher les coureurs" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./ajoute_equipe.php" method="get">
         <input type="submit" value="Insérer une équipe" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./ajoute_parti_equipe.php" method="get">
         <input type="submit" value="Insérer la participation d'une équipe" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./ajouter_coureur.php" method="get">
         <input type="submit" value="Insérer un coureur" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./ajouter_sponsor.php" method="get">
         <input type="submit" value="Insérer un sponsor" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./Info_annees.php" method="get">
         <input type="submit" value="Voir des informations par année" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
       <form name="a" action="./ajoute_annee.php" method="get">
         <input type="submit" value="Insérer une année du Tour de France" style="width:30em;height: 50px;display:inline-block;"> </form>
         <br>
         <form name="a" action="./ajoute_pays.php" method="get">
         <input type="submit" value="Insérer un Pays" style="width:30em;height: 50px;display:inline-block;"> </form>
       
        </div>
      </body>
   </html>