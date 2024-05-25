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
$_page = str_replace('£domain£',$_domain,$_page);
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

// On verifie si il sagit bien d'une soumission de formulaire 
if(!isset($_POST['civilite']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['email']) || !isset($_POST['tel']) || !isset($_POST['pays']) || !isset($_POST['categorie']) || !isset($_POST['sujet']) || !isset($_POST['message']) || !isset($_POST['connu']) || !isset($_POST['norobot']) || !isset($_POST['verif_norobot'])){
 $_url = $_domain.'about/?page=contact';
 header("Location: $_url");
}
// Sinon on commence le traitement du formulaire en s'assurant que tout les champs on bien été remplies
elseif(empty($_POST['civilite']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['tel']) || empty($_POST['pays']) || empty($_POST['categorie']) || empty($_POST['sujet']) || empty($_POST['message']) || empty($_POST['connu']) || empty($_POST['norobot']) || empty($_POST['verif_norobot'])){
 $_url = $_domain.'about/?page=contact';
 foreach($_POST as $key=>$value) $_url.='&'.$key.'='.urlencode($value);
 header("Location: $_url");
}
else{
// Verification E-mail, Tel, pays, categorie puis eventuellemnt enregistrement de données
$_email = $_POST['email'];
$_email_regexp = '`^[[:alnum:]]+([-_.]?[[:alnum:]]+)*@[[:alnum:]]+([-_.]?[[:alnum:]]+)*\.([a-z]{2,4})$`';
$_tel = $_POST['tel'];
$_tel_regexp = '`^[[:digit:]]{8,20}$`';
$_pays = $_POST['pays'];
$_pays_regexp = '`^[[:alpha:]]{2,3}$`';
$_categorie = $_POST['categorie']; 
$_categories = array('Autre','Actualité Panafricaine','Webmastering','Publicité','Tchat & Forum','Sondage');
$_norobot = $_POST['norobot'];
$_verif_norobot = $_POST['verif_norobot'];
$_verif_norobot = substr($_verif_norobot,5,8);
$_pw_array = str_split($_verif_norobot);
$_alphanum3 = array_flip($_alphanum3);
$_pw = '';
 foreach($_pw_array as $_key=>$_value) $_pw .= $_alphanum3[$_value];
 $_verif_norobot = $_pw;
 if(($_verif_norobot !== $_norobot) || !preg_match($_email_regexp,$_email) || !preg_match($_tel_regexp,$_tel) || !preg_match($_pays_regexp,$_pays) || !in_array($_categorie,$_categories)){
   $_url = $_domain.'about/?page=contact';
  foreach($_POST as $key=>$value) $_url.='&'.$key.'='.urlencode($value);
  
  // On ajoute les résultats des verifications des champs
  $_norobot_error = ($_verif_norobot !== $_norobot); settype($_norobot_error,'int');
  $_email_error = !preg_match($_email_regexp,$_email); settype($_email_error,'int');
  $_tel_error = !preg_match($_tel_regexp,$_tel); settype($_tel_error,'int');
  $_pays_error = !preg_match($_pays_regexp,$_pays); settype($_pays_error,'int');
  $_categorie_error = !in_array($_categorie,$_categories); settype($_categorie_error,'int');
  $_url.='&email_error='.$_email_error.'&tel_error='.$_tel_error.'&pays_error='.$_pays_error.'&categorie_error='.$_categorie_error.'&norobot_error='.$_norobot_error;
  // On renvoie l'internaut au formulaire
  header("Location: $_url");
 }
 else{ // Si tout les champs sont correctement remplies, On enregistre les données
  $_value = "NULL,'".$_POST['civilite']."',"."'".$_POST['nom']."',"."'".$_POST['prenom']."',"."'".$_POST['email']."',"."'".$_POST['tel']."',"."'".$_POST['pays']."',"."'".$_POST['categorie']."',"."'".$_POST['sujet']."',"."'".$_POST['message']."',"."'".$_POST['connu']."'";
  $_values = array("($_value)");
  $_param = array('_insert',array('`message`',$_values));
 if(_loader('_db',$_param)){
  $_text = '<h2> MERCI POUR VOTRE MESSAGE </h2>
  <p>Nous vous remercions de l&apos;intérêt que vous nous porté.Nous vous informons que nous avons belle et bien pris acte de votre message et qu&apos;il sera traité dans les plus brefs délais pour une prompte réponse. Nous vous souhaitons un agréable surf sur notre site.</p>
  </div>';
  $_metadata['title'] = 'MERCI POUR VOTRE MESSAGE';
 }
 else{
  $_text = '<h2> DESOLE ERREUR INTERNE </h2>
  <p>Une erreur interne s&apos;est produite lors de l&apos;envoi de votre message et nous ne l&apos;avons donc pas reçus. Vous en êtes aucunement responsable et nous vous prions de réessayer. Si cela ne marche toujours pas, veuillez nous envoyer un mail à l&apos;adresse <address><b><afpl> afriqueplus</afpl>.org</afpl>@gmail<a>.com</b> </address> dans lequel vous indiqueriez les valeurs que vous avez saisies dans chaque champs du formulaire ainsi qu&apos;une courte description des circonstances dans lesquelles l&apos;erreur s&apos;est produite.</p>
  <p>Merci d&apos;avance</p>
  </div>';
  $_metadata['title'] = 'DESOLE ERREUR INTERNE';  
 }
 }

} 

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
  'index' => array('£title£','£keyword£','£description£','£date£','£categorie£','£genre£','£organisme£','£link£','£theme£','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  $_metadata['title'],$_metadata['keyword'],$_metadata['description'],$_metadata['date'],$_metadata['categorie'],$_metadata['genre'],$_metadata['organisme'],$_metadata['link'],'default','<div id="head">'.$_head,'<div id="body"><div id="article">'.$_metadata['text'],'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;

}

?> 



