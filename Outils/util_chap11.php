<?php
// E.Porcq  util_chap11.php  28/08/2018 maj 19/08/2022


//---------------------------------------------------------------------------------------------
function AfficherDonnee1($tab)
{
  foreach($tab as $ligne)
  {
    foreach($ligne as $valeur)
	  echo $valeur."   ";
    echo "<br/>";
  }
}
//--------------------------------------------------------------------------------------------
function AfficherDonnee2($tab,$nbLignes)
{
	if ($nbLignes > 0) 
	{
		//echo $nbLignes;
		for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
		{
		  foreach ($tab[$i] as $data) // lecture des enregistrements de chaque colonne
		  {
			echo "$data ";
		  }
      echo "<br/>";
		}
		} 
		else 
		{
		echo "Pas de ligne<br />\n";
	} 
}
function AfficherDonnee3($tab,$nbLignes)
{
  if ($nbLignes > 0) 
  {
    echo "<table border=\"1\">\n";
    echo "<tr>\n";
    foreach ($tab as $key => $val)  // lecture des noms de colonnes
    {
      echo "<th>$key</th>\n";
    }
    echo "</tr>\n";
	echo $nbLignes;
    for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
    {
      echo "<tr>\n";
      foreach ($tab as $data) // lecture des enregistrements de chaque colonne
	  {
        echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  } 
  else 
  {
    echo "Pas de ligne<br />\n";
  } 
}
//---------------------------------------------------------------------------------------------
?>




