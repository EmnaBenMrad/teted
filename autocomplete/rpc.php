<?php

	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	$host = 'localhost'; //Votre host, souvent localhost
	$user = 'root'; //votre login
	$pass = ''; //Votre mot de passe
	$db = 'jira'; // Le nom de la base de donnee

	$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
	mysql_select_db($db) or die ('Erreur :'.mysql_error());
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = mysql_real_escape_string($_POST['queryString']);

			// Is the string length greater than 0?

			if(strlen($queryString) >0) {
				// Run the query: We use LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';

				// YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
				// eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10
				$query = "SELECT id,label FROM autocomplete WHERE label LIKE '$queryString%' LIMIT 10";
				$query_result = mysql_query($query);
				if (!$query_result) {
					echo ("Impossible d'afficher les résultats");
				} else {
					echo ("Voici les résultats : <br>\n");
					// On parcours chaque ligne du résultat
					while($row = mysql_fetch_array($query_result)) {
						echo '<li id=\''.$row["id"].'\' onClick="fillvalue(\''.$row["id"].'\');fill(\''.$row["label"].'\');">'.$row["label"].'</li>';
				  	}

				}

			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>
