<?php
/***************** Creation de l'application de syndication du site
Cette application est charg� d'afficher les flux de syndication RSS du site
 ****************************************************************************************************/ 
require_once('conf.php'); // Inclusion du fichier de configuration

if(!($_page = file_get_contents('xml/rss.xml',true))){ // On verifie que le squelette XML a bien �t� charg�e
 trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
 return false;
 exit;
}
else{
$_page = str_replace('�domain�',$_domain,$_page);
// On verifie le param�tre categorie
if(!isset($_GET['categorie']) || empty($_GET['categorie'])) $_GET['categorie']='general'; 

// On fixe la valeur de la variable $_cat en fonction de la categorie
switch($_GET['categorie']){
 case 'general':
  $_cat = '1';
  $_title = 'Syndiquer toute l&apos;actualit�';
  break;
 case 'Actualit�':
  $_cat = "`categorie`='Actualit�'";
  $_title = 'Syndiquer l&apos;actualit� Politique';
  break;
 case 'Economie':
  $_cat = "`categorie`='Economie'";
  $_title = 'Syndiquer l&apos;actualit� Economique';
  break;
 case 'Sant�':
  $_cat = "`categorie`='Sant�'";
  $_title = 'Syndiquer l&apos;actualit� Sanitaire';
  break;
 case 'Sport':
  $_cat = "`categorie`='Sport'";
  $_title = 'Syndiquer l&apos;actualit� Sportive';
  break;
 default:
  $_cat = '1';
  $_title = 'Syndiquer toute l&apos;actualit�';
}

// On selectionne les articles depuis la Base de Donn�e
$_param = array('_select',array('*','`article`',$_cat,'`date` DESC','30'));
$_data = _loader('_db',$_param);
// header('Content-Type: text/plain'); //print_r($_data);

// Listage de toute les sources
$_param = array('_select',array('*','`source`','1','`cle`','300'));
$_data1 = _loader('_db',$_param);
$_sources = array();
for($i=0;$i<count($_data1);$i++) $_sources[$_data1[$i]['initial']] = $_data1[$i]['nom'];

$_rss = '';
for($i=0;$i<count($_data);$i++) // Creation du contenue RSS
 $_rss .= '<item> <title> '.$_data[$i]['title'].'</title> <link> '.$_domain.'article/?article='.$_data[$i]['id'].'</link> <pubDate> '.$_data[$i]['date'].'</pubDate> <description> '.$_data[$i]['description'].'</description> <author domain="'.$_data[$i]['link'].'"> '.$_sources[$_data[$i]['source']].'</author> <category> '.$_data[$i]['categorie'].'</category> </item> 
 ';

header('Content-Type: application/rss+xml');
$_page = str_replace(array('�title�','�rss�'),array($_title,$_rss),$_page);
echo $_page;

}
?> 


