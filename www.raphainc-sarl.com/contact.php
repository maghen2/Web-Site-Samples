<?php
/********* Creation de la page de traitement du formulaire de contact
Ce script � pour charge de traiter le formulaire "Nous Contacter"
//***************************************************************************************************************/

require_once('conf.php'); // Inclusion du fichier de configuration
$page='Accueil';
$id='4';
$search='</h2></caption>';
$param=array($id);
$html=_loader('_page',$param);
// On verifie si il sagit bien d'une soumission de formulaire 
if(!isset($_POST['civilite']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['email']) || !isset($_POST['tel']) || !isset($_POST['sujet']) || !isset($_POST['message'])){
echo($html);
}
// ensuite on verifie si tout les champs du formulaire ont ete remplis
elseif(empty($_POST['civilite']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['tel']) || empty($_POST['sujet']) || empty($_POST['message']) || empty($_POST['connu'])){

$replace='</h2></caption><span style="color:red;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="#CC9900"> Vous devez remplir tous les champs du formulaire </marquee> </span>';
$html=str_replace($search,$replace,$html);
echo($html);
}
//Si oui on commence le traitement du formulaire en s'assurant que tout les champs ont �t� correctement remplies
else{
$_email = $_POST['email'];
$_email_regexp = '`^[[:alnum:]]+([-_.]?[[:alnum:]]+)*@[[:alnum:]]+([-_.]?[[:alnum:]]+)*\.([a-z]{2,4})$`';
$_tel = $_POST['tel'];
$_tel_regexp = '`^[[:digit:]]{8,20}$`';
// Verification E-mail, Tel, pays, categorie puis eventuellemnt enregistrement de donn�es
if(preg_match($_email_regexp,$_email)==0 || preg_match($_tel_regexp,$_tel)==0){

$replace='</h2></caption><span style="color:yellow;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="#CC9900">Saisie incorrecte! Rev�rifiez SVP</marquee> </span>';
$html=str_replace($search,$replace,$html);
echo($html);
}
 else{ // Si tout les champs sont correctement remplies, On enregistre les donn�es  et envoie le mail
  $_value = "NULL,'".$_POST['civilite']."',"."'".$_POST['nom']."',"."'".$_POST['prenom']."',"."'".$_POST['email']."',"."'".$_POST['web']."',"."'".$_POST['tel']."',"."'".$_POST['sujet']."',"."'".$_POST['message']."',"."'".$_POST['connu']."'";
  $_values = array("($_value)");
  $_param = array('_insert',array('`message`',$_values));
//Envoie du message par mail
     $to = 'raphainc@ymail.com';
     $from ='webmaster@raphainc.com';
     $bcc='maghen2@gmail.com';
     $email= $_POST['email'];
     $subject = $_POST['sujet'];
     $message = $_POST['message'];
     $message = wordwrap($message, 70);
     $sms ='Civilit�: 	'.$_POST['civilite']."\r\n".
'Nom: 	'.$_POST['nom']."\r\n".
'Prenom: 	'.$_POST['prenom']."\r\n".
'E-Mail: 	'.$_POST['email']."\r\n".
'Site Web: 	'.$_POST['web']."\r\n".
'T�l: 	'.$_POST['tel']."\r\n".
'Sujet:'.$_POST['sujet']."\r\n".
'a connu le site via: '.$_POST['connu']."\r\n".
'Message: 	'.$message."\r\n";

$headers = "To: $to\r\n
From: $from\r\n
Reply-To: $from\r\n
Cc: $email\r\n
Bcc: $bcc\r\n
X-Mailer: PHP/" . phpversion();   

 if(_loader('_db',$_param) && mail($to, $subject, $sms, $headers)){     
   $replace='</h2></caption><span style="color:green;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="lime">MERCI POUR VOTRE MESSAGE</marquee>Nous vous remercions de l&apos;int�r�t que vous port� � notre entreprise. Nous avons belle et bien pris acte de votre message et il sera trait� dans les plus brefs d�lais pour une prompte r�ponse. Nous vous souhaitons un agr�able surf sur notre site.</span></ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
  }
 else{
   $replace='</h2></caption><span style="color:#005000;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="#E0FFD0"> DESOLE! ERREUR INTERNE </marquee>Une erreur interne s&apos;est produite lors de l&apos;envoi de votre message et nous ne l&apos;avons donc pas re�us. Vous en �tes aucunement responsable et nous vous prions de r�essayer plustard. Si cela ne marche toujours pas, veuillez nous envoyer votre message par mail � l&apos;adresse <a href="raphainc@ymail.com" title="Contacts de RAPHA INC Sarl - exportation de bois et bambous depuis Douala au Cameroun" ><big>raphainc@ymail.com</big></a>.</span></ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
 }
 }
} 
?> 



