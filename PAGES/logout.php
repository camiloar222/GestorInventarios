<?php

session_start();


session_destroy();


//con este header podemos programar la funcion para redigir a cierta locacion al momento de accionar el boton que necesitemos;.
header('Location: index.php');
exit();
?>
