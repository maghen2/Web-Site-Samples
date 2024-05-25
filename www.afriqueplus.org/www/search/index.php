<?php
/********* Creation du Moteur de Recherche du programme
Ce script implemente le moteur de recherche du programme.
Il est chargé de faciliter la recherche d' information dans l'ensemble du site.
//***************************************************************************************************************/
$start = microtime(true);
require_once('conf.php'); // Inclusion du fichier de configuration

 (!isset($_GET['q']))? $_q = '' : $_q = $_GET['q']; // On recupere la question et...
 $_GET['q'] = $_q; // On crée la variable $_GET['q'] si elle n'existe pas
if(!($_page = file_get_contents('xml/squelette.xml',true))){ // On verifie que le squelette XML a bien été chargée
 trigger_error($_Erreur['Efile'][0],$_Erreur['Efile'][1]);
 return false;
 exit;
}
else{
$_page = str_replace('£domain£',$_domain,$_page);
// Construction dynamique du menu principale
 (empty($_q))? $q = 'Rechercher sur AfriquePus...' : $q = $_GET['q'];
 $_menu = '<h1 id="header"><a href="http://'.$_SERVER['HTTP_HOST'].'/" title="AfriquePlus - Accueil"><span> AfriquePlus </span></a></h1><div id="search"><form name="search" method="get" action=""><input type="text" name="q" size="25" value="'.stripslashes(htmlentities($q, ENT_QUOTES)).'" id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" /><input type="hidden" name="mode" value="normale" /><input type="submit" value=" " id="rechercher"/></form></div><ul id="menu">';
 $_menu .= '<li><a href="'.$_domain.'"> Accueil </a></li><li><a href="'.$_domain.'article/"> Articles </a></li><li><a href="'.$_domain.'about/?page=contact"> Contact </a></li><li><a href="'.$_domain.'about/?page=partenariat"> Partenariat </a></li><li><a href="'.$_domain.'about/?page=about"> A Propos </a></li>';
 $_head = $_menu;
 //$_q = mysql_escape_string($_GET['q']); //Protection de la question et... 
 $_q = "'$_q'"; //Mise entre quote pour MySQL

// Detection du type de recherche et selection les articles satisfaisants la recherche depuis la base de donnée
 //Detection du Mode de recherche
 (!isset($_GET['mode']) || empty($_GET['mode']))? $_mode = 'normale' : $_mode = mysql_escape_string($_GET['mode']);
 //Selection des articles selon le Mode de recherche et eventuellement d'autres parametres
 $_data = _adsearch($_mode);

 $_text ='<div id="search">
            <form name="search" method="get" action="">
              <input type="text" name="q" size="25" value="'.stripslashes(htmlentities($q, ENT_QUOTES)).'"  id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" />
            <input type="submit" value=" " id="rechercher"/>
            <ul class="radio">
            <li><label  title="Recherche avec plus de résultats"> Etandue <input class="radio" type="radio" name="mode" value="etandue" onClick="etandue();" /></label></li>
            <li><label title="Recherche avec plus de precission" for="ts_q"> Avancée <input class="radio" type="radio" name="mode" value="avancee" onClick="avancee();"  /></label></li>
            <li><p class="titre">Cette option vous permet d&#8217;acrroître vos résultats avec des sugestions intelligentes</p></li>
            <li><p class="titre">Cette option vous permet d&#8217;affiner votre recherche pour des résultats pointus</p></li>
            </ul>
            <table id="avancee" width="100%" align="center" border="0"><caption><h2>Options de la Recherche Avancée<h2></caption>
             <tr><td rowspan="5"><label for="ts_q"> Afficher tout les résultats contenant </label></td></tr>
             <tr><td><label for="ts_q"><strong>tous</strong>  les mots suivants</label></td><td colspan="4"><input id="ts_q" type="text" name="ts_q" size="40"></td></tr>
             <tr><td><label for="ex_q"><strong>exactement</strong> cette expression</label></td><td colspan="4"><input id="ex_q" type="text" name="ex_q" size="40"></td></tr>
             <tr><td><label for="ev_q"><strong>éventuellement</strong> les mots suivants </label></td><td colspan="4"><input id="ev_q" type="text" name="ev_q" size="40"></td></tr>
             <tr><td><label for="au_q"><strong>aucun</strong> des mots suivants</label></td><td colspan="4"><input id="au_q" type="text" name="au_q" size="40"></td></tr>
             <tr><td><label for="tpage"> Rechercher dans </label></td><td colspan="3"><label for="entete"><strong> Uniquement les entêtes </strong</label><input id="entete" type="radio" name="dans" checked="checked" value="entete"></td><td colspan="3"><label for="tpage"><strong> Toute la page </strong></label><input id="tpage" type="radio" name="dans" value="page"></td></tr>
             <tr><td><label for="source"><strong>Source</strong></label></td><td colspan="5"><select id="source"  name="source">
               <option  selected="1" value=""> Toute Source </option>
               <option value="aem"> Afrique Echos Magazine </option>
               <option value="afk"> Afrik </option>
               <option value="afp"> Afrique Presse </option>
               <option value="anz"> Ananzie </option>
               <option value="bbc"> BBC </option>
               <option value="f24"> France 24 </option>
               <option value="grioo"> Grioo </option>
               <option value="oao"> Occidental Afrique Occidentale </option>
               <option value="ohada"> OHADA </option>
               <option value="pmb"> Papa Maman Bebe </option>
               <option value="rfi"> RFI </option></select></td></tr>
              <tr><td><label for="categorie"><strong> Categorie </strong></label></td><td colspan="5"><select id="categorie"  name="categorie">
               <option  selected="1" value=""> Toute Categorie </option>
               <option value="Actualité"> Actualité </option>
               <option value="Economie"> Economie </option>
               <option value="Santé"> Santé </option>
               <option value="Sport"> Sport </option></select></td></tr>
             <tr><td><label for="av_date"> Publiés </label></td><td><label for="av_date"><strong> Entre le </strong></label></td><td><input id="av_date" type="text" name="av_date" size="15" value="01-01-1960"  onFocus="if(this.value==\'01-01-1960\') this.value=\'\'" ></td><td><label for="ap_date"><strong> et le </strong</label></td><td><input id="ap_date" type="text" name="ap_date" size="15" value="31-12-2010" onFocus="if(this.value==\'31-12-2010\') this.value=\'\'" ></td></tr>
             <tr><td><label for="num"><strong>Nombre de résultats</strong> à afficher</label></td><td colspan="5"><select id="num"  name="num">
               <option  value="10"> 10 résultats par page </option>
               <option  value="20"> 20 résultats par page </option>
               <option  selected="1" value="30"> 30 résultats par page </option>
               <option  value="40"> 40 résultats par page </option>
               <option  value="50"> 50 résultats par page </option>
               <option  value="60"> 60 résultats par page </option>
               <option  value="70"> 70 résultats par page </option>
               <option  value="80"> 80 résultats par page </option>
               <option  value="90"> 90 résultats par page </option>
               <option  value="100"> 100 résultats par page </option></select></td></tr>
             <tr><td><label for="pertinance"> Ordonner les résultats par  </label></td><td><label for="pertinance"><strong> Pertinance </strong></label><input id="pertinance" type="radio" name="ord" value="pertinance" checked="checked"></td><td><label for="date"><strong> Date </strong></label><input id="date" type="radio" name="ord" value="date" ></td><td><label for="source1"><strong> Source </strong></label><input id="source1" type="radio" name="ord" value="source" ></td><td><label for="lecture"><strong> Lecture </strong></label><input id="lecture" type="radio" name="ord" value="lecture" ></td><td><label for="vote"><strong> Vote </strong></label><input id="vote" type="radio" name="ord" value="vote" ></td></tr>  
            </table>
            </form>
          </div>';
 
//Affichage des resultats de la recherche en dessous du formulaire de recherche
 $_text .= '</div><h2><i><b> '.count($_data).' </b></i> Résultats pour la Recherche de <i>&laquo; '.stripslashes(htmlentities($q, ENT_QUOTES)).' &raquo;</i> </h2>';

 for($i=0;$i<count($_data);$i++) {
  $_param = array('_select',array('*','media',"`article`='".$_data[$i]['id']."'",'RAND()',1));
  $_img = _loader('_db',$_param);
  if(!isset($_img[0]['link'])){
   $_img[0]['link'] = 'http://localhost/src/img/no_img.jpg';
   $_img[0]['alt'] = 'Photo Indisponible';
  }
  $_text .= '<div class="vignette"><h3><a href="../article/?article='.$_data[$i]['id'].'">'.$_data[$i]['title'].'</a></h3><img class="vignette" src="'.$_img[0]['link'].'" alt="'.$_img[0]['alt'].'"  title="'.$_img[0]['alt'].'" width="100" height="100" /><p>'.$_data[$i]['description'].'</p></div>';
 }
// Construction de la variable Tableau $_article
 $_article['text'] = '<div id="search"><form name="search" method="get" action=""><input type="text" name="q" size="25" value="'.$q.'" id="q" onFocus="if(this.value==\'Rechercher sur AfriquePus...\') this.value=\'\'" /><input type="submit" value=" " id="rechercher"/></form></div>'; 
 $_article['keyword'] = ''; 
 for($i=0;$i<count($_data);$i++) {$_article['keyword'] .= $_data[$i]['keyword'].',';}
 $_article['description'] = '';
 for($i=0;$i<count($_data);$i++) {$_article['description'] .= $_data[$i]['description'].'.';}
 $_foot = ''; // Definition du pied de Page
  
 $_model = array(
  'index' => array('£title£','£keyword£','£description£','£date£','£categorie£','£genre£','£organisme£','£link£','£theme£','£script£','<div id="head">','<div id="body">','<div id="foot">'),
  'valeur' => array(
  stripslashes(htmlentities($q, ENT_QUOTES)),$_article['keyword'],$_article['description'],date('c'),'Recherche','','AfriquePlus','','default',$_js,'<div id="head">'.$_head,'<div id="body"><div id="article">'.$_text.'<div id="foot">'.$_foot
  )
 );
// On affiche la page
$_index = $_model['index'];
$_valeur = $_model['valeur'];
$_page = str_replace($_index,$_valeur,$_page);
echo $_page;
}

/************ Fonction _search
 Cette fonction est le coeur du moteur de recherche car c'est à elle que revient la charge de selectionner
 les articles adequats selon le mode de recherche et d'eventuels parametres suplementaires
********/ 
function _adsearch($_mode){
global $_Erreur,$_q,$q;
 $_modes = array('normale','etandue','avancee');
 $_cond = '';
 $_num = 30;
 $_ord = '';
if(!isset($_mode) || empty($_mode) || !in_array($_mode,$_modes)){ //Verification du parametre $_mode
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
 if($_mode == 'normale'){ // IL s'agit ici d'une recherche de base dite "Normale"
  $_param= array('_search',array('*','`article`','`title`,`keyword`,`description`,`categorie`,`genre`,`organisme`',$_q,$_mode,1));
  return _loader('_db',$_param);
 }
 else{
  if($_mode == 'etandue'){ // IL s'agit ici d'une recherche speciale dite "Etandue" Car elle accroît les resultats
  $_param= array('_search',array('*','`article`','`title`,`keyword`,`description`,`categorie`,`genre`,`organisme`',$_q,$_mode,'MATCH(`title`,`keyword`,`description`,`categorie`,`genre`,`organisme`) AGAINST('.$_q.' WITH QUERY EXPANSION) DESC'));
  return _loader('_db',$_param);   
  }
  elseif($_mode == 'avancee'){ // IL s'agit ici d'une recherche de specialiste dite "Avancee" Car elle offre toute une gammme de parametres librement modifiables
$_num = 30;
   $_ts_q = '+'.str_replace(' ',' +',trim($_GET['ts_q'])); //tous les mots suivants
   $_ex_q = '"'.$_GET['ex_q'].'"'; //exactement cette expression
   $_ev_q = preg_replace('`^([+-<>~("])`',' \\\$1',$_GET['ev_q']); //éventuellement les mots suivants
   $_ev_q = preg_replace('`\s([+-<>~("])`',' \\\$1',$_ev_q);
   $_au_q = '-'.str_replace(' ',' -',trim($_GET['au_q'])); //aucun des mots suivants
   
   if(!isset($_ts_q[1])) $_ts_q ='';
   if(!isset($_ex_q[2])) $_ex_q ='';
   if(!isset($_au_q[1])) $_au_q ='';
   $q = $_ts_q.' '.$_ex_q.' '.$_ev_q.' '.$_au_q; // Reconstitution de la question
   $_q = "'$q'";
   
    // Ici est definie si la reecherche se fera dans toute la page ou sera limité au Meta-Données
   (isset($_GET['dans']) && $_GET['dans']=='page')? $_dans = '`title`,`keyword`,`description`,`text`,`categorie`,`genre`,`organisme`' : $_dans = '`title`,`keyword`,`description`,`categorie`,`genre`,`organisme`';
   
   // Specification de la source de l'article
   $_source = $_GET['source'];
   if(!empty($_source)) $_cond = " AND `source`='$_source'";
   
   // Specification de la categorie
   $_categorie = $_GET['categorie'];
   if(!empty($_categorie)) $_cond.= " AND `categorie`='$_categorie'";
   
   // Specification de l'intervale horaire de validité
   $_av_date = $_GET['av_date'];
   $_ap_date = $_GET['ap_date'];
   if(!empty($_av_date) && $_av_date != '01-01-1960' && $_ap_date != '31-12-2010' && !empty($_ap_date)){
    $_av_date = explode('-',$_av_date);
    $_av_date = $_av_date[2].'-'.$_av_date[1].'-'.$_av_date[0];
    $_ap_date = explode('-',$_ap_date);
    $_ap_date = $_ap_date[2].'-'.$_ap_date[1].'-'.$_ap_date[0];
    $_cond.= " AND `date` BETWEEN '$_av_date' and '$_ap_date'";
   }
   
      // Specification du Nombre de resultats à Afficher 
   $_num = $_GET['num'];
   if(empty($_num) || !in_array($_num,array(10,20,30,40,50,60,70,80,90,100))) $_num = 30;
   
      // Specification du l'ordre de trie des resultats 
   $_ord = $_GET['ord'];
   
   switch($_ord){
    case 'pertinance': // Par default les resultats sont tries par ordre de Pertinance Decroissante
     $_ord = "MATCH($_dans) AGAINST($_q IN BOOLEAN MODE) DESC";
    break;
    case 'date': //
     $_ord = '`date` DESC';
    break;
    case 'source': //
    $_ord = '`source` DESC';
    break;
    case 'lecture': //
    $_ord = '`lecture` DESC';
    break;
    case 'vote': //
    $_ord = '`vote` DESC';
    break;
    default: // Par default les resultats sont tries par ordre de Pertinance Decroissante
     $_ord = "MATCH($_dans) AGAINST($_q IN BOOLEAN MODE) DESC";
   }
   
   $_param = array('_search',array('*','article',$_dans,$_q,$_mode,$_ord,$_cond,$_num));
   return _loader('_db',$_param);
  }
  else{
   trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
   return false;
   exit;
  }
 }
} 
}
//***************Fin de la fonction _search

?> 

