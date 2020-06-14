<?php 
session_start();// On d�marre la session
$id = $_SESSION['id'];
$user = $_SESSION['id'];
include "connexion.php"; // la page de connexion � la base jira
include "fonctions.php"; 
$page = "rapport_imputation";

// $lg est le parametre de langue et c est egal 1 pour le fran�ais
$lg = 1;
$i = 0;
$j = 0; 

$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres = mysql_query($sql_parametres);

$aParms = mysql_fetch_array($query_parametres);
/**fin requete de parametrage***/

$img_path  = $aParms['img_path'];
$lien_bord = "inactif";
$lien_adm  = "inactif";
$user_cras = "inactif";
$lien_adm_gn = "inactif";
$valid_projetct = "desactif";

$annee   = $_POST['annee'];
$mois    = $_POST['mois'];
$proj    = $_POST['proj'];
$cat     = $_POST['cat'];
$semaine = $_POST['semaine'];

$aFerJour = jour_fer_format($mois, $annee);

$liste_group = membershipbase($id);
$nb_group    = count($liste_group);
$aTabgroup   = array();

for($i=0;$i<$nb_group;$i++){
	$aTabgroup[$i] = $liste_group[$i];
}

for($j=0;$j<$nb_group;$j++){
	$sGroup = $aTabgroup[$j];
	if(strtolower($sGroup)=="project-watcher"){ $watcher = "all"; }
	if(strtolower($sGroup)=="td-administrators"){ $valid_imputation = "actif"; $valid_projetct="actif";}
	if(strtolower($sGroup)=="bd-cp"){ $valid_imputation = "actif"; }
	if(strtolower($sGroup)=="super-admin"){ $lien_adm_gn = "actif"; }
}

$fin_mois    = fin_mois($mois, $annee); 
$long_mois   = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$date1       = $annee."-".$mois."-01"; 
$date2       = $annee."-".$mois."-".$fin_mois;

for($d=1; $d<=$fin_mois; $d++){
	if($d<10) { $date=$annee."-".$mois."-0".$d; }
	else { $date=$annee."-".$mois."-".$d; }
	$aTDate[$d] = $date;
}





$iUserId     = $_POST['idU'];
$sUsername   = $_POST['nameU'];

$iNbSemaine  = ceil((count($aTDate))/7);


if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
	$aTDate2 = getMonthByWeek($semaine, $annee);
	$aTDate = $aTDate2;
	$date1 = $aTDate[0];  $date2 = $aTDate[6];
}


$iNbSam =  getSamedi ($mois, $annee);//  les samedi de mois
$iNbDim =  getDimanche ($mois, $annee);// les dimanches de mois
    
   
if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
	$iNbSam = 1; $iNbDim = 1;
}

if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
	$iNbFer = nbre_jour_fer($aTDate[0], $aTDate[6]);//nombre de jours ferie
}else{
	$iNbFer = nbre_jour_fer($date1, $date2);//nombre de jours ferie
}
	
$iNbJourTrav = $fin_mois - ($iNbSam + $iNbDim + $iNbFer);
//Imputation
$aTimp  = GetDetailImputation_user($date1, $date2, $proj,$iUserId); 
$anFact = list_proj_non_facturable(); 

//Projects
$aResult = select_project_s($iUserId, $date1, $date2, $proj, $cat);
$nb_row = count($aResult);

$html = "";
$iTotJproj = 0; 
 
$groupBuUser = 0;
$valideD = 0;
$valideBu = 0;

$query = "SELECT 
		groupbase.ID as id
	FROM 
		propertystring, propertyentry, userbase,groupbase, membershipbase 
	WHERE 
		propertystring.id = propertyentry.id 
		AND propertyentry.property_key = 'fullname' 
		AND propertyentry.entity_id = userbase.id
		AND groupbase.groupname=membershipbase.GROUP_NAME
		AND membershipbase.USER_NAME = userbase.username
	    AND userbase.ID ='".$iUserId."' 
	    AND groupbase.ID in ( select id from groupbasestatus where groupbasestatus.status = 1)
	    ORDER BY propertyvalue ASC ";
$db_query = mysql_query ( $query );
$dd = 0;
$tab = array ();
	while ( $row = mysql_fetch_row ( $db_query ) ) {
	$tab [$dd] = $row [0];
	$dd ++;
}

if(is_array($tab) && sizeof($tab)>0){
	foreach ($tab as $k=>$v){
		if($valideBu <=0){
			$valideBu = isvalideGroup($v, $user);
		}
	}
	
}
	
 foreach ($aResult as $key => $vProj) {
 	
	$validV = false;
	$validD = false;
	$verifIsvalidateur = false;
	// si le projet est valide ou nn
	
	
	$verifValidateur = isvalideProject($vProj['id'],$id);
	$valideD = isvalideProject($vProj['id'], $user);
	
	
	if($valideD > 0 || $lien_adm_gn == "actif" || $valideBu > 0){
		$verifIsvalidateur = true;
	}
	
	
		
	$html .='<tr  class="style-rapport-v tr-a-'.$iUserId.' proj-'.$vProj['id'].'"   bgcolor="#FCF2F2"  onmouseover="this.bgColor='."'#C7C5C5'".'" onmouseout="this.bgColor='."'#FCF2F2'".'">';
	$html .='<td class="style-rapport" nowrap="nowrap"  width="180" style="border:1px solid #999999" ><img src="'.$img_path.'spacer.gif" height="9" width="8" hspace="2" /><img src="'.$img_path.'detail_plus.jpg" height="9" width="9" hspace="2" id="img_'.$iUserId.'_'.$vProj['id'].'" class="img_d_'.$iUserId.'" onclick="showDetailsP('.$vProj['id'].','.$iUserId.')"/><b>'.$vProj['pname'].'</b></td>';
	
	$iTotValid = 0;
	
	foreach($aTDate as $keyd=>$valued){ 
		
		$vDate  = $valued;
		$aT = explode('-', $vDate);
		$class  = "";
	 	$colorp = "style='background-color:green'";
		$iVerif = false;
	 	$img    = "";
	 	$aT = explode('-', $vDate);
		if(isset($aTimp[$sUsername][$vProj['pname']][$vDate])) { 
		    $imputation = $aTimp[$sUsername][$vProj['pname']][$vDate]; 
		    
			if($imputation > 0){
				$verifImputProj = isvalideIssueP($vProj['id'], $vDate, $iUserId);
				if($verifImputProj > 0){
					$colorp = "style='background-color:green;border:1px solid #999999'";
					$class="isvalide";
					$iVerif  = true;
					$iTotValid = $iTotValid + $imputation;
				 }else{
					$colorp = "style='background-color:#FEB75A;border:1px solid #999999'";
					$class="isnovalide";
					$iVerif  = true;
				 }
			}
			
		  }
		 
		 // jour ferier ou samedi ou dimanche
		if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
		 	$colorp = "";
			$class  = "";
		 }
		 $classVide ="style-rapport-v";
		 
		 if (!$iVerif &&  (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour)) ) {
			$img   = '*&nbsp;*&nbsp;*';
			$colorp = "style='background-color:#FFFFFF;color:#000000;border:1px solid #999999'";
			$class = "isvalide videImp";
			$classVide ="style-rapport";
		 }
		 
		 $html .= '<td  '.$colorp.' class="'.$classVide.' '.$class.' proj-'.$iUserId.'-'.$keyd.' proj-'.$vProj['id'].' proj-'.$vProj['id'].'-'.$iUserId.'-'.$keyd.'"';
		 
		  // jour ferier ou samedi ou dimanche
		 if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
		    $html .= "bgcolor='#9E9E9E'"; 
		 } 
		
		 $html .= 'width="150" align="center">';
		 $html .= $img;
		 if(isset($aTimp[$sUsername][$vProj['pname']][$vDate])) { 
		 	
				$imputation = $aTimp[$sUsername][$vProj['pname']][$vDate]; 
				
				if(($imputation)>0){
					$html .=$imputation;	
				}else{
					$html.="&nbsp;";
				}
				$iTotJproj= $iTotJproj + $imputation;  
		 } else { 
		    	$html.="&nbsp;";
		 }  
		 $html.= '</td>';
	
	 }
	 
	 $iJnonValide = getTotNonValide($iUserId, $date1, $date2, $vProj['id']);
	 $iTotValid = getTotValide($iUserId, $date1, $date2, $vProj['id']);
	 
	 if($iJnonValide == 0){ 
		$colorTd = 'style="background-color:green;border:1px solid #999999"';
		$sClassp ="";
	 }else{
		$colorTd = 'style="background-color:#FEB75A;border:1px solid #999999"';
		$sClassp =" isnovalide";
	 }
	 
	 $html.='<td class="style-rapport-v proj-'.$vProj['id'].' projTTT-'.$vProj['id'].'-'.$iUserId.'"   align="center" width="30" style="background-color:green;border:1px solid #999999">'.$iTotValid.'</td>
	         <td class="style-rapport-v proj-user-'.$iUserId.' proj-u-'.$vProj['id'].$sClassp.' projTTTT-'.$vProj['id'].'-'.$iUserId.'"   align="center" width="30" '.$colorTd.'>';
	
	$display = '';
	if($iTotValid == 0){
		$display ="display:none;";
	}
   if($iJnonValide == 0 || ($verifIsvalidateur === false)){
   		
	  	if($verifIsvalidateur === true){
	  		if(is_array($anFact) && in_array($vProj['id'], $anFact)){
				 $html.='<table align="center"><tr><td class="style-rapport-v njr" style="border:0px transparent;">'.  $iJnonValide.'</td><td align="center" style="border:0px transparent;"><img src="../images/devalid.png" class="nvalideIs" style="cursor:pointer; margin-right:5px;'.$display.'" title="Annuler la validation" onclick="update_all_projNfactD(\''.$vProj['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"/><img src="../images/valid.png" style="cursor:pointer;margin-right:5px; display:none" title="Non facturable" onclick="update_all_projNfact(\''.$vProj['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)" class="valideIs"/></td></tr></table>';
			}else{
				 $html.='<div style="text-align:right;padding-top:5px"><input type="checkbox" title="Facturable" style="margin-top:-5px;float:right;display:none"></div> <br/><div  style="text-align:center" class="njr">'.  $iJnonValide.'</div><div  style="text-align:right"><img onclick="update_all_projD(\''.$vProj['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"  title="Annuler la validation" src="../images/devalid.png" class="nvalideIs" style="cursor:pointer; margin-right:5px;'.$display.'"> <img onclick="update_all_proj(\''.$vProj['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px;display:none" class="valideIs"></div>';
			}
	  	}else{
			$html.='<div  style="text-align:center">'.  $iJnonValide.'</div>';
	  	}
	  }
	 else{
		
		if(is_array($anFact) && in_array($vProj['id'], $anFact)){
			 $html.='<table align="center"><tr><td class="style-rapport-v njr" style="border:0px transparent;">'.  $iJnonValide.'</td><td align="center" style="border:0px transparent;"><img src="../images/devalid.png" class="nvalideIs" style="cursor:pointer; margin-right:5px;'.$display.'" title="Annuler la validation" onclick="update_all_projNfactD(\''.$vProj['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"/><img src="../images/valid.png" style="cursor:pointer;margin-right:5px" title="Non facturable" onclick="update_all_projNfact(\''.$vProj['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)" class="valideIs"/></td></tr></table>';
		}else{
			 $html.='<div style="text-align:right;padding-top:5px"><input type="checkbox" title="Facturable" style="margin-top:-5px;float:right"></div> <br/><div  style="text-align:center" class="njr">'.  $iJnonValide.'</div><div  style="text-align:right"><img onclick="update_all_projD(\''.$vProj['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"  title="Annuler la validation" src="../images/devalid.png" class="nvalideIs" style="cursor:pointer; margin-right:5px;'.$display.'"> <img onclick="update_all_proj(\''.$vProj['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this)"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px" class="valideIs"></div>';
		}
	 }
	 
	 $html.= '</td>
			  <td class="style-rapport-v" align="center" width="30"  style="background-color:green">'.$iTotJproj.'</td>
			  <td class="style-rapport videImp" align="center" width="30" style="background-color:#FFFFFF;color:#000000">*&nbsp;*&nbsp;*</td>';
	
	 $html .='</tr>';
	
	 // Details imputations projet
	 $aDetails = GetDetailImputation_user_details($date1, $date2, $vProj['id'],$iUserId,$sUsername);
	 
	 // Details projets
	 $aDetails1 = Getissueprojet_user($date1, $date2, $vProj['id'],$iUserId,$sUsername);
	 
	 foreach ($aDetails1 as $key2 => $v2) {
	    $html .='<tr  class="style-rapport-v tr-d-'.$iUserId.'-'.$vProj['id'].' tr-details-p"  bgcolor="#FCF2F2"  onmouseover="this.bgColor='."'#C7C5C5'".'" onmouseout="this.bgColor='."'#FCF2F2'".'"  style="display:none">';
		$html .='<td class="style-rapport" nowrap="nowrap"  width="180" style="border:1px solid #999999"><div style="float:left;width:200px;white-space:normal;padding-left:40px;"><img src="../images/right.gif" height="7" width="7" hspace="2" /><b>'.$v2['tache'].'</b></div></td>';
		$iTotVissue = 0;
		$iTotIssue  = 0;
		
		foreach($aTDate as $keyd=>$valued){ 
			$vDate   = $valued;
			$aT = explode('-', $vDate);
			$color   = "style='background-color:green;border:1px solid #999999'";
			$iVerif  = false;
			$img     = "";
			$checked = "";
			$isV = 0;
			$affiche = false;
			$vBy ="";
			
			if(isset($aDetails[$sUsername][$vProj['pname']][$v2['id']]['tmp'][$vDate])) { 
			 	 $idi        = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['id'][$vDate];
				 $imputation = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['tmp'][$vDate];
				 $validation = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['validation'][$vDate];
				 $valider_par = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['valider_par'][$vDate];
				 
				
				 if(intval($valider_par) > 0 ){
				 	$vBy = "Valider par : ".nom_prenom_user(intval($valider_par));
				 }
				 
				 if( $imputation>0){
					 $verifImput = isvalideIssue_f($idi);
					 if($verifImput > 0){
					 	$affiche = true;
					 	$isV = 1;
						$displayImpv = 'display:none;';
						$displayImpv2 = '';
						$color = "style='background-color:green;border:1px solid #999999'";
						$class="isvalide";
						if($validation == 1){
							$checked="checked='checked'";
						}else{
							$checked="";
						}
						
						$iTotVissue = $iTotVissue + $imputation ;
						$iVerif  = true;
					 }else{
					 	$isV = 0;
						$displayImpv = '';
						$displayImpv2 = 'display:none;';
					 	$affiche = false;
						$color = "style='background-color:#FEB75A;border:1px solid #999999'";
						$checked="";
						$class="isnovalide";
						$iVerif  = true;
					 }
				}
			}
			
		    if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
		 	  $color = "";
			  $class = "";
		    }	
		    
		     $classVide ="style-rapport-v";
			 if (!$iVerif &&  (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour))  ) {
			  $class = "isvalide videImp";
			  $color = "style='background-color:#FFFFFF;color:#000000;border:1px solid #999999'";
			  $img   = '*&nbsp;*&nbsp;*';
			   $classVide ="style-rapport";
		    }
		 
			$html .= '<td  title="'.$vBy.'" '.$color.' class="'.$classVide.' '.$class.' issue-'.$v2['id'].' proj-'.$vProj['id'].' issue-proj-'.$vProj['id'].'-user-'.$iUserId.' issue-'.$vProj['id'].'-'.$iUserId.'-'.$keyd.' issue-'.$v2['id'].'-'.$iUserId.'"';
			
			// jour ferie ou samedi ou dimanche
			 if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
			    $html .= "bgcolor='#9E9E9E'"; 
			} 
			
			$html.= 'width="10" align="center">';
		    $html.= $img;
		    
			if(isset($aDetails[$sUsername][$vProj['pname']][$v2['id']]['tmp'][$vDate])) { 
				
			    $imputation = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['tmp'][$vDate];
				$iTotIssue  = $iTotIssue + $imputation ;
			    $idi        = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['id'][$vDate];
			    $idproj     = $aDetails[$sUsername][$vProj['pname']][$v2['id']]['projid'][$vDate];
			     
			    if(($imputation)>0){
					if(($verifIsvalidateur === true || $lien_adm_gn == "actif")){
						 if(is_array($anFact) && in_array($idproj, $anFact)){
							 $html.='<table align="center"><tr><td class="style-rapport-v" style="border:0px transparent;">'.  $imputation.'</td><td align="center" style="border:0px transparent;"><img src="../images/devalid.png" class="nvalideIs" style="cursor:pointer;margin-right:5px;margin-bottom:5px;'.$displayImpv2.'" title="Annuler le validation" onclick="update_imp_nofactD(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\', \''.$vDate.'\',this, \''.$keyd.'\',\''.$vProj['id'].'\')"/> <img src="../images/valid.png" style="cursor:pointer;margin-right:5px;'.$displayImpv.'" title="Non facturable" onclick="update_imp_nofact(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\', \''.$vDate.'\',this, \''.$keyd.'\',\''.$vProj['id'].'\')" class="valideIs"/></td></tr></table>';
						 }else{
							 $html.='<div style="text-align:right;padding-top:5px"><input onclick="update_imp_check(\''.$v2['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\', \''.$vDate.'\',this, \''.$keyd.'\',\''.$vProj['id'].'\',\''.$isV.'\')" type="checkbox" '.$checked.' title="Facturable" style="margin-top:-5px;float:right;"></div> <br/><div  style="text-align:center">'.  $imputation.'</div><div  style="text-align:right"><img onclick="update_impD(\''.$v2['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\', \''.$vDate.'\',this, \''.$keyd.'\',\''.$vProj['id'].'\',\''.$isV.'\')"  title="Annuler la validation" src="../images/devalid.png" class="nvalideIs" style="cursor:pointer;margin-right:5px;margin-bottom:5px;'.$displayImpv2.'"><img onclick="update_imp(\''.$v2['id'].'\',\''.$id.'\',\'\',\''.$iUserId.'\', \''.$vDate.'\',this, \''.$keyd.'\',\''.$vProj['id'].'\',\''.$isV.'\')"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px;'.$displayImpv.'" class="valideIs"></div>';
						 }
					}else{
						 $html.='<table align="center"><tr><td class="style-rapport-v" style="border:0px transparent;">'.  $imputation.'</td></tr></table>';
					}
				}else{
					$html.="&nbsp;"; 
				}
				
			    $iTotJproj = $iTotJproj + $imputation;  
			} else {  
				$html.="&nbsp;"; 
			} 
			 
			$html.= '</td>';
			 
		  }
		  
		  $iJissueNvalide = $iTotIssue - $iTotVissue;
		  
		  if($iJissueNvalide  == 0){ 
		  	  $colorTd2 = 'style="background-color:green;border:1px solid #999999"'; 
		  	  $class    = "isvalide";
		  }else{
		  	  $colorTd2 = 'style="background-color:#FEB75A;border:1px solid #999999"'; 
		  	  $class    = "isnovalide";
		  }
		
		  $html.='<td class="style-rapport-v issueTT-'.$v2['id'].'-'.$iUserId.'"   align="center" width="30" style="background-color:green;border:1px solid #999999">'.$iTotVissue.'</td>';
		  
		  $displayIssue = '';
		  if($iTotVissue == 0){
				$displayIssue ="display:none;";
		  }
		  
		   if($iJissueNvalide == 0 || ($verifIsvalidateur === false)){
		  	if($verifIsvalidateur === true){
		  		if(is_array($anFact) && in_array($vProj['id'], $anFact)){
					$html.='<td class="style-rapport-v proj-'.$vProj['id'].' issueT-'.$v2['id'].'-'.$iUserId.' issue-proj-'.$vProj['id'].' '.$class.'" '.$colorTd2.' align="center" ><table align="center"><tr><td class="style-rapport-v njr" style="border:0px transparent;">'.  $iJissueNvalide.'</td><td align="center" style="border:0px transparent;"><img onclick="updateIssueNfactD(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Annuler la validation" src="../images/devalid.png" style="cursor:pointer;margin-right:5px;'.$displayIssue.'" class="nvalideIs"><img onclick="updateIssueNfact(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px; display:none" class="valideIs"></td></tr></table></td>';
				}else{
					$html.='<td class="style-rapport-v proj-'.$vProj['id'].' issueT-'.$v2['id'].'-'.$iUserId.'  issue-proj-'.$vProj['id'].' '.$class.'" '.$colorTd2.' align="center" ><div style="text-align:right;padding-top:5px"><input type="checkbox" title="Facturable" style="margin-top:-5px;float:right;display:none"></div> <br/><div  style="text-align:center" class="njr">'.  $iJissueNvalide.'</div><div  style="text-align:right"><img onclick="updateIssueD(\''.$v2['id'].'\',\''.$id.'\',1,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Annuler la validation" src="../images/devalid.png" style="cursor:pointer;margin-right:5px;'.$displayIssue.'" class="nvalideIs"><img onclick="updateIssue(\''.$v2['id'].'\',\''.$id.'\',1,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px; display:none" class="valideIs"></div></td>';
				}
		  	}else{
				$html.='<td class="style-rapport-v proj-'.$vProj['id'].' issue-proj-'.$vProj['id'].' '.$class.'" '.$colorTd2.' align="center"><div  style="text-align:center">'.  $iJissueNvalide.'</div></td>';
		  	}
		  }else{
			
			if(is_array($anFact) && in_array($vProj['id'], $anFact)){
				$html.='<td class="style-rapport-v proj-'.$vProj['id'].' issueT-'.$v2['id'].'-'.$iUserId.' issue-proj-'.$vProj['id'].' '.$class.'" '.$colorTd2.' align="center" ><table align="center"><tr><td class="style-rapport-v njr" style="border:0px transparent;">'.  $iJissueNvalide.'</td><td align="center" style="border:0px transparent;"><img onclick="updateIssueNfactD(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Annuler la validation" src="../images/devalid.png" style="cursor:pointer;margin-right:5px;'.$displayIssue.'" class="nvalideIs"><img onclick="updateIssueNfact(\''.$v2['id'].'\',\''.$id.'\',2,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px" class="valideIs"></td></tr></table></td>';
			}else{
				$html.='<td class="style-rapport-v proj-'.$vProj['id'].' issueT-'.$v2['id'].'-'.$iUserId.'  issue-proj-'.$vProj['id'].' '.$class.'" '.$colorTd2.' align="center" ><div style="text-align:right;padding-top:5px"><input type="checkbox" title="Facturable" style="margin-top:-5px;float:right"></div> <br/><div  style="text-align:center" class="njr">'.  $iJissueNvalide.'</div><div  style="text-align:right"><img onclick="updateIssueD(\''.$v2['id'].'\',\''.$id.'\',1,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Annuler la validation" src="../images/devalid.png" style="cursor:pointer;margin-right:5px;'.$displayIssue.'" class="nvalideIs"><img onclick="updateIssue(\''.$v2['id'].'\',\''.$id.'\',1,\''.$iUserId.'\',\''.$date1.'\',\''.$date2.'\',this,\''.$vProj['id'].'\')"  title="Valider" src="../images/valid.png" style="cursor:pointer;margin-right:5px" class="valideIs"></div></td>';
			}
		  }
		  
		  $html.='<td class="style-rapport-v"    align="center" width="30" style="background-color:green;border:1px solid #999999">'.$iTotIssue.'</td>
		          <td class="style-rapport videImp"   align="center" width="30" style="background-color:#FFFFFF;color:#000000;border:1px solid #999999">*&nbsp;*&nbsp;*</td>';
		  $html .='</tr>'; 
	}
	
 	$iTotJproj=0; 
} 
 echo $html;exit;