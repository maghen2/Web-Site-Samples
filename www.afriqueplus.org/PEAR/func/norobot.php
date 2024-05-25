<?php
/********* Creation de la Fonction _norobot();
 Cette fonction a pour mission de générer aléatoirement une image ou est inscrit un mot de passe
de 8 caracteres permettant de lutter contre la soumission automatique de formulaire
//***************************************************************************************************************/
require_once('conf.php'); // Inclusion du fichier de configuration

function _norobot(){
 global $_domain,$_alphanum,$_alphanum2,$_alphanum3;
shuffle($_alphanum);
$_keys = array_rand($_alphanum,20); // On choisit aléatoirement 20 clés du tableau $_alphanum

$_pws = ''; // Creation puis...
 foreach($_keys as $_key=>$_value) $_pws.= $_alphanum[$_value]; // remplissage de la variable PassWord ($_pw)
$_pw = substr($_pws,0,8);
$_e1 = substr($_pws,8,5);
$_e2 = substr($_pws,13,7);
$_pw_array = str_split($_pw);
$_hash = '';
foreach($_pw_array as $_key=>$_value) $_hash.= $_alphanum3[$_value]; 
$_hash = $_e1.$_hash.$_e2;
$_img = $_domain.'include/image.php?txt='.$_hash;
$_return = array('pw'=>$_pw,'img'=>$_img,'id'=>$_hash);
return $_return;
}

_norobot();




?>
