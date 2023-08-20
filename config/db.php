<?php
/*Datos de conexion a la base de datos*/

if ( ($_SERVER['SERVER_NAME'] == "localhost")  ){

   //Servidor de Desarrollo
   define('DB_HOST', 'localhost');//DB_HOST:  generalmente suele ser "127.0.0.1"
   define('DB_USER', 'root');//Usuario de tu base de datos
   define('DB_PASS', '');//Contraseña del usuario de la base de datos
   define('DB_NAME', 'u572581706_Optica');//Nombre de la base de datos

}