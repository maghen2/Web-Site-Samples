<?php
header("HTTP/1.0 403 Internal Server Error");

echo'<title>ERREUR 500 Internal Server Error: Désolè une erreur interne au serveur s\'est produite lors de la demande du fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'</title>';
echo'<h3><font color="red">ERREUR 500 Internal Server Error: Désolè une erreur interne au serveur s\'est produite lors de la demande du fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'</font></h3>'
?>
