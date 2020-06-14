<?php
include("../connexion.php");
include("../fonctions.php");
if(isset($_POST) && !empty($_POST)){
	 $sql = "UPDATE imputation SET validation = '".$_POST['value']."', Date_validation = '".date('Y-m-d')."', valide_par = '".$_POST['login']."' WHERE issue = '".$_POST['id']."' AND user = '".$_POST['user']."' 
	 AND Date BETWEEN '" . $_POST['date1'] . "' AND '" . $_POST['date2'] . "' ";
	 mysql_query($sql) or die("erreur");
}