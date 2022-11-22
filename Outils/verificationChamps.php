<?php
	//DEBUT CheckTiret
	// Vérifie les tirets d'une chaîne de caractères avec critères de l'énoncé
	function CheckTirets_NOM($char){
		if(isset($char) and !empty($char)){
			//Test nombres d'occurances des doubles tirets
			if(preg_match_all("/(--)/i", $char) >= 2){
				return false;
			}
			//Test tiret au début et à la fin de la chaîne de caractères
			if(preg_match("/^(-)$/i", $char) or preg_match("/(-)$/i", $char)){
				return false;
			}
			//Test plus de deux tirets
			if(preg_match("/.*(--)[-]+/i", $char)){
				return false;
			}
			//Test tiret en fin ou début de mot
			if(preg_match("/(-'-)/i", $char)){
				return false;
			}
			return true;
		}
		return false;
	}
	//Rappelle de la fonction précedente + nouvelle condition
	function CheckTirets_PRENOM($char){
		if(CheckTirets_NOM($char)){
			if(preg_match("/.*(--).*/i", $char)){
				return false;
			}
			return true;
		}
		return false;
	}
	//FIN CheckTiret

	//DEBUT CheckSlash
	// Vérifie les slash d'une chaîne de caractères avec critères de l'énoncé
	function CheckSlash_NOM_PRENOM($char){
		if(isset($char) and !empty($char)){
			//Test pour les Slash et BackSlash
			if(preg_match("/.*(\\\).*/i", $char) || preg_match("/.*(\/).*/i", $char)){
				return false;
			}
			return true;
		}
		return false;
	}
	//FIN CheckSlash

	//DEBUT CheckApo
	// Vérifie les Apostrophe d'une chaîne de caractères avec critères de l'énoncé
	function CheckApostrophe($char){
		if(isset($char) and !empty($char)){
            //Test apostrophe avec espace à droite et à gauche
            if(preg_match("/.*( ' ).*/i", $char)){
                return false;
            }
            //Test avec deux apostrophes collées
            if(preg_match("/.*('').*/i", $char)){
                return false;
            }
            //Test avec seulement une apostrophe isolée
            if(preg_match("/.*(').*/i", $char)){
                if(strlen($char) == 1){
                    return false;
                }
            }
			//Test apostrophe avec espace à gauche en fin de mot
			if(preg_match("/( ')$/i", $char)){
                    return false;
                
            }
			return true;
		}
		return false;
	}
	//FIN CheckApo

	// Vérifie les Apostrophe d'une chaîne de caractères avec critères de l'énoncé
	function CheckInterdit($char){
		if(isset($char) || !empty($char)) {
			$temp = explode(' ', $char);
			for ($i=0; $i < sizeof($temp); $i++) { 
				if(preg_match("/[^a-zA-ZéÉèÈêÊàÀâÂëËáäÄȧāãçÇčęïÏíîÎīłṇóôÔöÖōõšṣṭúüÜûÛùÙÿŸžẓœŒæÆ'-]/i", $temp[$i])) {
					return 0;
			}
			return 1;
		}
		return 0;
		}
	}

	//Vérifie la présence de symbole Euro dans une chaîne de caractères
	function CheckEuro($char) {
		if(isset($char) || !empty($char)) {
				if(preg_match("/(€)/", $char)) {
					return 0;
				}
				return 1;
			}
			return 0;
		}

	//Vérifie la longueur d'une chaîne de caractères
	function CheckLongueur_NOM($char) {
		if(isset($char) || !empty($char)) {
			if(mb_strlen($char) > 35) {
				return 0;
			}
			return 1;
		}
		return 0;
	}

	//Vérifie la longueur d'une chaîne de caractères
	function CheckLongueur_PRENOM($char) {
		if(isset($char) || !empty($char)) {
			if(mb_strlen($char) > 30) {
				return 0;
			}
			return 1;
		}
		return 0;
	}

	//Assamblage des fonctions pour vérifier un Nom de coureur avant insertion dans la BDD
	function CheckNom($char){
		if(isset($char) && !empty($char)){
			return CheckSlash_NOM_PRENOM($char)
			&& CheckTirets_NOM($char)
			&& CheckInterdit($char)
			&& CheckApostrophe($char)
			&& CheckEuro($char)
			&& CheckLongueur_NOM($char)
			&& CheckSlash_NOM_PRENOM($char);
		}
		return false;
	}

	//Assamblage des fonctions pour vérifier un Prenom de coureur avant insertion dans la BDD
	function CheckPrenom($char){
		if(isset($char) && !empty($char)){
			return CheckSlash_NOM_PRENOM($char)
			&& CheckTirets_PRENOM($char)
			&& CheckInterdit($char)
			&& CheckApostrophe($char)
			&& CheckEuro($char)
			&& CheckLongueur_PRENOM($char)
			&& CheckSlash_NOM_PRENOM($char);
		}
		return false;
	}

	//FIN CheckTiret
?>