<?php
header("HTTP/1.0 403 Forbidden");

echo'<title>ERREUR 403 Forbidden: Accès au fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' refusé</title>';
echo'<h3><font color="red">ERREUR 403 Forbidden: Accès au fichier http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' refusé</font></h3>'
?>
