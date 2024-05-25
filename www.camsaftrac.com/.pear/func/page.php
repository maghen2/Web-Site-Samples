<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
/* Creation de la Fonction _page()
Cette API a pour but d'interfacer l'affichage des pages web. C'est le moteur d'affichage du site
Elle construit la page appropriée aux parametres fournis
Elle construit les hyperliens et structure le site
*/

function _page($_id=0){ //par defaut on affiche la page d'acceuil
global $_Erreur;
global $_domain;

 $_param1 = array('_select',array('*','article',"`id`='$_id'",1,1));

 if(!($_article=_loader('_db',$_param1))){ // Verification de la selection des tables dans Mysql
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   return false;
   exit;
   }
   elseif( !($_article=$_article[0]) || empty($_article)){// Verification de l'existance de la page demandée
    $param=array(404);
    _loader('_htaccess',$param);
    exit;
   }
   else{// On charge le squelette xml du site
   if(!($_xml = file_get_contents('.pear/xml/squelette.xml'))){ // On verifie que le squelette XML a bien été chargée
    trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
    return false;
    exit;
   }
   else{// On finalise la page avec toute les infos complementaires
  /*
   £categorie£
  £date£
  £description£
  £domain£
  £genre£
  £keyword£
  £theme£
  £title£
  £header£
  £menu£
  £nav£
  £content£
  £footer£
  */

$_index=array('£date£','£description£','£domain£','£keyword£','£title£','£content£');
$_valeur=array($_article['date'],$_article['description'],$_domain,$_article['keyword'],$_article['title'],$_article['content']);
$_xml = str_replace($_index,$_valeur,$_xml);
//var_dump($_valeur);
return $_xml;  
   }
   
   }
}













?>
