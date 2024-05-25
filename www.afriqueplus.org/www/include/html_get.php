<?php
//header('content-type: text/plain');
$_noart = array(
 '<p>Article temporairement indisponible</p>',
 '<p>Cet article ne contient pas de texte. Il possède toute fois des ressources multimédias</p>',
 ''
);

$start = microtime(true);
require_once('conf.php');
/* Creation de la Fonction _html_get()
Cette fonction aura pour mission non seulement de Completer la table `article` mais aussi éventuellement la table `media` avec des contenue provenants des sites syndiqués
C'est donc elle qui Finira le travail débuté par rss_get().
Elle accomplira cette tâche en 3 étapes.
 1- Obtention du fichier html
 2- Analyse et Extration de données
 3- Traitement et Sauvegarde des données
*/

_html_get();
function _html_get(){
global $_Erreur,$_expreg,$_domain,$_noart,$_spchar;

 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);

/*************** Obtention de la Ressource Et Determination du Type de Traitement ***************/
$_param = array('_select',array('`id`,`link`,`source`,`title`','article','`text`=" "')); // Tableau pour la selection d'article
 // On virifie si la selection s'est effectuée avec succès
if(!($_link =_loader('_db',$_param)) || !is_array($_link)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
else{
 if(empty($_link[0])){
  echo'£end£';
  return'£end£';
  exit;
 }
 else{
$_id = $_link[0][0]; // ID de l'article
$_url = $_link[0][1]; // URL de l'article 
$_source = $_link[0][2]; // Source de l'article
$_title = $_link[0][3]; // Titre de l'article
echo'<h3 align="center"><a href="'.$_url.'" target="_blank">'.$_url.'</a></h3>';
  if(!($_file = @fopen($_url,'r'))){ // On verifie si l'article est indisponible
   $_param = array('_update',array('`article`',"`text`='".$_noart[0]."'","`id`='$_id'"));
   _loader('_db',$_param);
  }
  else{ // Si disponible on verifie le type de l'article via son Type-Mime
    // On recupere les entêtes HTTP echangées par le script et le serveur distant dans un tableau
   $_metadata = stream_get_meta_data($_file);
   $_metadata = $_metadata['wrapper_data'];
   fclose($_file);
    
    // On s'assure que le fichier(article) est du html
    if(in_array('Content-Type: text/html',$_metadata) || in_array('Content-Type: application/xhtml+xml',$_metadata)){

/*********************** Traitement HTML de la ressouce (Analyse & Extration de données) ******************************************/
     $_html = file_get_contents($_url,false); // Telechargement du fichier HTML
      // Extration du contenu de la Meta-Balise KEYWORDS
 $_pcre = $_expreg['html']['get']['keyword'];
  (preg_match($_pcre,$_html,$_match)>0)? $_keyword = $_match[1] : $_keyword = '';
  $_keyword = mb_convert_encoding($_keyword,'latin1',mb_detect_encoding($_keyword,"auto",true));
  $_keyword = html_entity_decode($_keyword,ENT_QUOTES);
  $_keyword = str_ireplace($_spchar_unicode,$_spchar_signe,$_keyword); // Decodage Unicode
  
 $_data =  $_source($_url); // Apel à la fonction `source`()
 print_r($_data);
 $_text = $_data['text'];
 $_organisme = $_data['organisme'];
 $_keyword = str_replace('?',"'",$_keyword);
 $_keyword = str_replace("'","\'",$_keyword);
 
$_param = array('_update',array('`article`',"`keyword`='$_keyword',`text`='$_text',`organisme`='$_organisme'","`id`='$_id'"));
   _loader('_db',$_param); // Mise à jour des articles
   
if(!empty($_data['media']) && !empty($_data['media'][1]) && $_link = $_data['media'][1]){ // Des medias ont étés trouvés et classés
 if(is_string($_link)){ // Il y'a un seul media
  $_tla = $_data['media'];
  $_tla = str_replace("'","\'",$_tla);
  $_tla = implode("','",$_tla);
  $_param = array('_insert',array('media(`article`,`type`,`link`,`alt`)',array("('$_id','$_tla')")));                 
 }
 elseif(is_array($_link)){ // Il y'a plusieurs media
  $_alt = $_data['media'][2];
  if(is_array($_alt)){ // Il y'a plusieurs images with alt attributs
   $_value ='';
   $_type = $_data['media'][0];
   $_alt = $_data['media'][2];
   for($i=0;$i<count($_link);$i++) $_value .= "('$_id','$_type','".$_link[$i]."','".str_replace("'","\'", $_alt[$i])."')"; //#0
   $_value = str_replace("')('","'),('",$_value);
   $_param = array('_insert',array('media(`article`,`type`,`link`,`alt`)',array($_value)));
  }
  else{
   $_value ='';
   $_type = $_data['media'][0];
   $_alt = $_data['media'][2];
   for($i=0;$i<count($_link);$i++) $_value .= "('$_id','$_type','".$_link[$i]."','".str_replace("'","\'", $_alt)."')"; //#1
   $_value = str_replace("')('","'),('",$_value);

   $_param = array('_insert',array('media(`article`,`type`,`link`,`alt`)',array($_value)));
  }
 }
 else return false; // Une erreur s'est produite lors de l'operation d'aquisition des medias
_loader('_db',$_param); // Mise à jour des media  
}
   }
    else{
/********************* Traitement Non-HTML de la ressource ***************************************************************/
     $_type = 'A';
     $_content_type = implode(' ',$_metadata);
     if(is_int(stripos($_content_type,'audio'))) $_type = 'A';
     if(is_int(stripos($_content_type,'video'))) $_type = 'V';
     if(is_int(stripos($_content_type,'image'))) $_type = 'I';
           
     $_title = str_replace("'","\'",$_title);       
     $_param = array('_insert',array('media(`article`,`link`,`alt`,`type`)',array("('$_id','$_url','$_title','$_type')")));
    if(_loader('_db',$_param)){
      $_text = $_noart[1];
      $_text = str_replace("'","\'",$_text);
      $_param = array('_update',array('`article`',"`text`='$_text'","`id`='$_id'"));
    _loader('_db',$_param); 
     } 
    }
  }
  
 }
} 
}

/**********************************************************************************************************************/

/************************** Fonction callback() 
Cette fonction a pour rôle d'aider _l'application à rendre absolue les liens(href,src,...)
contenue dans des articles syndiqués.
*****************************************************/
function _callback($_match){
 static $_url; // url du fichier html. elle est fournie par _pclass ou autre fonction avant
if(func_num_args()===2){ // quelle ne soit apellée par un preg_replace_callback()
 $_url = func_get_arg(1);
 return true;
}

 $_href = $_match[1]; // url de la ressource
 $_tag = $_match[0]; // balise de la ressource au complet

$_src = _loader('_absolute_url',array($_url,$_href)); // url rendue absolue
$_tag = str_replace("$_href","$_src",$_tag);
 return $_tag;
}


/************************** Fonction pclass() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles
structuré sous la forme <p class=".*?">.*?</p>
*****************************************************/

function _pclass($_url,$_pcre_organisme,$_pcre_text){
  global $_Erreur,$_expreg,$_noart,$_spchar,$_allowable_tag;
if(empty($_url) || empty($_pcre_organisme) || empty($_pcre_text) || !$_html = file_get_contents($_url,false)){
 trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
 return false;
 exit;
}
else{
 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);
  // Obtention de l'organisme
 (preg_match($_pcre_organisme,$_html,$_match)>0)? $_organisme = $_match[1] : $_organisme = '';
 
 $_organisme = mb_convert_encoding($_organisme,'latin1',mb_detect_encoding($_organisme,"auto",true));
 $_organisme = html_entity_decode($_organisme,ENT_QUOTES); // Decodage Html
 $_organisme = str_ireplace($_spchar_unicode,$_spchar_signe,$_organisme); // Decodage Unicode
 $_organisme = str_replace("?","'",$_organisme); // Correction de l'encodage puis...
 $_organisme = str_replace("'","\'",$_organisme); // Protection pour MySQL


if(preg_match_all($_pcre_text,$_html,$_match)>0){
  // Obtention du Texte
 $_text = $_match[1];
 $_text = implode('</p><p>',$_text);
 $_text = '<p>'.$_text.'</p>';
 $_text = mb_convert_encoding($_text,'latin1',mb_detect_encoding($_text,"auto",true)); // Encodage du texte en ISO-8859-1mb_detect_encoding($_text)
 $_text = html_entity_decode($_text,ENT_QUOTES);

  _callback(1,$_url); // Initialisation de la variable $_url de _callback()
  // Recherche d' eventuelles images et liens afin de...
    // réparer les attributs SRC | HREF...
    unset($_pcre); 
  $_pcre[0] = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*/?\s*>`si';
  $_pcre[1] = '`<\s*a[^>]*href\s*=\s*["\'](.*?)["\'][^>]*/?\s*>.*?<\s*/\s*a\s*>`si';
  $_text = preg_replace_callback($_pcre,'_callback',$_text); // Correction des attributs src et href
    // puis inscription des images dans la variable media pour leur sauvegarde
   $_pcre = $_expreg['html']['get']['img'];
   if(preg_match_all($_pcre,$_text,$_match)>0){ // Selection image avec attribut src
    $_link = $_match[1];
    $_alt = $_match[2];
   }  // Selection image sans attribut alt
   else{
    $_pcre = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*/?\s*>`si';
    if(preg_match_all($_pcre,$_text,$_match)>0){
      $_link = $_match[1];
      $_alt = '';
    }
    else{
     $_link = '';
     $_alt = '';
    }
   }
$_media = array('I',$_link,$_alt); // Creation du tableau `media`

  // Nettoyage du texte 
 $_text = str_ireplace($_spchar_unicode,$_spchar_signe,$_text); // Decodage Unicode
 
 $_pcre = $_expreg['html']['clean'];
 $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style paragraphes
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises superflues
 $_text = str_replace("?","'",$_text); // Correction de l'encodage puis...
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
}
else{
 $_text = $_noart[0];
 $_organisme ='';
 $_media = '';
}
return array('organisme' => $_organisme,'text' => $_text,'media' => $_media);
}
}

/************************** Fonction aem() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles AEM
*****************************************************/
function aem($_url){
 $_organisme = '`<\s*title\s*>.*?[Afriqu&#8217; Echos Magazine] : (.*?) :.*?<\s*/?title\s*>`si'; //pcre captant l'organisme s'il exist
 $_text = '`<p class="spip">(.*?)</p>`si'; //pcre pour capter le contenue de l'article
 $_return = _pclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction oao() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles OAO
*****************************************************/
function oao($_url){
 $_organisme = '`<\s*title\s*>(.*?) :.*? - [L\'Occidental Afrique Occidentale]\s*<\s*/?title\s*>`si'; //pcre captant l'organisme s'il exist
 $_text = '`<p class="spip">(.*?)</p>`si'; //pcre pour capter le contenue de l'article
 $_return = _pclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction afk() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles AFK
*****************************************************/
function afk($_url){
 $_organisme = '`<\s*title\s*>(.{2,20}) :.*?<\s*/?title\s*>`si'; //pcre captant l'organisme s'il exist
 $_text = '`<p class="spip">(.*?)</p>`si'; //pcre pour capter le contenue de l'article
 $_return = _pclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction anz() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles ANZ
*****************************************************/
function anz($_url){
 $_organisme = '`<afriqueplus>Pas d\'organisme</afriqueplus>`si'; //pcre captant l'organisme s'il exist
 $_text = '`<p class="spip">(.*?)</p>`si'; //pcre pour capter le contenue de l'article
 $_return = _pclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction bbc() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles BBC
*****************************************************/
function bbc($_url){
 $_organisme = '`<\s*title\s*>\s*BBCAfrique.com\s*\|.*?\|\s*(.*?)\s*:.*?<\s*/\s*title\s*>`si'; //pcre captant l'organisme s'il exist
 $_text = '`<p class="storytext">(.*?)</p>`si'; //pcre pour capter le contenue de l'article
 $_return = _pclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
 $_html = file_get_contents($_url,false);
// Ajout de l'image
 $_pcre = '`<\s*img.*?src\s*=\s*["\']((?:http://www\.bbc\.co\.uk/worldservice/images/\d+/.*?)|(?:\.\./\.\./\.\./\.\./\.\./\.\./newsimg\.bbc\.co\.uk/media/images/\d+/.*?))["\'].*?alt\s*=\s*["\'](.*?)["\'].*?/?\s*>`si';
if(preg_match_all($_pcre,$_html,$_match)>0){
 $_img = $_match[0];// Selection image sans se soucier de l'attribut alt
 $_link = $_match[1]; // capture des attributs src
 $_alt = $_match[2]; // Capture des valeurs alt
 for($i=0;$i<count($_img);$i++){ //On modifie les attributs SRC
  $_src = $_link[$i];
  $_src = _loader('_absolute_url',array($_url,$_src)); // Obtention du lien absolut de l'image
  $_link[$i] = $_src;
 } 
}
else{
    $_link = '';
    $_alt = '';
}
$_media = array('I',$_link,$_alt); // Creation du tableau `media`
 // Ajout uniquement de la premiére Image car les autres ont étè faites par _pclass()
if(isset($_img) && !empty($_img)){
  $_text = $_return['text'];
  $_text = '<img src="'.$_link[0].'" alt="'.$_alt[0].'"><p><strong>'.$_alt[0].'</strong></p>'.$_text;
  $_return['text'] = $_text;
}
$_return['media'] = $_media;
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction _divclass() 
Cette fonction est charger d'extraire les infos pour les champs `text`, `organisme`  et `media` des articles
structuré sous la forme <div class=".*?">.*?</div>
*****************************************************/
function _divclass($_url,$_pcre_organisme,$_pcre_text){

  global $_Erreur,$_expreg,$_noart,$_spchar,$_allowable_tag;
if(empty($_url) || empty($_pcre_organisme) || empty($_pcre_text) || !$_html = file_get_contents($_url,false)){
 trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
 return false;
 exit;
}
else{
 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);
  // Obtention de l'organisme
 (preg_match($_pcre_organisme,$_html,$_match)>0)? $_organisme = $_match[1] : $_organisme = '';
 $_organisme = mb_convert_encoding($_organisme,'latin1',mb_detect_encoding($_organisme,"auto",true));
 $_organisme = html_entity_decode($_organisme,ENT_QUOTES); // Decodage Html
 $_organisme = trim($_organisme); // Suppression des espaces aux extremites
 $_organisme = str_ireplace($_spchar_unicode,$_spchar_signe,$_organisme); // Decodage Unicode
 $_organisme = str_replace("?","'",$_organisme); // Correction de l'encodage puis...
 $_organisme = str_replace("'","\'",$_organisme); // Protection pour MySQL 
 

if(preg_match_all($_pcre_text,$_html,$_match)>0){
// Obtention du Texte
(!isset($_match[1][1]) || empty($_match[1][1]))? $_text = $_match[1][0] : $_text = $_match[1][1];
// pré-traitement du texte
  $_text = mb_convert_encoding($_text,'latin1',mb_detect_encoding($_text,"auto",true)); // Encodage du texte en ISO-8859-1mb_detect_encoding($_text)
  $_text = html_entity_decode($_text,ENT_QUOTES); // On decode toute les entitès HTML
  $_text = trim($_text); // Suppression des espaces aux extremites
  $_text = str_ireplace($_spchar_unicode,$_spchar_signe,$_text); // Decodage Unicode
  $_pcre = $_expreg['html']['clean'][0]; 
  $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style
  _callback(1,$_url); // Initialisation de la variable $_url de _callback()
   
  // Recherche d' eventuelles images et liens afin de...
    // réparer les attributs SRC | HREF...
    unset($_pcre); 
  $_pcre[0] = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*/?\s*>`si';
  $_pcre[1] = '`<\s*a[^>]*href\s*=\s*["\'](.*?)["\'][^>]*/?\s*>.*?<\s*/\s*a\s*>`si';
  $_text = preg_replace_callback($_pcre,'_callback',$_text); // Correction des attributs src et href
    // puis inscription des images dans la variable media pour leur sauvegarde
   $_pcre = $_expreg['html']['get']['img'];
   if(preg_match_all($_pcre,$_text,$_match)>0){ // Selection image avec attribut src
    $_link = $_match[1];
    $_alt = $_match[2];
   }  // Selection image sans attribut alt
   else{
    $_pcre = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]*/?\s*>`si';
    if(preg_match_all($_pcre,$_text,$_match)>0){
      $_link = $_match[1];
      $_alt = '';
    }
    else{
     $_link = '';
     $_alt = '';
    }
   }
$_media = array('I',$_link,$_alt); // Creation du tableau `media`
  // Nettoyage du texte 
 $_pcre = $_expreg['html']['clean'];
 $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style paragraphes
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
}
else{
 $_text = $_noart[0];
 $_media ='';
 $_media = '';
}
return array('organisme' => $_organisme,'text' => $_text,'media' => $_media);
}
}
/***************************Fin de la fonction divclass******************************************/

/************************** Fonction pmb() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles PMB
*****************************************************/
function pmb($_url){
global $_noart;
 
 $_organisme = '`<\s*title\s*>(.{2,20}) :.*?<\s*/?title\s*>`si'; //pcre captant l'organisme s'il existe
 $_text = '`(?:<div class="content">|<div class="texte">)(.*?)</div>`si'; //pcre pour capter le contenue de l'article;
 $_return = _divclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
// Nettoyage du texte 
if($_return['text'] != $_noart[0]){
 $_text = $_return['text'];
 $_text = str_replace("?","'",$_text); // Correction de l'encodage puis...
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL 
 $_text = trim($_text);
 $_return['text'] = $_text;
}
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction pmb() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles PMB
*****************************************************/
function ohada($_url){
global $_noart;
 $_organisme = '`<afrique>L\'Afrique</afrique>`si'; //pcre captant l'organisme s'il existe
 $_text = '`<div class="texte">(.*?)</div>`si'; //pcre pour capter le contenue de l'article;
 $_return = _divclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
// Nettoyage du texte 
if($_return['text'] != $_noart[0]){
 $_text = $_return['text'];
 $_text = str_replace("?","'",$_text); // Correction de l'encodage puis...
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL 
 $_text = trim($_text);
 $_return['text'] = $_text;
}
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction afp() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles AFP
*****************************************************/
function afp($_url){
global $_noart,$_allowable_tag;
 
 $_organisme = '`<\s*title\s*>.*?Africa Presse - (.*?):.*?<\s*/?title\s*>`si'; //pcre captant l'organisme s'il existe
 $_text = '`<div class="content">(.*?)</div>`si'; //pcre pour capter le contenue de l'article;
 $_return = _divclass($_url,$_organisme,$_text); //Obtention de l'array('organisme' => $_organisme,'text' => $_text)
// Nettoyage du texte 
if($_return['text'] != $_noart[0]){
 $_text = $_return['text'];
 $_text = preg_replace('`\r+`','</p><p>',$_text); // Remplacament des Retour Chariot par des paragraphes
 $_text = '<p>'.$_text.'</p>';
 $_text = str_replace("?","'",$_text); // Correction de l'encodage puis...
 $_text = trim($_text);
 $_tidy = tidy_parse_string($_text,array('output-xhtml'=>true,'wrap'=>0));
 tidy_clean_repair($_tidy);
 $_text = tidy_get_body($_tidy);
 $_text = $_text->value;
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
 $_text = trim($_text);
 $_return['text'] = $_text;
}
 return $_return; //renvoi du dit tableau à _html_get()
}

/************************** Fonction f24() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles F24
*****************************************************/
function f24($_url){
  global $_Erreur,$_expreg,$_noart,$_spchar,$_allowable_tag;
if(!$_html = file_get_contents($_url,false)){
 trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
 return false;
 exit;
}
else{
 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);

  // Obtention de l'organisme
  $_pcre_organisme = '`<p class="f_suptitle">(.*?)</p>`si';
 (preg_match($_pcre_organisme,$_html,$_match)>0)? $_organisme = $_match[1] : $_organisme = '';
 
 $_organisme = mb_convert_encoding($_organisme,'latin1',mb_detect_encoding($_organisme,"auto",true));
 $_organisme = html_entity_decode($_organisme,ENT_QUOTES); // Decodage Html
 $_organisme = str_ireplace($_spchar_unicode,$_spchar_signe,$_organisme); // Decodage Unicode
 $_organisme = str_replace("'","\'",$_organisme); // Protection pour MySQL
 
 
 // Definition des autres variables de la fonction
$_pcre_text = '`<div id="text">(.*?)\s+</div>`si';
if(preg_match($_pcre_text,$_html,$_match)>0){ // Alors l'article existe
  // Obtention du Texte
 $_text = $_match[1]; // Portion du Html contenant l'article
 $_text = mb_convert_encoding($_text,'latin1',mb_detect_encoding($_text,"auto",true)); // Encodage du texte en ISO-8859-1mb_detect_encoding($_text)
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_pcre = $_expreg['html']['clean'][0];
 $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style
   _callback(1,$_url); // Initialisation de la variable $_url de _callback()
 // Traitement des images
  $_pcre = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]/?\s*>`si';
  $_text = preg_replace_callback($_pcre,'_callback',$_text); // Correction des attributs src des img
 
 // Extraction des Videos
 $_pcre = '`<object.*?>.*?</object>`si';
 if(preg_match($_pcre,$_html,$_match)>0){ //l'article a une video et non une image...
  $_emb = $_match[0];
  $_pcre = '`<param name="url" value="(.*?)" />`si';
 (preg_match($_pcre,$_emb,$_match)>0)? $_link = $_match[1] : $_link = '';
 $_alt = '';
  $_media = array('V',$_link,$_alt); // Creation du tableau `media`
 }
 else{ // Sinon l'article a plustôt une image
  (preg_match('`style="background-image: url\((.*?)\)`',$_html,$_match)>0)? $_link = $_match[1] : $_link = '';
  if(!empty($_link)){ // on verifie si url de l'image à étè capturée
   $_link = _loader('_absolute_url',array($_url,$_link));
   $_media = array('I',$_link,'');
   $_emb='<img src="'.$_link.'" alt="Image illustrant l\'article">';
  }   
 }
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
 $_text = trim($_text);
 $_text = preg_replace('`\s{3,}`','</p><p>',$_text); // Remplacement des retours chariots par des paragraphes
 $_text = str_ireplace($_spchar_unicode,$_spchar_signe,$_text);
 $_text = '<p>'.$_text.'</p>';
 if(!empty($_emb)){
 $_text = $_emb.$_text; // Ajout de La video ou de l'image
 $_text = trim($_text); // Suppresiion des espaces aux extremité de la chaîne
 }
 $_text = str_replace("\'","'",$_text); 
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
 $_tidy = tidy_parse_string($_text,array('output-xhtml'=>true,'wrap'=>0));
 tidy_clean_repair($_tidy); // Utilisation de Tidy pour
 $_text = tidy_get_body($_tidy);
 $_text = $_text->value;
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
  }
  else{
  $_organisme = '';
  $_text = $_noart[0];
  $_media = '';
  }
return array('organisme' => $_organisme,'text' => $_text,'media' => $_media);
 }
}

/************************** Fonction grioo() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles AFP
*****************************************************/
function grioo($_url){
  global $_Erreur,$_expreg,$_noart,$_spchar,$_allowable_tag;
if(!$_html = file_get_contents($_url,false)){
 trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
 return false;
 exit;
}
else{
 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);

$_organisme = '';
 // Definition des autres variables de la fonction
$_pcre_text = '`<b>Publicit&eacute;</b>(.*?)/articleforum.gif`si';
if(preg_match($_pcre_text,$_html,$_match)>0){
  // Obtention du Texte
 $_text = $_match[1]; // Portion du Html contenant l'article
 $_text = mb_convert_encoding($_text,'latin1',mb_detect_encoding($_text,"auto",true)); // Encodage du texte en ISO-8859-1mb_detect_encoding($_text)
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
  $_pcre = $_expreg['html']['clean'][0];
 $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style
 
 // Traitement de toute les Image
   $_pcre = '`<\s*img[^>]*src\s*=\s*["\']((?:(?:images/rubriques/)|(?:http://www.grioo.com/images/rubriques/)).*?)["\'][^>]*/?\s*>`si';
  if(preg_match_all($_pcre,$_text,$_match)>0){
    $_img = $_match[0];// Selection image sans se soucier de l'attribut alt
    $_link = $_match[1]; // capture des attributs src
    $_marque = array(); // Ce Tableau contientra tout les marqeurs d'images selectionées
    for($i=0;$i<count($_img);$i++) $_marque[$i] = '£img'.$i.'£';
    $_text = str_replace($_img,$_marque,$_text); // On marque l'emplacement des images
    for($i=0;$i<count($_img);$i++){
    $_src = $_link[$i];
    $_src = _loader('_absolute_url',array($_url,$_src)); // Obtention du lien absolut de l'image
    $_link[$i] = $_src;
    $_pict = $_img[$i];
    $_img[$i] = preg_replace('`src\s*=\s*["\'](images/rubriques/.*?)["\']`','src="'.$_src.'"',$_pict);
    }
    $_alt = '';
   }
   else{
    $_link = '';
    $_alt = '';
   }
$_media = array('I',$_link,$_alt); // Creation du tableau `media`
 $_allowable_tag = str_replace('<img>,','',$_allowable_tag); // Suppression aussi des images
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
 $_text = str_ireplace(array('<b>Publicité</b>','<a name="para0"></a>'),'',$_text); // Suppression de publicite et autres
 $_text = trim($_text);
 $_text = preg_replace('`\s*\r+\s*`','</p><p>',$_text); // Remplacement des retours chariots par des paragraphes
 $_text = preg_replace('`\s{2,}`',' ',$_text); // Suppression des espaces superflues
 $_text = '<p>'.$_text.'</p>';
 if(!empty($_img))
 $_text = str_replace($_marque,$_img,$_text); // Remplaçage des marques d'images par leurs images
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
  }
  else{
  $_organisme = '';
  $_text = $_noart[0];
  $_media = '';
  }
return array('organisme' => $_organisme,'text' => $_text,'media' => $_media);
 }
}


/************************** Fonction rfi() 
Cette fonction est charger d'extraire les infos pour les champs `text` et `organisme` des articles RFI
*****************************************************/
function rfi($_url){
global $_Erreur,$_expreg,$_noart,$_spchar,$_allowable_tag;
if(!$_html = file_get_contents($_url,false)){
 trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
 return false;
 exit;
}
else{
 // Definition des 2 tableaux de correspondances pour les convertions unicode
$_spchar_unicode = array_values($_spchar);
$_spchar_signe = array_keys($_spchar);
  // Obtention de l'organisme
  $_pcre_organisme = '`<div class="corps"><h1>(.*?)</h1>`si';
 (preg_match($_pcre_organisme,$_html,$_match)>0)? $_organisme = $_match[1] : $_organisme = '';
 $_organisme = mb_convert_encoding($_organisme,'latin1',mb_detect_encoding($_organisme,"auto",true)); // Encodage du texte en ISO-8859-1
 $_organisme = html_entity_decode($_organisme,ENT_QUOTES); // Decodage Html
 $_organisme = str_ireplace($_spchar_unicode,$_spchar_signe,$_organisme); // Decodage Unicode
 $_organisme = str_replace("'","\'",$_organisme); // Protection pour MySQL
 $_organisme = trim($_organisme);
 
 // Definition des autres variables de la fonction
$_pcre_text = '`<div class="texte">(.*?)</div>`si';
if(preg_match($_pcre_text,$_html,$_match)>0){ // Alors l'article existe
  // Obtention du Texte
 $_text = $_match[1]; // Portion du Html contenant l'article
 $_text = mb_convert_encoding($_text,'latin1',mb_detect_encoding($_text,"auto",true)); // Encodage du texte en ISO-8859-1
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_pcre = $_expreg['html']['clean'][0];
 $_text = preg_replace($_pcre,'',$_text); // Suppression des passages Script,NoScript et Style
   _callback(1,$_url); // Initialisation de la variable $_url de _callback()
 // Traitement des images éventuelles
  $_pcre = '`<\s*img[^>]*src\s*=\s*["\'](.*?)["\'][^>]/?\s*>`si';
  $_text = preg_replace_callback($_pcre,'_callback',$_text); // Correction des attributs src des img
 
 // Extraction des images
 $_pcre = '`<div class="photo">(.*?<img.*?src="(.*?)".*?alt="(.*?)".*?)</div>`si';
 if(preg_match($_pcre,$_html,$_match)>0){ //l'article est dotée d'une image
  for($i=0;$i<count($_match);$i++){
   $_emb = $_match[$i];
   $_match[$i] = mb_convert_encoding($_emb,'latin1',mb_detect_encoding($_emb,"auto",true)); // Encodage du texte en ISO-8859-1
   }
  $_link = $_match[2]; // On recupere les valeurs des parentheses
  $_alt = $_match[3]; // capturentes
  $_link = _loader('_absolute_url',array($_url,$_link)); // on transforme le lien en absolue
  $_media = array('I',$_link,$_alt); // Creation de la table media
  $_emb = $_match[1]; // On recupere la partie contenant l'image
  $_emb = preg_replace('`src="(.*?)"`si','src="'.$_link.'"',$_emb); // On remplace le src de l'image
 }
 else $_media = '';
 $_allowable_tag = str_replace('<p>,','',$_allowable_tag);
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
 $_text = preg_replace('`\r+`','</p><p>',$_text); // Remplacement des retours chariots par des paragraphes
 $_text = preg_replace('`\s{2,}`',' ',$_text); // Suppressions des espaces redondant
 $_text = str_ireplace($_spchar_unicode,$_spchar_signe,$_text);
 $_text = '<p>'.$_text.'</p>';
if(!empty($_emb))
 $_text = $_emb.$_text; // Ajout de La video ou de l'image
 $_tidy = tidy_parse_string($_text,array('output-xhtml'=>true,'wrap'=>0));
 tidy_clean_repair($_tidy);
 $_text = tidy_get_body($_tidy);
 $_text = $_text->value;
 $_text = html_entity_decode($_text,ENT_QUOTES); // Decodage des entitées HTML
 $_allowable_tag ='<p>,'.$_allowable_tag;
 $_text = strip_tags($_text,$_allowable_tag); // Suppression des balises html Superflues
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
 $_text = str_replace("?","'",$_text); //Correction de l'encodage
 $_text = str_replace("\'","'",$_text); // Prevention des apostrophes
 $_text = str_replace("'","\'",$_text); // Protection pour MySQL
 $_text = trim($_text);
  }
  else{
  $_organisme = '';
  $_text = $_noart[0];
  $_media = '';
  }
return array('organisme' => $_organisme,'text' => $_text,'media' => $_media);
 }
}

echo'<h1 align="center"><i>'.(microtime(true)-$start).'</i> Secondes</h1>';
?>
