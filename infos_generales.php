<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Infos Générales</title>
   
  </head>
  <body>
  <h2>Infos Générales<hr></h2>
 
 <?php 
     include_once "Outils/fonc_oracle.php";
	   include_once "Outils/util_chap11.php";
	   include_once 'Outils/info_conn.php';
	   include_once 'Outils/util.php';
 
   $nCoureur=$_GET['id'];
   $conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);
   
   //permet de savoir le nom, prénom, la date de naissance et la date du premier Tour de France d'un coureur
   $sql3 = "SELECT nom, prenom, n_equipe, n_sponsor, annee_naissance, annee_prem from tdf_parti_coureur  
   join tdf_coureur using(n_coureur) where n_coureur='".$nCoureur."'";
   $cur = PreparerRequeteOCI($conn,$sql3);
   $res = ExecuterRequeteOCI($cur);
   $nb = LireDonneesOCI1($cur,$donnees);
   $nom = $donnees[0]['NOM'];
   $prenom = $donnees[0]['PRENOM'];
   $naissance = $donnees[0]['ANNEE_NAISSANCE'];
   $prem = $donnees[0]['ANNEE_PREM'];
   echo $prenom." ".$nom." est un cycliste né en ".$naissance.".";
   echo "<br>";
   echo "Il a participé à son premier Tour en ".$prem.".";
   
   //permet le nombre de participations d'un coureur
   $sql = "SELECT n_coureur, nom, prenom, count(*) as nb_participations from tdf_parti_coureur  
   		   join tdf_coureur using(n_coureur) where n_coureur='".$nCoureur."' group by n_coureur, nom, prenom  order by n_coureur ";
   $cur = PreparerRequeteOCI($conn,$sql);
   $res = ExecuterRequeteOCI($cur);
   $nb = LireDonneesOCI1($cur,$donnees);
   $nbParti = $donnees[0]['NB_PARTICIPATIONS'];
   echo "<br>";
   echo "<br>";
   echo "Il a pris part ".$nbParti." fois au Tour de France. ";
   
   //permet de savoir la place du coureur pour chaque annee puis s'il a abandonné et combien de fois
   $sql2 ="SELECT annee, n_etape, libelle, tdf_abandon.commentaire from tdf_abandon 
   join tdf_typeaban using(c_typeaban) where n_coureur='".$nCoureur."'";
   $sql5 = "(SELECT distinct annee as ANNEE, rang_arrivee as RANG_ARRIVEE, null as STATUT from tdf_classements_generaux where n_coureur='".$nCoureur."')
   union
   (SELECT distinct annee as ANNEE, rang_arrivee as RANG_ARRIVEE, 'DQ' as STATUT from tdf_classements_generaux where n_coureur='".-$nCoureur."')
   order by ANNEE ";
   $cur = PreparerRequeteOCI($conn,$sql2);
   $cur1 = PreparerRequeteOCI($conn,$sql5);
   $res = ExecuterRequeteOCI($cur);
   $res1 = ExecuterRequeteOCI($cur1);
   $nb = LireDonneesOCI1($cur,$donnees);
   $nb1 = LireDonneesOCI1($cur1,$donnees);
   //$n_etape = $donnees['N_ETAPE'];
   echo "<br>";
   $annee = $donnees[0]['ANNEE'];
   $place = $donnees[0]['RANG_ARRIVEE'];
   for ($i = 0; $i <= $nbParti - $nb; $i++) {
     if ($place > 0){
      if(!empty($donnees[$i])) {
        $annee = $donnees[$i]['ANNEE'];
        $place = $donnees[$i]['RANG_ARRIVEE'];
        echo "En ".$annee.", il est arrivé à la ".$place."ème place.";
        echo "<br>";
    }
    }
  }
  if ($nb != 0){
    echo "<br>";
    echo "Il a donc abandonné ".$nb." fois dans sa carrière.";
  }else{
    echo "<br>";
    echo "Il n'a jamais été contraint d'abandonner un Tour de France.";
  }
   
   echo "</td>";
   
  
 ?>
    <br>
    <br>
    <hr>
    <br>
    <!--   passer d’une page à une autre -->
    <div style="text-align:center">
    <form name="a" action="./Info_annees.php" method="get">
      <input type="submit" value="Aller sur la page « Infos Années »" style="width:20em;height: 30px;display:inline-block;"> </form>
    <br>
    <form name="b" action="./affich_COUR_NAT.htm" method="get">
      <input type="submit" value="Revenir à la page précédente" style="width:20em;height: 30px;display:inline-block;"></form>
    <br>
    <form name="b" action="./MENU.php" method="get">
      <input type="submit" value="Aller au menu général" style="width:20em;height: 30px;display:inline-block;"></form>
    </div>
    </body>
</html>