<?php
include("../connexion.php");
include("../fonctions.php");
$html = '<OPTION value="">Collaborateur</option>';
if(isset($_POST) && !empty($_POST)){
	$group = $_POST['group'];
	$proj = $_POST['proj'];
	$aListP = array('0'=>$proj);
	$liste ="";
	$aCollabs = liste_group_user_bu_proj($group,$aListP);
	if(sizeof($aCollabs) > 0){
		foreach ($aCollabs as $sUsename){
			if($liste == ""){
				$liste = "'".$sUsename."'";
			}else{
				$liste .=",'".$sUsename."'";
			}
		}
	}
		
	$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
		FROM propertystring, propertyentry, userbase
		WHERE propertystring.id = propertyentry.id
		AND propertyentry.property_key = 'fullname'
		AND propertyentry.entity_id = userbase.id ";
		
		if(sizeof($aCollabs) > 0 && $liste !=""){
			$query1 .=" AND userbase.username in (".$liste.") ";
		}else{
			$query1 .=" AND membershipbase.GROUP_NAME = 'BD-users' ";
		}
		
		$query1 .=" AND userbase.id not in (select user from userbasestatus where status =0 ) 
		ORDER BY propertyvalue ASC ";
		
		
	
}

$result1 = mysql_query($query1);

while($row1 = mysql_fetch_array($result1)){ 
		   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; 
$html .='<OPTION value="'.$row1[0].'" >'.$nom.'</OPTION>';
 }


echo $html;exit;