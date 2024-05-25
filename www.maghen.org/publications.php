<?php
/* Copyrights Mai 213, MAGHEN NEGOU Rostant, Tout droit réservé
MAGHEN NEGOU Rostant
Communicateur Informaticien
Tél:(+237) 77 92 06 85 - 94 27 12 80 - 77 55 57 43 - 77 28 28 51
BP: Douala Cameroun
Email : maghen2@gmail.com
*/
require_once('conf.php'); // Inclusion du fichier de configuration
(empty($_GET['page']))?$page='Accueil':$page=$_GET['page'];
(empty($_GET['id']))?$id='3':$id=$_GET['id'];
$param=array($page,$id);
$html=_loader('_page',$param);
// On remplace £tableau£ par $tableau
$tableau='<table class="ref" border="1" bordercolor="#DFA679" cellpadding="4" cellspacing="0">  <tr valign="top">  <th >  <p> TITRE  </p>  </th>  <th>  <p> EXTRAIT  </p>  </th>  <th>  <p style="text-align: center"> LIENS  </p>  </th>  </tr>';
 $dir=scandir('src/doc/');
 $numd=count($dir)-2;
 $i=1;
  for ($num='a'; $i<=$numd; $num++) {// Traitement de chaque sous-dossier des documents
   $i++;
   $dir=scandir("src/doc/$num/");
   $mask='/^(\w[-._\w]*\w.?).(html)$/';
   foreach ($dir as $value) {
    preg_match($mask,$value,$match);
    if(!empty($match)) $fichier=$match;
   }
$doc="src/doc/$num/".$fichier[0];
$doc=file_get_contents($doc);
$mask_titre='`<\s*(?:title).*?>(.*?)<\s*/(?:title)\s*>`si';
$mask_description='`<\s*(?:META\s*NAME="DESCRIPTION"\s*CONTENT=")(.*?)">`si';
preg_match($mask_titre,$doc,$titre);
preg_match($mask_description,$doc,$description);
$titre=$titre[1];
$description=$description[1];
$fichier=$fichier[1];
$tableau.='<tr valign="top"> <td><a title="'.$description.'" name="'.$fichier.'">'.$titre.'</a></td> <td>'.$description.'</td> <td><p><a href="src/doc/'.$num.'/'.$fichier.'.html" title="Telecharger le document au format HTML">  <img src="src/img/html.jpg" alt="Telecharger le document au format HTML" title="Telecharger le document au format HTML"></a>  </p>  <p>  <a href="src/doc/'.$num.'/'.$fichier.'.pdf" title="Telecharger le document au format PDF">  <img src="src/img/pdf.jpg" alt="Telecharger le document au format PDF" title="Telecharger le document au format PDF"></a>  </p>  <p>  <a href="src/doc/'.$num.'/'.$fichier.'.odt" title="Telecharger le document au format ODT">  <img src="src/img/odt.jpg" alt="Telecharger le document au format ODT" title="Telecharger le document au format ODT"></a>  </p></td></tr>
';   
  }
  
$tableau.='</table>';
$html = str_replace('£tableau£',$tableau,$html);
echo($html);
/*

</ul>
<table class="ref" border="1" bordercolor="#DFA679" cellpadding="4" cellspacing="0">
  <tr valign="top">
    <th >
      <p> TITRE
      </p>
    </th>
    <th>
      <p> EXTRAIT
      </p>
    </th>
    <th>
      <p style="text-align: center"> LIENS
      </p>
    </th>
  </tr>
  <tr valign="top"> <td> DE L'INFORMATION ET LA COMMUNICATION À LA SOCIÉTÉ DE L'INFORMATION ET DE LA COMMUNICATION</td> <td> ... j'ai voulu à travers cet ouvrage partager avec mes camarades étudiants de la filière communication de l'université de Douala, le fruit de mes recherches personnelles sur les sciences de l'information et de la communication (SIC). Ceci est d'autant plus naturel que tout savoir pour grandir a besoin d'être critiqué. Ce livre n'a pas la prétention de remplacer ou de se substituer au cours magistral de l'enseignant ...</td> <td>
      <p>
        <a href="src/doc/1/de_l-information_et_la_communication_a_la_societe_de_l-information_et_de_la_communication.html" title="Telecharger le document au format HTML"> 
          <img src="src/img/html.jpg" alt="Telecharger le document au format HTML" title="Telecharger le document au format HTML"></a>
      </p>
      <p>
        <a href="src/doc/1/de_l-information_et_la_communication_a_la_societe_de_l-information_et_de_la_communication.pdf" title="Telecharger le document au format PDF"> 
          <img src="src/img/pdf.jpg" alt="Telecharger le document au format PDF" title="Telecharger le document au format PDF"></a>
      </p>
      <p>
        <a href="src/doc/1/de_l-information_et_la_communication_a_la_societe_de_l-information_et_de_la_communication.odt" title="Telecharger le document au format ODT"> 
          <img src="src/img/odt.jpg" alt="Telecharger le document au format ODT" title="Telecharger le document au format ODT"></a>
      </p></td>
  </tr>
</table>
*/
?>
