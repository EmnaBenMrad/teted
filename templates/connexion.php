<?php
switch ($_SERVER["SERVER_NAME"]) {

		case "10.44.162.191" :
		{
			$serveur_bd = "localhost";
			$user_bd = "";
			$password_bd = "";
			$bd="jira";
			break;
		}
		case "localhost" :
		{
			$serveur_bd = "localhost";
			$user_bd = "root";
			$password_bd = "";
			$bd="jira";
			break;
		}
		case "tn.businessdecision.com" :
		{
			$serveur_bd = "localhost";
			$user_bd = "root";
			$password_bd = "";
			$bd="jira";
			break;
		}

		case "41.226.20.102" :
		{
			$serveur_bd = "localhost";
			$user_bd = "root";
			$password_bd = "";
			$bd="jira";
			break;
		}
		case "41.224.50.5" :
		{
			$serveur_bd = "localhost";
			$user_bd = "root";
			$password_bd = "";
			$bd="jira";
			break;
		}
	}


$conn = new mysqli('remote.addr.org.uk', 'root', '', 'jira', '3306');

#$conn = mysql_connect($serveur_bd,$user_bd, $password_bd);

mysql_select_db($bd);

?>
