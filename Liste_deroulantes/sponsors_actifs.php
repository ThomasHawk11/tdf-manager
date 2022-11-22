<?php
	function listeDeroulanteSponsors($conn, $num_equipe_spons = ''){

		$req = 'SELECT n_equipe, n_sponsor, nom from tdf_sponsor join
		(
			select n_equipe,max(n_sponsor) as n_sponsor from tdf_sponsor 
			join tdf_equipe using(n_equipe) where annee_disparition is null
			group by n_equipe
		)
		using(n_equipe,n_sponsor)order by nom asc';
		$cur = PreparerRequeteOCI($conn,$req);
		$res = ExecuterRequeteOCI($cur);
		$nbLignes = LireDonneesOCI1($cur,$tab);


		echo "<select name='sponsors'  >
			<option value=''>----------------- Equipes actives ----------------</option>";
	for ($i=0;$i<$nbLignes;$i++)
	{
		$temp = $tab[$i]["N_EQUIPE"].":".$tab[$i]["N_SPONSOR"];

		if($temp == $num_equipe_spons){
			echo '<option value="'.$temp.'"';
			echo 'selected>'.$tab[$i]['NOM'].'</option>';
		}
		else{
			echo '<option value="'.$temp.'"';
			echo '>'.$tab[$i]['NOM'].'</option>';
		}
	}
	echo "</select>";
	}
?>