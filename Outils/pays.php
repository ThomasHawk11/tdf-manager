<?php
	include ("./Outils/fonc_oracle.php");
    include ("./Outils/util_chap11.php");
    include ("./Outils/util.php");
    include_once './Outils/info_conn.php';
    include ("./Outils/emoji_pays.php");
	$conn = OuvrirConnexionOCI($infoConn['login'], $infoConn['mdp'],$infoConn['instanceOCI']);

	function listeDeroulantePays($conn, $code_cio = ''){

		$req = 'SELECT nom, code_cio, code_iso FROM tdf_nation WHERE annee_disparition IS NULL ORDER BY nom';
		$cur = PreparerRequeteOCI($conn,$req);
		$res = ExecuterRequeteOCI($cur);
		$nbLignes = LireDonneesOCI1($cur,$tab);
    	FermerConnexionOCI($conn);

		echo "<br/>
			<select name='pays'  >
				<option value=''>---------- Pays* ---------</option>";
		for ($i=0;$i<$nbLignes;$i++)
		{
			$emoji=getEmojiFlag($tab[$i]["CODE_ISO"]);
			echo '<option value="'.$tab[$i]["CODE_CIO"].'"';
			if($code_cio != '' && $code_cio == $tab[$i]["CODE_CIO"]){
				echo " selected";
			}
			echo '>'.$emoji.' '.$tab[$i]['NOM'].'</option>';
		}
		echo "</select>
		<br/>";
	}
?>
<!-- Ancien Code BACKUP
	<br/>
		<select name="pays"  >
			<option value="">---------- Pays* ---------</option> 

			<?php
				//remplirOption($tab,$nbLignes);		
			?>
		</select>
	<br/>
	<?php
		/*function remplirOption($tab,$nbLignes)
		{
			for ($i=0;$i<$nbLignes;$i++)
			{
				$emoji=getEmojiFlag($tab[$i]["CODE_ISO"]);
				echo '<option value="'.$tab[$i]["CODE_CIO"].'">'.$emoji.' '.$tab[$i]['NOM'];
				echo '</option>';
			}
		}
		*/
	?>
-->