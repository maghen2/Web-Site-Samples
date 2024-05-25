<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
/* Creation de la Fonction _page()
Cette API a pour but d'interfacer l'affichage des pages web. C'est le moteur d'affichage du site
Elle construit la page appropriée aux parametres fournis
Elle construit les hyperliens et structure le site
*/

function _page($_page,$_id=0){ //par defaut on affiche la page d'acceuil
global $_Erreur;
global $_domain;
$_page=array('header'=>'<h1> MAGHEN NEGOU Rostant</h1><address>Tél: (+237) 77 92 06 85 / 94 27 12 80 / 33 06 61 02 / 77 55 57 43</address><address>BP : 11815 Akwa, Douala, Cameroun</address><address>Émail : <a href="mailto:maghen2@gmail.com"> maghen2@gmail.com </a> &nbsp;&nbsp;&nbsp; Site Web : <a href="http://www.maghen.org/"> www.maghen.org</a></address><h2> COMMUNICATEUR &bull; INFORMATICIEN</h2>',
            'menu'=>'<menu class="menu"><li><a href="index.php" title="Bienvenue sur mon site personnel">Accueil</a></li><li><a href="prestations.php" title="Mes prestations et mon savoir-faire">Prestations</a></li><li><a href="portfolio.php" title="Mon Portfolio et Mes Références">Références</a></li><li><a href="publications.php" title="Mes Publications">Publications</a></li><li><a href="cv.php" title="Ma Formation et Ma Vie Professionelle">CV &amp; LM</a></li><li><a href="apropos.php" title="A propos de ce site web et de son promoteur">A Propos</a></li><li><a href="contact.php" title="Contactez MAGHEN NEGOU Rostant, Communicateur et Informaticien à Douala au Cameroun">Contact</a></li></menu>',
            'nav'=>'<form class="search" name="search" method="get" action="/" id= "search"><fieldset><legend>MOTEUR DE RECHERCHE</legend> <input type="text" class="q" name="q" size="25" value="Rechercher sur le site..." id="q" onfocus= "if(this.value==\'Rechercher sur le site...\') this.value=\'\'"> <input type="hidden" name="mode" value="normale"> <input type= "submit" value="Chercher"></fieldset></form><menu class="menu"> <li> <a href="index.php" title="Bienvenue sur mon site personnel">Accueil</a></li> <li> <a href="prestations.php" title="Mes prestations et mon savoir-faire">Prestations</a></li> <li> <a href="portfolio.php" title="Mon Portfolio et Mes Références">Références</a></li> <li> <a href="publications.php" title="Mes Publications">Publications</a></li> <li> <a href="cv.php" title="Ma Formation et Ma Vie Professionelle">CV &amp; LM</a></li> <li> <a href="apropos.php" title="A propos de ce site web et de son promoteur">A Propos</a></li> <li> <a href="contact.php" title="Contactez MAGHEN NEGOU Rostant, Communicateur et Informaticien à Douala au Cameroun">Contact</a></li></menu><h2>Documents</h2><ul> <li> <a href="publications.php#offre_de_services_en_communication_marketing_et_creation_de_sites_web" title= "J\'offre un service complet qui couvre les domaines du Marketing, de la Communication et de la Programmation Informatique. 10 ann&eacute;es d&rsquo;exp&eacute;rience professionnelle dans ces domaines m\'ont permis une excellente ma&icirc;trise des outils d\'&eacute;laboration et d&rsquo;impl&eacute;mentation de solutions efficaces.  Ainsi ma formation pluridisciplinaire r&eacute;pond aux exigences des diff&eacute;rents postes que j\'ai eu &agrave; occuper dans divers entreprises. Ce ci m\'a amen&eacute; &agrave; me sp&eacute;cialis&eacute; en Communication Marketing Int&eacute;gr&eacute;e (CMI). Ma mission est de collaborer avec une vari&eacute;t&eacute; de clients et une &eacute;quipe d\'employ&eacute;s motiv&eacute;s et responsables, afin d\'offrir des solutions innovatrices, en ayant toujours en t&ecirc;te l\'obligation de r&eacute;sultats. En effet, Au cours de ma carri&egrave;re, j\'ai tout particuli&egrave;rement mis l\'accent sur l\'am&eacute;lioration des proc&eacute;d&eacute;s internes et l\'augmentation du chiffre d\'affaires. Comme vous pourrez le constater &agrave; la lecture de mon CV, je suis un habitu&eacute; des univers dynamiques et exigeants. je recherche aujourd&rsquo;hui de nouveaux d&eacute;fis &agrave; relever dans lesquelles je pourrais mettre &agrave; profit aussi bien mon  exp&eacute;rience que mes qualit&eacute;s relationnelles. Je me tiens &agrave; votre disposition pour toute information compl&eacute;mentaire">Offre de services en communication-marketing et cr&eacute;ation de sites web</a> </li> <li> <a href="publications.php#cv_maghen_negou_rostant" title= "J\'offre un service complet qui couvre les domaines du Marketing, de la Communication et de la Programmation Informatique. 10 ann&eacute;es d&rsquo;exp&eacute;rience professionnelle dans ces domaines m\'ont permis une excellente ma&icirc;trise des outils d\'&eacute;laboration et d&rsquo;impl&eacute;mentation de solutions efficaces. Ainsi ma formation pluridisciplinaire r&eacute;pond aux exigences des diff&eacute;rents postes que j\'ai eu &agrave; occuper dans divers entreprises. Ce ci m\'a amen&eacute; &agrave; me sp&eacute;cialis&eacute; en Communication Marketing Int&eacute;gr&eacute;e (CMI). Ma mission est de collaborer avec une vari&eacute;t&eacute; de clients et une &eacute;quipe d\'employ&eacute;s motiv&eacute;s et responsables, afin d\'offrir des solutions innovatrices, en ayant toujours en t&ecirc;te l\'obligation de r&eacute;sultats. En effet, Au cours de ma carri&egrave;re, j\'ai tout particuli&egrave;rement mis l\'accent sur l\'am&eacute;lioration des proc&eacute;d&eacute;s internes et l\'augmentation du chiffre d\'affaires.">CV de MAGHEN NEGOU Rostant (10 ans d&rsquo;exp&eacute;rience)</a> </li> <li> <a href="publications.php#lettre_de_motivation_maghen_negou_rostant" title= "J\'offre un service complet qui couvre les domaines du Marketing, de la Communication et de la Programmation Informatique. 10 ann&eacute;es d&rsquo;exp&eacute;rience professionnelle dans ces domaines m\'ont permis une excellente ma&icirc;trise des outils d\'&eacute;laboration et d&rsquo;impl&eacute;mentation de solutions efficaces. Ainsi ma formation pluridisciplinaire r&eacute;pond aux exigences des diff&eacute;rents postes que j\'ai eu &agrave; occuper dans divers entreprises. Ce ci m\'a amen&eacute; &agrave; me sp&eacute;cialis&eacute; en Communication Marketing Int&eacute;gr&eacute;e (CMI).Ma mission est de collaborer avec une vari&eacute;t&eacute; de clients et une &eacute;quipe d\'employ&eacute;s motiv&eacute;s et responsables, afin d\'offrir des solutions innovatrices, en ayant toujours en t&ecirc;te l\'obligation de r&eacute;sultats. En effet, Au cours de ma carri&egrave;re, j\'ai tout particuli&egrave;rement mis l\'accent sur l\'am&eacute;lioration des proc&eacute;d&eacute;s internes et l\'augmentation du chiffre d\'affaires. ">Lettre de Motivation de MAGHEN NEGOU Rostant</a> </li> <li> <a href="publications.php#analyse_critique_d_un_site_web_cas_de_expressexchange.net" title= "Suite &agrave; l\'entretien que j\'ai eu le vendredi 08 mars 2013 avec le Top-Management de Express Exchange Sarl, J\'ai effectu&eacute; une analyse critique du site web expressexchange.net. Ceci m\'a permit de faire un &eacute;tat des lieux de leur site web et de leur proposer des solutions. Bien qu\'il ne traite exclusivement que du cas du site expressexchange.net et que certaines informations confidentielles ont &eacute;t&eacute; supprim&eacute;es, ce document est une &eacute;tude de cas dont les proc&eacute;dures de constructions sont tout a fait r&eacute;utilisables pour l\'analyse critique de tout site web">Analyse critique d\'un site web : cas de expressexchange.net</a></li> <li> <a href="publications.php#de_l-information_et_la_communication_a_la_societe_de_l-information_et_de_la_communication" title= "Moi, MAGHEN NEGOU Rostant alias &laquo;&nbsp;Dix-Huit&nbsp;&raquo;,  &eacute;tudiant en communication &agrave; la facult&eacute; des lettres et sciences humaines de l\'universit&eacute; de Douala au Cameroun, j\'ai voulu &agrave; travers cet ouvrage partager avec mes camarades &eacute;tudiants de la fili&egrave;re communication de l\'universit&eacute; de Douala, le fruit de mes recherches personnelles sur les sciences de l\'information et de la communication (SIC). Ceci est d\'autant plus naturel que tout savoir pour grandir a besoin d\'&ecirc;tre critiqu&eacute;. Ce livre n\'a pas la pr&eacute;tention de remplacer ou de se substituer au cours magistral de l\'enseignant (m&ecirc;me si celui ci, malgr&eacute; toute l\'abn&eacute;gation donc peut faire preuve tout enseignant ne peut exc&eacute;der 30% des connaissances n&eacute;cessaires &agrave; la formation de l\'&eacute;tudiant ), encore moins de r&eacute;pondre &agrave; toutes les questions que pourrait se poser un &eacute;tudiant en Communication, mais il aspire plus t&ocirc;t &agrave; apporter sa modeste contribution au processus  d\'assimilation des enseignements au quel doit s\'adonner tout &eacute;tudiant. &Eacute;tant donn&eacute; que les 70% restant requi&egrave;rent d\'importantes recherches personnelles, ce livre devrait &ecirc;tre d\'une r&eacute;elle utilit&eacute; au vu de sa richesse, son explicit&eacute;, sa concision et sa pr&eacute;cision">De l\'information et la communication &agrave; la soci&eacute;t&eacute; del\'information et de la communication</a> </li></ul><h2> Infos</h2><p> Ce site est construit dans le strict respect des normes et standards internationaux en matière de réalisation de sites web. C\'est pourquoi il ne fonctionne correctement que sur les navigateurs web récents. Ce site a subit avec succès des tests sur plusieurs navigateurs tels que: <cite>Internet Explorer 8, Mozilla Firefox 12, Google Chrome 14 et Opera 11</cite>. <a href="http://fr.wikipedia.org/wiki/Accessibilité_du_web"> En savoir plus...</a></p><h2> Mon profile </h2><ul> <li> <a href="http://cm.viadeo.com/fr/profile/rostant.maghen.negou" title= "Viadeo">Viadeo</a></li> <li> <a href="https://fr-fr.facebook.com/maghen2" title= "Facebook">Facebook</a></li> <li> <a href="https://plus.google.com/106399514297681129096" title= "Google Plus">Google Plus</a></li> <li> <a href="https://picasaweb.google.com/lh/sredir?uname=106399514297681129096&target=ALBUM&id=5679450080955063729" title= "Picasaweb">Picasaweb</a></li> <li> <a href="http://www.google.cm/search?q=Rostant+MAGHEN+NEGOU" title= "Google">Google</a></li> <li> <a href="http://fr.search.yahoo.com/search?p=Rostant+MAGHEN+NEGOU" title= "Yahoo">Yahoo</a></li> <li> <a href="http://www.bing.com/search?q=Rostant+MAGHEN+NEGOU" title= "Bing">Bing</a></li></ul><h2> Liens</h2><ul> <li> <a href="http://www.alsacreations.com/" title= "AlsacréationS,apprendre le XHTML, les CSS et les standards W3C de la conception Web">Alsacréations</a></li> <li> <a href="http://www.developpez.com/" title= "Le plus grand Club francophone des professionnels de l\'informatique : Forum, Cours et tutoriels">Developpez.com</a></li> <li> <a href="http://www.commentcamarche.net/" title= "Comment ça marche l\'informatique? Se dépanner, Se faire aider, Se former à l\'informatique et aux nouvelles technologies">Comment ça marche</a></li> <li> <a href="http://fr.wikipedia.org/" title= "Wikipédia, l\'encyclopédie libre ou le savoir pour tous">Wikipédia</a></li></ul>',
            'content'=>'',
            'footer'=>'<p> &copy;CopyRight 2013 &bull; MAGHEN NEGOU Rostant; Tout droit réservé</p>'
            );


 if(!isset($_page) || empty($_page)){ // Verification des parametres
   trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
   return false;
   exit;
 }
 else{// Chargement des données à afficher
   
 
 if(! @include_once($_id.'.php')){ // Verification de la selection des tables dans Mysql
   trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
   return false;
   exit;
   }
   elseif(empty($_article) || empty($_page)){// Verification de l'existance de la page demandée
    $param=array(404);
    _loader('_htaccess',$param);
    exit;
   }
   else{// On charge le squelette xml du site
   if(!($_xml = file_get_contents('.pear/xml/squelette.xml'))){ // On verifie que le squelette XML a bien été chargée
    trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
    return false;
    exit;
   }
   else{// On finalise la page avec toute les infos complementaires
  /*
   £categorie£
  £date£
  £description£
  £domain£
  £genre£
  £keyword£
  £theme£
  £title£
  £header£
  £menu£
  £nav£
  £content£
  £footer£
$_article=$_article[0];
$_page=$_page[0];
  */

$_index=array('£categorie£','£date£','£description£','£domain£','£genre£','£keyword£','£theme£','£title£','£header£','£menu£','£nav£','£content£','£footer£');
$_valeur=array($_article['categorie'],$_article['date'],$_article['description'],$_domain,$_article['genre'],$_article['keyword'],'default',$_article['title'],
$_page['header'],
$_page['menu'],
$_page['nav'],
$_page['content'].$_article['content'],
$_page['footer']
);
$_xml = str_replace($_index,$_valeur,$_xml);
//var_dump($_valeur);
return $_xml;  
   }
   
   }
 }
}













?>
