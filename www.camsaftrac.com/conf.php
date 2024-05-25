<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
/********* Creation du fichier de cofiguration du programme
Ce fichier contient l'ensemble des variables,fonctions et classes qui doivent être disponibles
dans chaque script.
 * les noms de variable et de fonction incluses commencent par un UNDERSCORE
 *  
***********/

//***************************************************************************************************************
error_reporting(E_ALL);
/********* Definition de toute les Routines Auto-Exécutables du Programme **********/
 
 //************* Protection des Variables Superglobales Modifiables par l'internaut contre les attaques par Injection SQL
 $_GET = array_map("addslashes",$_GET);
 $_POST = array_map("addslashes",$_POST);
 $_COOKIE = array_map("addslashes",$_COOKIE);
/********* Fin des definitions de Routines Auto-Exécutables ***********/

/********* Definition de toute les Variables de Configuration du Programme **********/
 set_include_path('.pear/func/'); // redefinition du repertoire par default des inclusions
$_domain = 'http://'.$_SERVER['HTTP_HOST'].'/'; // Nom de domain du site
$_htaccess=array(array(403,404,500),array('403'=>'HTTP/1.0 403 Forbidden','404'=>'HTTP/1.0 404 Not Found','500'=>'HTTP/1.0 403 Internal Server Error')); // Tableau des erreurs HTTP
$_Erreur = array( // Tableau repertoriant les # ERREURS Personaliséés
 'Eparam' => array('Paramètre de Fonction Incorect',E_USER_WARNING),
 'Edb' => array('Erreur de Traitement avec MySQL',E_USER_WARNING),
 'Eright' => array('Authorisation Refusée',E_USER_WARNING),
 'Efile' => array('Erreur de gestion de fichier',E_USER_WARNING)
);
$_expreg['nom'] = '/^\w*[\s\w]*\w*$/';
$_expreg['email'] = '/^(\w+[\.\-_]?\w+)+@(\w+[\.\-_]?\w+)+(\.\w{2,4}){1,2}$/';
$_expreg['web'] = '/^(\w+[\.\-_]?\w+)+(\.\w{2,4}){1,2}$/';
$_expreg['tel'] = '/^\+\d{7,}$/';
/********* Fin des definitions de Variable ***********/

//****************************************************************************************************************

/*********** Definition de toute les Fonctions de Configuration du Programme ***********/

/* Function Loader
Cette fonction a pour but de CHARGER AUTOMATIQUEMENT les API du site sans se soucier des eventuelles contraintes:
inclusion des fichiers necessaire, gestion des erreurs,verifications,etc ex: _db
*/
function _loader($_func_name,$_param=array(true)){
global $_Erreur;
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
   require_once($_func_file);
    // On renvoi le result de l'éxecution de la fonction
   return  call_user_func_array($_func_name,$_param);
   exit;
 }
}
//***** Fin de la fonction _loader()

/* Function get_date()
Cette fonction a pour but de FORMATER la date en francais et la retournée 
*/
function get_date($_date=0,$_format=0){
   $eng_words = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July','August', 'September', 'October', 'November', 'December');
   $french_words = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
 // On verifie si la date et le format on été transmise
if($_date==0){
 $_timestamp = mktime();
}
else{
 if($_format=='MySQL'){// Traitement d'une date MySQL. ex: 2007-09-30 12:07:28
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
