<?php
/********* Creation de la page d'apropos
Cette page sera l'interface d'entr�e pour quelques pages classiques du site
//***************************************************************************************************************/
$start = microtime(true);
require_once('conf.php'); // Inclusion du fichier de configuration


if(!($_page = file_get_contents('xml/squelette.xml',true))){ // On verifie que le squelette XML a bien �t� charg�e
 trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
 return false;
 exit;
}
else{
$_page = str_replace('�domain�',$_domain,$_page);
// Construction du menu principale
 $_menu = '<h1 id="header"><a href="http://'.$_SERVER['HTTP_HOST'].'/" title="AfriquePlus - Accueil"><span> AfriquePlus </span></a></h1><div id="search"><form name="search" method="get" action="../search/"><input type="text" name="q" size="25" value="Rechercher sur AfriquePus..." id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" /><input type="hidden" name="mode" value="normale" /><input type="submit" value=" " id="rechercher"/></form></div><ul id="menu">';
 $_menu .= '<li><a href="'.$_domain.'"> Accueil </a></li><li><a href="'.$_domain.'../article/"> Articles </a></li><li><a href="'.$_domain.'about/?page=contact"> Contact </a></li><li><a href="'.$_domain.'about/?page=partenariat"> Partenariat </a></li><li><a href="'.$_domain.'about/?page=about"> A Propos </a></li>';
 $_head = $_menu;

// On ajoute le texte Principal
if(!isset($_GET['page'])) $_GET['page'] = '';
switch  ($_GET['page']) {
 case 'partenariat' :
  header("Location: .?page=about");
  $_metadata = array(
  'title' => '',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '
  ',
  '' => ''
 );
 break;
 case 'contact' :
// Selection des nom et code de pays 
  $_param = array('_select',array('*','`pays`',1,'`nom`',500));
  $_data = _loader('_db',$_param);
  $_pays = '';
  for($i=0;$i<count($_data);$i++)
    $_pays .= '<option value="'.$_data[$i]['code'].'"> '.$_data[$i]['nom'].' </option>';
   $_norobot = _loader('_norobot',array(''));       
// Je verifie s'il sagit d'une redirection vers le formulaire apres echec de Soumissions
if(isset($_GET['civilite']) || isset($_GET['nom']) || isset($_GET['prenom']) || isset($_GET['email']) || isset($_GET['tel']) || isset($_GET['pays']) || isset($_GET['categorie']) || isset($_GET['sujet']) || isset($_GET['message']) || isset($_GET['connu']) || isset($_GET['norobot']) || isset($_GET['verif_norobot'])){
 // Je d�slashes le tableau $_GET pour afficher les valeurs
$_GET = array_map("stripslashes",$_GET);
// On prepare la super global $_GET pour en extraire les valeurs par default des champs
 if(!isset($_GET['civilite']) || empty($_GET['civilite'])) $_GET['civilite'] = 'Mr';
if(!isset($_GET['nom']) || empty($_GET['nom'])) $_GET['nom'] = '';
if(!isset($_GET['prenom']) || empty($_GET['prenom'])) $_GET['prenom'] = '';
if(!isset($_GET['email']) || empty($_GET['email'])) $_GET['email'] = '';
if(!isset($_GET['tel']) || empty($_GET['tel'])) $_GET['tel'] = '';
if(!isset($_GET['pays']) || empty($_GET['pays'])) $_GET['pays'] = 'cm';
if(!isset($_GET['categorie']) || empty($_GET['categorie'])) $_GET['categorie'] = 'Autre';
if(!isset($_GET['sujet']) || empty($_GET['sujet'])) $_GET['sujet'] = '';
if(!isset($_GET['message']) || empty($_GET['message'])) $_GET['message'] = '';
if(!isset($_GET['connu']) || empty($_GET['connu'])) $_GET['connu'] = 'Autre';
if(!isset($_GET['email_error']) || empty($_GET['email_error'])) $_GET['email_error'] = false;
if(!isset($_GET['tel_error']) || empty($_GET['tel_error'])) $_GET['tel_error'] = false;
if(!isset($_GET['norobot_error']) || empty($_GET['norobot_error'])) $_GET['norobot_error'] = false;
//if(!isset($_GET['']) || empty($_GET[''])) $_GET[''] = '';

$_text = '<h2> FORMULAIRE MAL REMPLI </h2><p>Votre message n&apos;a pas �t� envoy� car des erreurs se sont gliss�es lors du remplissage du formulaire. Corrigez ces erreurs puis r�essayez � nouveau. Pour ce faire les indications sis dessous vous serons sans doute utiles.</p>';
// ON verifie si au moins un champs �tait vide
if(empty($_GET['civilite']) || empty($_GET['nom']) || empty($_GET['prenom']) || empty($_GET['email']) || empty($_GET['tel']) || empty($_GET['pays']) || empty($_GET['categorie']) || empty($_GET['sujet']) || empty($_GET['message']) || empty($_GET['connu']) || empty($_GET['norobot']) || empty($_GET['verif_norobot']))
$_text .='<h4>  Tout les champs du formulaire sont OBLIGATOIRES </h4> <p> Vous devez obligatoirement <strong>remplir tous les champs </strong>du formulaire car chaque information demand�e est <strong>indispensable</strong></p>';
// On verifie si l'addresse email �tait incorrect
if($_GET['email_error']) $_text .= '<h4>  votre adresse �mail n&apos;est pas valide </h4><p><strong>Seul les lettres, les chiffres, le point(.), le tiret(-), la barre de soulignement(_), et un seul arrobas(@)</strong> sont permis dans la syntaxe d&apos;une adresse �mail. Et elle doit �tre de la forme <strong>example@example.com</strong> .</p>';
// On verifie si le num�ro de t�l�phone �tait incorrect
if($_GET['tel_error']) $_text .= '<h4>  Votre num�ro de t�l�phone n&apos;est pas valide. </h4> <p>Un num�ro de t�l�phone valide doit obligatoirement <strong>commencer par l&apos;indicatif du pays,</strong> exemple <strong>237 pour le Cameroun</strong>, puis votre num�ro de t�l�phone proprement dit, exemple <strong>77920685</strong>. Le num�ro de t�l�phone est donc <strong>un nombre de 8 � 20 chiffres</strong> indicatif compris. <strong>EX: 23777920685</strong></p>';
// On verifie si  il y'avait une erreur norobot
if($_GET['norobot_error']) $_text .= '<h4>  Le champs "Recopier le texte de l&apos;image" est incorrecte. </h4> <p><strong>Observez attentivement</strong> le texte de l&apos;image avant  de le taper dans le champs appropri�. Notez que le texte que vous tap� doit �tre <strong>sans espacement, sensible � la case (diff�renciation des majuscules et minuscules) et identique � celui inscrit sur l&apos;image.</strong></p>';
 
 /***** JavaScript
  *  Ajout du JavaScript pour la selection des pr�cedentes valeures
*/
$_text2 ='<script>';
// Selection de la Civilit�
$civilite = array('Mr','Mlle','Mme');
$civilite = array_flip($civilite);
$_text2 .=' document.contact.civilite.options['.$civilite[$_GET['civilite']].'].selected=true;';
// Selection du Pays
$pays = array('af','za','al','dz','de','ad','ao','ai','aq','ag','an','sa','ar','am','aw','au','at','az','bs','bh','bd','be','bz','bj','bm','bt','by','bo','ba','bw','bv','br','bn','bg','bf','bi','kh','cm','ca','cv','ky','cl','cn','cx','cy','cc','co','km','ck','kp','kr','cr','ci','hr','cu','dk','dj','dm','eg','sv','ae','ec','er','es','ee','us','et','fk','fo','fj','fi','fr','ga','gm','ge','gs','gh','gi','gr','gd','gl','gp','gu','gt','gn','gq','gw','gy','gf','ht','hm','hn','hk','hu','um','in','id','iq','ir','ie','is','il','it','jm','jp','jo','kz','ke','kg','ki','kw','bb','la','ls','lv','lb','lr','ly','li','lt','lu','mo','mk','mg','my','mw','mv','ml','mt','mp','ma','mh','mq','mu','mr','yt','mx','fm','md','mc','mn','ms','mz','mm','na','nr','np','ni','ne','ng','nu','nf','no','nc','nz','om','ug','uz','pk','pw','pa','pg','py','nl','pe','ph','pn','pl','pf','pr','pt','qa','cg','cf','do','cz','re','ro','uk','ru','rw','eh','pm','vc','kn','sm','sh','lc','as','ws','st','sn','sc','sl','sg','sk','si','so','sd','lk','se','ch','sr','sj','sz','sy','tj','tw','tz','td','io','tf','th','tp','tg','tk','to','tt','tn','tm','tc','tr','tv','ua','uy','vu','va','ve','vi','vg','vn','wf','ye','yu','zr','zm','zw');
$pays = array_flip($pays);
$_text2 .=' document.contact.pays.options['.$pays[$_GET['pays']].'].selected=true;';
// Selection de la categorie
$categorie = array('Autre','Actualit� Panafricaine','Webmastering','Publicit�','Tchat & Forum','Sondage');
$categorie = array_flip($categorie);
$_text2 .=' document.contact.categorie.options['.$categorie[$_GET['categorie']].'].selected=true;';
// Selection de "Comment nous avez vous connu"
$connu = array('TV Radio','Internet','Papier','Bouche � Oreille','Autre');
$connu = array_flip($connu);
$_text2 .=' document.contact.connu['.$connu[$_GET['connu']].'].checked=true;';




$_text2 .='</script>';

// Tableau contenant toute les infos
$_metadata = array(
  'title' => 'FORMULAIRE MAL REMPLI',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => $_text.'<div>
    <form name="contact" method="post" action="contact.php" onSubmit="return _verif_form(this)" >
            <table width="100%" align="center" border="0"><caption><h2> NOUS CONTACTER </h2></caption>
              <tr><td><label for="Civilite"><strong> Civilit�  <sup>*</sup></strong></label></td><td colspan="5"><select id="Civilite"  name="civilite">
               <option selected="1" value="Mr"> Monsieur </option>
               <option value="Mme"> Madame </option>
               <option value="Mlle"> Mademoiselle </option></select></td></tr>
             <tr><td><label for="nom"><strong> Nom  <sup>*</sup></strong></label></td><td colspan="5"><input id="nom" type="text" name="nom" size="40" value="'.$_GET['nom'].'"></td></tr>
             <tr><td><label for="prenom"><strong> Prenom  <sup>*</sup></strong></label></td><td colspan="5"><input id="prenom" type="text" name="prenom" size="40" value="'.$_GET['prenom'].'"></td></tr>
             <tr><td><label for="email"><strong>E-Mail <sup>*</sup></strong></label></td><td colspan="5"><input id="email" type="text" name="email" size="40" value="'.$_GET['email'].'"></td></tr>
             <tr><td><label for="tel"><strong> T�l(avec l\'indicatif) EX: <u>237</u>77920685  <sup>*</sup></strong></label></td><td colspan="5"><input id="tel" type="text" name="tel" size="40" value="'.$_GET['tel'].'"></td></tr>
             <tr><td><label for="pays"><strong> Pays  <sup>*</sup></strong></label></td><td colspan="5"><select id="pays"  name="pays">'.
              $_pays
             .'
               </select></td></tr>
              <tr><td><label for="categorie"><strong> Categorie  <sup>*</sup></strong></label></td><td colspan="5"><select id="categorie"  name="categorie">
               <option  selected="1" value="Autre"> Autre Categorie </option>
               <option value="Actualit� Panafricaine"> Actualit� Panafricaine </option>
               <option value="Webmastering"> Webmastering </option>
               <option value="Publicit�"> Publicit� </option>
               <option value="Tchat & Forum"> Tchat &amp; Forum </option>
               <option value="Sondage"> Sondage </option>
               </select></td></tr>
               <tr><td><label for="sujet"><strong> Sujet  <sup>*</sup></strong></label></td><td colspan="5"><input id="sujet" type="text" name="sujet" size="40" value="'.$_GET['sujet'].'"></td></tr>
               <tr><td><label for="message"><strong> Message  <sup>*</sup></strong></label></td><td colspan="5"><textarea id="message"  name="message" cols="40" rows="10">'.$_GET['message'].'</textarea></td></tr>
             <tr><td><label for="pertinance"><strong> Comment nous avez vous connu?   <sup>*</sup></strong></label></td><td><label for="TV_Radio"><strong> TV Radio </strong></label><input id="TV_Radio" type="radio" name="connu" value="TV Radio"></td><td><label for="internet"><strong> Internet </strong></label><input id="internet" type="radio" name="connu" value="Internet"></td><td><label for="papier"><strong> Papier </strong></label><input id="papier" type="radio" name="connu" value="Papier" ></td><td><label for="bouche_oreille"><strong> Bouche � Oreille </strong></label><input id="bouche_oreille" type="radio" name="connu" value="Bouche � Oreille" ></td><td><label for="autre"><strong> Autre </strong></label><input id="autre" type="radio" name="connu" value="Autre" checked="checked"></td></tr>
             <tr><td><label for="norobot"><strong> Recopier le texte de l\'image  <sup>*</sup></strong></label></td><td colspan="3"><img src="'.$_norobot['img'].'" alt="Anti Robot" title="Anti Robot" /></td><td colspan="2"><input id="norobot" type="text" name="norobot" size="40"></td></tr>
             <input type="hidden" name="verif_norobot" value="'.$_norobot['id'].'" />
             <tr><td colspan="3"><input type="submit" value="Envoyer" style="  margin: 5px;  border: 5px solid #ab4;  width: 250px;  height: 30px;" /></td><td colspan="3"><input type="reset" value="Reinitialiser" style="  margin: 5px;  border: 5px solid #ab4;  width: 250px;  height: 30px;" Onclick="return confirm(\'Attention!!! \n Si vous continu�, toute les informations contenuent dans le formulaire seront d�finitivement �ffac�es.\n Ete vous absolument certain de vouloir continuer?\')" /></td></tr>
            </table>
            </form>
          </div>
  </div>'.$_text2,
  '' => ''
 );
}
// Si ce n'est pas le cas on Affiche le formulaire par defaut
else{  
  $_metadata = array(
  'title' => 'Nous Contacter',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '<p>AfriquePlus se r�jouit de votre int�r�t pour elle et vous prie de bien remplir le formulaire ci-dessous car chaque champ est important pour le traitement optimale de votre message. Veuillez donc � �tre explicite, concis, pr�cis et rigoureux dans votre d�marche et nous t�cherons de vous faire parvenir notre r�ponse dans les plus brefs d�lais.</p>
<div>
    <form name="search" method="post" action="contact.php" onSubmit="return _verif_form(this)" >
            <table width="100%" align="center" border="0"><caption><h2> NOUS CONTACTER </h2></caption>
              <tr><td><label for="Civilite"><strong> Civilit�  <sup>*</sup></strong></label></td><td colspan="5"><select id="Civilite"  name="civilite">
               <option selected="1" value="Mr"> Monsieur </option>
               <option value="Mme"> Madame </option>
               <option value="Mlle"> Mademoiselle </option></select></td></tr>
             <tr><td><label for="nom"><strong> Nom  <sup>*</sup></strong></label></td><td colspan="5"><input id="nom" type="text" name="nom" size="40"></td></tr>
             <tr><td><label for="prenom"><strong> Prenom  <sup>*</sup></strong></label></td><td colspan="5"><input id="prenom" type="text" name="prenom" size="40"></td></tr>
             <tr><td><label for="email"><strong>E-Mail <sup>*</sup></strong></label></td><td colspan="5"><input id="email" type="text" name="email" size="40"></td></tr>
             <tr><td><label for="tel"><strong> T�l(avec l\'indicatif) EX: <u>237</u>77920685  <sup>*</sup></strong></label></td><td colspan="5"><input id="tel" type="text" name="tel" size="40"></td></tr>
             <tr><td><label for="pays"><strong> Pays  <sup>*</sup></strong></label></td><td colspan="5"><select id="pays"  name="pays">'.
              $_pays
             .'
               </select></td></tr>
              <tr><td><label for="categorie"><strong> Categorie  <sup>*</sup></strong></label></td><td colspan="5"><select id="categorie"  name="categorie">
               <option  selected="1" value="Autre"> Autre Categorie </option>
               <option value="Actualit� Panafricaine"> Actualit� Panafricaine </option>
               <option value="Webmastering"> Webmastering </option>
               <option value="Publicit�"> Publicit� </option>
               <option value="Tchat & Forum"> Tchat &amp; Forum </option>
               <option value="Sondage"> Sondage </option>
               </select></td></tr>
               <tr><td><label for="sujet"><strong> Sujet  <sup>*</sup></strong></label></td><td colspan="5"><input id="sujet" type="text" name="sujet" size="40"></td></tr>
               <tr><td><label for="message"><strong> Message  <sup>*</sup></strong></label></td><td colspan="5"><textarea id="message"  name="message" cols="40" rows="10"></textarea></td></tr>
             <tr><td><label for="pertinance"><strong> Comment nous avez vous connu?   <sup>*</sup></strong></label></td><td><label for="TV_Radio"><strong> TV Radio </strong></label><input id="TV_Radio" type="radio" name="connu" value="TV Radio"></td><td><label for="internet"><strong> Internet </strong></label><input id="internet" type="radio" name="connu" value="Internet"></td><td><label for="papier"><strong> Papier </strong></label><input id="papier" type="radio" name="connu" value="Papier" ></td><td><label for="bouche_oreille"><strong> Bouche � Oreille </strong></label><input id="bouche_oreille" type="radio" name="connu" value="Bouche � Oreille" ></td><td><label for="autre"><strong> Autre </strong></label><input id="autre" type="radio" name="connu" value="Autre" checked="checked"></td></tr>
             <tr><td><label for="norobot"><strong> Recopier le texte de l\'image  <sup>*</sup></strong></label></td><td colspan="3"><img src="'.$_norobot['img'].'" alt="Anti Robot" title="Anti Robot" /></td><td colspan="2"><input id="norobot" type="text" name="norobot" size="40"></td></tr>
             <input type="hidden" name="verif_norobot" value="'.$_norobot['id'].'" />
             <tr><td colspan="3"><input type="submit" value="Envoyer" style="  margin: 5px;  border: 5px solid #ab4;  width: 250px;  height: 30px;" /></td><td colspan="3"><input type="reset" value="Reinitialiser" style="  margin: 5px;  border: 5px solid #ab4;  width: 250px;  height: 30px;" Onclick="return confirm(\'Attention!!! \n Si vous continu�, toute les informations contenuent dans le formulaire seront d�finitivement �ffac�es.\n Ete vous absolument certain de vouloir continuer?\')" /></td></tr>
            </table>
            </form>
          </div>
  </div>',
  '' => ''
 );
}
 break;
 case 'source' :
   // Listage de toute les sources
 $_sources ='<h2> LISTE EXHAUSTIVE DE TOUT NOS POURVOYEURS D&#8217;ARTICLE</h2>';
 $_param = array('_select',array('*','`source`',1,'`nom`',50));
 $_data = _loader('_db',$_param);
 
  for($i=0;$i<count($_data);$i++) 
   $_sources .= '<div class="vignette"><h3><a target="_blank" href="'.$_data[$i]['url'].'">'.$_data[$i]['nom'].'</a></h3><a target="_blank" href="'.$_data[$i]['url'].'"><img class="vignette" src="'.$_data[$i]['logo'].'" alt="'.$_data[$i]['nom'].'"  title="'.$_data[$i]['nom'].'" width="100" height="100" /></a><p>'.$_data[$i]['description'].'</p><address><strong> Initials: </strong>'.$_data[$i]['initial'].'</address>  <address><strong> Statut: </strong>'.$_data[$i]['activite'].'</address>  <address><strong> Url: </strong><a target="_blank" href="'.$_data[$i]['url'].'">'.$_data[$i]['url'].'</a></address>  <address><strong> Date d&#8217;ajout: </strong>'.get_date($_data[$i]['date'],'MySQL').'</address>  <address><strong> Mail: </strong>'.$_data[$i]['mail'].'</address>  <address><strong> Adresse: </strong><pre>'.$_data[$i]['adresse'].'</pre></address>   </div>';
 
 
  $_metadata = array(
  'title' => 'Liste exhaustive de tout les pourvoyeurs d&#8217;article d&#8217;AfriquePlus',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '<p>Tout le m�rite de la profusion et la qualit� d&#8217;articles pr�sents sur AfriquePlus revient largement aux pourvoyeurs( sources )  d&#8217;article d&#8217;AfriquePlus car ce sont eux qui produisent les articles. De ce fait sans eux l&#8217;espace qui vous permet de lire  �couter ou visionner de l&#8217;actualit� sur AfriquePlus n&#8217;aurait jamais exist�. AfriquePlus tient particulierement � t�moigner toute sa gratitude  � ces g�n�reux pourvoyeurs pour leur apport qui lui est oh combien vital.'.$_sources.'</p>  <div class="vignette"><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit r�serv�</p><p>AfriquePlus collecte et indexe du contenu provenant de plusieurs sources d\'actualit�s africaines soigneusement s�lectionn�es afin de contribuer � l\'africanisation du web.</p><p>Chaque article publi� sur AfriquePlus est la propri�t� exclusive de son/ses ayant(s) droit de ce fait:<p><ul><li><span class="foot">Sauf avis contraire, AfriquePlus ne poss�de aucun droit sur quelque article que se soit</span></li><li><span class="foot">pour toute autorisation de reproduction,diffusion ou r�utilisation contactez directement le(s) propri�taire(s) des droits de l\'article indiqu�(s) au pied de page par la mention  Source </span></li><li><span class="foot">AfriquePlus d�cline toute responsabilit�, quelqu\'elle soit, totale ou partielle, sur le contenue des articles par lui publi�s</span></li><li><span class="foot">Les pourvoyeurs d\'informations de AfriquePlus gardent l\'enti�re responsabilit� de leur production</span></li></ul></div>
  </div>',
  '' => ''
 );
 break;
 case 'copyright' :
  $_metadata = array(
  'title' => '',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '
  ',
  '' => ''
 );
 break;
 case 'accessibilite' :
 $_metadata = array(
  'title' => '',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '
  ',
  '' => ''
 );
 break;
 case 'sitemap' :
 $_metadata = array(
  'title' => '',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '
  ',
  '' => ''
 );
 break;
default:
  $_metadata = array(
  'title' => 'A propos d&#8217;AfriquePlus',
  'keyword' => '',
  'description' => '',
  'date' => '',
  'categorie' => '',
  'genre' => '',
  'organisme' => '',
  'link' => '',
  'text' => '<p>  le d�veloppement de l&#8217;Afrique �tant un travail argnieux et de longue alaine, notre participation ne saurais en �tre de moindre.  En conformit� avec notre cahier de charge pr�sent� � la page d&#8217;acceuil, les Afriqueplussiens et les Afriqueplussiennes se  proposent de concr�tiser leur philosophie � travers les r�alisations suivantes: </p> <h3>  I - ACTUALIT� PANAFRICAINE </h3><p> Dans cette r�alisation, nous fournirons un portail web complet d&#8217;actualit� panafricaine regroupant des sources d&#8217;information de  premier ordre minutieusement s�lectionn�es afin de toujours vous proposer les meilleurs articles du web. Il para�t important  de remercier les diff�rents pourvoyeurs d&#8217;articles sans qui cette r�alisation n&#8217;aurait pas �t� possible. <a href="?page=source">Voir toute nos source</a></p><h3>  II - WEBMASTERING </h3><p> La fracture num�rique Nord-Sud tant d�cri�e se manifeste aussi par la pr�sence marginale de l&#8217;Afrique sur le web. AfriquePlus se propose de participer � la r�sorption de ce d�ficit num�rique en aidant le maximum d&#8217;organisation � but lucratif ou non � se doter d&#8217;un site web � la pointe de la technologie, conforme aux toutes derni�res normes d&#8217;accessibilit�s et du W3C. Pour b�n�ficier de cette aide, utiliser le formulaire de candidature. </p><p><a href=".?page=contact"> Nous contacter pour en savoir plus.... </a></p><h3>  III - PUBLICIT� </h3><p> Cet espace est d�di� � la promotions des produits et organisations du continent. Il est adapt� � la consolidation de la visibilit� publicitaire via le web. </p><p><a href=".?page=contact"> Nous contacter pour en savoir plus.... </a></p><h3>  IV - TCHAT & FORUM </h3><p> Le d�veloppement de la culture d�mocratique tant essentiel au d�veloppement du continent passe par une participation de  plus en plus grande de chaque citoyen � la gestion de la chose publique. Cet espace d&#8217;�change et de discussion libre �  pour but de favoriser la culture du d�bat contradictoire, du consensus, du respect d&#8217;autrui, d�mocratique. </p><p><a href=".?page=contact"> Nous contacter pour en savoir plus.... </a></p><h3>  V - SONDAGE </h3><p> Recueillir l&#8217;opinion des populations gr�ce � des sondages est le but de cet espace. L&#8217;importance d&#8217;un tel outil n&#8217;�tant plus � d�monter il nous est apparut indispensable d&#8217;en d�velopper.  </p><p><a href=".?page=contact"> Nous contacter pour en savoir plus.... </a></p></div>
 ',
  '' => ''
 );
}
$_text = $_metadata['text'];
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
  
 // On ecrie le pied de page
$_foot = '<ul><h3>Page calcul�e en <span class="foot">'.round(microtime(true)-$start,2).' Secondes</span></p></ul><p class="copyright">Copyright &copy; Septembre 2007 AfriquePlus Tout droit r�serv�</p>';


 
 $_model = array(
  'index' => array('�title�','�keyword�','�description�','�date�','�categorie�','�genre�','�organisme�','�link�','�theme�','�script�','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  $_metadata['title'],$_metadata['keyword'],$_metadata['description'],$_metadata['date'],$_metadata['categorie'],$_metadata['genre'],$_metadata['organisme'],$_metadata['link'],'default',$_js,'<div id="head">'.$_head,'<div id="body"><div id="article">'.$_text,'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;
}

?> 



