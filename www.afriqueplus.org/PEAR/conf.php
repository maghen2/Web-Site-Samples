<?php
/********* Creation du fichier de cofiguration du programme
Ce fichier contient l'ensemble des variables,fonctions et classes qui doivent �tre disponibles
dans chaque script.
 * les noms de variable et de fonction incluses commencent par un UNDERSCORE
 *  
***********/

//***************************************************************************************************************

/********* Definition de toute les Routines Auto-Ex�cutables du Programme **********/
 
 //************* Protection des Variables Superglobales Modifiables par l'internaut contre les attaques par Injection SQL
 $_GET = array_map("addslashes",$_GET);
 $_POST = array_map("addslashes",$_POST);
 $_COOKIE = array_map("addslashes",$_COOKIE);
/********* Fin des definitions de Routines Auto-Ex�cutables ***********/

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

$_Erreur = array( // Tableau repertoriant les # ERREURS Personalis��s
 'Eparam' => array('Param�tre de Fonction Incorect',E_USER_WARNING),
 'Edb' => array('Erreur de Traitement avec MySQL',E_USER_WARNING),
 'Eright' => array('Authorisation Refus�e',E_USER_WARNING),
 'Efile' => array('Erreur de gestion de fichier',E_USER_WARNING)
);

$_expreg = array( // Tableau Repertoriant les Expression Relationnelles du Logiciel
'html' => array( // Ce sous-tableau contient les expressions reguli�res necessaire au Traitement du html
  'clean' => array( // Ce sous-tableau contient les expressions reguli�res necessaire au Nettoyage du html
   '`<\s*(?:script|noscript|style).*?>.*?<\s*/(?:script|noscript|style)\s*>`si',
   '`\s\s+`'
   )
   ,
  'get' => array( // Ce sous-tableau contient les expressions reguli�res necessaire � Extration de donn�es du html
   'keyword' => '`<\s*meta\s*name\s*=\s*["\']\s*keywords\s*["\']\s*content\s*=\s*["\'](.*?)["\']\s*/?\s*>`i',
   'img' => '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*alt\s*=\s*["\'](.*?)["\'][^>]*[^>]*/?\s*>`si',
   'object' =>'``',
   'embed' =>'``'
  )
 )
);

$_spchar = array( // Tableau de correspondance des Signes nomm�s pour le jeu de caract�res ISO 8859-1 et pour les signes propres � HTML
'�' => '&#8211;',
'�' => '&#8212;',
'�' => '&#8216;',
'�' => '&#8217;',
'�' => '&#8218;',
'�' => '&#8220;',
'�' => '&#8221;',
'�' => '&#8222;',
'�' => '&#8225;',
'�' => '&#8240;',
'�' => '&#8249;',
'�' => '&#8250;',
'�' => '&#161;',
'�' => '&#162;',
'�' => '&#163;',
'�' => '&#164;',
'�' => '&#165;',
'�' => '&#166;',
'�' => '&#167;',
'�' => '&#168;',
'�' => '&#169;',
'�' => '&#170;',
'�' => '&#171;',
'�' => '&#172;',
'�' => '&#173;',
'�' => '&#174;',
'�' => '&#175;',
'�' => '&#176;',
'�' => '&#177;',
'�' => '&#178;',
'�' => '&#179;',
'�' => '&#180;',
'�' => '&#181;',
'�' => '&#182;',
'�' => '&#183;',
'�' => '&#184;',
'�' => '&#185;',
'�' => '&#186;',
'�' => '&#187;',
'�' => '&#188;',
'�' => '&#189;',
'�' => '&#190;',
'�' => '&#191;',
'�' => '&#192;',
'�' => '&#193;',
'�' => '&#194;',
'�' => '&#195;',
'�' => '&#196;',
'�' => '&#197;',
'�' => '&#198;',
'�' => '&#199;',
'�' => '&#200;',
'�' => '&#201;',
'�' => '&#202;',
'�' => '&#203;',
'�' => '&#204;',
'�' => '&#205;',
'�' => '&#206;',
'�' => '&#207;',
'�' => '&#208;',
'�' => '&#209;',
'�' => '&#210;',
'�' => '&#211;',
'�' => '&#212;',
'�' => '&#213;',
'�' => '&#214;',
'�' => '&#215;',
'�' => '&#216;',
'�' => '&#217;',
'�' => '&#218;',
'�' => '&#219;',
'�' => '&#220;',
'�' => '&#221;',
'�' => '&#222;',
'�' => '&#223;',
'�' => '&#225;',
'�' => '&#226;',
'�' => '&#227;',
'�' => '&#228;',
'�' => '&#229;',
'�' => '&#230;',
'�' => '&#231;',
'�' => '&#232;',
'�' => '&#233;',
'�' => '&#234;',
'�' => '&#235;',
'�' => '&#236;',
'�' => '&#237;',
'�' => '&#238;',
'�' => '&#239;',
'�' => '&#240;',
'�' => '&#241;',
'�' => '&#242;',
'�' => '&#243;',
'�' => '&#244;',
'�' => '&#245;',
'�' => '&#246;',
'�' => '&#247;',
'�' => '&#248;',
'�' => '&#249;',
'�' => '&#250;',
'�' => '&#251;',
'�' => '&#252;',
'�' => '&#253;',
'�' => '&#254;',
'�' => '&#255;'
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
  // Verification des param�tres
 if(!is_string($_func_name) || empty($_func_name) || !is_array($_param) ||  empty($_param)){
   // G�nerer un avertissement si les param�tres ne sont pas du bon type
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  // Renvoie Faux puis Arr�t du script
  return false;
  exit;
 }
 else {
   $_func_file = substr($_func_name,1).'.php';
   $_included_files = array_map("basename",get_included_files());
     // On verifie si la dite fonction n'exist pas encore
  if(!in_array($_func_file,$_included_files) || !function_exists($_func_name))

   require_once('func/'.$_func_file);
    // On renvoi le result de l'�xecution de la fonction
   return  call_user_func_array($_func_name,$_param);
   exit;
 }
}
//***** Fin de la fonction _loader()

/* Function get_date()
Cette fonction a pour but de FORMATER la date en francais et la return�e 
*/
function get_date($_date=0,$_format=0){
   $eng_words = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July','August', 'September', 'October', 'November', 'December');
   $french_words = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche', 'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre');
 // On verifie si la date et le format on �t� transmise
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
   $date_str = date('l',$_timestamp).' '.date('d',$_timestamp).' '.date('F',$_timestamp).' '.date('Y',$_timestamp).' � '.date('G',$_timestamp).':'.date('i',$_timestamp).':'.date('s',$_timestamp);
   $date_str = str_replace($eng_words, $french_words, $date_str); // Traduction de la date en fran�ais
   return $date_str;
}
//***** Fin de la fonction _get_date()


/*********** Fin des definitions de Fonction ***********/              





?>
