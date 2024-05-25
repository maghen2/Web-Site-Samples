<?php
/***************** Creation de l'application des articles
 Cette Application aura pour but d'afficher les articles et d'en suggerés intelligament
 ****************************************************************************************************/ 
$start = microtime(true);
require_once('conf.php'); // Inclusion du fichier de configuration

 // Si aucun article n'est specifié On affiche un article par Defaut
if(!isset($_GET['article']) || empty($_GET['article'])){
 $_param = array('_select',array('`id`','`article`','1','RAND()','1'));
 $_article_ids = _loader('_db',$_param);
 $_article_id = $_article_ids[0][0];
 $_GET['article'] = $_article_id;
}


if(!($_page = file_get_contents('xml/squelette.xml',true)) || !($_id = addslashes($_GET['article']))){ // On verifie que le squelette XML a bien été chargée
 trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
 return false;
 exit;
}
else{
$_page = str_replace('£domain£',$_domain,$_page);
// Construction du menu principale
 $_menu = '<h1 id="header"><a href="http://'.$_SERVER['HTTP_HOST'].'/" title="AfriquePlus - Accueil"><span> AfriquePlus </span></a></h1><div id="search"><form name="search" method="get" action="../search/"><input type="text" name="q" size="25" value="Rechercher sur AfriquePus..." id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" /><input type="hidden" name="mode" value="normale" /><input type="submit" value=" " id="rechercher"/></form></div><ul id="menu">';
 $_menu .= '<li><a href="'.$_domain.'"> Accueil </a></li><li><a href="'.$_domain.'article/"> Articles </a></li><li><a href="'.$_domain.'about/?page=contact"> Contact </a></li><li><a href="'.$_domain.'about/?page=partenariat"> Partenariat </a></li><li><a href="'.$_domain.'about/?page=about"> A Propos </a></li>';
 $_head = $_menu;

// On selection l'article demandée depuis la base de donnée
 $_param = array('_select',array('*','`article`',"`id`='$_id'",'`cle`'));
 if(($_article = _loader('_db',$_param))){

// On incremente de 1 le compteur du nombre de lecture de l'article
 $_param = array('_update',array('`article`','`lecture`=`lecture`+1',"`id`='$_id'",'`cle`','1'));
  _loader('_db',$_param);
}
// On ajoute quelques articles
  // 5 Autres Articles de la même source
 $_param = array('_select',array('`nom`','source',"`initial`='".$_article[0]['source']."'",'RAND()',1));
 $_data = _loader('_db',$_param);
 $_text='</div><h2>5 Autres Articles de la Source <i>&laquo; '.$_data[0]['nom'].' &raquo;</i> </h2>';
 $_source = $_data[0]['nom'];

 $_param = array('_select',array('`id`,`title`,`description`','`article`',"`source`='".$_article[0]['source']."'",'RAND()',5));
 $_data = _loader('_db',$_param);
 
 for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="index.php?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }

 // 5 Dernieres Nouvelles
 $_text .='<h2>5 Dernieres Nouvelles au <i>&laquo; '.get_date().' &raquo;</i> </h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',1,'`date` DESC',5));
 $_data = _loader('_db',$_param);
 
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="index.php?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
  // 5 autres articles de la même Categorie
 $_text .='<h2>5 Autres Articles de la Categorie <i>&laquo; '.$_article[0]['categorie'].' &raquo;</i></h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',"`categorie`='".$_article[0]['categorie']."'",'RAND()',5));
 $_data = _loader('_db',$_param);
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="index.php?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
  // 5 autres articles de la même Region / Organisme
 $_text .='<h2>5 Autres Articles de la Region / Organisme <i>&laquo; '.$_article[0]['organisme'].' &raquo;</i></h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',"`organisme`='".addslashes($_article[0]['organisme'])."'",'RAND()',5));
 $_data = _loader('_db',$_param);
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="index.php?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
  // 5 autres articles du même Theme
 $_text .='<h2>5 Autres Articles du Theme <i>&laquo; '.$_article[0]['genre'].' &raquo;</i></h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',"`genre`='".$_article[0]['genre']."'",'RAND()',5));
 $_data = _loader('_db',$_param);
 
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="index.php?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }    

 // On ecrit la mention légale
$_text = '<div class="vignette"><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit réservé</p><p>AfriquePlus collecte et indexe du contenu provenant de plusieurs sources d\'actualités africaines soigneusement sélectionnées afin de contribuer à l\'africanisation du web.</p><p>Chaque article publié sur AfriquePlus est la propriété exclusive de son/ses ayant(s) droit de ce fait:<p><ul><li><span class="foot">Sauf avis contraire, AfriquePlus ne possède aucun droit sur quelque article que se soit</span></li><li><span class="foot">pour toute autorisation de reproduction,diffusion ou réutilisation contactez directement le(s) propriétaire(s) des droits de l\'article indiqué(s) au pied de page par la mention <a href="'.$_article[0]['link'].'" target="_blank"> Source </a></span></li><li><span class="foot">AfriquePlus décline toute responsabilité, quelqu\'elle soit, totale ou partielle, sur le contenue des articles par lui publiés</span></li><li><span class="foot">Les pourvoyeurs d\'informations de AfriquePlus gardent l\'entière responsabilité de leur production</span></li></ul><a href="../about/?page=source"><h3>Voir la <i>&laquo; LISTE EXHAUSTIVE DE TOUT NOS POURVOYEURS D’ARTICLE &raquo;</i></h3></a></div>'.$_text; 
  // On ecrie le pied de page
$_foot = '<ul><li>Article lue <span class="foot">'.$_article[0]['lecture'].' fois</span></li><li>Score <span class="foot">'.$_article[0]['vote'].'</span></li><li>Source <a href="'.$_article[0]['link'].'" target="_blank"><span class="foot">'. $_source.'</span></a></li><li>Page calculée en <span class="foot">'.round(microtime(true)-$start,2).' Secondes</span></li></ul><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit réservé</p>';
 
 $_model = array(
  'index' => array('£title£','£keyword£','£description£','£date£','£categorie£','£genre£','£organisme£','£link£','£theme£','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  $_article[0]['title'],$_article[0]['keyword'],$_article[0]['description'],$_article[0]['date'],$_article[0]['categorie'],$_article[0]['genre'],$_article[0]['organisme'],$_article[0]['link'],'default','<div id="head">'.$_head,'<div id="body"><div id="article">'.$_article[0]['text'].$_text,'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;
}

/************************ Bonus pour les articles
 
 * Voter cet article
 * Envoyer par mail
 * Imprimer
 * Commenter
 * Créer un systeme de crash

Creation de 2 Tables suplementaires pour pouvoir commenter
 - utilisateur
 - comment
 
 
 
 
 */


?>
