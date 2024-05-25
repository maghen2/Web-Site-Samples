<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/

/* Creation de la Fonction _bd()
Cette fonction aura pour mission de servir d'unique intermediaire entre toute les autres applications 
du programme et le sgdbr MySQL.
Elle établit la connexion et gere les reqêtes MySQL.
Elle est muni de routines lui permettant d'automatiser des actions les plus courantes tel que
la SELECTION, l'INSERTION, la MISE à JOUR, la SUPPRESSION, la RECHERCHE FULL-TEXT de données et
l'EXECUTION SHELL des instruction Mysql après filtrage des instructions Dangereuses tel que:
CREATE, DROP, ALTER, RENAME, TRUNCATE, LOAD
*/

function _db($_func,$_param,$_id=0){
global $_Erreur;
 $_db = array(
 0 => array('host'=>'localhost','user' => 'root','pwd' => '','db' => 'camsaftrac'),
 1 => array('host'=>'mysql.hostinger.fr','user' => 'u338037903_rapha','pwd' => 'raphainc-sarl.com','db' => 'u338037903_rapha') 
);
$_func_array = array(1 => '_select','_insert','_delete','_update','_search','_shell');

/************************** Definition du corps de la fonction principale ******************************/
 // Verification des parametres
 if(!in_array($_func,$_func_array) || !function_exists($_func) || !is_array($_param) || empty($_param) || !array_key_exists($_id,$_db)){
   trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
   return false;
   exit;
 }
 else{
   // Verification de l'etablissement de la connection avec MySQL
  if(!($_mysqli = new mysqli($_db[$_id]['host'],$_db[$_id]['user'],$_db[$_id]['pwd'],$_db[$_id]['db'])) || mysqli_connect_errno()){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
  }
    // Renvoi à l'appelant de la reponse de la requête
  else{
   if(!($_param = array_merge(array('mysqli' => &$_mysqli),$_param)) || !($_func = array_search($_func,$_func_array))){
    trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);   
    echo $_mysqli->error.'<b>OU</b>';
    trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
    return false;
   exit;
   }
   else return call_user_func_array($_func_array[$_func],$_param);
  }
}
}
//***** Fin de la definition du corps de la fonction principale 

/************************** definition des sous-fonctions du programes **********************************/ 

//***************************************************************************************************
/* Fonction _select()
Cette Fonction est Chargée de satisfaire le besoin de selection simple de donnée
*/
function _select($_mysqli,$_col,$_table,$_cond=1,$_ord='NULL',$_num='0 , 1'){
// Definition de la variable d'initialisation du tableau des resultats
global $_Erreur;
// Verification des paramètres
if(!is_object($_mysqli) || empty($_mysqli) || empty($_col) || empty($_table) || empty($_cond) || empty($_ord) || empty($_num)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
// Definition de la requête SQL
$_query="SELECT $_col FROM $_table WHERE $_cond ORDER BY $_ord LIMIT $_num";
// Verification de la bonne exécution de la requête
 if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
 }
 else{
  for($_i=0;$_i<$_result->num_rows;$_i++)
   $_data[] = $_result->fetch_array();
   if(!isset($_data)) $_data[] = '';
  return $_data;
 }
}
}
//***** Fin de la Fonction _select()

//*****************************************************************************************************
/* Fonction _insert()
Cette Fonction est chargée de satisfaire le besoin d'insertion simple de données
*/

function _insert($_mysqli,$_table,$_values){
 // Definition de la variable d'initialisation du tableau des resultats
global $_Erreur;
 // Verification des paramètres
if(!is_object($_mysqli) || empty($_mysqli) || !is_string($_table) || empty($_table) || !is_array($_values) || empty($_values)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
// Definition de la requête
 $_values = implode(',',$_values);
 $_query ="INSERT INTO $_table VALUES $_values";
 if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit; 
 }
 else{
  if(!($_data = $_mysqli->affected_rows)){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;   
  }
  else{
  return $_data;
  exit;   
  }

 }
 
}
}
//***** Fin de la Fonction _insert()

//*****************************************************************************************************
/* Fonction _update()
Cette Fonction est chargée de satisfaire le besoin de mise à jour simple de données
*/
function _update($_mysqli){
 global $_Erreur;
 $_arg_num = func_num_args();
 $_arg_array = func_get_args();
 
if(!is_object($_mysqli) || empty($_mysqli)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
 // Mise à jour momo-table
if($_arg_num === 6){
   // Verification des paramètres
 if(empty($_arg_array[1]) || empty($_arg_array[2]) || empty($_arg_array[3])){
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  return false;
  exit;
 }
 else{
  // Definition des variables
  $_table = $_arg_array[1];
  $_value = $_arg_array[2];
  $_cond = $_arg_array[3];
  $_ord = $_arg_array[4];
  (!empty($_arg_array[4]))?  $_ord = $_arg_array[4] :  $_ord = 'NULL';
  (!empty($_arg_array[5]))?  $_num = $_arg_array[5] :  $_num = 1;
   // Definition de la requête SQL
  $_query = "UPDATE $_table SET $_value WHERE $_cond ORDER BY $_ord LIMIT $_num";
  if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
  }
  else{
   if(!($_data = $_mysqli->affected_rows)){
    if($_data === 0){
      return $_data;
      exit;
    }
    else{
     trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
     echo $_mysqli->error; echo'<br>'.$_query;
     return false;
     exit;
    }   
   }
   else return $_data;
  }
 }
}
  // Mises à Jour multi-tables
elseif($_arg_num === 4){
  // Verification des Paramètres
 if(empty($_arg_array[1]) || empty($_arg_array[2]) || empty($_arg_array[3])){
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  return false;
  exit;
 }
 else{
   // Definition des variables
  $_table = $_arg_array[1];
  $_value = $_arg_array[2];
  $_cond = $_arg_array[3];
  $_query ="UPDATE $_table SET $_value WHERE $_cond";
  if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
  }
  else{
   if(!($_data = $_mysqli->affected_rows)){
    if($_data === 0){
      return $_data;
      exit;
    }
    else{
     trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
     echo $_mysqli->error; echo'<br>'.$_query;
     return false;
     exit;
    }   
   }
   else return $_data;
  }
 }
}
 // Le numbre de paramètre est incorrect
else{ 
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
}
}
//***** Fin de la Fonction _update()

//*****************************************************************************************************
/* Fonction _delete()
Cette Fonction est chargée de satisfaire le besoin de suppression simple de données
*/
function _delete($_mysqli){
 global $_Erreur;
 $_arg_num = func_num_args();
 $_arg_array = func_get_args();
 
if(!is_object($_mysqli) || empty($_mysqli)){
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit; 
}
else{
 // Suppression momo-table
if($_arg_num === 5){
   // Verification des paramètres
 if(empty($_arg_array[1]) || empty($_arg_array[2]) || empty($_arg_array[3])){
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  return false;
  exit;
 }
 else{
  // Definition des variables
  $_table = $_arg_array[1]; 
  $_cond = $_arg_array[2];
  $_ord = $_arg_array[3];
  (!empty($_arg_array[4]))?  $_num = $_arg_array[4] :  $_num = 1;
   // Definition de la requête SQL
  $_query = "DELETE FROM $_table WHERE $_cond ORDER BY $_ord LIMIT $_num";
  if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
  }
  else{
   if(!($_data = $_mysqli->affected_rows)){
    if($_data === 0){
      return $_data;
      exit;
    }
    else{
     trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
     echo $_mysqli->error; echo'<br>'.$_query;
     return false;
     exit;
    }   
   }
   else return $_data;
  }
 }
}
  // Suppression multi-tables
elseif($_arg_num === 4){
  // Verification des Paramètres
 if(empty($_arg_array[1]) || empty($_arg_array[2]) || empty($_arg_array[3])){
  trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
  return false;
  exit;
 }
 else{
   // Definition des variables
  $_table1 = $_arg_array[1];
  $_table2 = $_arg_array[2];
  $_cond = $_arg_array[3];
  $_query ="DELETE FROM $_table1 USING $_table2 WHERE $_cond";
  if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
  }
  else{
   if(!($_data = $_mysqli->affected_rows)){
    if($_data === 0){
      return $_data;
      exit;
    }
    else{
     trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
     echo $_mysqli->error; echo'<br>'.$_query;
     return false;
     exit;
    }   
   }
   else return $_data;
  }
 }
}
 // Le numbre de paramètre est incorrect
else{ 
 trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
 return false;
 exit;
}
}
}
//***** Fin de la Fonction _delete()

//*****************************************************************************************************
/* Fonction _search()
Cette Fonction est chargée de simplifier la recherche de données en mode FULL-TEXT
*/
function _search($_mysqli,$_col1,$_table,$_col2,$_q,$_mode,$_ord,$_cond=1,$_num=30){
global $_Erreur;
 // Verification des Paramètres
if(!is_object($_mysqli) || empty($_mysqli) || empty($_col1) || empty($_table) || empty($_col2) || empty($_q) || empty($_mode) || empty($_ord)){
trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
return false;
exit;
}
else{
  // Affectation de $_num
 if(empty($_num)) $_num = 30;
  // Definition de la requête
 switch($_mode){
  case 'normale':// Recherche Classique (par default)
   $_score ="MATCH($_col2) AGAINST($_q) AS score";
   $_query ="SELECT $_col1,$_score FROM $_table WHERE MATCH($_col2) AGAINST($_q) AND $_cond ORDER BY $_ord LIMIT $_num";
   break;
  case 'etandue':// Recherche avec plus de resultats (plus gourmande)
   $_mode ='WITH QUERY EXPANSION';
   $_query ="SELECT $_col1 FROM $_table WHERE MATCH($_col2) AGAINST($_q $_mode) AND $_cond ORDER BY $_ord LIMIT $_num";   
   break;
  case 'avancee':// Recherche avancée (plus rigoureuse)
   $_mode ='IN BOOLEAN MODE';
   $_query ="SELECT $_col1 FROM $_table WHERE MATCH($_col2) AGAINST($_q $_mode) $_cond ORDER BY $_ord LIMIT $_num";
   //echo $_query;  
   break;   
  default:
   trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
   return false;
   exit;
 }
// Verification de la bonne exécution de la requête
 if(!($_result = $_mysqli->query($_query))){
   trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
   echo $_mysqli->error; echo'<br>'.$_query;
   return false;
   exit;
 }
 else{
  $_data = array();
  for($_i=0;$_i<$_result->num_rows;$_i++)
   $_data[] = $_result->fetch_array();
  return $_data;
 }
}
}
//***** Fin de la Fonction _search()

//*****************************************************************************************************
/* Fonction _shell()
Cette Fonction est chargée de simplifier l'execution libre des instructions MySQL
après filtrage des instructions Dangereuses telles que:
CREATE, DROP, ALTER, RENAME, TRUNCATE, LOAD
*/
function _shell($_mysqli,$_sql){
global $_Erreur;
$_denied = array('CREATE','DROP','ALTER','RENAME','TRUNCATE','LOAD');
 // Verification des Paramètres
if(!is_object($_mysqli) || empty($_mysqli) || empty($_sql)){
trigger_error($_Erreur['Eparam'][0],$_Erreur['Eparam'][1]);
return false;
exit;
}
else{
 if(is_int(stripos($_sql,$_denied[0])) || is_int(stripos($_sql,$_denied[1])) || is_int(stripos($_sql,$_denied[2]))
 || is_int(stripos($_sql,$_denied[3])) || is_int(stripos($_sql,$_denied[4])) || is_int(stripos($_sql,$_denied[5]))){
  trigger_error($_Erreur['Eright'][0],$_Erreur['Eright'][1]);
  return false;
  exit;
 }
 else{
  $_query = $_sql;
  // Verification de la bonne exécution de la requête
  if(!($_result = $_mysqli->query($_query))){
    trigger_error($_Erreur['Edb'][0],$_Erreur['Edb'][1]);
    echo $_mysqli->error; echo'<br>'.$_query;
    return false;
    exit;
  }
  else{
   if(empty($_result)){
    return true;
    exit;
   }
   else{
    if(is_object($_result)){
     if(is_int($_result->num_rows)){
      if($_result->num_rows > 0){
      for($_i=0;$_i<$_result->num_rows;$_i++)
       $_data[] = $_result->fetch_array();
      return $_data;
      exit;
      }
      else{return 0; exit;}
     }
     elseif(is_int($_mysqli->affected_rows)){
      $_data = $_mysqli->affected_rows;
      return $_data;
      exit;
     }
     else{
      return true;
      exit;
     }
    }
   else{
    return $_result;
    exit;
   }
  }
  }
  }
 }
}
//***** Fin de la Fonction _shell()
//******************************** FIN de l'Application _DB ********************************************************//
?>
