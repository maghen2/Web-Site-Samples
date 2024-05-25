<?php
header("HTTP/1.0 404 Not Found");

echo'<title>ERREUR 404 le fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' est Introuvable</title>';
echo'<h3><font color="red">ERREUR 404: Le fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' n\'exist pas</font></h3>'
?>
