<?php
include("../connexion.php");
include("../fonctions.php");
if(isset($_POST) && !empty($_POST)){

		 $sql = "UPDATE imputation SET validation = '0', Date_validation = null, valide_par = null WHERE issue = '".$_POST['id']."' AND Date ='".$_POST['date']."' AND user = '".$_POST['user']."' ";
		 mysql_query($sql) or die("erreur");
	
}