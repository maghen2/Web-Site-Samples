<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
/* Script de gestion personnalisée des erreurs HTTP */

function _htaccess($_error){
global $_Erreur;
global $_htaccess;
$search='£erreur£';
$param=array('Accueil','7');
$html=_loader('_page',$param);
if(!empty($_error) && in_array($_error,$_htaccess[0])){//Verification des parametres
  $header= $_htaccess[1][$_error];
 switch ($_error) {
 case $_htaccess[0][0] :
  $replace='<h2>ERREUR 403 Forbidden: Accès au fichier refusé</h2><p style="color:red;font-size:2em;font-weight:bold;text-align:left">ERREUR 403 Forbidden: L\'accès au fichier à l\'adressse <span style="color:lime;font-style:italic;">http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'</span>  vous a été refusé</p>'; 
 break; 
 case $_htaccess[0][1] :
  $replace='<h2>ERREUR 404: le fichier démandé est introuvable</h2><p style="color:red;font-size:2em;font-weight:bold;text-align:left">ERREUR 404: Le fichier à l\'adressse <span style="color:lime;font-style:italic;">http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'</span> n\'existe pas ou n\'existe plus</p>'; 
 break; 
 case $_htaccess[0][2] :
  $replace='<h2>ERREUR 500 Internal Server Error:  Erreur interne</h2><p style="color:red;font-size:2em;font-weight:bold;text-align:left">ERREUR 500 Internal Server Error: Désolè une erreur interne au serveur s\'est produite lors de la demande du fichier à l\'adressse <span style="color:lime;font-style:italic;">http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'</span></p>'; 
 break; 
 default :
 }
$html=str_replace($search,$replace,$html);
header($header);
echo($html);
}
else{// Sinon erreur de parametres
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
}

?>
