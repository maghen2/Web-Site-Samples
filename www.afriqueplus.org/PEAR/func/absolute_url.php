<?php
/********** _absolute_url()
 Cette fonction aura pour mission de determiner le chemin absolut d'acces à une ressource
à partir de l'url du fichier Html($_base) et l'adresse relative de la dite ressource($_url)
*/
require_once('conf.php');

function _absolute_url($_base,$_url){
 global $_Erreur,$_expreg;
 // Verification de la validité des paramètres
if(empty($_base) || empty($_url)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
else{
 if(stripos($_url,'http://')===0 ||stripos($_url,'https://')===0 ||stripos($_url,'ftp://')===0)
  return $_url;
 else{
  if(stripos($_base,'http://')===0 || stripos($_base,'https://')===0 || stripos($_base,'ftp://')===0){
 if(!is_array(explode('/',dirname($_base))) || !is_int(substr_count($_url,'../'))){
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  return false;
  exit;
 }
 else{
  $_dir = explode('/',dirname($_base));
  $_num = substr_count($_url,'../');
  $_src = '';
  $_url = str_replace('../','',$_url);
  $_num = count($_dir)-$_num;
  for($i=0;$i<$_num;$i++) $_src .= $_dir[$i].'/';
  return $_src.$_url;
   }
  }
 }
}
}
?>
