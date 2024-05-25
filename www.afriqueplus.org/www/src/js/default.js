/********* Creation du fichier de cofiguration du programme
Ce fichier contient l'ensemble des variables,fonctions et classes qui doivent être disponibles
dans chaque script.
 * les noms de variable et de fonction incluses commencent par un UNDERSCORE
 * Ce fichier est le fichier JavaScript par Default pour toute page du site 
***********/

//*************************************************************************************************************************

   //********* Definition de toute les Variables de Configuration du Programme **********/
_domain = 'http://'+window.location.host+'/'; // Nom de domain du site
_path = _domain+'src/js/';; // repertoire par default des inclusions

_Erreur = new Array();
 _Erreur['Eparam'] = 'Paramètre de Fonction Incorect';
 _Erreur['Edb'] = 'Erreur de Traitement avec MySQL';
 _Erreur['Eright'] = 'Authorisation Refusée';
 _Erreur['Efile'] = 'Erreur de gestion de fichier';
//*************************************************************************************************************************


//*************************************************************************************************************************
  
  //******************* Definition de toute les Fonctions de Configuration du Programme **********************************//

/* Function Loader
Cette fonction a pour but de CHARGER AUTOMATIQUEMENT toute fonction sans se soucier de 
l'inclusion des fichiers necessaire
*/


function _loader(_func_name,_param){
  // Verification des paramètres
 if(!typeof(_func_name) == 'string' || _func_name == '' || !typeof(_param) == 'object' ||  _param == ''){
  return false;
  exit;
 }
 else {
   _func_file = _func_name.substr(1)+'.js';
     // On inclut le script de la fonction
    _head = document.getElementsByTagName("head")[0];
   
    // On renvoi le result de l'éxecution de la fonction
    
   return  eval();call_user_func_array(_func_name,_param);
   exit;
 }
}
//***** Fin de la fonction _loader()  ********************************/

/* Function isDigit
Cette fonction verifie la chaîne "ch" est de type Numerique
*/
  function isDigit( ch )
  {
    if ( (ch >= '0') && (ch <= '9') )
      return true;
    else
      return false;
  }

/* Function isAlpha
Cette fonction verifie la chaîne "ch" est de type String
*/
  function isAlpha( ch )
  {
    if ( ((ch >= 'a') && (ch <= 'z')) || ((ch >= 'A') && (ch <= 'Z')) )
      return true;
    else
      return false;
  }

/* Function isAlnum
Cette fonction verifie la chaîne "ch" est de type AlphaNumerique
*/
  function isAlnum( ch )
  {
    if ( isAlpha( ch ) || isDigit( ch ) )
      return true;
    else
      return false;
  }

/* Function notIn
Cette fonction verifie qu'aucun signe de str2 ne se trouve dans str1
*/
  function notIn( str1, str2 )
  {
    var i = 0;
    var j = str2.length;
    for( ; i<j; i++ )
    {
      var str3 =  str2.charAt(i);
      if( str1.indexOf( str3 ) != -1 )
        return false;
    }
    return true;
  }


/*
  Inspirated by Lutz Eymers script for E-mail syntax verification
  Copyright Lutz Eymers <ixtab@polzin.com>, 1997
  Polzin GmbH, Duesseldorf
*/

  function verif_nom_utilisateur( nom_utilisateur, mustBeQuoted )
  {
    var i = 0;
    var j = nom_utilisateur.length;
    if ( nom_utilisateur.charAt(0) != '"' )
    {
      if ( (nom_utilisateur.charAt(0) <  ' ') || (nom_utilisateur.charAt(0) >  '~')
            || !notIn( mustBeQuoted, nom_utilisateur.charAt(0) ) )
        return false;
      for( i=1; i<j; i++ )
      {
        if ( ( (nom_utilisateur.charAt(i) < ' ') || (nom_utilisateur.charAt(i) >  '~')
              || !notIn ( mustBeQuoted, nom_utilisateur.charAt(i) ) )
             && ( nom_utilisateur.charAt(i-1) != '\\' ) )
          return false;
      }
    }
    else
    {
      if ( nom_utilisateur.charAt( j-1 ) != '"' )
        return false;
      for( i=1; i<j-1; i++ )
      {
        if ( ( (nom_utilisateur.charAt(i) == '\n') || (nom_utilisateur.charAt(i) == '\r')
              || (nom_utilisateur.charAt(i) == '\"') )
            && (nom_utilisateur.charAt(i-1) != '\\') )
          return false;
      }

    }
    return true;
  }


  function checkNr ( nr )
  {
    var i=0;
    var j=nr.length;

    if( j < 1 )
      return false;

    for( ; i<j; i++ )
      if( ( nr.charAt(i) < '0' ) || ( nr.charAt(i) > '9' ) )
        return false;

    return true;
  }


  function checkIpnr( ipnr )
  {
    var iL=0;
    var iC=0;
    var i=0;
    var sNr = "";

    for( ; i< ipnr.length; i++ )
    {
      if ( ipnr.charAt(i) == '.' )
      {
        if ( !iL || (iL> 3) || parseInt( sNr,10 ) > 255 )
          return false;
        iC++;
        iL = 0;
        sNr = "";
        continue;
      }
      if ( isDigit ( ipnr.charAt(i) ) )
      {
        iL++;
        sNr = sNr + ipnr.charAt(i);
        continue;
      }
      return false;
    }

    if ( parseInt( sNr,10 ) > 255 )
      return false;
    if ( ( (iC==3) && (iL>=1) && (iL<=3) ) || ( (iC==4) && (!iL) )  )
      return true;
    else
      return false;
  }


  function checkFqdn( fqdn )
  {
    var iL=0;
    var iC=0;
    var i=fqdn.length-1;

    if ( (fqdn.charAt(0) == '.') || (fqdn.charAt(0) == '-') )
      return false;
    if ( fqdn.charAt(i) == '.' )
      i=i-1;

    for( ; i>=0; i-- )
    {
      if ( fqdn.charAt(i) == '.' )
      {
        if ( iL < 2 && iC < 2 )
          return false;
        if ( fqdn.charAt(i-1) == '-' )
          return false;
        iC++;
        iL = 0;
        continue;
      }
      if ( isAlnum ( fqdn.charAt(i) ) )
      {
        iL++;
        continue;
      }
      if ( fqdn.charAt(i) == '-' )
      {
        if ( !iL )
          return false;
        iL++;
        continue;
      }
      return false;
    }

    if ( !iC || ( iL == 1 && iC < 2 ) || ( !iL && iC==1 ) ) {
      return false;
    }

    return true;

  }


  function verif_nom_hote( nom_hote )
  {
    if ( nom_hote.charAt(0) == '[' )
    {
      if ( nom_hote.charAt(nom_hote.length-1) != ']' )
        return false;
      var ipnr = nom_hote.substring( 1, nom_hote.length -1 );
        return checkIpnr( ipnr );
    }

    if ( nom_hote.charAt(0) == '#' )
    {
      var nr = nom_hote.substring( 1, nom_hote.length );
        return checkNr( nr );
    }

    return checkFqdn( nom_hote );
  }


  function verif_adresse_elec( adresse )
  {
    var status = true;
    var nom_utilisateur = "";
    var nom_hote = "";

    if ( adresse.length < 8 )
      return false;

    var separateur = adresse.lastIndexOf("@");
    if ( separateur == -1 )
      return false;

    nom_utilisateur = adresse.substring(0, separateur );
    if ( ! verif_nom_utilisateur( nom_utilisateur, "<>()[],;:@\" " ) )
      return false;

    nom_hote = adresse.substring(separateur+1, adresse.length );
    if ( ! verif_nom_hote( nom_hote ) )
      return false;

    return true;
  }


  function verif_adresse( email, allowFullname )
  {
    var existFullname = false;
    var status = true;
    var fullname = "";
    var adress = "";
    if ( email.length < 8 )
      return false;
    var emailBegin = email.indexOf("<");
    var emailEnd = email.lastIndexOf(">");

    if ( (emailBegin == -1) && (emailEnd == -1) )
      return verif_adresse_elec( email );

    if ( ( (emailBegin == -1) && (emailEnd != -1) )
        || ( (emailBegin != -1) && (emailEnd == -1) ) )
      return false;

    adress = email.substring( emailBegin+1, emailEnd );

    if ( ! verif_adresse_elec( adress ) )
      return false;

    if ( email.length == adress.length + 2 )
      return true;
    else
      if ( ! allowFullname )
        return false;

    if ( emailEnd == email.length - 1 )
    {
      if ( emailBegin == 0 )
        return true;
      if ( email.charAt( emailBegin -1 ) != ' ' )
        return false;
      fullname = email.substring( 0, emailBegin-1 );
      return verif_nom_utilisateur ( fullname, "<>()[],;:@\"" );
    }

    return false ;

  }

  var js11=true;

  function _verif_form( form )
  {
    var avertissement = "";
    if ( form.email.value == "" ) {
      avertissement = "Veuillez indiquer votre adresse électronique ICI!\n";
      alert(avertissement);
      form.email.focus();
          return false;
    }
    else
    {
      if ( js11 ) {
        if ( ! verif_adresse( form.email.value, true ) ) {
          alert ("La syntaxe de votre adresse électronique est erronée!\n");
                  return false;
        } else {
          return true;
        }
      }
    }
    if ( avertissement != "" ) {
      alert( "Erreur!\n\n" + avertissement );
          return false;
    }
    return false;
  }
/********************************* 
 Les fonctions avancee et etandue motifient respectivement  le formulaire de recherche pour en faciliter l'usage
**/
function avancee(){
 document.getElementById('avancee').style.display='block';
 document.search[1].q.style.display='none';
}
function etandue(){
 document.getElementById('avancee').style.display='none';
 document.search[1].q.style.display='block';
}
//****** Fin des fonctions avancee et etandue **************************************/




