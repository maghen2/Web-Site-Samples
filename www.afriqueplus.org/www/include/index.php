<?php
include_once('conf.php');
/* Creation d'un Pseudo-CRON
 Elle a pour but de faciliter l'exècution periodique de certains script d'entretien du programme
*/

// Appel de rss_get.php afin d'ajouter de nouveaux articles
$_rss_get = $_domain.'include/rss_get.php';
while(stripos(file_get_contents($_rss_get,false),'£end£')===false){
 $p = fopen($_rss_get,'r');
 fclose($p);
}

// Appel de html_get.php afin de completer les articles ajoutés par rss_get.php
$_html_get = $_domain.'include/html_get.php';
while(stripos(file_get_contents($_html_get,false),'£end£')===false){
 $p = fopen($_html_get,'r');
 fclose($p);
}

?>
