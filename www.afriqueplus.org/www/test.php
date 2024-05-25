

<?php
/********* Creation de la page de traitement du formulaire de contact
Ce script à pour charge de traiter le formulaire "Nous Contacter"
//***************************************************************************************************************/
$start = microtime(true);
require_once('conf.php'); // Inclusion du fichier de configuration


if(!($_page = file_get_contents('xml/squelette.xml',true))){ // On verifie que le squelette XML a bien été chargée
 trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
 return false;
 exit;
}
else{
// On définie le tableau récapitulatif des données

  $_metadata = array(
  'title' => '',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '',
  '' => ''
 ); 
 
// Construction du menu principale
 $_menu = '<h1 id="header"><a href="http://'.$_SERVER['HTTP_HOST'].'/" title="AfriquePlus - Accueil"><span> AfriquePlus </span></a></h1><div id="search"><form name="search" method="get" action="../search/"><input type="text" name="q" size="25" value="Rechercher sur AfriquePus..." id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" /><input type="hidden" name="mode" value="normale" /><input type="submit" value=" " id="rechercher"/></form></div><ul id="menu">';
 $_menu .= '<li><a href="'.$_domain.'"> Accueil </a></li><li><a href="'.$_domain.'../article/"> Articles </a></li><li><a href="'.$_domain.'about/?page=contact"> Contact </a></li><li><a href="'.$_domain.'about/?page=partenariat"> Partenariat </a></li><li><a href="'.$_domain.'about/?page=about"> A Propos </a></li>';
 $_head = $_menu;

/*
while(stripos(file_get_contents('http://localhost/include/html_get.php'),'£end£')===false)
fopen('http://localhost/include/html_get.php','r');

$sql = file_get_contents('sql/pays.sql',true); 
print_r(_loader('_db',array('_shell',array($sql))));

  $_files = scandir('include/font/');
  $str='(
  ';
  foreach($_files as $key=>$value) $str.="'$value',";
  $str.='
  )';
  echo'<pre>';
*/
  $_param = array('_select',array('*','`pays`',1,'`nom`',500));
  $_data = _loader('_db',$_param);
  $_pays = '';
  for($i=0;$i<count($_data);$i++)
    $_pays .= "'".$_data[$i]['code']."',";
echo $_pays;




  $_text ='<h2>PAGE DES TESTS DU SITE </h2>
'.$str.'
</div>';
$_metadata['title'] = 'PAGE DES TESTS DU SITE';

// On ajoute quelques articles

 // 5 Dernieres Nouvelles
 $_text .= '<h2>5 Dernieres Nouvelles <i>&laquo; '.date('c').' &raquo;</i> </h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',1,'`date` DESC',5));
 $_data = _loader('_db',$_param);
 
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="../article/?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
 
// 5 articles pour chaque Categorie
 $_param = array('_select',array('DISTINCT `categorie`','`article`',1,'`categorie`',30));
 $_data = _loader('_db',$_param);
 $_categorie = $_data;
 $_num = count($_categorie);
 for($_i=0;$_i<$_num;$_i++){
  $_text .='<h2>5 Articles de la Categorie <i>&laquo; '.$_categorie[$_i]['categorie'].' &raquo;</i></h2>';
  $_param = array('_select',array('`id`,`title`,`description`','`article`',"`categorie`='".$_categorie[$_i]['categorie']."'",'RAND()',5));
  $_data = _loader('_db',$_param);
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="../article/?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
 
 } 
$_metadata['text'] = $_text;

   
 // On ecrie le pied de page
$_foot = '<ul><h3>Page calculée en <span class="foot">'.round(microtime(true)-$start,2).' Secondes</span></p></ul><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit réservé</p>';

 
 $_model = array(
  'index' => array('£title£','£keyword£','£description£','£date£','£categorie£','£genre£','£organisme£','£link£','£theme£','£script£','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  $_metadata['title'],$_metadata['keyword'],$_metadata['description'],$_metadata['date'],$_metadata['categorie'],$_metadata['genre'],$_metadata['organisme'],$_metadata['link'],'default',$_js,'<div id="head">'.$_head,'<div id="body"><div id="article">'.$_metadata['text'],'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;
 
}

?> 






