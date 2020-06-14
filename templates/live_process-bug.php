<?php session_start();
include "connexion.php"; 
include "fonctions.php";
function update($FieldValue, $FormName, $FieldName){
	$tab = explode("||",$FieldValue);
	$idImp = $tab[0];
	$login = $tab[1];
	$value = $tab[2];
	$sql = "UPDATE imputation SET validation = '".$value."', Date_validation = '".date('Y-m-d')."', valide_par='".$login."' WHERE ID = '".$idImp."'";
	mysql_query($sql) or die("erreur");
	$res = $idImp.":".nom_prenom_user($login).":".format_date2(date('Y-m-d'));
	//$res = $FieldValue;
	echo  $res;
}
function update2($FieldValue, $FormName, $FieldName){
	$tab = explode("||",$FieldValue);
	$iduser = $tab[0];
	$idissue = $tab[1];
	$login = $tab[2];
	$value = $tab[3];
	$sql = "UPDATE imputation SET validation = '".$value."', Date_validation = '".date('Y-m-d')."', valide_par = '".$login."' WHERE issue = '".$idissue."' AND user = '".$iduser."'";
	mysql_query($sql) or die("erreur");
   //$res = $idissue.":".nom_prenom_user($login).":".format_date2(date('Y-m-d'));
   $req = "SELECT ID FROM imputation WHERE issue = ".$idissue." AND user = ".$iduser."";
   $query = mysql_query($req);
   $nb = mysql_num_rows($query);
   $res = "";
   while($row = mysql_fetch_row($query))
   {
   $res.=$row[0]."|".nom_prenom_user($login)."|";
   $res.=format_date2(date('Y-m-d'))."|";
   }
   echo $res;
}

function affiche($FieldValue){

	$sql = "SELECT valide_par FROM imputation WHERE ID = $FieldValue";
	$query = mysql_query($sql) or die("erreur");
	$nb = mysql_num_rows($query);
	if($nb==0) { $nom_valid = ''; }
	else { $nom_valid = mysql_result($query, 0, 'valide_par'); }
	echo $nom_valid;
	$res_ = "Error:$FormName:$FieldName:#FFFFFF:ggggggg";
	$res .= $nom_valid;
}

function update_imputation2($FieldValue, $FormName, $FieldName)
{
	$tab = explode("||",$FieldValue); 	
	$idIMP = $tab[0];
	$ancienval = $tab[1];
	$issueid = $tab[2];
	$userid = $tab[3];
	$date = $tab[4];
	$value = $tab[5];
	//echo $FieldValue;
$sql = "SELECT sum(imputation) 
		FROM imputation
		WHERE user = ".$userid."
		AND issue = ".$issueid."
		AND Date = ".$date."";
		//echo $sql;
}

if(isset($_GET) && count($_GET) > 0 && count($_GET) < 5) {
	$_GET['fct']($_GET['val'], $_GET['FormName'], $_GET['FieldName']);
}

if(isset($_GET) && count($_GET) > 4) {
	$_GET['fct']($_GET['val'],$_GET['val1'], $_GET['FormName'], $_GET['FieldName'], $_GET['FieldName2']);
}

?>
