<?php
/********* Creation de la Fonction _image
 Cette fonction a pour mission de générer automatiquement une image affichant un texte precis
//***************************************************************************************************************/
require_once('conf.php'); // Inclusion du fichier de configuration
 // Creation de l'image
/*On utilise bg.png, qui ne va pas être modifiée
mais on ne pourra plus en faire une copie sans 
le message qui s'affiche au-dessus*/
header("Content-type:image/png");

function _image(){
global $_alphanum,$_alphanum2,$_alphanum3;
$_fonts = array(
  'Framdcn.TTF','LucidaSU.ttf','arial.ttf','arialbd.ttf','arialbi.ttf','ariali.ttf','ariblk.ttf','comic.ttf','comicbd.ttf','cour.ttf','courbd.ttf','courbi.ttf','couri.ttf','framd.ttf','framdit.ttf','georgia.ttf','georgiab.ttf','georgiai.ttf','georgiaz.ttf','impact.ttf','kartika.ttf','lsans.ttf','lsansd.ttf','lsansdi.ttf','lsansi.ttf','lucon.ttf','micross.ttf','pala.ttf','palab.ttf','palabi.ttf','palai.ttf','sylfaen.ttf','tahoma.ttf','tahomabd.ttf','times.ttf','timesbd.ttf','timesbi.ttf','timesi.ttf','trebuc.ttf','trebucbd.ttf','trebucbi.ttf','trebucit.ttf','verdana.ttf','verdanab.ttf','verdanai.ttf','verdanaz.ttf','vrinda.ttf',
  );
if(isset($_GET['txt'])){
 $_txt = $_GET['txt'];
 $_txt = substr($_txt,5,8);
 $_pw_array = str_split($_txt);
 $_alphanum3 = array_flip($_alphanum3);
 $_pw = '';
 foreach($_pw_array as $_key=>$_value) $_pw.= ' '.$_alphanum3[$_value];
 $_txt = $_pw;
 
}
else{
 $_txt = 'ERREUR';
}
    

$_img = imageCreate(200,50);
$_color = imageColorAllocate($_img,150,194,145);
$_font_color = imageColorAllocate($_img,255,255,255);
$_font_name = 'font/'.$_fonts[mt_rand(0,(count($_fonts)-1))];

for($i=0;$i<5;$i++)imageline($_img,mt_rand(0,200),mt_rand(0,50),mt_rand(0,200),mt_rand(0,50),$_font_color);
imagettfText($_img,15,-5,0,20,$_font_color,$_font_name,$_txt);
imagePNG($_img);

}

_image();
?>
