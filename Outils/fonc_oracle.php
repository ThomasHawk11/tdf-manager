<?php
// E.Porcq  fonc_oracle.php  12/10/2009 maj du 11/10/2016

/* Exemples
$conn = OuvrirConnexion('XXX', 'XXX','info');
$conn = @OuvrirConnexion('XXX', 'XXX','kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr');
*/

//---------------------------------------------------------------------------------------------
function OuvrirConnexionOCI($session,$mdp,$instance)
{
  //$conn = @oci_connect($session, $mdp,$instance); // @ évite l'affichage du message d'erreur
  //$conn = oci_connect($session, $mdp,$instance,"WE8ISO8859P15"); // @ évite l'affichage du message d'erreur
  $conn = oci_connect($session, $mdp,$instance,"AL32UTF8");
  if (!$conn) //si pas de connexion retourne une erreur
  {  
	$e = oci_error();
	exit;
  }
  return $conn;
}
//---------------------------------------------------------------------------------------------
function OuvrirConnexionOCI2($session,$mdp,$instance)
{
  $conn = @oci_connect($session, $mdp,$instance,"AL32UTF8");
  if (!$conn) //si pas de connexion retourne une erreur
  {  
	$e = oci_error();
	//avec un message pour pouvoir revenir à la page de connexion
	echo "<br>Votre nom d'utilisateur ou votre mot de passe est &eacute;ronn&eacute;e, veuillez vous reconnecter...<br>";
	echo "<form action = '12_2a.htm' method='post' enctype='application/x-www-form-urlencoded'>
				<input type='submit' value='Retour'>
		  </form>";
	exit;
  }
  return $conn;
}
//---------------------------------------------------------------------------------------------
function PreparerRequeteOCI($conn,$req)
{
  $cur = oci_parse($conn, $req);
  
  if (!$cur) 
  {  
	$e = oci_error($conn);  
	print htmlentities($e['message']);  
	exit;
  }
  return $cur;
}
//---------------------------------------------------------------------------------------------
function ExecuterRequeteOCI($cur)
{
  $r = oci_execute($cur, OCI_DEFAULT);
  //echo "<br>résultat de la requête: $r<br />";
  if (!$r) 
  {  
	$e = oci_error($r);  
	echo htmlentities($e['message']);  
	exit;
  }
  return $r;
}
//---------------------------------------------------------------------------------------------
function ajouterParamOCI($cur,$param,&$variable,$taille=0) // fonctionne avec preparerRequeteOCI
{
	$res = oci_bind_by_name($cur,$param,$variable, $taille);
	return $res;
}
//---------------------------------------------------------------------------------------------
function ValiderTransacOCI($conn)
{
  $res = oci_commit($conn);
  return $res;
}//---------------------------------------------------------------------------------------------
function FermerConnexionOCI($conn)
{
  $res = oci_close($conn);
  return $res;
}
//---------------------------------------------------------------------------------------------
function LireDonneesOCI1($cur,&$tab)
{
	$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW|OCI_ASSOC); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_COLUMN|OCI_ASSOC); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW|OCI_NUM); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_COLUMN|OCI_NUM); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
  return $nbLignes;
}
//---------------------------------------------------------------------------------------------
function LireDonneesOCI2($cur,&$tab)
{
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW|OCI_ASSOC); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_COLUMN|OCI_ASSOC); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW|OCI_NUM); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
	//$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_COLUMN|OCI_NUM); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
  return $nbLignes;
}
//---------------------------------------------------------------------------------------------

?>
