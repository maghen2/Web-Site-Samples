<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit r�serv�
MAGHEN NEGOU Rostant
Communicateur Informaticien
T�l:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/

/* Script de gestion centralis�e des images */
require_once('conf.php'); // Inclusion du fichier de configuration
$param=array('6');
$search='�image�';
(!empty($_GET['id']))? $image=$_GET['id']:$image='Image031';
$html=_loader('_page',$param);
$replace='<img src="src/img/big/'.$image.'.jpg" alt="'.$image.'">';
$html=str_replace($search,$replace,$html);
echo($html);
?>
