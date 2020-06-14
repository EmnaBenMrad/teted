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
function updateImputRole($FieldValue, $FormName, $FieldName){
	$tab = explode("||",$FieldValue);
	$idImp = $tab[0];
	$role_id = $tab[1];
	$sql = "UPDATE imputation SET role = '".$role_id."' WHERE ID = '".$idImp."'";
	mysql_query($sql) or die("erreur");
	
}


function updateProj($FieldValue, $FormName, $FieldName){
	$tab = explode("||",$FieldValue);
	$idproj = $tab[0];
	$login = $tab[1];
	$value = $tab[2];
	$date_deb = $tab[3];
	$date_fin = $tab[4];
	$sql = "UPDATE imputation SET validation = '".$value."', Date_validation = '".date('Y-m-d')."', valide_par = '".$login."'  WHERE Project = '".$idproj."' AND (date between '$date_deb' and '$date_fin') ";
	mysql_query($sql) or die("erreur");
	
}
function update2($FieldValue, $FormName, $FieldName, $aImpRole ){
	$tab = explode("||",$FieldValue);
	$iduser = $tab[0];
	$idissue = $tab[1];
	$login = $tab[2];
	$value = $tab[3];
	$date_deb = $tab[4];
	$date_fin = $tab[5];
	$aImp = explode (":",$tab[6]);
	$sql = "UPDATE imputation SET validation = '".$value."', Date_validation = '".date('Y-m-d')."', valide_par = '".$login."' WHERE issue = '".$idissue."' AND user = '".$iduser."' AND (date between '$date_deb' and '$date_fin') ";
	mysql_query($sql) or die("erreur");
	$cnt = "";
	$cnt = count($aImp);
	if($cnt){
		for($i =0 ; $i<$cnt; $i++){
			$tmp = explode("**", $aImp[$i]);
			$tmpImp = $tmp[0];
			$tmpRole = $tmp[1];	
			$defRole = $tmp[2];
			if(!$tmpRole) {$roleId = $defRole; } else {$roleId = $tmpRole;} 
			$sql = "UPDATE imputation SET  role = '".$roleId."' WHERE ID = '".$tmpImp."'";
			mysql_query($sql) or die("erreur: ".$sql);
		}
	}
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
