<?php
/********* Creation de la page de traitement du formulaire de contact
Ce script � pour charge de traiter le formulaire "Nous Contacter"
//***************************************************************************************************************/

require_once('conf.php'); // Inclusion du fichier de configuration
$search='</h2></caption>';
$param=array(7);
$html=_loader('_page',$param);

// On verifie si il sagit bien d'une soumission du formulaire 
if(!isset($_POST['civilite']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['email']) || !isset($_POST['web']) || !isset($_POST['tel']) || !isset($_POST['sujet']) || !isset($_POST['msg']) || !isset($_POST['connu'])){
echo($html);
}
// sinon on verifie si tout les champs du formulaire ont ete remplis
elseif(empty($_POST['civilite']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['web']) || empty($_POST['tel']) || empty($_POST['sujet']) || empty($_POST['msg']) || empty($_POST['connu'])){

$replace='</h2></caption><div class="important echec"> <marquee behavior="alternate">VOUS DEVEZ REMPLIR TOUS LES CHAMPS DU FORMULAIRE!</marquee>';
$replace.='<p>Le ou les champs suivants sont vides:</p><ol>';
if(empty($_POST['civilite'])) $replace.='<li>Civilit�</li>';
if(empty($_POST['nom'])) $replace.='<li>Nom</li>';
if(empty($_POST['prenom'])) $replace.='<li>Pr�nom</li>';
if(empty($_POST['email'])) $replace.='<li>E-Mail</li>';
if(empty($_POST['web'])) $replace.='<li>Site Web</li>';
if(empty($_POST['tel'])) $replace.='<li>T�l�phone</li>';
if(empty($_POST['sujet'])) $replace.='<li>Sujet</li>';
if(empty($_POST['msg'])) $replace.='<li>Message</li>';
if(empty($_POST['connu'])) $replace.='<li>connu ce site</li>';
$replace.='</ol></div>';
$html=str_replace($search,$replace,$html);
echo($html);
}
//Enfin on commence le traitement du formulaire en s'assurant que tout les champs ont �t� correctement remplies
else{
$civilite = $_POST['civilite'];
$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);
$email = $_POST['email'];
$web = $_POST['web'];
$tel = $_POST['tel'];
$sujet = trim($_POST['sujet']);
$msg = $_POST['msg'];
$connu = $_POST['connu'];
// Verification des champs du formulaire  puis eventuellemnt enregistrement de donn�es
if(count(str_split($nom))<3 || preg_match($_expreg['nom'],$nom)==0 || count(str_split($prenom))<3 || preg_match($_expreg['nom'],$prenom)==0 || count(str_split($sujet))<3 || preg_match($_expreg['nom'],$sujet)==0 || count(str_split($msg))<20 || preg_match($_expreg['email'],$email)==0 || preg_match($_expreg['web'],$web)==0 || preg_match($_expreg['tel'],$tel)==0){
$replace='</h2></caption><div class="important echec"> <marquee behavior="alternate"> SAISIE INCORRECTE! V�RIFIEZ PUIS R�ESSAYEZ</marquee>';
$replace.='<p>Le ou les champs ci-dessous ont des valeurs incorrectes.</p><ol>';
if(count(str_split($nom))<3 || preg_match($_expreg['nom'],$nom)==0) $replace.='<li>Nom: '.$nom.'</li>';
if(count(str_split($prenom))<3 || preg_match($_expreg['nom'],$prenom)==0) $replace.='<li>Pr�nom: '.$prenom.'</li>';
if(count(str_split($msg))<20 || preg_match($_expreg['email'],$email)==0) $replace.='<li>E-Mail: '.$msg.'</li>';
if(preg_match($_expreg['web'],$web)==0) $replace.='<li>Site Web: '.$web.'</li>';
if(preg_match($_expreg['tel'],$tel)==0) $replace.='<li>T�l�phone: '.$tel.'</li>';
if(count(str_split($sujet))<3 || preg_match($_expreg['nom'],$sujet)==0) $replace.='<li>Sujet: '.$sujet.'</li>';
$replace.='</ol></div>';
$html=str_replace($search,$replace,$html);
echo($html);
}
 else { // Si tout les champs sont correctement remplies, On enregistre les donn�es  et envoie le mail

// On prepare la requete pour la sauvergarde du message   
  $_value = "NULL,'$civilite','$nom','$prenom','$email','$web','$tel','$sujet','$msg','$connu',NULL";
  $_values = array("($_value)");
  $_param = array('_insert',array('`message`',$_values));

//On prepare la requete pour l'envoie du message par mail
     $to = 'camsaftrac@gmail.com';
     $from = $email;
     $bcc = 'maghen2@gmail.com';
     $msg = wordwrap($msg, 70);
     $couriel = " 
Civilit�: 	$civilite\r\n
Nom: 	$nom\r\n
Prenom: 	$prenom\r\n
E-Mail: 	$email\r\n
Site Web: 	$web\r\n
T�l: 	$tel\r\n
Sujet:$sujet\r\n
A connu le site via: $connu\r\n
Message: 	$msg\r\n";

$headers = "To: $to\r\n
From: $from\r\n
Reply-To: $from\r\n
Cc: $email\r\n
Bcc: $bcc\r\n
X-Mailer: PHP/" . phpversion();   

// On execute les requetes pr�par�es
$retour1 = _loader('_db',$_param);
$retour2 = mail($to, $sujet, $couriel, $headers);

// Verification finale
 if($retour1 && $retour2){     
   $replace='</h2></caption><div class="important succes">  <marquee behavior="alternate"> MERCI POUR VOTRE MESSAGE  </marquee> Nous vous remercions de l&apos;int�r�t que vous port� � notre entreprise. Nous avons re�u votre message. Il sera trait� dans les plus brefs d�lais et vous recevrez rapidement une r�ponse. Nous vous souhaitons un agr�able surf sur notre site web. </div> </ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
  }
 else{
   $replace='</h2></caption><div class="important echec">  <marquee behavior="alternate">  DESOLE! ERREUR INTERNE  </marquee>  Une erreur interne s&apos;est produite lors de l&apos;envoi de votre message et nous ne l&apos;avons pas re�u. Nous vous prions de r�essayer plustard. Si cela ne marche toujours pas, veuillez nous envoyer votre message par mail � l&apos;adresse  <a href="camsaftrac@gmail.com" title="Contacts de CAMEROON SALES FORCE AND TRADING COMPANY LTD" ><big>camsaftrac@gmail.com</big></a>.  </div>  </ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
 }
 }
} 
?> 



