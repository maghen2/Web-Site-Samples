<?php
/********* Creation du fichier de cofiguration du programme
Ce fichier contient l'ensemble des variables,fonctions et classes qui doivent être disponibles
dans chaque script.
 * les noms de variable et de fonction incluses commencent par un UNDERSCORE
 *  
***********/

//***************************************************************************************************************

/********* Definition de toute les Routines Auto-Exécutables du Programme **********/
 
 //************* Protection des Variables Superglobales Modifiables par l'internaut contre les attaques par Injection SQL
 $_GET = array_map("addslashes",$_GET);
 $_POST = array_map("addslashes",$_POST);
 $_COOKIE = array_map("addslashes",$_COOKIE);
/********* Fin des definitions de Routines Auto-Exécutables ***********/

/********* Definition de toute les Variables de Configuration du Programme **********/
$_path = substr(get_include_path(),2); // repertoire par default des inclusions
$_domain = 'http://'.$_SERVER['HTTP_HOST'].'/'; // Nom de domain du site
// Ajout du fichier de configuration JavaScript
$_js = $_domain.'src/js/default.js';
$_js = file_get_contents($_js,false);
$_alphanum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9'
);
$_alphanum2 = array('s','Q','I','M','9','Y','B','S','t','k','l','g','q','W','j','G','D','e','u','o','K','1','L','2','C','p','O','4','b','7','5','i','U','c','x','T','F','z','0','r','A','E','H','R','3','6','w','P','a','J','Z','h','8','v','n','N','X','V','d','f','m','y'
);
$_alphanum3 = array_combine($_alphanum,$_alphanum2);
$_allowable_tag ='<a>,<p>,<img>,<object>,<embed>,<b>,<i>,<u>,<strong>,<tt>,<strike>,<sup>,<sub>,<em>,<code>,<samp>,<kbd>,<var>,<cite>,<dfn>,<acronym>,<abbr>,<q>,<del>,<ins>';

$_Erreur = array( // Tableau repertoriant les # ERREURS Personaliséés
 'Eparam' => array('Paramètre de Fonction Incorect',E_USER_WARNING),
 'Edb' => array('Erreur de Traitement avec MySQL',E_USER_WARNING),
 'Eright' => array('Authorisation Refusée',E_USER_WARNING),
 'Efile' => array('Erreur de gestion de fichier',E_USER_WARNING)
);

$_expreg = array( // Tableau Repertoriant les Expression Relationnelles du Logiciel
'html' => array( // Ce sous-tableau contient les expressions regulières necessaire au Traitement du html
  'clean' => array( // Ce sous-tableau contient les expressions regulières necessaire au Nettoyage du html
   '`<\s*(?:script|noscript|style).*?>.*?<\s*/(?:script|noscript|style)\s*>`si',
   '`\s\s+`'
   )
   ,
  'get' => array( // Ce sous-tableau contient les expressions regulières necessaire à Extration de données du html
   'keyword' => '`<\s*meta\s*name\s*=\s*["\']\s*keywords\s*["\']\s*content\s*=\s*["\'](.*?)["\']\s*/?\s*>`i',
   'img' => '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*alt\s*=\s*["\'](.*?)["\'][^>]*[^>]*/?\s*>`si',
   'object' =>'``',
   'embed' =>'``'
  )
 )
);

$_spchar = array( // Tableau de correspondance des Signes nommés pour le jeu de caractères ISO 8859-1 et pour les signes propres à HTML
'–' => '&#8211;',
'—' => '&#8212;',
'‘' => '&#8216;',
'’' => '&#8217;',
'‚' => '&#8218;',
'“' => '&#8220;',
'”' => '&#8221;',
'„' => '&#8222;',
'‡' => '&#8225;',
'‰' => '&#8240;',
'‹' => '&#8249;',
'›' => '&#8250;',
'·' => '&#161;',
'¢' => '&#162;',
'£' => '&#163;',
'¤' => '&#164;',
'¡' => '&#165;',
'Š' => '&#166;',
'§' => '&#167;',
'¨' => '&#168;',
'Œ' => '&#169;',
'ª' => '&#170;',
'' => '&#171;',
'' => '&#172;',
'­' => '&#173;',
'' => '&#174;',
'¯' => '&#175;',
'°' => '&#176;',
'‘' => '&#177;',
'²' => '&#178;',
'³' => '&#179;',
'´' => '&#180;',
'œ' => '&#181;',
'' => '&#182;',
'' => '&#183;',
'¸' => '&#184;',
'±' => '&#185;',
'º' => '&#186;',
'Ÿ' => '&#187;',
'¥' => '&#188;',
'½' => '&#189;',
'µ' => '&#190;',
'¿' => '&#191;',
'À' => '&#192;',
'Á' => '&#193;',
'Â' => '&#194;',
'Ã' => '&#195;',
'Ä' => '&#196;',
'Å' => '&#197;',
'Æ' => '&#198;',
'Ç' => '&#199;',
'È' => '&#200;',
'É' => '&#201;',
'Ê' => '&#202;',
'Ë' => '&#203;',
'Ì' => '&#204;',
'Í' => '&#205;',
'Î' => '&#206;',
'Ï' => '&#207;',
'Ğ' => '&#208;',
'Ñ' => '&#209;',
'Ò' => '&#210;',
'Ó' => '&#211;',
'Ô' => '&#212;',
'Õ' => '&#213;',
'Ö' => '&#214;',
'×' => '&#215;',
'Ø' => '&#216;',
'Ù' => '&#217;',
'Ú' => '&#218;',
'Û' => '&#219;',
'Ü' => '&#220;',
'İ' => '&#221;',
'Ş' => '&#222;',
'ß' => '&#223;',
'á' => '&#225;',
'â' => '&#226;',
'ã' => '&#227;',
'ä' => '&#228;',
'å' => '&#229;',
'æ' => '&#230;',
'ç' => '&#231;',
'è' => '&#232;',
'é' => '&#233;',
'ê' => '&#234;',
'ë' => '&#235;',
'ì' => '&#236;',
'í' => '&#237;',
'î' => '&#238;',
'ï' => '&#239;',
'ğ' => '&#240;',
'ñ' => '&#241;',
'ò' => '&#242;',
'ó' => '&#243;',
'ô' => '&#244;',
'õ' => '&#245;',
'ö' => '&#246;',
'÷' => '&#247;',
'ø' => '&#248;',
'ù' => '&#249;',
'ú' => '&#250;',
'û' => '&#251;',
'ü' => '&#252;',
'ı' => '&#253;',
'ş' => '&#254;',
'ÿ' => '&#255;'
);

/********* Fin des definitions de Variable ***********/

//****************************************************************************************************************

/*********** Definition de toute les Fonctions de Configuration du Programme ***********/

/* Function Loader
Cette fonction a pour but de CHARGER AUTOMATIQUEMENT toute fonction sans se soucier de 
l'inclusion des fichiers necessaire
*/
function _loader($_func_name,$_param=array(true)){
global $_Erreur,$_path;
  // Verification des paramètres
 if(!is_string($_func_name) || empty($_func_name) || !is_array($_param) ||  empty($_param)){
   // Génerer un avertissement si les paramètres ne sont pas du bon type
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  // Renvoie Faux puis Arrêt du script
  return false;
  exit;
 }
 else {
   $_func_file = substr($_func_name,1).'.php';
   $_included_files = array_map("basename",get_included_files());
     // On verifie si la dite fonction n'exist pas encore
  if(!in_array($_func_file,$_included_files) || !function_exists($_func_name))

   require_once('func/'.$_func_file);
    // On renvoi le result de l'éxecution de la fonction
   return  call_user_func_array($_func_name,$_param);
   exit;
 }
}
//***** Fin de la fonction _loader()

/* Function get_date()
Cette fonction a pour but de FORMATER la date en francais et la returnée 
*/
function get_date($_date=0,$_format=0){
   $eng_words = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July','August', 'September', 'October', 'November', 'December');
   $french_words = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
 // On verifie si la date et le format on été transmise
if($_date==0){
 $_timestamp = mktime();
}
else{
 if($_format=='MySQL'){// Traitement d'une date MySQL 2007-09-30 12:07:28
  preg_match_all('`\d+`',$_date, $_data);
  $_j = $_data[0][2];
  $_mo = $_data[0][1];
  $_a = $_data[0][0];
  $_h = $_data[0][3];
  $_mi = $_data[0][4];
  $_s = $_data[0][5];
 }
  $_timestamp = mktime($_h,$_mi,$_s,$_mo,$_j,$_a); 
}    
    // Obtention de la date 
   $date_str = date('l',$_timestamp).' '.date('d',$_timestamp).' '.date('F',$_timestamp).' '.date('Y',$_timestamp).' à '.date('G',$_timestamp).':'.date('i',$_timestamp).':'.date('s',$_timestamp);
   $date_str = str_replace($eng_words, $french_words, $date_str); // Traduction de la date en français
   return $date_str;
}
//***** Fin de la fonction _get_date()


/*********** Fin des definitions de Fonction ***********/              





?>
