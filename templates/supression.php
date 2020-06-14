<?php

session_start();// On démarre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
	session_destroy();  // On détruit la session
	header("Location: login.php");
 }
 
$HTTP_SESSION_VARS["ID"] = $_SESSION['id'];
$user = $_SESSION['id'];

if(isset($_POST['id']) && $_POST['id'] !=''){
	$userC = $_POST['id'];
}else{
	$userC = $_SESSION['id'];
}

//si le le login n'est pas fourni par session donc redirection vers la page login.php
if (!isset($_SESSION['login'])){
  	header("Location: login.php");
}

include "connexion.php"; // la page de connexion à la base jira
include "fonctions.php"; 


// $lg est le parametre de langue et c est egal 1 pour le français
$lg = 1;
$sql_parametres   = "select * from parametres where langue=".$lg;
$query_parametres = mysql_query($sql_parametres);
$tab_parametres   = mysql_fetch_array($query_parametres);

(isset($_POST['limps']))? $lipms = $_POST['limps'] : $lipms ='' ;

/**fin requete de paramètrage***/
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Suivi des imputations</title>
		<link href="../style/style.css" rel="stylesheet" type="text/css" />
		<LINK media=print href="../Style/global_printable.css" type=text/css rel=stylesheet>
		<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>
		<LINK href="../style/global.css" type=text/css rel=StyleSheet>
	</head>

 <script type="text/javascript"	src="../js/jquery.js"></script>
	
 <SCRIPT language=JavaScript>//javascript pour l'action sur la liste déroulante

			function remplirFormDel(user, project, issue, mm, week, total , y, limps,page){
				
				$('form#formdel #formdel_limps').val(limps);
				$('form#formdel #formdel_id').val(user);
				$('form#formdel #formdel_proj').val(project);
				$('form#formdel #formdel_issue').val(issue);
				$('form#formdel #formdel_mm').val(mm);
				$('form#formdel #formdel_semaine').val(week);
				$('form#formdel #formdel_y').val(y);
				$('form#formdel #formdel_tot').val(total);
				$('form#formdel #formdel_page').val(page);
				$('form#formdel').submit();
			}
			
			function changePage2(x)
			{
			if(x == "nothing")
			    {
			      document.forms[0].reset();
			      document.forms[0].elements[0].blur();
			      return;
			    }
			else
				{
				 location.href = x; 
				}
			}
			//--> 
			</SCRIPT>
						<script language="JavaScript" type="text/JavaScript">

function popup(page) {
	// ouvre une fenetre sans barre d'etat, ni d'ascenceur
	window.open(page,'popup','width=550,height=600,toolbar=no,scrollbars=No,resizable=yes,');	
}
</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" bgcolor="#FFFFFF">
	<form action="supp.php" method="post"name="formdel" id='formdel'>
		<input type="hidden" name='limps' id='formdel_limps' value=''/>
		<input type="hidden" name='page' value='' id='formdel_page'/>
		<input type="hidden" name='id' value='' id='formdel_id'/>
		<input type="hidden" name='proj' value='' id='formdel_proj'/>
		<input type="hidden" name='issue' value='' id='formdel_issue'/>
		<input type="hidden" name='mm' value='' id='formdel_mm'/>
		<input type="hidden" name='semaine' value='' id='formdel_semaine'/>
		<input type="hidden" name='y' value='' id='formdel_y'/>
		<input type="hidden" name='tot' value='' id='formdel_tot'/>
	</form>
<?php $i=0;
	$j=0;
	$lien_bord = "inactif";
	$lien_adm  = "inactif";
	$group     = membershipbase($user);
	
	$nb_group = count($group);
	for($j==0;$j<$nb_group;$j++)
	{
	$list_group = $group[$j];
	if(strtolower($list_group)=="td-tdbusers"){ $lien_bord = "actif"; }
	if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
	}
	 
	 
	 $login  = $_SESSION['login']; //récupération de la variable de session
	 @$id    = $_POST['id'];
	 @$proj  = $_POST['project'];
	 @$issue = $_POST['issue'];
	 @$mm    = $_POST['mm'];
	 @$y     = $_POST['y'];
	 @$semaine = $_POST['semaine'];
	 $page     = $_GET['page'];
	 if($page==0) { $lien_page = "suivi_imputation.php?id="; }
	 if($page==1) { $lien_page = "update_imputation.php?collab="; }
		
	 
	if(isset($_POST['y'])){ 
		$y = $_POST['y'];
	}
	if(isset($_POST['semaine'])){ 
		$y = $_POST['y'];
		$semaine = $_POST['semaine'];
	}
	if(isset($_POST['semaine'])){ 
		$y = $_POST['y'];
		$semaine = $_POST['semaine'];
	}
	if((isset($_GET['semaine'])) || (isset($_POST['semaine'])) ){
	
		$verifImpV1 = false;$verifImpV2 = false;$verifImpV3 = false;$verifImpV4 = false;$verifImpV5 = false;$verifImpV6 = false;$verifImpV7 = false;
		
		if($semaine>50 && $mm=='01') {
			$years  = $y-1;
			$wknber = weeknumber($y, 12, 31);
		}else {
			$years  = $y;
			$wknber = $semaine;
		}
		$weekDay = getDaysInWeek ($wknber, $years);
		$exe 	 = explode("-", $weekDay[0]);
		$d1 	 = $weekDay[0];
		
		$date1 	   = $weekDay[0];
		$d1_affich = $exe [2] . "-" . $exe [1];
		
		$exe1 	   = explode("-", $weekDay[1]);
		$d2 	   = $weekDay[1];
		$date2 	   = $weekDay[1];
		$d2_affich = $exe1 [2] . "-" . $exe1 [1];
		
		$exe2 	   = explode("-", $weekDay[2]);
		$d3        = $weekDay[2];
		$date3     = $weekDay[2];
		$d3_affich = $exe2 [2] . "-" . $exe2 [1];
		
		$exe3      = explode("-", $weekDay[3]);
		$d4        = $weekDay[3];
		$date4     = $weekDay[3];
		$d4_affich = $exe3 [2] . "-" . $exe3 [1];
		
		$exe4 	   = explode("-", $weekDay[4]);
		$d5 	   = $weekDay[4];
		$date5 	   = $weekDay[4];
		$d5_affich = $exe4 [2] . "-" . $exe4 [1];
		
		$exe5 	   = explode("-", $weekDay[5]);
		$d6 	   = $weekDay[5];
		$date6 	   = $weekDay[5];
		$d6_affich = $exe5 [2] . "-" . $exe5 [1];
		
		$exe6 	   = explode("-", $weekDay[6]);
		$d7 	   = $weekDay[6];
		$date7	   = $weekDay[6];
		$d7_affich = $exe6 [2] . "-" . $exe6 [1];
	
	}
	$jour_max = $tab_parametres ['param_fermeture_imputation'];
	$datefin  = $exe6[0]."-".$exe6[1]."-".$exe6[2];//$dat2['year']."-".$dat2['month']."-".$dat2['day'];
	
	$readOnly1 = "false";
	
	$jours_courant = date('d');
	$mois_courant  = date('m');
	$annee_courant = date('Y');
	
	$reel_month   = date('m');
	$readOnly1    = 'false';
	$imput_month1 = $exe[1];
	
	if($reel_month==$imput_month1){
		$readOnly1 = "false";
	}else{
		if($imput_month1==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly1 = "true";
		}elseif($imput_month1==11 && $reel_month==01){
			$readOnly1 = "true";
		}elseif($imput_month1==12 && $reel_month==02){
			$readOnly1 = "true";
		}elseif($reel_month - $imput_month1 > 1){
			$readOnly1 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly1 = "true";	
		}
	}
	$readOnly2 = "false";
	
	$imput_month2 = $exe1[1];
	if($reel_month==$imput_month2){
		$readOnly2 = "false";
	}else{
		if($imput_month2==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly2 = "true";
		}elseif($imput_month2==11 && $reel_month==01){
			$readOnly2 = "true";
		}elseif($imput_month2==12 && $reel_month==02){
			$readOnly2 = "true";
		}elseif($reel_month - $imput_month2 > 1){
			$readOnly2 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly2 = "true";	
		}
	}
	$readOnly3="false";
	
	$imput_month3 = $exe2[1];
	if($reel_month==$imput_month3){
		$readOnly3 = "false";
	}else{
		if($imput_month3==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly3 = "true";
		}elseif($imput_month3==11 && $reel_month==01){
			$readOnly3 = "true";
		}elseif($imput_month3==12 && $reel_month==02){
			$readOnly3 = "true";
		}elseif($reel_month - $imput_month3 > 1){
			$readOnly3 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly3 = "true";	
		}
	}
	$readOnly4="false";
	
	$imput_month4 = $exe3[1];
	if($reel_month == $imput_month4){
		$readOnly4 = "false";
	}else{
		if($imput_month4==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly4 = "true";
		}elseif($imput_month4==11 && $reel_month==01){
			$readOnly4 = "true";
		}elseif($imput_month4==12 && $reel_month==02){
			$readOnly4 = "true";
		}elseif($reel_month - $imput_month4 > 1){
			$readOnly4 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly4 = "true";	
		}
	}
	$readOnly5="false";
	
	$imput_month5 = $exe4[1];
	if($reel_month==$imput_month5){
		$readOnly5 = "false";
	}else{
		if($imput_month5==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly5 = "true";
		}elseif($imput_month5==11 && $reel_month==01){
			$readOnly5 = "true";
		}elseif($imput_month5==12 && $reel_month==02){
			$readOnly5 = "true";
		}elseif($reel_month - $imput_month5 > 1){
			$readOnly5 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly5 = "true";	
		}
	}
	$readOnly6="false";
	
	$imput_month6 = $exe5[1];
	if($reel_month == $imput_month6){
		$readOnly6 = "false";
	}else{
		if($imput_month6==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly6 = "true";
		}elseif($imput_month6==11 && $reel_month==01){
			$readOnly6 = "true";
		}elseif($imput_month6==12 && $reel_month==02){
			$readOnly6 = "true";
		}elseif($reel_month - $imput_month6 > 1){
			$readOnly6 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly6 = "true";	
		}
	}
	$readOnly7="false";
	
	$imput_month7 = $exe6[1];
	if($reel_month == $imput_month7){
		$readOnly7 = "false";
	}else{
		if($imput_month7==12 && $reel_month==01){
			if(date('d') > $jour_max) $readOnly7 = "true";
		}elseif($imput_month7==11 && $reel_month==01){
			$readOnly7 = "true";
		}elseif($imput_month7==12 && $reel_month==02){
			$readOnly7 = "true";
		}elseif($reel_month - $imput_month7 > 1){
			$readOnly7 = "true";
		}else{
			if(date('d') > $jour_max) $readOnly7 = "true";	
		}
	}
	
	
	if($lien_adm == 'actif'){
		$readOnly1 = "false";
		$readOnly2 = "false";
		$readOnly3 = "false";
		$readOnly4 = "false";
		$readOnly5 = "false";
		$readOnly6 = "false";
		$readOnly7 = "false";
	}
	if($readOnly1=="false") $datedebut=$exe[0]."-".$exe[1]."-".$exe[2];
	elseif($readOnly2=="false") $datedebut=$exe1[0]."-".$exe1[1]."-".$exe1[2];
	elseif($readOnly3=="false") $datedebut=$exe2[0]."-".$exe2[1]."-".$exe2[2];
	elseif($readOnly4=="false") $datedebut=$exe3[0]."-".$exe3[1]."-".$exe3[2];
	elseif($readOnly5=="false") $datedebut=$exe4[0]."-".$exe4[1]."-".$exe4[2];
	elseif($readOnly6=="false") $datedebut=$exe5[0]."-".$exe5[1]."-".$exe5[2];
	elseif($readOnly7=="false") $datedebut=$exe6[0]."-".$exe6[1]."-".$exe6[2];
	else $datedebut="null";
	if($datedebut=="null") $datefin = "null";
	
	 $sql_tot=" SELECT sum(imputation) as tot 
			   FROM imputation 
			   WHERE user= '".$id."'
				 And imputation.Project = '".$proj."'
				 And imputation.issue='".$issue."' 
				 AND imputation.DATE BETWEEN '".$datedebut."' AND '".$datefin."'"; 
	$execut= mysql_query($sql_tot);
	$tab_tot=mysql_fetch_array($execut);
	$total= $tab_tot ['tot'] ;
	if($total>0){
	?>
	<form action="supression.php" method="post" onSubmit="return loginCheck(this);">
	<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><?php include("entete.php"); ?></td>
	  </tr>
	   <tr>
	    <td><?php echo tab_vide(9); ?></td>
	  </tr> <tr>
	    <td align="center" id="titre-fiche">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><TABLE cellSpacing="0" cellPadding="1" width="100%" align="center" bgColor="#bbbbbb" border="0">
	              <TBODY>
	              <TR>
	                <TD vAlign="top" width="100%" colSpan="2">
	                  <TABLE cellSpacing="0" cellPadding="4" width="100%" 
	                  bgColor="#ffffff" border="0">
	                    <TBODY>
	                    <TR>
	                      <TD vAlign="top" width="180%" bgColor="#EFEFEF">
	                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
	                          <TBODY>
	                          <TR>
	                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><H3 class="formtitle"><?php echo $tab_parametres['confirmer_suppression'];?></H3>                              </TD>
	                          </TR></TBODY></TABLE></TD>
	                  </TBODY></TABLE></TD></TR></TBODY></TABLE><?php echo tab_vide(9); ?>
	    <?php include("connexion.php");
		?>
	
	           
	<?php /**********************************Ben smida Rihab le 07/03/2007**********************************/
	/*******************************************************************************************/
	
	    
	//requéte de sélection des imputations 
	 $r="SELECT DISTINCT (
		imputation.issue
		), project.ID, project.pname, jiraissue.summary
		FROM imputation, jiraissue, userbase, project
		WHERE imputation.Project = project.ID and imputation.Project =$proj AND imputation.issue = jiraissue.ID and imputation.issue =$issue AND imputation.user =".$id."
		AND imputation.user = userbase.id
		AND imputation.DATE
		BETWEEN '".$date1."'
		AND '".$date7."' ";
	$q= mysql_query($r);
	@$nb_enreg=mysql_num_rows($q);
	//$tab=mysql_fetch_array($q);
	if ($nb_enreg!=0){?>
	<TABLE cellSpacing="0" cellPadding="0" width="100%" align="center"bgColor="#bbbbbb" border="0">
	              <TBODY>
	              <TR>
	                <TD>
	                  <TABLE cellSpacing="1" cellPadding="4" width="100%" 
	                  bgColor="#bbbbbb" border="0">
	                    <TBODY>
	                    <tr valign="middle" align="center" bgColor="#EFEFEF">
	        <td  vAlign="middle" id="titre-projets"><b><?php echo $tab_parametres['projet'];?></b></td>
	        <td id="titre-taches"><b><?php echo $tab_parametres['taches'];?></b></td>
	        <td id="titre-Jour" ><b><?php echo $tab_parametres['lundi'];?></b><br />
	<?php 
	echo $d1_affich ;
	 ?>
	 <input name="date_lun" type="hidden" value="<?php echo $date1; ?>" /></td>
	        <td id="titre-Jour"  ><b><?php echo $tab_parametres['mardi'];?></b><br />
	
	<?php 
	
	echo $d2_affich;
	 ?><input name="date_mar" type="hidden" value="<?php echo $date2; ?>" /></td>
	        <td id="titre-Jour"  ><b><?php echo $tab_parametres['mercredi'];?></b><br />
	
	<?php 
	echo $d3_affich;
	 ?><input name="date_mer" type="hidden" value="<?php echo $date3; ?>" /></td>
	        <td id="titre-Jour"  ><b><?php echo $tab_parametres['jeudi'];?></b><br />
	
	<?php 
	echo $d4_affich;
	 ?><input name="date_jeu" type="hidden" value="<?php echo $date4; ?>" /></td>
	        <td id="titre-Jour" ><b><?php echo $tab_parametres['vendredi'];?></b><br />
	
	<?php 
	echo $d5_affich;
	 ?><input name="date_ven" type="hidden" value="<?php echo $date5; ?>" /></td>
	        <td id="titre-Jour" ><b><?php echo $tab_parametres['samedi'];?></b><br />
	
	<?php 
	echo $d6_affich;
	 ?><input name="date_sam" type="hidden" value="<?php echo $date6; ?>" /></td>
	        <td id="titre-Jour"  ><b><?php echo $tab_parametres['dimanche'];?></b><br />
	
	<?php
	echo $d7_affich;
	 ?>
	    <input name="date_dim" type="hidden" value="<?php echo $date7; ?>" /></td>
	        <td id="titre-Jour"><b><?php echo $tab_parametres['RAF'];?></b></td>
	        <td colspan="2" class="style2"><?php echo $tab_parametres['operation'];?></td>
	        </tr>
	      
		  <?php while ($tab=mysql_fetch_array($q)){ 
		    $id_tache=$tab['issue'];
	
	
	$datedeb1 = explode("-",$date1);
	if($lien_adm == 'actif'){
		
			$requete1 = requeteselection($date1,$date1,$id_tache, $id);
			$query1= mysql_query($requete1);	
			$tab1=mysql_fetch_array($query1);
			
		
		
		$datedeb2 = explode("-",$date2);
		
			$requete2 = requeteselection($date2,$date2,$id_tache,$id);
			$query2= mysql_query($requete2);
			$tab2=mysql_fetch_array($query2);
	
		
		$datedeb3 = explode("-",$date3);
		
			$requete3 = requeteselection($date3,$date3,$id_tache,$id);
			$query3= mysql_query($requete3);
			$tab3=mysql_fetch_array($query3);
	
		$datedeb4 = explode("-",$date4);
		
			$requete4 = requeteselection($date4,$date4,$id_tache,$id);
			$query4= mysql_query($requete4);
			$tab4=mysql_fetch_array($query4);
		
		
		$datedeb5 = explode("-",$date5);
		
			$requete5 = requeteselection($date5,$date5,$id_tache,$id);
			$query5= mysql_query($requete5);
			$tab5=mysql_fetch_array($query5);
		
		$datedeb6 = explode("-",$date6);
		
			$requete6 = requeteselection($date6,$date6,$id_tache,$id);
			$query6= mysql_query($requete6);
			$tab6=mysql_fetch_array($query6);
		
		
		$datedeb7 = explode("-",$date7);
		
			$requete7 = requeteselection($date7,$date7,$id_tache,$id);
			$query7= mysql_query($requete7);
			$tab7=mysql_fetch_array($query7);	
		
	
	}else{
			if($datedeb1[2] > $jour_max && $datedeb1[1] != date('m')){
			$tab1 = array();
		}else{
			$requete1 = requeteselection($date1,$date1,$id_tache, $id);
			$query1= mysql_query($requete1);	
			$tab1=mysql_fetch_array($query1);
			if(isset($tab1['imputation']) && $tab1['imputation'] != '0.00' ) {
				$verifImpV1 = verifImpIsValideDate($d1, $userC);
			}
	
		}
		
		$datedeb2 = explode("-",$date2);
		if($datedeb2[2] > $jour_max && $datedeb2[1] != date('m')){
			$tab2 = array();
		}else{
			$requete2 = requeteselection($date2,$date2,$id_tache,$id);
			$query2= mysql_query($requete2);
			$tab2=mysql_fetch_array($query2);
			if(isset($tab2['imputation']) && $tab2['imputation'] != '0.00' ) {
				$verifImpV2 = verifImpIsValideDate($d2, $userC);
			}
		}
		
		$datedeb3 = explode("-",$date3);
		if($datedeb3[2] > $jour_max && $datedeb3[1] != date('m')){
			$tab3 = array();
		}else{
			$requete3 = requeteselection($date3,$date3,$id_tache,$id);
			$query3= mysql_query($requete3);
			$tab3=mysql_fetch_array($query3);
			if(isset($tab3['imputation']) && $tab3['imputation'] != '0.00' ) {
				$verifImpV3 = verifImpIsValideDate($d3, $userC);
			}
		}
		$datedeb4 = explode("-",$date4);
		if($datedeb4[2] > $jour_max && $datedeb4[1] != date('m')){
			$tab4 = array();
		}else{
			$requete4 = requeteselection($date4,$date4,$id_tache,$id);
			$query4= mysql_query($requete4);
			$tab4=mysql_fetch_array($query4);
			if(isset($tab4['imputation']) && $tab4['imputation'] != '0.00' ) {
				$verifImpV4 = verifImpIsValideDate($d4, $userC);
			}
		}
		
		$datedeb5 = explode("-",$date5);
		if($datedeb5[2] > $jour_max && $datedeb5[1] != date('m')){
			$tab5 = array();
		}else{
			$requete5 = requeteselection($date5,$date5,$id_tache,$id);
			$query5= mysql_query($requete5);
			$tab5=mysql_fetch_array($query5);
			if(isset($tab5['imputation']) && $tab5['imputation'] != '0.00' ) {
				$verifImpV5 = verifImpIsValideDate($d5, $userC);
			}
		}
		
		$datedeb6 = explode("-",$date6);
		if($datedeb6[2] > $jour_max && $datedeb6[1] != date('m')){
			$tab6 = array();
		}else{
			$requete6 = requeteselection($date6,$date6,$id_tache,$id);
			$query6= mysql_query($requete6);
			$tab6=mysql_fetch_array($query6);
			if(isset($tab6['imputation']) && $tab6['imputation'] != '0.00' ) {
				$verifImpV6 = verifImpIsValideDate($d6, $userC);
			}
		}
		
		$datedeb7 = explode("-",$date7);
		if($datedeb7[2] > $jour_max && $datedeb7[1] != date('m')){
			$tab7 = array();
		}else{
			$requete7 = requeteselection($date7,$date7,$id_tache,$id);
			$query7= mysql_query($requete7);
			$tab7=mysql_fetch_array($query7);	
			if(isset($tab7['imputation']) && $tab7['imputation'] != '0.00' ) {
				$verifImpV7 = verifImpIsValideDate($d7, $userC);
			}
		}
		
	}
	$raf = requeteselection($date1,$date7,$id_tache,$id);
	$query_raf= mysql_query($raf);
	$tab_raf=mysql_fetch_array($query_raf);		
	// calcul total imputation par issue 
	
	
	$y = $_POST['y'];
	$semaine = $_POST['semaine'];
	
	//$fin_mois = fin_mois ($mm, $y);
	 if(isset($_POST['mm']) && $_POST['mm']!='' && isset($_POST['semaine']) && $_POST['semaine'] !='' && isset($_POST['y']) && $_POST['y'] !=''){
		$where = '?mm='.$_POST['mm'].'&week='.$_POST['semaine'].'&y='.$_POST['y'];
	}else{
		$where = '';
	}
	
	?>
		  
		  
		  <tr bgcolor="#FFFFFF" valign="middle" align="left">
	        <td vAlign="top" class="titre2" width="200"><?php echo $tab['pname']; $proj=$tab[1]; ?></td>
	        <td vAlign="top" class="titre2" width="300"><?php echo $tab['summary']; $issue=$tab[0];?></td>
	        <td class = "x2" <?php if($verifImpV1=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="lund1" class="input-jour"  value="<?php if(isset($tab1['imputation']) && $tab1['imputation'] != '0.00' ) { echo $tab1['imputation']; } ?>"/>
	        <?php if(isset($tab1['imputation']) && $tab1['imputation'] != '0.00' && $readOnly1 == "false") { echo $tab1['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV2=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="mar1" class="input-jour"  value="<?php if(isset($tab2['imputation']) && $tab2['imputation'] != '0.00') { echo $tab2['imputation']; } ?>"/><?php if(isset($tab2['imputation']) && $tab2['imputation'] != '0.00' && $readOnly2 == "false") { echo $tab2['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV3=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="mer1" class="input-jour"  value="<?php if(isset($tab3['imputation']) && $tab3['imputation'] != '0.00') { echo $tab3['imputation']; } ?>" /><?php if(isset($tab3['imputation']) && $tab3['imputation'] != '0.00' && $readOnly3 == "false") { echo $tab3['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV4=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="jeu1" class="input-jour" value="<?php if(isset($tab4['imputation']) && $tab4['imputation'] != '0.00') { echo $tab4['imputation']; } ?>"/><?php if(isset($tab4['imputation']) && $tab4['imputation'] != '0.00' && $readOnly4 == "false") { echo $tab4['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV5=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="ven1" class="input-jour" value="<?php if(isset($tab5['imputation']) && $tab5['imputation'] != '0.00') { echo $tab5['imputation']; } ?>"/><?php if(isset($tab5['imputation']) && $tab5['imputation'] != '0.00' && $readOnly5 == "false") { echo $tab5['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV6=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="sam1" class="input-jour" value="<?php if(isset($tab6['imputation']) && $tab6['imputation'] != '0.00') { echo $tab6['imputation']; } ?>"/><?php if(isset($tab6['imputation']) && $tab6['imputation'] != '0.00' && $readOnly6 == "false") { echo $tab6['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2" <?php if($verifImpV7=== true && $lien_adm !="actif"){?> style="background-color:red" title="Suppression impossible" <?php }?>><input type="hidden" name="dim1" class="input-jour" value="<?php if(isset($tab7['imputation']) && $tab7['imputation'] != '0.00') { echo $tab7['imputation']; } ?>"/><?php if(isset($tab7['imputation']) && $tab7['imputation'] != '0.00' && $readOnly7 == "false") { echo $tab7['imputation']; } ?>
	          &nbsp;</td>
	        <td class = "x2"><input type="hidden" name="raf1" class="input-jour" value="<?php if($tab_raf['RAF'] != '0.00') { echo $tab_raf['RAF']; } ?>"/><?php echo $tab_raf['RAF']; ?>
	          &nbsp;</td>
			<td align="center" id="titre-Jour">
			  <a href="<?php echo $lien_page.$id."&proj=$proj&issue=$issue&mm=$mm&semaine=$semaine&y=$y"; ?>"><img src="../images/annuler.gif" border="0"  title="<?php echo $tab_parametres['annuler']; ?>" /></a></td>
			<td align="center" id="titre-Jour"><a href="#"
			onclick="remplirFormDel('<?php echo $userC;?>','<?php echo $proj;?>','<?php echo $issue;?>','<?php echo $mm;?>','<?php echo $semaine;?>','<?php echo $total;?>','<?php echo $y;?>','<?php echo $lipms;?>','<?php echo $page;?>')"
			><img src="../images/modifier.gif" title="<?php echo $tab_parametres['supprimer']; ?>" width="20" height="20" /></a></td>
	      </tr> <?php } ?> </TBODY></TABLE> </TD></TR></TBODY></TABLE><?php }?></form>
	<?php } else { 
	 ?><?php
			echo tab_vide ( 20 );
			?>
	 <TABLE cellSpacing="0" cellPadding="1" width="100%" align="center"
				bgColor="#bbbbbb" border="0">
				<TBODY>
					<TR>
						<TD vAlign="top" width="100%" colSpan="2">
						<TABLE cellSpacing="0" cellPadding="4" width="100%"
							bgColor="#ffffff" border="0">
							<TBODY>
								<TR>
									<TD vAlign="top" width="180%" bgColor="#EFEFEF">
									<TABLE cellSpacing="0" cellPadding="0" width="100%">
										<TBODY>
											<TR>
												<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left">
												<H3 class=formtitle><?php
												echo "Pas d'imputations accessibles pour cette semaine";
												
												?></H3>
												<a href="suivi_imputation.php?<?php echo "mm=$mm&week=$semaine&y=$y"; ?>">retour</a></TD>
											</TR>
										</TBODY>
									</TABLE>
									</TD>
							
							</TBODY>
						</TABLE>
						</TD>
					</TR>
				</TBODY>
			</TABLE><?php
			echo tab_vide ( 20 );
			}
			?>
	</td>
	  </tr></Td>
	  </tr>
	  <tr>
	    <td width="100%" ></td>
	  </tr>
	 
	  <tr>
	    <td>&nbsp;</td>
	  </tr><?php include("bottom.php"); ?>
</body>
</html>