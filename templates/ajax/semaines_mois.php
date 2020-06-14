<?php
include("../connexion.php");
include("../fonctions.php");

$html ='';
if(isset($_POST['mois']) && $_POST['mois'] !='' && isset($_POST['annee']) && $_POST['annee'] !=''){
	$html= '  <option value="" >Semaine </option>';
	$semaines = getListOfWeek($_POST['mois'], $_POST['annee']);
	for($i = 0; $i < count($semaines); $i ++) {
		$html .='
	 <option
		value="'.$semaines[$i].'">'.$semaines[$i].'</option>';
		
	}  
}
echo $html;exit();
?>