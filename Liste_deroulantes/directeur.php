<?php
	//Affiche une liste déroulante des directeurs présent dans la BDD
	function listeDeroulanteDirecteur($conn, $num_d = '', $num = ''){

		$req = 'SELECT n_directeur as NUM_D, prenom as PRENOM_D, nom as NOM_D from tdf_directeur order by nom';
		$cur = PreparerRequeteOCI($conn,$req);
		$res = ExecuterRequeteOCI($cur);
		$nbLignes = LireDonneesOCI1($cur,$tab);

		echo "<select name='directeur".$num."'  >
				<option value=''>---------------- Directeur ".$num." ---------------</option>";
		for ($i=0;$i<$nbLignes;$i++)
		{
			echo '<option value="'.$tab[$i]["NUM_D"].'"';
			if($num_d != '' && $num_d == $tab[$i]["NUM_D"]){
				echo " selected";
			}
			echo '>'.$tab[$i]['PRENOM_D'].' '.$tab[$i]['NOM_D'].'</option>';
		}
		echo "</select>";
	}
?>