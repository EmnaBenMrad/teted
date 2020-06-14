<?php 
include("../connexion.php");
include("../fonctions.php");

$html ='';
if(isset($_POST['semaine']) && $_POST['semaine'] !='' && isset($_POST['annee']) && $_POST['annee'] !=''){
	$aRes = getMonthByWeek($_POST['semaine'], $_POST['annee']);
	if(isset($aRes[0])){
		$addS1 = explode('-', $aRes[0]);
		if(sizeof($addS1)>2){
				echo intval($addS1[1]);exit();
		}
	}
}
echo $html;exit();
?>