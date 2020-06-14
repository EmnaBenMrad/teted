<?php

define('DB_HOST',       'localhost');
define('DB_USER',       'root');
define('DB_PASSWORD',   '');
define('DB_NAME',       'bdd');
define('DB_TABLE_NAME', 'inlinemod');

// Connexion à la base de données
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());
?>