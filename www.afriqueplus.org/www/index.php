<?php
/********* Creation de la page d'acceuil
Cette page sera la vitrine du site car elle est la principale page d'entrée du site
Elle doit proposer un acces fluide et logique à tout le contenu du site
//***************************************************************************************************************/
$start = microtime(true);
require_once('conf.php'); // Inclusion du fichier de configuration


if(!($_page = file_get_contents('xml/squelette.xml',true))){ // On verifie que le squelette XML a bien été chargée
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

// On ajoute le texte de Presentation
 $_text = '<h1>..:: Acceuil &int; Bienvenue Chez AfriquePlus ::..</h1><h2>AfriquePlus &bull; L&#8217; autre visage de l&#8217;Afrique </h2>
 <p>
AfriquePlus est un portail panafricain qui pense qu&#8217;au déla des appréories, des préjugés et des prénotions populaires tant
dispachés par les gros médias et pas seulement, l&#8217;Afrique regorge de personnes dynamiques et remplie de bonnes volonté qui
reuississent déja à faire beaucoup à partir de rien ou presque. Sans se laisser submerger par un selle d&#8217;afro-optimisme,
AfriquePlus se focalisera sur l&#8217;initiativité panafricaine.
</p><p>
Considérant d&#8217;une part que le développement est un tout entier et d&#8217;autre part que toute chose aussi sublime soit elle n&#8217;est
que la somme de ses éléments constituants; tout projet susceptible d&#8217;apporter un plus à l&#8217;Afrique, dans quelque domaine que
ce soit, aussi marginal soit il, dès l&#8217;instant qu&#8217;il est productif, est louable. Tel est le noeud gordien de la philosophie
d&#8217;AfriquePlus.
</p><p>
Les AfriPlusiens et AfriPlusiennes sont toute ces personnes sans distinction de sexe, religion, ethnie, âge, appartenance 
politique, niveau intellectuel, niveau social qui adhérent à la philosophie d&#8217;AfriquePlus.
 </p><p>
A cet effet AfriquePlus ne ménagera aucun effort afin de contribuer efficacement à l&#8217;édification de notre continent.
Concrètement et dans un premier temps il s&#8217;agira pour nous de:
<ul> 
<li> créer un espace complet d&#8217;actualités panafricaines susceptible d&#8217;aider les uns et les autres à facilement et mieux s&#8217;informer sur 
 l&#8217;actualité du continent tout en ayant l&#8217;opportunité de réagir librement sur cette même actualité afin que chaque jour leurs
voix soient de mieux en mieux entandues.</li> 

<li> Créer une base de données évolutive regroupant des exemples de projet de développement afin d&#8217;aider les promoteurs à la 
conceptualisation puis la réalisation de nouveaux projets.</li>

<li> Créer un réseau d&#8217;échange, d&#8217;entraide et de coopération entre les promoteurs afin de cumuler les savoir-faires et ainsi
accroître les chances de réussites des projets.</li>

<li> Créer un portail web panafricain regroupant les services web les plus populaires, afin que les internauts africains aient
un site web à leurs semblances et ressemblances conçut spécifiquement pour eux.</li>

<li> Apporter des soutiens technologiques, financiers, logistiques, humains et autres à la réalisation et l&#8217;essor
 d&#8217;initiatives de développement.</li>

<li> Au déla de tout ceci, il sagira d&#8217;inciter chaque africain à s&#8217;approprier son destin individuel et collectif afin que
nous menions et gagnions tous ensemble notre combat contre l&#8217;inproductivisme pour une Afrique Nkrhumanienne.
</li></ul>
</p><p>
AfriquePlus est un projet essentiellement participatif qui est né et ne survivra que grâce à la contribution de tout les
AfriquePlussiens et AfriquePlussiennes car les prestations afriqueplussiennes se veulent accessibles au plus grand nombre donc
essentiellement gratuites.
</p><p>
Cette gratuité ne devant aucunement nuire à la qualité des prestations, car nous voulons avant toute chose l&#8217;obtention de 
résultats probants qui nous permettrons de prouver qu&#8217;il existe un autre type de développement respectueux de l&#8217;environnement, 
des droits de l&#8217;Homme, durable,  et que seul ce type de développement est le mieux adapté à l&#8217;Afrique.
</p></div>';

// On ajoute quelques articles

 // 5 Dernieres Nouvelles
 $_text .= '<h2>5 Dernieres Nouvelles au <i>&laquo; '.get_date().' &raquo;</i> </h2>';
 $_param = array('_select',array('`id`,`title`,`description`','`article`',1,'`date` DESC',5));
 $_data = _loader('_db',$_param);
 
  for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="article/?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
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
  $_text .= '<div class="vignette"><h3><a href="article/?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
 
 } 
  
 // On ecrie le pied de page
$_foot = '<ul><li>Page calculée en <span class="foot">'.round(microtime(true)-$start,2).' Secondes</span></li></ul><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit réservé</p>';
 
 // On finalise la page avec toute les infos complementaires
 $_data = array(
  'title' => 'Acceuil &int; Bienvenue Chez AfriquePlus',
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
 
 $_model = array(
  'index' => array('£title£','£keyword£','£description£','£date£','£categorie£','£genre£','£organisme£','£link£','£theme£','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  $_data['title'],$_data['keyword'],$_data['description'],$_data['date'],$_data['categorie'],$_data['genre'],$_data['organisme'],$_data['link'],'default','<div id="head">'.$_head,'<div id="body"><div id="article">'.$_data['text'].$_text,'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;
}

?> 



