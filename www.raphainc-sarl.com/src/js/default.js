

  function verif_form( form )
  {
    var avertissement = "";
    if ( form.email.value == "" ) {
      avertissement = "Veuillez indiquer ici votre adresse électronique!\n";
      alert(avertissement);
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


  
