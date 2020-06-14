<?php 
switch ($_SERVER["SERVER_NAME"]) {

		case "tetd.businessdecision.com" :
		{
			$serveur_bd = "192.168.0.4";
			$user_bd = "intranet"; 
			$password_bd = "";
			$bd="jira3135";
			break;
		}
		case "127.0.0.1" :
		{
			$serveur_bd = "localhost";
			$user_bd = "root";
			$password_bd = "";
			$bd="jira3135";
			break;
		}
		case "apache.bdtn.com" :
		{
			$serveur_bd = "192.168.2.3";
			$user_bd = "root";
			$password_bd = "redhat";
			$bd="jira3135";
			break;
		}

		case "192.168.2.5" :
		{ 
			$serveur_bd = "mysql-devel.bdtn.com:3307";
			$user_bd = "root";
			$password_bd = "redhat";
			$bd="jira3135";
			break;
		}
		case "213.150.170.26" :
		{
			$serveur_bd = "192.168.2.3";
			$user_bd = "root";
			$password_bd = "redhat";
			$bd="jira3135";
			break;
		}
		case "tn.businessdecision.com" :
		{
			$serveur_bd = "mysql-devel.bdtn.com:3307";
			$user_bd = "root";
			$password_bd = "redhat";
			$bd="jira3135";
			break;
		}
		case "41.224.36.162" :
		{
			$serveur_bd = "192.168.2.3";
			$user_bd = "root";
			$password_bd = "redhat";
			$bd="jira3135";
			break;
		}
	}
$conn = mysql_connect($serveur_bd,$user_bd, $password_bd);
mysql_select_db($bd);

?>
