<?php
/********* Creation de la page de traitement du formulaire de contact
Ce script à pour charge de traiter le formulaire "Nous Contacter"
//***************************************************************************************************************/
require_once('conf.php'); // Inclusion du fichier de configuration
$page='Accueil';
$id='6';
$search='</h2></caption>';
$param=array($page,$id);
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
//Si oui on commence le traitement du formulaire en s'assurant que tout les champs ont été correctement remplies
else{
$_email = $_POST['email'];
$_email_regexp = '`^[[:alnum:]]+([-_.]?[[:alnum:]]+)*@[[:alnum:]]+([-_.]?[[:alnum:]]+)*\.([a-z]{2,4})$`';
$_tel = $_POST['tel'];
$_tel_regexp = '`^[[:digit:]]{8,20}$`';
// Verification E-mail, Tel, pays, categorie puis eventuellemnt enregistrement de données
if(preg_match($_email_regexp,$_email)==0 || preg_match($_tel_regexp,$_tel)==0){

$replace='</h2></caption><span style="color:yellow;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="#CC9900">Saisie incorrecte! Revérifiez SVP</marquee> </span>';
$html=str_replace($search,$replace,$html);
echo($html);
}
 else{ // Si tout les champs sont correctement remplies, On enregistre les données  et envoie le mail
  $_value = "NULL,'".$_POST['civilite']."',"."'".$_POST['nom']."',"."'".$_POST['prenom']."',"."'".$_POST['email']."',"."'".$_POST['tel']."',"."'".$_POST['sujet']."',"."'".$_POST['message']."',"."'".$_POST['connu']."'";
  $_values = array("($_value)");
  $_param = array('_insert',array('`message`',$_values));
//Envoie du message par mail
     $to = 'maghen2@gmail.com';
     $from ='webmaster@maghen.org';
     $email= $_POST['email'];
     $subject = $_POST['sujet'];
     $message = $_POST['message'];
     $message = wordwrap($message, 70);
     $sms ='Civilité: 	'.$_POST['civilite']."\r\n".
'Nom: 	'.$_POST['nom']."\r\n".
'Prenom: 	'.$_POST['prenom']."\r\n".
'E-Mail: 	'.$_POST['email']."\r\n".
'Tél: 	'.$_POST['tel']."\r\n".
'Sujet:'.$_POST['sujet']."\r\n".
'a connu le site via: '.$_POST['connu']."\r\n".
'Message: 	'.$message."\r\n";

$headers = "To: $to\r\n
From: $from\r\n
Reply-To: $from\r\n
Cc: $email\r\n
X-Mailer: PHP/" . phpversion();   

 if(mail($to, $subject, $sms, $headers)){ // _loader('_db',$_param) &&     
   $replace='</h2></caption><span style="color:green;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="lime">MERCI POUR VOTRE MESSAGE</marquee>Je vous remercie de l&apos;intérêt que vous me porté. J\'ai belle et bien pris acte de votre message et il sera traité dans les plus brefs délais pour une prompte réponse. Je vous souhaites un agréable surf sur mon site.</span></ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
  }
 else{
   $replace='</h2></caption><span style="color:#005000;font-size:1.2em;font-weight:bold;"> <marquee behavior="alternate" bgcolor="#E0FFD0"> DESOLE! ERREUR INTERNE </marquee>Une erreur interne s&apos;est produite lors de l&apos;envoi de votre message et je ne l&apos;ai donc pas reçus. Vous en êtes aucunement responsable et je vous pries de réessayer plustart. Si cela ne marche toujours pas, veuillez m&apos;envoyer votre message par mail à l&apos;adresse <a href="mailto:maghen2@gmail.com" title="Contact de MAGHEN NEGOU Rostant, Communicateur et Informaticien à Douala au Cameroun" ><big>maghen2@gmail.com</big></a>.</span></ br>';
   $html=str_replace($search,$replace,$html);
   echo($html);
 }
 }
} 
?> 



