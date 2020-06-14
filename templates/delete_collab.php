<?php
include("connexion.php");
include("fonctions.php");
if(isset($_POST) && !empty($_POST)){
	 if(isset($_POST['id']) && isset($_POST['idproject'])){
	 	deleteDataValidate($_POST['id'],$_POST['idproject']);
	 }
}