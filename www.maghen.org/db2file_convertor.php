<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/

/* Script de gestion centralisée des erreurs */
require_once('conf.php'); // Inclusion du fichier de configuration
for ($z=2;$z<=7;$z++ ) {
$param=array('_select',array('*','article',"id=$z"));
$_article=_loader('_db',$param);
$_article=$_article[0];
$_article=str_replace("'", "\'", $_article, $i);
echo('$_article=array(
');
$title=$_article['title'];
$key=$_article['keyword'];
$des=$_article['description'];
$cat=$_article['categorie'];
$date=$_article['date'];
$genre=$_article['genre'];
$cont=$_article['content'];

$tab="'title'=>'".$title."',
  'keyword'=>'".$key."',
  'description'=>'".$des."',
  'date'=>'".$cat."',
  'categorie'=>'".$date."',
  'genre'=>'".$genre."',
  'content'=>'".$cont."'
  ";
echo($tab);
echo(");
");
}
?>
