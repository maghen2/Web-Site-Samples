

  function verif_form( form )
  {
    var avertissement = "";
    if ( form.email.value == "" ) {
      avertissement = "Veuillez indiquer ici votre adresse �lectronique!\n";
      alert(avertissement);
          return false;
    }
    else
    {
      if ( js11 ) {
        if ( ! verif_adresse( form.email.value, true ) ) {
          alert ("La syntaxe de votre adresse �lectronique est erron�e!\n");
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


  
