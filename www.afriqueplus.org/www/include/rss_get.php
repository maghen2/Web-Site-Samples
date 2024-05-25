<?php
require_once('conf.php');
/* Creation de la Fonction _rss_get()
Cette fonction aura pour mission de nourrir la base de donnée avec des contenue provenants des sites syndiqués
Elle accomplira cette tâche en 3 étapes.
 1- Obtention du fichier xml
 2- Analyse et Extration de données
 3- Traitement et Sauvegarde des données
*/
/******************************* Definition des Variables Partagées ********************************/
$_tag = array('title','link','description','category','dc:subject','pubDate','dc:date');
$_dir = $_path.'tmp/'; // Dossier d'analyse des fichiers rss
$_item = array(); // Cette variable contiendra le fichier RSS sous forme d'un tableau Multidimentionel
$_i = 0; // Cette variable contiendra le nombre d'item present dans le fichier

/******************************  I-OBTENTION DU FICHIER XML *****************************************/
/* Fonction _rss_get_file()
Cette fonction est chargée d'obtenir le fichier xml et de determiner s'il doit être analysé ou non
*/
_rss_get_file();
function _rss_get_file(){
 global $_Erreur,$_path;
  // Tableau de paramètres destinè à la selection de l'URL du fichier rss
$_param=array(
 '_select',
 array('*','`rss`','`date` < NOW()','date ASC',1)
);
 // On virifie si la selection s'est effectuée avec succès
if(!($_rss=_loader('_db',$_param)) || !is_array($_rss)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
else{
 if(empty($_rss[0])){
  echo'£end£';
  return'£end£';
  exit;
 }
 else{
$_file_dir = 'tmp/'.$_rss[0]['source'].$_rss[0]['cle'];
$_file_in = file_get_contents($_file_dir,true); // Contenue du fichier local
$_file_out = file_get_contents($_rss[0]['link'],false); // Contenue du fichier distant

 // Re-definition de $_param pour la Mise à jour de la Table rss
 $_cle = $_rss[0]['cle'];
$_param =array(
 '_update',
 array('rss','`date` = ADDTIME(NOW(),`period`)',"`cle` = $_cle",'date ASC',1)
);
 // On verifie si le fichier de syndication à changé depuis la dernière visite
 if($_file_in !== $_file_out){
  // On remplie  le fichier du nouveau contenue
  // On met à jour le champ `date` de la table des flux rss
   if(!file_put_contents($_file_dir,$_file_out,FILE_USE_INCLUDE_PATH)  || !_loader('_db',$_param)){
    trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
    return false;
    exit;   
   }
   else{
    $categorie = $_rss[0]['categorie']; // Servira à _rss_put_content() de relier chaque article à sa Categorie
    $source = $_rss[0]['source']; // Servira à _rss_put_content() de relier chaque article à sa Source
    $_file_out = str_ireplace(array('<![CDATA[',']]>'),'',$_file_out); // Suppression des entite CDATA
    _rss_get_content($_file_out,$categorie,$source);
    return true;
    exit;
  }
 }
 else{
  if(!_loader('_db',$_param)){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   return false;
   exit; 
  }
  else{
   return true;
   exit;
  }
 }
clearstatcache();
}
}
}

/*******************************************************************************************************************/
/******************************  II-ANALYSE ET EXTRATION DE DONNEES **********************************/
/* Fonction _rss_get_content()
Cette fonction a pour tâche d'analyser et extraire les données XML  puis de les transformer sous forme de tableau
*/
function _rss_get_content($_rss,$categorie,$source){
 global $_tag,$_i,$_item,$_Erreur;
//******** Creation et Parametrage du parseur
if(!($_id = xml_parser_create())){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
 if(!xml_parser_set_option($_id,XML_OPTION_CASE_FOLDING,0) || !xml_parser_set_option($_id,XML_OPTION_TARGET_ENCODING,'ISO-8859-1')){
   trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
  return false;
  exit;
 }
 else{
   // Creation des Gestionnaires d'Evenemnts et lancement du parssage
  xml_set_element_handler($_id,'_DebutElt','_FinElt');
  xml_set_character_data_handler($_id,'_txt');
  xml_parse($_id,$_rss,true);
  xml_parser_free($_id);
   // Passation du Tableau de donnée à _rss_put_content() pour Traitement et Sauvegarde
  _rss_put_content($_item,$categorie,$source);
 }
}
}
//**************** Creation des fonctions de traitements
 // Fonction de Debut de Balise
function _DebutElt($_id,$_nom,$_attr){
 global $_tag,$_i,$_item,$_Erreur;
if(strcasecmp($_nom,'item') === 0){
 $_item[$_i] = array();
 _txt($_id,'',0);
}
elseif(in_array($_nom,$_tag) === true){
  $_item[$_i][$_nom] = '';
  _txt($_id,'',$_nom);
}
else{
 if(strcasecmp($_nom,'media:content') === 0) $_item[$_i][$_nom] = str_replace("'","\\'",$_attr['url']);
 else{
  if(strcasecmp($_nom,'media:thumbnail') === 0) $_item[$_i][$_nom] = str_replace("'","\\'",$_attr['url']);
 }
_txt($_id,'',0);
}
}

 // Fonction de Fin d'Elt
function _FinElt($_id,$_nom){
 global $_i;
if(strcasecmp($_nom,'item') === 0){ $_i++; $_item[$_i] = array(); }

}

 // Fonction de Noeud Texte
function _txt($_id,$_txt){
 global $_i,$_item;
 static $_nom;

if(func_num_args() == 3) $_nom = func_get_arg(2);
if($_nom !== 0 && isset($_item[$_i][$_nom])){
 $_item[$_i][$_nom] = html_entity_decode($_item[$_i][$_nom],ENT_QUOTES);
 $_item[$_i][$_nom].= str_replace("'","\\'",str_replace('?',"'",html_entity_decode($_txt,ENT_QUOTES)));
}
}

/*******************************************************************************************************************/
/******************************  III-TRAITEMENT ET SAUVEGARDE DES DONNEES ****************************/
/* Fonction _rss_put_content()
Cette fonction est chargé du traitement et de la sauvegarde des données
*/
function _rss_put_content($_item,$categorie,$source){
 global $_Erreur,$_expreg;
  // Tableau contenant l'ensemble de donées necessaires au formatage MySQL des date
 $_date = array(
  'month'=>array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
  'mois'=>array('01','02','03','04','05','06','07','08','09','10','11','12'),
   //  Sun, 22 Apr 2007 00:30:00 +0100  Tue, 17 Apr 2007 07:15:59 MDT  Sun, 22 Apr 2007 02:29:16 GMT
  'pcre1'=>'`(\d{2})\s(\d{2})\s(\d{4})\s(\d{2}:\d{2}:\d{2})`',
   //  2007-04-20T13:57:23Z  2007-04-22T04:27:05+00:00  2007-04-19 12:26:33
  'pcre2'=>'`(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2}:\d{2})`'
 );
  $_article = array();

for($i=0;$i<count($_item);$i++){
  /*************************** TRAITEMENT DES DONNEES ******************************/
// Creation des Tableau des Requêttes MySQL
 $_article[$i] = array('_insert',array('article(`title`,`link`,`description`,`genre`,`date`,`id`,`categorie`,`source`)',array('(')));
// On commence la distribution des données
$_article[$i][1][1][0] .= "'".strip_tags(trim($_item[$i]['title']))."',";
$_article[$i][1][1][0] .= "'".trim($_item[$i]['link'])."',";
$_article[$i][1][1][0] .= "'".strip_tags(trim($_item[$i]['description']))."',";
// Traitement des offsets de Categories
 if(empty($_item[$i]['category'])){
  if(!empty($_item[$i]['dc:subject']))
   $_item[$i]['category'] = $_item[$i]['dc:subject'];
  else $_item[$i]['category'] = '';
 }
$_article[$i][1][1][0] .= "'".trim($_item[$i]['category'])."',"; 
// Traitement des Dates
 if(empty($_item[$i]['pubDate'])){
  if(!empty($_item[$i]['dc:date']))
   $_item[$i]['pubDate'] = $_item[$i]['dc:date'];
  else $_item[$i]['pubDate'] = '';
 }
// Reformatage des Dates au format MySQL
 if(!empty($_item[$i]['pubDate'])){
  $pubDate = $_item[$i]['pubDate'];
  $month = $_date['month'];
  $mois = $_date['mois'];
       // 1er cas: la Date a le mois en Lettre donc est sous la forme  Sun, 22 Apr 2007 00:30:00
      if(($pubdate = str_ireplace($month,$mois,$pubDate,$count)) && $count === 1){
      $date_pcre1 = $_date['pcre1'];
      if(preg_match($date_pcre1,$pubdate,$_pcre1) === false || empty($_pcre1[1]) || empty($_pcre1[2]) || empty($_pcre1[3]) || empty($_pcre1[4])){
        trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
        return false;
        exit; 
      }
      else{
       // Sun, 22 06 2007 00:30:00 +0100  (\d{2})\s(\d{2})\s(\d{4})\s(\d{2}:\d{2}:\d{2})
       $date = $_pcre1[3].'-'.$_pcre1[2].'-'.$_pcre1[1].' '.$_pcre1[4];
       $_article[$i][1][1][0] .= "'".trim($date)."',";
      }
     }
      // 2nd cas: la Date a le mois en Chiffre donc est sous l'une des formes 2007-04-22T04:27:05 ou 2007-04-22 04:27:05
     else{
      $date_pcre2 = $_date['pcre2'];
      if(preg_match($date_pcre2,$pubDate,$_pcre2) === false || empty($_pcre2[1]) || empty($_pcre2[2])){
        trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
        return false;
        exit; 
      }
      else{
       // 2007-04-22T04:27:05+00:00  (\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2}:\d{2})
       $date = $_pcre2[1].' '.$_pcre2[2];
       $_article[$i][1][1][0] .= "'".trim($date)."',";
      }      
     }
 }
 else $_article[$i][1][1][0] .= "NULL,";
// Traitement des offsets media:content
//    $_media = array('_insert',array('media(`article`,`type`,`link`)',array('('));
if(!empty($_item[$i]['media:content'])){
 $_media = array();
 $_media[$i] = array('_insert',array('media(`link`,`article`,`type`)',array('(')));
 $_media[$i][1][1][0] .= "'".trim($_item[$i]['media:content'])."',";
}

// On doit maintenant completer les données pour les champs `article.id`,`article.categorie`,`article.source`,`media.article`,`media.type`
   // Completons le champ `article`
    // article(`title`,`link`,`description`,`genre`,`date`,`id`,`categorie`,`source`)
$id = "SELECT COUNT(*) AS `num` FROM `article` WHERE `source` = '$source'";

if(!($id = _loader('_db',array('_shell',array($id))))){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
else{
 $id = $source.($id[0]['num']+1);
 $_article[$i][1][1][0] .= "'".trim($id)."',";
 $_article[$i][1][1][0] .= "'".trim($categorie)."',";
 $_article[$i][1][1][0] .= "'".trim($source)."')"; 

   // Completons le champ `media`
     // media(`link`,`article`,`type`)
if(!empty($_media[$i])){
 $_media[$i][1][1][0] .= "'".trim($id)."',";
 (isset($_item[$i]['media:thumbnail']))? $_media[$i][1][1][0] .= "'V')" : $_media[$i][1][1][0] .= "'A')";
}
}
  /*************************** NETTOYAGE ET ENREGISTREMENT DES DONNEES ******************************/
$_param = $_article[$i][1][1][0];
$_regex = $_expreg['html']['clean'];
$_param = preg_replace($_regex,'',$_param);
$_article[$i][1][1][0] = $_param;
$_param = $_article[$i];
_loader('_db',$_param);
if(isset($_media[$i])){
 $_param = $_media[$i];
 _loader('_db',$_param);
  }
 }
}

/*******************************************************************************************************************/
?>
