<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/

/* Script de gestion centralisée des erreurs */
$table='<table border="1" bordercolor="#00BFFF" color="gold" cellpadding="1" cellspacing="1">
 <caption align="center" valign="top"><h2>UN ÉCHANTILLONS DE NOS PRODUITS</h2>
 </caption>
 <tr valign="top">
  <th> DESIGNATION </th> <th> IMAGE </th> <th> DESCRIPTION </th></tr>';
for ($i='Image001';$i<='Image037';$i++) {
$table.='<tr valign="top"><th>$Nom$</th><td><a href="image.php?id='.$i.'" title="Cliquez pour agrandir la photo"><img src="src/img/'.$i.'.jpg" alt="Cliquez pour agrandir la photo" title="Cliquez pour agrandir la photo" width="250" /></a></td><td><p><strong>$Description$</strong></p></td></tr>';
}
	$table.='</table>';
  echo($table);
?>
