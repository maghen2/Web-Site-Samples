<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit r�serv�
MAGHEN NEGOU Rostant
Communicateur Informaticien
T�l:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/

/* Script de gestion centralis�e des erreurs */
require_once('conf.php'); // Inclusion du fichier de configuration

//On verifie si le script a ete apel� avec les parametres corrects(par Apache par example)
(!empty($_GET['error']) && in_array($_GET['error'],$_htaccess[0]))? $param[0]=$_GET['error']:$param[0]=$_htaccess[0][0];
//var_dump($param);
_loader('_htaccess',$param);
?>
