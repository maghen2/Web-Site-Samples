<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
/********* Creation de la page d'acceuil
Cette page sera la vitrine du site car elle est la principale page d'entrée du site
Elle doit proposer un acces fluide et logique à tout le contenu du site
//***************************************************************************************************************/
require_once('conf.php'); // Inclusion du fichier de configuration
(empty($_GET['page']))?$page='Accueil':$page=$_GET['page'];
(empty($_GET['id']))?$id='0':$id=$_GET['id'];
$param=array($page,$id);
$html=_loader('_page',$param);
echo($html);
?>
