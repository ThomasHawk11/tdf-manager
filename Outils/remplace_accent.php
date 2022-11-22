<?php
include_once 'util.php';

//Fonction de remplacement de caractères d'une chaîne selon critères d'insertion dans la BDD d'un Nom
function RemplaceAccentsNom($initial) {
        $TB_CONVERT = array(
            'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Œ' => 'OE' , 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y','Ÿ'=>'Y', 'Þ' => 'B', 'ß' => 'SS', 'ø' => 'o', 'æ' => 'ae', 'œ' => 'oe' 
        );
        
        $s = strtr($initial, $TB_CONVERT);
    
        return $s;

    }
//Fonction de remplacement de caractères d'une chaîne selon critères d'insertion dans la BDD d'un Prenom
    function RemplaceAccentsPrenom($initial) {
        $TB_CONVERT = array(
            'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'Ae', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Œ' => 'OE' , 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y','Ÿ'=>'Y', 'Þ' => 'B', 'ß' => 'Ss', 'ø' => 'o', 'æ' => 'ae', 'œ' => 'oe' 
        );
        
        $s = strtr($initial, $TB_CONVERT);
    
        return $s;

    }

//Fonction qui met en Majuscule une chaîne de caractères avec de potentiel accent
function ucwords_accent($string)
{
    if (mb_detect_encoding($string) != 'UTF-8') {
        $string = mb_convert_case(utf8_encode($string), MB_CASE_TITLE, 'UTF-8');
    } else {
        $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
    return $string;
}

//Fonction qui vérifie la présence d'apostrophe dans une chaîne de caractères pour les doublés
// Utile pour les select en SQL pour éviter des erreurs
function Apostrophe_DOUBLET_SQL($char)
{
    $tab = explode("'", $char);
    if(sizeof($tab) > 0){
        return implode("''", $tab);
    }
    return $char;
}
?>