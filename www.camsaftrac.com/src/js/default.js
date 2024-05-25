/* Script pour creer un diaporama
Objectif: changer d'image à interval de temps regulier avec effet de transition (transparence)
*/

var i = Math.round(Math.random()*10);
(i>6)? i = 10-i : i;
obj = new Object();
// On charge les images en memoire pour faciliter la diaporama
var image = new Array();
image[0] = new Image(); image[0].src="/src/theme/slide1.gif";
image[1] = new Image(); image[1].src="/src/theme/slide2.gif";
image[2] = new Image(); image[2].src="/src/theme/slide3.gif";
image[3] = new Image(); image[3].src="/src/theme/slide4.gif";
image[4] = new Image(); image[4].src="/src/theme/slide5.gif";
image[5] = new Image(); image[5].src="/src/theme/slide6.gif";
image[6] = new Image(); image[6].src="/src/theme/slide7.gif";

// Fonction de diaporama
function slideshow(){
obj = document.getElementById("slideshow").style; 
var j = image[i].src;
obj.backgroundImage = 'url('+j+')';
//alert ("i = "+i);
(i>=6)? i = 0 : i++;
window.setTimeout("slideshow()",15*1000);
}

/* Script pour Aider l'Utilisateur dans la saisi du formulaire
Objectif: Verifier chaque champ et aider en temps réel l'utilisateur
1-Change la couleur du champ en vert ou rouge
2-Affiche le message d'erreur
*/
var expreg = new Array();
expreg['nom'] = /^\w*[\s\w]*\w*$/;
expreg['email'] = /^(\w+[\.\-_]?\w+)+@(\w+[\.\-_]?\w+)+(\.\w{2,4}){1,2}$/;
expreg['web'] = /^(\w+[\.\-_]?\w+)+(\.\w{2,4}){1,2}$/;
expreg['tel'] = /^\+\d{7,}$/;

// Tableau des proprietes CSS à appliquer selon l"issue de la verification.
var un = new Array();
un['cl'] = "#660000";
un['bc'] = "#660000";
un['ts'] = "1px 1px 1px #CC0000";
un['bg'] = "red";
un['bs'] = "inset 1px 1px 5px #FFCCCC, 1px 1px 15px #993333";
un['vl'] = "";
var deux = new Array();
deux['cl'] = "green";
deux['bc'] = "green";
deux['ts'] = "1px 1px 1px #00CC00";
deux['bg'] = "lime";
deux['bs'] = "inset 1px 1px 5px #CCFFCC, 1px 1px 15px #339933";

var obj = new Object();
function formulaire(){
// On traite tout d'abord les champs NOM, PRENOM, SUJET et MESSAGE car leurs conditions sont identique 'NON VIDE'
var a = document.getElementById("contact");

// Verification du champ NOM
if(a.nom.value == ""  || a.nom.value == "Veuillez écrire correctement votre Nom!" || a.nom.value.length < 3){
  obj = a.nom.style;
  un['vl'] = "Veuillez écrire correctement votre Nom!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.nom.value = un['vl'];
   a.nom.focus();
   return false;
}
else{
obj = a.nom.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ PRENOM
if(a.prenom.value == ""  || a.prenom.value == "Veuillez écrire correctement votre Prénom!" || a.prenom.value.length < 3){
  obj = a.prenom.style;
  un['vl'] = "Veuillez écrire correctement votre Prénom!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.prenom.value = un['vl'];
   a.prenom.focus();
   return false;
}
else{
obj = a.prenom.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ E-Mail
if(a.email.value.search(expreg['email']) < 0 || a.email.value == "camsaftrac@gmail.com"  || a.email.value.length < 6){
  obj = a.email.style;
  un['vl'] = "Veuillez écrire correctement votre adresse Courriel!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.email.value = un['vl'];
   a.email.focus();
   return false;
}
else{
obj = a.email.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ Site Web
if(a.web.value.search(expreg['web']) < 0 || a.web.value == "www.camsaftrac.com" || a.web.value.length < 6){
  obj = a.web.style;
  un['vl'] = "Veuillez écrire correctement votre adresse Web!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.web.value = un['vl'];
   a.web.focus();
   return false;
}
else{
obj = a.web.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ Téléphone
if(a.tel.value.search(expreg['tel']) < 0 || a.tel.value == "+23733066102" || a.tel.value.length < 6){
  obj = a.tel.style;
  un['vl'] = "Veuillez écrire correctement votre adresse Téléphonique!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.tel.value = un['vl'];
   a.tel.focus();
   return false;
}
else{
obj = a.tel.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ SUJET
if(a.sujet.value == ""  || a.sujet.value == "Veuillez écrire correctement le Sujet de votre message!" || a.sujet.value.length < 3){
  obj = a.sujet.style;
  un['vl'] = "Veuillez écrire correctement le Sujet de votre message!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.sujet.value = un['vl'];
   a.sujet.focus();
   return false;
}
else{
 obj = a.sujet.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}

// Verification du champ MESSAGE
if(a.msg.value == ""  || a.msg.value == "Veuillez écrire correctement votre Message!"  || a.msg.value.length < 20){
  obj = a.msg.style;
  un['vl'] = "Veuillez écrire correctement votre Message!";
  obj.color = un['cl'];
  obj.borderColor = un['bc'];
  obj.textShadow = un['ts'];
  obj.backgroundColor = un['bg'];
  obj.boxShadow = un['bs'];
  a.msg.value = un['vl'];
   a.msg.focus();
   return false;
}
else{
 obj = a.msg.style;
  obj.color = deux['cl'];
  obj.borderColor = deux['bc'];
  obj.textShadow = deux['ts'];
  obj.backgroundColor = deux['bg'];
  obj.boxShadow = deux['bs'];
}


// Si toutes les verifications sont OK alors on permet l'envoi du formulaire
  return true;  
}

/*
if(a.lieu.value == "") {
   alert("Veuillez entrer votre lieu de résidence!");
   a.lieu.focus();
   return false;
  }
 if(a.courriel.value == "") {
   alert("Veuillez entrer votre adresse électronique!");
   a.courriel.focus();
   return false;
  }
 if(a.courriel.value.indexOf('@') == -1) {
   alert("Ce n'est pas une adresse électronique!");
   a.courriel.focus();
   return false;
  }
 if(a.age.value == "") {
   alert("Veuillez entrer votre âge!");
   a.age.focus();
   return false;
  }
 var chkZ = 1;
 for(i=0;i<a.age.value.length;++i)
   if(a.age.value.charAt(i) < "0"
   || a.age.value.charAt(i) > "9")
     chkZ = -1;
 if(chkZ == -1) {
   alert("Cette mention n'est pas un nombre!");
   a.age.focus();
   return false;
  }
*/

/*

 var sortie = "";
 for (var propriete in a)
   sortie = sortie + "document." + propriete + ": " + a[propriete] + "<br>";
 document.write("<p>Propriétés de l'objet <i> Formulaire <\/i><\/p>")
 document.write(sortie);
 return false; 
*/
