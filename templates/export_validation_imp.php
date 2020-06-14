<?php
	 session_start();// On demarre la session
	 
	 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
		session_destroy();  // On detruit la session
		header("Location: login.php");
	 }
	
	 include "connexion.php"; // la page de connexion a la base jira
	 include "fonctions.php";

	set_time_limit(2000000);

	$id    = $_SESSION['id'];
	$user  = $_SESSION['id'];
	$login = $_SESSION['login'];

	$page = "export_valid_imputation";
	

	$lg = 1;
	$sql_parametres   = "select * from parametres where langue=".$lg;
	$query_parametres = mysql_query($sql_parametres);
	$tab_parametres   = mysql_fetch_array($query_parametres);
	
	/**fin requete de parametrage***/
	$img_path = $tab_parametres['img_path'];
	$i = 0;
	$j = 0; 
	$lien_bord = "inactif";
	$lien_adm  = "inactif";
	$user_cras = "inactif";
	
	if(isset($_SESSION['liste_group']) && !empty($_SESSION['liste_group'])){
		$liste_group = $_SESSION['liste_group'];
	}else{
		$liste_group = membershipbase($user);
	}
	
	
	$nb_group = count($liste_group);
	$tab_group=array();
	
	for($i=0;$i<$nb_group;$i++){
		$tab_group[$i] = $liste_group[$i];
	}
	
	for($j=0;$j<$nb_group;$j++){
		$list_group = $tab_group[$j];
		if(strtolower($list_group)=="td-tdbusers"){ $lien_bord = "actif"; }
		if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
		if(strtolower($list_group)=="bd-cra"){ $user_cras = "actif"; }
		if(strtolower($list_group)=="project-watcher"){ $watcher = "all"; }
		if(strtolower($list_group)=="td-administrators"){ $valid_imputation = "actif"; $valid_projetct="actif";}
		if(strtolower($list_group)=="bd-cp"){ $valid_imputation = "actif"; }
	}

	if(isset($_REQUEST['y'])) { $annee=$_REQUEST['y']; } else { $annee=date('Y'); }
	if(isset($_REQUEST['mm'])) { $mois=$_REQUEST['mm']; } else { $mois=date('m'); }
	if(isset($_REQUEST['projss'])) { $proj=$_REQUEST['projss']; }  else { $proj=""; }
	if(isset($_REQUEST['collab'])) { $collab=$_REQUEST['collab']; }  else { $collab=""; }
	if(isset($_REQUEST['gb'])) { $gb=$_REQUEST['gb']; }  else { $gb=""; }
	if(isset($_REQUEST['cat'])) { $cat=$_REQUEST['cat']; }  else { $cat=""; }
	if(isset($_REQUEST['semaine'])) { $semaine=$_REQUEST['semaine']; }  else { $semaine=""; }
	if(isset($_REQUEST['pg'])) { $pg=$_REQUEST['pg']; }  else { $pg=1; }

	
	
	header('Content-disposition: attachement; filename="export_validation_'.$annee.'_'.$mois.'.xls"');
	header("Content-Type: application/x-msexcel");
	header('Expires: 0');

	$fin_mois  = fin_mois($mois, $annee); 
	$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
	$date1     = $annee."-".$mois."-01";  $date2 = $annee."-".$mois."-".$fin_mois;
	
	for($d=1; $d<=$fin_mois; $d++){
		if($d<10) { 
			$date=$annee."-".$mois."-0".$d; 
		}else { 
			$date=$annee."-".$mois."-".$d; 
		}
		$aTDate[$d] = $date;
	}

	// nobre de semaine par mois
	$iNbSemaine = ceil((count($aTDate))/7);


	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$aTDate2 = getMonthByWeek($semaine, $annee);
		$aTDate = $aTDate2;
		$date1 = $aTDate[0];  $date2 = $aTDate[6];
	}


	// liste imputation
	$listImp     = GetImputationUser($date1, $date2, $proj);
	//jours ferie
	$aFerJour = jour_fer_format($mois, $annee);
	

	while ($row = mysql_fetch_row($listImp) ){
		$aTSomImp[$row[1]][$row[2]]= $row[0];
	}


	 if((isset($watcher)) and ($watcher=="all")){
		$query = "SELECT 
						DISTINCT(project.ID),
						project.pname 
				  FROM project 
				  WHERE 
						 project.ID not in(SELECT na.source_node_id 
										   FROM nodeassociation na
										   WHERE 
												na.source_node_entity = 'Project'
												and na.sink_node_entity = 'PermissionScheme'
												and na.association_type = 'ProjectScheme'
												and na.sink_node_id = 10220
												) 
				  ORDER BY project.pname";
				  
					
	 }else {	
		$tab_proj = gestion_role($id, $login);
		$query = "SELECT  distinct(project.id), project.pname
				  FROM  project
				  WHERE  project.ID IN ".$tab_proj;
	
	
		$query.=" ORDER BY project.pname";
	}

	
	$nb_sam =  getSamedi ($mois, $annee);//  les samedi de mois
	$nb_dim =  getDimanche ($mois, $annee);// les dimanches de mois
	    
	   
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$nb_sam = 1; $nb_dim = 1;
	}
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$nb_f = nbre_jour_fer($aTDate[0], $aTDate[6]);//nombre de jours ferie
	}else{
		$nb_f = nbre_jour_fer($date1, $date2);//nombre de jours ferie
	}


	$sLisBdUsers = liste1('BD-users');//Liste utilisateurs B&D Tunis
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$listimp = liste2($aTDate[$deb], $aTDate[$fin]);//Liste utilisateurs B&D Tunis qui ont imputes
	}else{
		$listimp = liste2($date1, $date2);//Liste utilisateurs B&D Tunis qui ont imputes
	}
	
	$sListBdMaroc = liste1('BD-Maroc');//Liste utilisateurs B&D Maroc
	$sListByBU    = "";

	if($gb != ""){
		// liste group BU (poles)
		$sListByBU = liste_group_user_bu($gb);
	}
	// List user
	$list   = list_userbase_fu($proj, $cat,$collab, $pg, $sLisBdUsers, $listimp ,$sListBdMaroc, $sListByBU, $gb);
	
	// nombre de page (pagination)
	$nbpage = nbpagefu($proj, $cat,"", $sLisBdUsers, $listimp ,$sListBdMaroc, $sListByBU, $gb); 
	
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$nb_jour_trav = 7 - ( 2 + $nb_f ); // Nombre de jours de travail
	}else{
		$nb_jour_trav = $fin_mois-( $nb_sam + $nb_dim+$nb_f );// Nombre de jours de travail
	}
	
	$html = '<html>';

	$html .='<body><table id="myTable" cellpadding="1" cellspacing="1" class="table"';

					 if( $semaine != "" ) {
					 	$html .='style="width:60%; margin-left:150px" ><thead><th axis="string" width="580px" class="th" bgcolor="#EEEEEE" colspan="6">'.$tab_parametres['collab'].'</th>';
							 
					 	foreach($aTDate as $key=>$value) { 
					 		$html .='<th axis="number" class="th2" style="width:150px;text-align:center" bgcolor="#EEEEEE" ><b>'.$key.'</th>';
					 	}
					 	
						$html .='<th style="width:150px;text-align:center" bgcolor="#EEEEEE" class="th"  axis="number">Valide</th><th style="width:150px;text-align:center" class="th"  bgcolor="#EEEEEE" axis="number">Non valide</th><th style="width:150px;text-align:center" class="th"  bgcolor="#EEEEEE" axis="number">Total</th><th style="width:150px;text-align:center" class="th"  bgcolor="#EEEEEE" axis="number">Diff</th><tbody>';
						
				   		$cmt = 1;  
					   		
					   		foreach ($list as $key => $vUser) { 
							
					   			$result = select_project_f($vUser['id'], $date1, $date2, $proj, $cat);
								$nb_row = count($result);
						
								$html .='<tr bgcolor="#F8F7F7" ><td width="580px" colspan="6" style="border:1px solid #999999"><div style="min-width:250px">';
									
							    $html .='&nbsp;<b>'.$vUser['username'].'</b></div>';
								
								$html .='</td>';
								
								$cmp         = 1; 
								$Total       = 0;
								$TotalValide = 0;
								
								foreach ($aTDate as $key => $vDate) {  
									
									$ImpT = 0;
									if (isset($aTSomImp[$vUser['username']][$vDate])) {
										$ImpT = $aTSomImp[$vUser['username']][$vDate];
									}
									
									$auj = str_replace('-','' , date('Y-d-m'));
									$dd  = str_replace('-','' , $vDate);
									$colorp ="";
									 
									if($ImpT >=1){
										  
										  $verifAll = isvalideIssueAll($vDate,$vUser['id'] );
										 
										  if($verifAll>0 ){
											  $colorp      = "bgcolor='#FEB75A'";
											  $class       = "isnovalide";
										  }else{
											  $colorp      = "bgcolor='green'";
											  $class       = "isvalide";
											  $TotalValide = $TotalValide + $ImpT;
										  }
										  
									}elseif($ImpT < 1 && $ImpT > 0){
										 
										  $verifAll = isvalideIssueAllNonImp($vDate,$vUser['id'] );
										  if($verifAll>0 ){
											  $colorp = "bgcolor='#FEB75A'";
											  $class  = "isnovalide";
										  }else{
											$colorp      = "bgcolor='green'";
											$class       = "isvalide";
											$TotalValide = $TotalValide + $ImpT;
										  }
									}
									
									if(($ImpT) < 1){
										if(intval($auj) >= intval($dd) && (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour))){
											$colorp = "bgcolor='red'";
											$class  = "";
										}
										if($ImpT >0){
											 $verifnAll = isnvalideIssueAll($vDate,$vUser['id'] );// tache valide
											 if($verifnAll == 0 ){
												$TotalValide = $TotalValide + $ImpT;
											 }
										}
									} 
									
									// samedi ou dimanche ou jour ferie
									if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
										$colorp = "bgcolor='#9E9E9E'"; 
										$class = "";
									} 
								
		
									$html .='<td '.$colorp.' style="border:1px solid #999999" width="150" align="center" class="'.$class.' userT-'.$vUser['id'].'-'.$key.'"><span class="style-rapport-v">';
									
											 if (isset($aTSomImp[$vUser['username']][$vDate])) {
												 $Imp = $aTSomImp[$vUser['username']][$vDate]; $Total = $Total +  $Imp; 
												 if(($Imp)>0){ $html .=$Imp;}else{$html .="&nbsp;"; } 
											  } else {
												 $html .="&nbsp;"; 
											  }  
									 $html .='</span></td>';
									 $cmp++;
								}
									
									$delta = $Total - $nb_jour_trav;
									
									if( $delta<0 ) { $color="#FF0000"; } else { $color="#006600"; }
									
									$diffV = $Total - $TotalValide;
									
									$html .='<td width="150" class="style-rapport-v"  style="border:1px solid #999999" align="center" width="30" bgcolor="green">'.$TotalValide.'</td><td width="150" class="style-rapport-v" align="center" width="30" ';
									
									 if($diffV == 0){ 
									 	$html .='bgcolor="green"'; 
									 }else{ 
									 	$html .='bgcolor="#FEB75A"'; 
									 } 
									 
									 $html .='>'.($diffV) .'</td><td  width="150" align="center" style="border:1px solid #999999" ';
									 
									 if($delta == 0 && $diffV == 0){ 
									 	$html .='bgcolor="green"';  
									 }else{ 
									 	$html .='bgcolor="green"'; 
									 }
									 
									 $html .='><span class="style-rapport-v" >'.$Total.'</span></td><td  width="150" align="center" width="30" style="border:1px solid #999999"';
									 
									 if(isset($color)) {  
									 	$html .=' bgcolor="'.$color.'"'; 
									 } 
									 
									 $html .='><span class="style-rapport-v" style="color:#FFFFFF">'.$delta.'</span></td></tr>';


						  			$cmt++; 
						  
						  
									//Imputation
									$aTimp   = GetDetailImputation_user($date1, $date2, $proj,$vUser['id']); 
									
									//list projets non facturable
									$anFact  = list_proj_non_facturable();
		
									//Projects
									$aResult = select_project_s($vUser['id'], $date1, $date2, $proj, $cat);
									$nb_row  = count($aResult);
		
									
									$iTotJproj=0; 
									 
									// parcours des projets
									 foreach ($aResult as $key => $vProj) {
										
											
										$html .='<tr  class="style-rapport-v"   bgcolor="#FCF2F2"  >';
										$html .='<td class="style-rapport" nowrap="nowrap"  width="180" colspan="6" style="border:1px solid #999999"><b>----->'.$vProj['pname'].'</b></td>';
										
										$iTotValid = 0;
										
										// parcours des dates
										foreach($aTDate as $keyd=>$valued){ 
											
											$vDate  = $valued;
											$class  = "";
											$colorp = "bgcolor='green'";
											$iVerif = false;
											$img    = "";
											
											if(isset($aTimp[$vUser['username']][$vProj['pname']][$vDate])) { 
												$imputation = $aTimp[$vUser['username']][$vProj['pname']][$vDate]; 
												
												if($imputation > 0){
													// valide issue (tâche)
													$verifImputProj = isvalideIssueP($vProj['id'], $vDate, $vUser['id']);
													
													if($verifImputProj > 0){
														$colorp    = "bgcolor='green'";
														$class     = "isvalide";
														$iVerif    = true;
														$iTotValid = $iTotValid + $imputation;
													 }else{
														$colorp  = "bgcolor='#FEB75A'";
														$class   = "isnovalide";
														$iVerif  = true;
													 }
												}
												
											  }
											 
											 // jour ferier ou samedi ou dimanche
											if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
												$colorp ="";
												$class="";
											 }
											 $classVide = "style-rapport-v";
											 // ni jour ferier ni samedi ni dimanche	
											 if(!$iVerif && (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour))){
												$img = '*&nbsp;*&nbsp;*';
												$colorp  = "bgcolor='white'";
												$class="isvalide videImp";
												$classVide = "style-rapport";
											 }
											 
											 $html .= '<td  '.$colorp.' class="'.$classVide.'" ';
											 
											  // jour ferier ou samedi ou dimanche
											if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
												$html .= "bgcolor='#9E9E9E'  ".$colorp; 
											 } 
											
											 $html.= 'width="150" align="center">';
											 $html .= $img;
											 if(isset($aTimp[$vUser['username']][$vProj['pname']][$vDate])) { 
												
													$imputation = $aTimp[$vUser['username']][$vProj['pname']][$vDate]; 
													
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
										 
										 $iJnonValide = getTotNonValide($vUser['id'], $date1, $date2, $vProj['id']);
										 $iTotValid = getTotValide($vUser['id'], $date1, $date2, $vProj['id']);
										 
										 if($iJnonValide == 0){ 
											$colorTd = 'bgcolor="green;"';
											$sClassp = "";
										 }else{
											$colorTd = 'bgcolor="#FEB75A"';
											$sClassp = " isnovalide";
										 }
										 
										 $html.='<td class="style-rapport-v"   align="center" width="30" bgcolor="green" style="border:1px solid #999999">'.$iTotValid.'</td>
												 <td class="style-rapport-v"   align="center" width="30" '.$colorTd.' style="border:1px solid #999999">';
										$html.='<div  style="text-align:center">'.  $iJnonValide.'</div>';
										 
										 $html.= '</td>
												  <td class="style-rapport-v" align="center" width="30"  bgcolor="green" style="border:1px solid #999999">'.$iTotJproj.'</td>
												  <td class="style-rapport videImp" align="center" width="30" bgcolor="white" style="border:1px solid #999999">*&nbsp;*&nbsp;*</td>';
										
										 $html .='</tr>';
										
										 // Details imputations projet
										 $aDetails = GetDetailImputation_user_details($date1, $date2, $vProj['id'],$vUser['id'],$vUser['username']);
										 
										 // Details projets
										 $aDetails1 = Getissueprojet_user($date1, $date2, $vProj['id'],$vUser['id'],$vUser['username']);
										 
										 foreach ($aDetails1 as $key2 => $v2) {
											$html .='<tr  class="style-rapport-v tr-d-'.$vUser['id'].'-'.$vProj['id'].' tr-details-p"  bgcolor="#FCF2F2" >';
											$html .='<td class="style-rapport" nowrap="nowrap"  width="180" colspan="6" style="border:1px solid #999999"><div style="float:left;width:200px;white-space:normal;padding-left:40px;"><b>------------>'.$v2['tache'].'</b></div></td>';
											$iTotVissue = 0;
											$iTotIssue  = 0;
											
											foreach($aTDate as $keyd=>$valued){ 
												$vDate   = $valued;
												$color   = "bgcolor='green'";
												$iVerif  = false;
												$img     = "";
												$checked = "";
												if(isset($aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['tmp'][$vDate])) { 
													 $idi        = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['id'][$vDate];
													 $imputation = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['tmp'][$vDate];
													 $validation = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['validation'][$vDate];
													 
													 if( $imputation>0){
														 $verifImput = isvalideIssue_f($idi);
														 
														 if($verifImput > 0){
															$color = "bgcolor='green'";
															$class="isvalide";
															
															if($validation == 1){
																$checked="checked='checked'";
															}else{
																$checked="";
															}
															
															$iTotVissue = $iTotVissue + $imputation ;
															$iVerif  = true;
														 }else{
														 	
															$color   = "bgcolor='#FEB75A'";
															$checked = "";
															$class   = "isnovalide";
															$iVerif  = true;
														 }
													}
												}
												
												if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
												  $color="";
												  $class ="";
												}	
												 $classVide = "style-rapport-v";
												if(!$iVerif && (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour))){
												  $class = "isvalide videImp";
												  $color   = "bgcolor='white'";
												  $img   = '*&nbsp;*&nbsp;*';
												   $classVide = "style-rapport";
												}
											 
												$html .= '<td  '.$color.' style="border:1px solid #999999" class="'.$classVide.'"';
												
												// jour ferie ou samedi ou dimanche
												if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
													$html .= "bgcolor='#9E9E9E'"; 
												} 
												
												$html.= 'width="10" align="center">';
												$html.= $img;
												
												if(isset($aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['tmp'][$vDate])) { 
													
													$imputation = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['tmp'][$vDate];
													$iTotIssue  = $iTotIssue + $imputation ;
													$idi        = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['id'][$vDate];
													$idproj     = $aDetails[$vUser['username']][$vProj['pname']][$v2['id']]['projid'][$vDate];
													 
													if(($imputation)>0){
													    $html.='<table align="center"><tr><td class="style-rapport-v" style="border:0px transparent;">'.  $imputation.'</td></tr></table>';
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
												  $colorTd2 = 'bgcolor="green;"'; 
												  $class    = "isvalide";
											  }else{
												  $colorTd2 = 'bgcolor="#FEB75A"'; 
												  $class    = "isnovalide";
											  }
											
											  $html.='<td class="style-rapport-v"  style="border:1px solid #999999"  align="center" width="30" bgcolor="green">'.$iTotVissue.'</td>';
											  
											 
												$html.='<td class="style-rapport-v" '.$colorTd2.' style="border:1px solid #999999" align="center"><div  style="text-align:center">'.  $iJissueNvalide.'</div></td>';
											 
											  
											  $html.='<td class="style-rapport-v"    align="center" width="30" bgcolor="green" style="border:1px solid #999999">'.$iTotIssue.'</td><td class="style-rapport videImp"   align="center" width="30" bgcolor="white" style="border:1px solid #999999">*&nbsp;*&nbsp;*</td>';
											  $html .='</tr>'; 
										}
										
										$iTotJproj=0; 
									} 
						  } 
					$html .='</tbody></table></div>';
			 } 
			$html .=' </td></tr>';
			$html .='</table></body></html>';

echo $html; exit();