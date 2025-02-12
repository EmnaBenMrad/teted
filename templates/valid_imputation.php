<?php
	
	 session_start();// On demarre la session
	 
	 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
		session_destroy();  // On detruit la session
		header("Location: login.php");
	 }
	 
	 include "connexion.php"; // la page de connexion a la base jira
	 include "fonctions.php";

	//si le le login n'est pas fourni par session donc redirection vers la page login.php
	if (!isset($_SESSION['login'])) {
	  header("Location: login.php");
	}
	set_time_limit(2000);

	$id = $_SESSION['id'];
	$user = $_SESSION['id'];
	$login = $_SESSION['login'];

	$page = "valid_imputation";
	// $lg est le parametre de langue et c est egal 1 pour le francais

	$lg = 1;
	$sql_parametres   = "select * from parametres where langue=".$lg;
	$query_parametres = mysql_query($sql_parametres);
	$tab_parametres   = mysql_fetch_array($query_parametres);
	/**fin requete de parametrage***/
	$img_path = $tab_parametres['img_path'];
	$i = 0;
	$j=0; 
	$lien_bord = "inactif";
	$lien_adm  = "inactif";
	$user_cras = "inactif";
	
	if(isset($_SESSION['liste_group']) && !empty($_SESSION['liste_group'])){
		$liste_group = $_SESSION['liste_group'];
	}else{
		$liste_group = membershipbase($user);
	}
	
	
	$nb_group  = count($liste_group);
	$tab_group = array();
	
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

	$aFerJour = jour_fer_format($mois, $annee);
	
	$iFinMois  = fin_mois($mois, $annee); 
	$iLongMois = strlen($mois); if((isset($mois)) and ($iLongMois==1)) $mois = "0".$mois;
	$date1     = $annee."-".$mois."-01";  $date2 = $annee."-".$mois."-".$iFinMois;
	
	for($d=1; $d<=$iFinMois; $d++){
		if($d<10) { 
			$date=$annee."-".$mois."-0".$d; 
		}else { 
			$date=$annee."-".$mois."-".$d; 
		}
		$aTDate[$d] = $date;
	}

	// nobre de semaine par mois
	$nbSemaine = ceil((count($aTDate))/7);

 
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$aTDate2 = getMonthByWeek($semaine, $annee);
		$aTDate = $aTDate2;
		// liste imputation
		$listImp = GetImputationUser($aTDate[0], $aTDate[6], $proj);
		$date1   = $aTDate[0];  $date2 = $aTDate[6];
	}else{
		// liste imputation
		$listImp     = GetImputationUser($date1, $date2, $proj);
	}

	while ($row = mysql_fetch_row($listImp) ){
		$TSomImp[$row[1]][$row[2]]= $row[0];
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
	$list_bd_users = liste1('BD-users');//Liste utilisateurs B&D Tunis
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$listimp = liste2($aTDate[0], $aTDate[6]);//Liste utilisateurs B&D Tunis qui ont imputes
	}else{
		$listimp = liste2($date1, $date2);//Liste utilisateurs B&D Tunis qui ont imputes
	}
	
	$list_bd_maroc = liste1('BD-Maroc');//Liste utilisateurs B&D Maroc
	$sListByBU     = "";

	if($gb !=""){
		$sListByBU = liste_group_user_bu($gb);
	}

	$list = list_userbase_fu($proj, $cat,$collab, $pg, $list_bd_users, $listimp ,$list_bd_maroc, $sListByBU, $gb);
	
	$nbpage = nbpagefu($proj, $cat,$collab, $list_bd_users, $listimp ,$list_bd_maroc, $sListByBU, $gb); 
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$nb_f = nbre_jour_fer($aTDate[0], $aTDate[6]);//nombre de jours ferie
	}else{
		$nb_f = nbre_jour_fer($date1, $date2);//nombre de jours ferie
	}
	
	if(isset($_REQUEST['semaine']) && $_REQUEST['semaine'] !=""){
		$nb_jour_trav = 7-(2+$nb_f);
	}else{
		$nb_jour_trav = $iFinMois-($nb_sam+$nb_dim+$nb_f);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<link href="../style/sortableTable.css" rel="stylesheet" type="text/css">
		<link href="../style/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css">
		<style>
			#myTable, #myTable td, #myTable th{border:1px solid #999999;}
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Validation imputations</title>
		<script language="JavaScript" src="../ajax.js"></script>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/jquery.quicksearch.js"></script>
		<script type="text/javascript" src="../js/validation.js"></script>
		<script language="javascript">
			jQuery(function(){
				jQuery.noConflict();
				jQuery('input#id_search').quicksearch('table#myTable tbody tr');
				
			});
	
			// details projet
			function showDetailsP(idProj,idUser){
				
					if(jQuery('#img_'+idUser+'_'+idProj).hasClass('visite-img-p')){
						jQuery('#img_'+idUser+'_'+idProj).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
						jQuery('#img_'+idUser+'_'+idProj).removeClass('visite-img-p');
						jQuery('tr.tr-d-'+idUser+'-'+idProj).attr('style','display:none');
					}else{
						jQuery('#img_'+idUser+'_'+idProj).addClass('visite-img-p');
						jQuery('#img_'+idUser+'_'+idProj).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
						jQuery('tr.tr-d-'+idUser+'-'+idProj).attr('style','');
						
					}
			}
			
			// details user
			function showHideRow(id,cmp, id_user,username, semaine){
				
				 if(jQuery('img.visite-img').length > 0){
						
						var idT = jQuery('img.visite-img:first').attr('user');
						var id_userT = jQuery('img.visite-img:first').attr('rel');
						if(parseInt(id_userT) > 0 && idT != id && id_userT != id_user){
							jQuery('#img_'+idT).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
							jQuery('#img_'+idT).removeClass('visite-img');
							jQuery('tr.tr-a-'+id_userT).attr('style','display:none');
							jQuery('tr.tr-details-p').attr('style','display:none');
							jQuery('.img_d_'+id_userT).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
							jQuery('.img_d_'+id_userT).removeClass('visite-img-p');
						}
						
				 }
				 
				 if(jQuery('#img_'+id).hasClass('visite-img')){
						jQuery('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
						jQuery('#img_'+id).removeClass('visite-img');
						//alert(jQuery('tr.tr-'+id_user).length);
						jQuery('tr.tr-a-'+id_user).attr('style','display:none');
						jQuery('tr.tr-details-p').attr('style','display:none');
						jQuery('.img_d_'+id_user).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
						jQuery('.img_d_'+id_user).removeClass('visite-img-p');
					}else{
						jQuery('#img_'+id).attr('src',"../images/load.gif");
						
						if(!jQuery('#img_'+id).hasClass('show-detail')){
							jQuery('#img_'+id).addClass('show-detail');
							jQuery.post('rapport_imputation_userv.php',{'annee':'<?php  echo $annee;?>','mois':'<?php  echo $mois;?>','proj':'<?php  echo $proj;?>','cat':'<?php  echo $cat;?>','semaine':'<?php  echo $semaine;?>','idU':id_user,'nameU':username}, function(data){
								
								jQuery('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
								jQuery('#img_'+id).addClass('visite-img');
								
								jQuery(data).insertAfter(jQuery('tr.tr-'+id_user));
								jQuery('tr.tr-'+id_user).attr('style','');
							});
						}else{
							jQuery('#img_'+id).addClass('visite-img');
							jQuery('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
							jQuery('tr.tr-a-'+id_user).attr('style','');
						}
					}
					
			}

		</script>

	</head>
	<body>
		<br />

		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
		  <tr valign="top" >
			<td height="76" ><?php include("entete.php"); ?></td>
		  </tr>
		  <tr>
			<td>
				<br /><form action="valid_imputation.php"  method="post" name ="form">
						<TABLE cellSpacing="0" cellPadding="0" width="100%" bgColor="#bbbbbb" border="0">
							<TBODY>
							  <TR>
								<TD>
									<TABLE align="right" cellSpacing="1" cellPadding="4" width="100%"  bgColor="#bbbbbb" border="0">
										<TBODY bgColor="#EFEFEF">
											<tr height="20" valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
												<td height="10" colspan="13" align="left">
												
												<?php echo $tab_parametres['annee'];?>&nbsp;&nbsp;&nbsp;
													  <select name="annee" id="annee" class="input-annee" style="width:100px">
														  <?php for ($i=2007;$i<=2025;$i++){ ?>
															<option <?php if ($i==$annee) { echo "selected=\"selected\""; } ?> value="<?php echo $i; ?>"><?php echo $i; ?> </option><?php } ?>
													  </select>&nbsp;
													  
												<?php echo $tab_parametres['mois']; ?>&nbsp;:
                                                                                                 <?php if((date('d') >= $tab_parametres['param_fermeture_validation']) && strtolower($list_group)=="bd-mangers"){?>
												
													  <select name="mois" id="mois" class="input-annee" style="width:150px;" onchange="changeMois(this)">
												  
															<option value="<?php echo $mois; ?>"  selected><?php echo nom_mois($mois-1); ?></option>
												 
													  </select>
                                                                                               
                                                                                                 <?php }elseif((date('d') < $tab_parametres['param_fermeture_validation'])&& strtolower($list_group)=="bd-mangers"){ ?>
                                                                                              <select name="mois" id="mois" class="input-annee" style="width:150px;" onchange="changeMois(this)">
												  
															<option value="<?php echo $mois-1; ?>"  selected><?php echo nom_mois($mois-2); ?></option>
                                                                                                                        <option value="<?php echo $mois; ?>"  selected><?php echo nom_mois($mois-1); ?></option>
												 
													  </select>
                                                                                                <?php  } else { ?>
													    <select name="mois" id="mois" class="input-annee" style="width:150px;" onchange="changeMois(this)">
												  <?php for ($i=1;$i<=12;$i++){ ?>
															<option value="<?php echo $i; ?>" <?php if(($i)==$mois){ echo "selected";}?>><?php echo nom_mois($i-1); ?></option>
												  <?php } ?>
													  </select>
                                                                                                             <?php } ?>
													&nbsp; Semaine :
													  <select name="semainess" id="semaines" class="input-annee" style="width:150px" >
														    <option  <?php if ($semaine == "" ) { echo "selected=\"selected\""; } ?> value="" >Semaine </option>
												<?php $semaines = getListOfWeek($mois, $annee);
												for($i = 0; $i < count($semaines); $i ++) {
													
													?>
												 <option
													value="<?php echo $semaines[$i];?>"
													<?php
													if ($semaines[$i] == $semaine) {
														echo "selected=\"selected\"";
													}
													?>><?php
													echo $semaines[$i];
													?> </option><?php
													
												}  ?>
													  </select>&nbsp;
													  
											    <?php  $listproj = array();
													   $result = mysql_query($query);?>
													   <select style="width:150px" onChange="changePage3(this.form.projets.options[this.form.projets.options.selectedIndex].value)" 	name="projets" id="projets" class="input-liste"  style="width:250px">
															<option value=""><?php echo $tab_parametres['liste_projet'];?></option>
												<?php $jj = 0;
													while($row = mysql_fetch_array($result)){ 
													  $listproj[$jj] = $row[0];
													  $pro = $row[0];  $jj++;?>
															<option value="<?php echo $row[0]; ?>" <?php if ((isset($proj)) and ($pro==$proj)){ echo "selected"; } ?>><?php echo $row[1]; ?></option>	
											  <?php } ?>
													  </select>&nbsp;&nbsp;
													  
													  <select name="collabss" id="collab" class="input-liste-collab" style="width:180px">
															<OPTION value=""><?php echo $tab_parametres['collab'];?></OPTION>
															<?php 
															if(!isset($proj)) { 
																$pp = $listproj;
															}else{
																$pp =array('0'=>$proj);
															}


															// if((isset($watcher)) and ($watcher=="all"))
															if(isset($proj) && $proj !="") { 
																$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
																		FROM propertystring, propertyentry, userbase
																		WHERE propertystring.id = propertyentry.id
																		AND propertyentry.property_key = 'fullname'
																		AND propertyentry.entity_id = userbase.id
																		AND userbase.username IN ".liste_group_user_f($pp)." 
																		AND userbase.id not in (select user from userbasestatus where status =0) 
																		ORDER BY propertyvalue ASC ";
															}elseif(isset($gb) && $gb !="") { 
																$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
																		FROM propertystring, propertyentry, userbase
																		WHERE propertystring.id = propertyentry.id
																		AND propertyentry.property_key = 'fullname'
																		AND propertyentry.entity_id = userbase.id
																		AND userbase.ID IN ".liste_group_user_bu($gb)." 
																		AND userbase.id not in (select user from userbasestatus where status =0) 
																		ORDER BY propertyvalue ASC ";
															}else{
																$query1="SELECT 
																		DISTINCT(userbase.ID), propertystring . propertyvalue 
																	FROM 
																		propertystring, propertyentry, userbase, project, membershipbase 
																	WHERE 
																		propertystring.id = propertyentry.id 
																		AND propertyentry.property_key = 'fullname' 
																		AND propertyentry.entity_id = userbase.id
																		AND membershipbase.USER_NAME = userbase.username
																		AND membershipbase.GROUP_NAME = 'BD-users' 
																		AND userbase.ID  not in (select user from userbasestatus where status =0) 
																	ORDER BY propertyvalue ASC ";
															}

																	$result1 = mysql_query($query1);
																	$nb = mysql_num_rows($result1);	
															?>
													<?php while($row1 = mysql_fetch_array($result1)){ 
															   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; ?>
														<OPTION value="<?php echo $row1[0]; ?>" <?php if ((isset($collab)) and ($collab==$coll)){ echo "selected"; } ?> ><?php echo $nom; ?></OPTION>
													<?php } ?>
													</select>
													
													&nbsp;&nbsp;<select name="group-bu" id="group-bu" class="input-liste-collab" onChange="changeListeUserBu(this.value)" style="width:180px">

																	<OPTION value="">Liste BU</OPTION>
													<?php 
														$queryBu="SELECT DISTINCT(groupbase.ID), groupbase.groupname
																							FROM groupbase
																							left join  groupbasestatus on (   groupbase.ID = groupbasestatus.id)
																							where groupbasestatus.status = 1
																							ORDER BY groupbase.groupname ASC ";


															$aBu = mysql_query($queryBu);
															if($aBu){
															$nbBu = mysql_num_rows($aBu);	
													?>
															  <?php while($row2 = mysql_fetch_array($aBu)){ 
																	$idBu = ($row2[0]);$libBu = ($row2[1]);?>
																	<OPTION value="<?php echo  $idBu ; ?>" <?php if ((isset($gb)) and ($gb==$idBu)){ echo "selected"; } ?> ><?php echo $libBu; ?></OPTION>
																<?php }}?>


														</select>&nbsp;&nbsp;<input type="button" class="buttonImg"  value=" " name="Affich" onClick="gopagef('<?php echo $_SERVER['PHP_SELF']; ?>')">&nbsp; &nbsp;
															  <input type="submit" id="export-excel" name="exporter" class="buttonExcelImg" value=""  onClick="gopage('export_validation_imp.php')"  /><input type="hidden" name="rapport_activite" id="rapport_activite" value="1" /> <input type="hidden" name="mois" id="mois" value="<?php echo $mois; ?>" /> <input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>" /> <input type="hidden" name="pg" id="pageS" value="<?php echo $pg; ?>" /> <input type="hidden" name="mm" id="moiss" value="<?php echo $mois; ?>" /><input type="hidden" name="y" id="annees" value="<?php echo $annee; ?>" /><input type="hidden" name="collab" id="collabs" value="<?php echo $collab; ?>" /><input type="hidden" name="projss" id="projss" value="<?php echo $proj; ?>" /> <input type="hidden" name="gb" id="gbu" value="<?php echo $gb; ?>" /><input type="hidden" name="semaine" id="semaine" value="<?php echo $semaine; ?>" /> <input type="hidden" name="valid_imputation" id="valid_imputation" value="<?php echo $page; ?>" /> 

													</td>
												</tr>
										</TBODY>
									</TABLE>
								</TD>
							</TR>
						</TBODY>
					</TABLE>		
				</form>
		   <br />               
		  <TABLE cellSpacing="0" cellPadding="1" width="100%" align="center" 
					bgColor="#bbbbbb" border="0">
					  <TBODY>
					  <TR>
						<TD vAlign="top" width="100%" colSpan="2">
						  <TABLE cellSpacing="0" cellPadding="4" width="100%"  bgColor="#ffffff" border="0">
							<TBODY>
							<TR>
									 <TD vAlign="top" width="180%" bgColor="#EFEFEF">
										<TABLE cellSpacing="0" cellPadding="0" width="100%">
										  <TBODY>
										  <TR>
											<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b> Validation imputations </b></font>                           </TD>
										  </TR></TBODY>
										</TABLE>
									   </TD>
									</TBODY>
								 </TABLE>
								</TD>
							</TR>
						</TBODY>
		  </TABLE>
		  <br />   
		<?php  if(isset($_REQUEST) && !empty($_REQUEST) && isset($_REQUEST['y']) && !empty($_REQUEST['y'])) { ?>
			  <div id="div_form">
					<form action="#">
						<fieldset <?php if($semaine !="") {?>style="width:58.5%; margin-left:150px"<?php }?> >
							<b>Rechercher:</b>&nbsp;&nbsp;<input type="text" autofocus="" placeholder="Search" id="id_search" value="" name="search">
						</fieldset>
					</form>
					<table id="myTable" cellpadding="1" cellspacing="1" class="table" <?php if($semaine !="") {?>style="width:60%; margin-left:150px"<?php }?> >
						 <thead>
							 <th axis="string" width="180" class="th"><?php echo $tab_parametres['collab'];?></th>
							<?php foreach($aTDate as $key=>$value) {
								
								$aTab= explode('-',$value);?>
							<th axis="number" class="th2" style="width:150px;text-align:center" ><b><?php echo $aTab[2].'-'.$aTab[1] ?></b></th><?php } ?>
							<th style="width:150px;text-align:center" class="th"  axis="number">Valid�</th>
							<th style="width:150px;text-align:center" class="th"  axis="number">Non valid�</th>
							<th style="width:150px;text-align:center" class="th"  axis="number"><?php echo $tab_parametres['total'];?></th>
							<th style="width:150px;text-align:center" class="th"  axis="number"><?php echo $tab_parametres['delta'];?></th>
						<tbody>
					<?php   $cmt=1;  foreach ($list as $key => $vUser) { 
							$result = select_project_f($vUser['id'], $date1, $date2, $proj, $cat);
							$nb_row = count($result);
						?>
						<tr  id="<?php echo $cmt; ?>" bgcolor="#F8F7F7" onmouseover="this.bgColor='#C7C5C5';" onmouseout="this.bgColor='#F8F7F7'" class="tr-<?php echo $vUser['id']?>">

						<td width="180" >
							<div style="min-width:250px"><?php  if ($nb_row!=0)  { ?><a onclick="javascript:showHideRow('<?php echo $cmt; ?>', '<?php echo $nb_row; ?>','<?php echo $vUser['id'];?>','<?php echo $vUser['username'];?>','<?php echo $semaine;?>')" id="'<?php echo $cmt; ?>', '<?php echo $nb_row; ?>','<?php echo $vUser['id'];?>','<?php echo $vUser['username'];?>','<?php echo $semaine;?>'" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" hspace="2" id="img_<?php echo $cmt; ?>" user="<?php echo $cmt; ?>" rel="<?php echo $vUser['id'];?>" height="9" width="9"/></a><?php } else { ?><img src="<?php echo $img_path; ?>spacer.gif" height="9" width="9" hspace="2" /><?php }	?>&nbsp;<b><?php echo $vUser['username']; ?></b></div>
						</td>
						<?php $cmp=1; $Total=0;$TotalValide=0;
						foreach ($aTDate as $key => $vDate) {  
						
						    $aT = explode('-', $vDate);
							$ImpT = 0;
							if (isset($TSomImp[$vUser['username']][$vDate])) {
								$ImpT = $TSomImp[$vUser['username']][$vDate];
							}
							$auj = str_replace('-','' , date('Y-d-m'));
							$dd = str_replace('-','' , $vDate);
							$colorp ="";
							$class  = "";
							if($ImpT >=1){
								  
								  $verifAll = isvalideIssueAll($vDate,$vUser['id'] );
								 
								  if($verifAll>0 ){
									  $colorp = "style='background-color:#FEB75A'";
									  $class  = "isnovalide";
								  }else{
									  $colorp      = "style='background-color:green'";
									  $class       = "isvalide";
									  $TotalValide = $TotalValide + $ImpT;
								  }
							}elseif($ImpT < 1 && $ImpT > 0){
								 
								  $verifAll = isvalideIssueAllNonImp($vDate,$vUser['id'] );
								  if($verifAll>0 ){
									  $colorp = "style='background-color:#FEB75A'";
									  $class  = "isnovalide";
								  }else{
									  $colorp      = "style='background-color:green'";
									  $class       = "isvalide";
									  $TotalValide = $TotalValide + $ImpT;
								  }
							}
							if(($ImpT) < 1){
							
								if(intval($auj) >= intval($dd) && (typeDate($vDate) != 'Samedi')  && (typeDate($vDate) != 'Dimanche')  && (!in_array($vDate, $aFerJour))){
								$colorp = "style='background-color:red'";
								$class  = "";
							}
								if($ImpT >0){
									 $verifnAll = isnvalideIssueAll($vDate,$vUser['id'] );
									 if($verifnAll == 0 ){
										$TotalValide =$TotalValide + $ImpT;
									 }
								}
							} 
							if($cmp < 10) $cmppp = '0'.$cmp;
							
							
							if((typeDate($vDate) == 'Samedi')  or (typeDate($vDate) == 'Dimanche')  or (in_array($vDate, $aFerJour)) ){
								$colorp =  "style='background-color:#9E9E9E'"; 
								$class  = "";
							} 
						?>

							<td <?php echo $colorp; ?> width="150" align="center" class="<?php echo $class; ?> userT-<?php echo $vUser['id']; ?>-<?php echo $key;?>">
								<span class="style-rapport-v">
									<?php if (isset($TSomImp[$vUser['username']][$vDate])) {
											$Imp = $TSomImp[$vUser['username']][$vDate]; $Total = $Total +  $Imp; 
											if(($Imp)>0){ echo $Imp;}else{echo "&nbsp;"; } 
										  } else {
												echo "&nbsp;"; 
										  }  ?>
								</span>
							</td>
							<?php $cmp++;
							}
							
							$delta = $Total - $nb_jour_trav;
							if($delta<0) { $color="#FF0000"; } else { $color="#006600"; }
							$diffV = getTotNonValide($vUser['id'], $date1, $date2);
							$TotalValide = getTotValide($vUser['id'], $date1, $date2);
							?>
							<td width="150" class="style-rapport-v userT-nov-<?php echo $vUser['id'];?>"   align="center" width="30" style='background-color:green'><?php echo $TotalValide ;?></td>
							<td width="150" class="style-rapport-v user-nov-<?php echo $vUser['id'];?><?php if($diffV > 0){ ?> isnovalide<?php }?>"   align="center" width="30" <?php if($diffV == 0){ ?>style='background-color:green' <?php }else{ ?>style='background-color:#FEB75A'<?php } ?>><?php echo ($diffV) ;?></td>
							<td  width="150" align="center"  <?php if($delta == 0 && $diffV == 0){ ?>style='background-color:green' <?php }else{ ?>style='background-color:green'<?php } ?>><span class="style-rapport-v" > <?php echo $Total; ?></span></td>
							<td  width="150" align="center" width="30" <?php if(isset($color)) {  ?> bgcolor="<?php echo $color; ?>"<?php } ?>><span class="style-rapport-v" style="color:#FFFFFF"><?php   echo $delta; ?></span></td>
						</tr>


						<?php  $cmt++; } ?>
						</tbody>
					</table>
				</div>
			  <?php } ?>
			 </td>
			</tr>
			<?php  if(isset($_REQUEST) && !empty($_REQUEST) && isset($_REQUEST['y']) && !empty($_REQUEST['y'])) { ?>
			<tr>
				<td>
					<div class="center-wrap"><div class="carousel-pagination"><p><?php if($pg !='all'){?><a role="button" <?php if ($pg == 1){?>class="active" <?php }?> id="p-1" onclick='changePagePaginate(1)'><span>1</span></a><?php for( $i=2;$i<=$nbpage+1;$i++){ ?><a role="button" class=" <?php if ($pg == $i){ echo "active ";}?>paginate-page" id="p-<?php echo $i;?>" onclick="changePagePaginate('<?php echo $i;?>')"><span><?php echo $i;?></span></a><?php }?><a <?php if ($pg == 'all'){?>class="active" <?php }?> role="button" onclick='changePagePaginate("all")'><span>Tous</span></a><?php }else{?><a role="button"   onclick='changePagePaginate(1)'><span>Pagination</span></a><a <?php if ($pg == 'all'){?>class="active" <?php }?> role="button" onclick='changePagePaginate("all")'><span>Tous</span></a><?php }?></p></div></div>
				</td>
			</tr>
			<?php } ?>
			<tr valign="top" >
				<td height="76" ><?php include("bottom.php"); ?></td>
			</tr>
		</table>
	</body>
</html>
