<?php
include("../connexion.php");
include("../fonctions.php");
if(isset($_POST) && !empty($_POST)){
	 $sql = "UPDATE imputation SET validation = '".$_POST['value']."', Date_validation = '".date('Y-m-d')."', valide_par = '".$_POST['login']."' WHERE issue = '".$_POST['id']."' AND Date ='".$_POST['date']."' AND user = '".$_POST['user']."' ";
	 mysql_query($sql) or die("erreur");
}