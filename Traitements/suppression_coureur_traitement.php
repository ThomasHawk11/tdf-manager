<?php
	include '../Outils/pdo_oracle.php';
	include '../Outils/util_chap11.php';
	include '../Outils/info_conn.php';

	//Traitement des variables envoyées par méthode POST avant suppression d'un coureur de la BDD
	if(isset($_GET['n_coureur']) && !empty($_GET['n_coureur'])){
		$n_coureur = $_GET['n_coureur'];

		$conn = OuvrirConnexionPDO($infoConn['pdo'], $infoConn['login'],$infoConn['mdp']);
		$req = "DELETE FROM tdf_coureur
				WHERE n_coureur = ".$n_coureur."";
		
		//echo $req;
		
		$res = majDonneesPDO($conn,$req);
		$req = "DELETE FROM tdf_app_nation
				WHERE n_coureur = ".$n_coureur."";

		//echo $req;

		$res = majDonneesPDO($conn,$req);

		echo 'Coureur supprimé !';
	}
	else{
		echo 'Échec de la suppression !';
	}
?>