<?php 
 
session_start();// On demarre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
session_destroy();  // On detruit la session
header("Location: login.php");
 }
//si le le login n'est pas fourni par session donc redirection vers la page login.php
if (!isset($_SESSION['login'])) 
{
  header("Location: login.php");
}

//print_r($_SESSION['groupetab']); 
$id = $_SESSION['id'];
$user = $_SESSION['id'];
 include "connexion.php"; 
 include "fonctions.php"; 
 include "function_date.php";

 
$valid_projetct = "desactif";
// $lg est le parametre de langue et c est egal 1 pour le franeais
$lg=1;
$sql_parametres="SELECT * 
				 FROM parametres 
				 WHERE langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de parametrage***/

/*$jour_max  = $tab_parametres['parametre_jour']; 
 
 $jour_cour = date('d'); $mois_cour = date('m'); $annee_cour = date('Y');
 
 if(($mois_cour=='01') and ($jour_cour < $jour_max)) { $annee_deb = $annee_cour-1; $mois_deb = 12; $mois_fin = 12; $annee_fin = $annee_cour; $mm='12'; }
 else {
 if($jour_cour < $jour_max) { $mois_deb = (date('m') - 1); } else { $mois_deb = date('m'); }
   if(strlen($mois_deb)==1) { $mois_deb="0".$mois_deb; }
 $mois_fin = date('m');
 $annee_deb = date('Y');  $annee_fin = date('Y'); 
}*/

$jour_cour = date('d'); 
 $mois_cour = date('m');
$annee_cour = date('Y');


$periode_validation = "vrai";

$img_path = $tab_parametres['img_path'];
$i = 0;
$j=0; 
$valid_imputation = "inactif";
$liste_group = membershipbase($id);
$nb_group = count($liste_group);
$tab_group=array();
for($i=0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j=0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if(strtolower($list_group)=="project-watcher"){ $watcher = "all"; }
if(strtolower($list_group)=="td-administrators"){ $valid_imputation = "actif"; $valid_projetct="actif";}
if(strtolower($list_group)=="bd-cp"){ $valid_imputation = "actif"; }
}
if($valid_imputation=="inactif") {
?>
<script language="javascript">document.location.href="../templates/login.php"</script>
<?php
}
$login = $_SESSION['login']; //recuperation de la variable de session
//@$semaine = $_GET['semaine']; 
if(isset($_REQUEST['proj'])) { $proj = $_REQUEST['proj']; }
if(isset($_REQUEST['facturation'])) { $facturation = $_REQUEST['facturation']; }else{$facturation = -1;}    
if(isset($_REQUEST['collab'])) { $collab = $_REQUEST['collab']; }
if(isset($_REQUEST['mois'])) { $mois = $_REQUEST['mois']; }
if(isset($_REQUEST['annee'])) { $annee = $_REQUEST['annee']; }
if(!isset($annee)) $annee = date('Y');
if(!isset($mois)) $mois = date('m');

(isset($annee))? $annee_deb = $annee : $annee_deb = date('Y');
(isset($mois))? $mois_deb = $mois : $mois_deb = date('Y');
(isset($annee))? $annee_fin = $annee : $annee_fin = date('Y');
(isset($mois))? $mois_fin = $mois : $mois_fin = date('Y');

if(!empty($_POST['Date']))
{ $Date = $_POST['Date'];
$verif_date1 = verif_date($Date);
} 

if(!empty($_POST['Date2']))
{ $Date2 = $_POST['Date2'];
$verif_date2 = verif_date($Date2);
} 
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {

$compar_date = comparaison_date($Date2, $Date);
}
$nombre = 1000;  // on va afficher 5 resultats par page.
if (!isset($limite)) $limite = 0; // si on arrive sur la page pour la premiere fois 
               // on met limite e 0.
if(isset($_REQUEST['limite'])) { $limite = $_REQUEST['limite'] ; } 
if(isset($_REQUEST['affich'])) { $affich = $_REQUEST['affich'] ; }  
$limitesuivante = $limite + $nombre;
$limiteprecedente = $limite - $nombre;
if(isset($_REQUEST['pid'])) { $proj = $_REQUEST['pid']; }
@$af = $_REQUEST['af'];
if((isset($af)) and ($af==1)) { $affich = "afficher"; }
if(isset($_REQUEST['cid'])) { $collab = $_REQUEST['cid']; }
/*if(isset($_REQUEST['d1'])) { $Date = urldecode($_REQUEST['d1']); }
if(isset($_REQUEST['d2'])) { $Date2 = urldecode($_REQUEST['d2']); }*/
/*******************          MEDINI Mounira Le 10-04-2008 .:. Ajoue de Date et mois pour choix des projets e valider           ***************/

if(((isset($annee)) and ($annee!='nothing')) and ((isset($mois)) and ($mois!='nothing')))
{ $j_fin = fin_mois($mois, $annee); 
$Date = '01/'.$mois.'/'.$annee; 
$Date2 = $j_fin.'/'.$mois.'/'.$annee; }
if(((isset($annee)) and ($annee!='nothing')) and ((!isset($mois)) or ($mois=='nothing')))
{ $j_fin = fin_mois(12, $annee); 
$Date = '01/01/'.$annee; 
$Date2 = $j_fin.'/12/'.$annee; }

if(((!isset($annee)) or ($annee=='nothing')) and ((isset($mois)) and ($mois!='nothing')))
{ $j_fin = fin_mois($mois, date('Y')); 
$Date = '01/'.$mois.'/'.date('Y'); 
$Date2 = $j_fin.'/'.$mois.'/'.date('Y'); }




if(!empty($_POST['Date']))
{ $Date = $_POST['Date'];
} 

if(!empty($_POST['Date2']))
{ $Date2 = $_POST['Date2'];
} 
if(isset($Date)) { $verif_date1 = verif_date($Date); } 
if(isset($Date2)) { $verif_date2 = verif_date($Date2); } 
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {

$compar_date = comparaison_date($Date2, $Date);
}

$jfin_fin = fin_mois($mois_fin, $annee_fin);
$date_deb = "01/".$mois_deb."/".$annee_deb;
$date_fin = $jfin_fin."/".$mois_fin."/".$annee_fin; 

if((isset($Date)) and (isset($Date2)))
{
$compar_date_deb1 = comparaison_date($Date, $date_deb); if ($Date==$date_deb) { $compar_date_deb1="true"; }
$compar_date_deb2 = comparaison_date($date_fin, $Date); if ($Date==$date_fin) { $compar_date_deb2="true"; }
 
$compar_date_fin1 = comparaison_date($Date2, $date_deb); if ($Date2==$date_deb) { $compar_date_fin1="true"; }
$compar_date_fin2 = comparaison_date($date_fin, $Date2); if ($Date2==$date_fin) { $compar_date_fin2="true"; }
//echo $Date."=>".$Date2."=>".$compar_date_deb1."=>".$compar_date_deb2."=>".$compar_date_fin1."=>".$compar_date_fin2;

	if($compar_date_deb1=="faux" or $compar_date_deb2=="faux" or $compar_date_fin1=="faux" or $compar_date_fin2=="faux")
	{
	//$periode_validation = "faux";
	$Date=$date_deb;
	$Date2=$date_fin;
	}
$periode_validation = "vrai";
}
?>
<!------------------------ MEDINI Mounira Le 23/05/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['valid_imputation']; ?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<!--<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>-->
<!--<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>-->
<!--<LINK href="../style/global.css" type=text/css rel=StyleSheet>-->
<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style>
<?php include "../style/style_excel.html"; ?>
</head>
 
 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste deroulante -----------------//--><!--
			function changePage1(x)
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
<script language="javascript">
// fonction pour le cas d'appui sur la touche entree
function testsubmit() {
    if (document.formulaire.action=="") return false;
return true ;
}

//fonction pour choisir l'action
function gopage(page)
{
	if($('#projets').val() =="<?php echo $_SERVER['PHP_SELF']; ?>"){
		alert('Veuillez choisir un projet');
		return false;
	}else{
	    document.formulaire.action = page;
		
		if(page=="export_rapport_history.php")
		{
		document.getElementById('rapport_validation').value=document.getElementById('div_form').innerHTML;document.getElementById('formulaire').submit()
		
		document.getElementById('proj').value=document.getElementById('proj').innerHTML;document.getElementById('formulaire').submit()
		document.getElementById('collaborateur').value=document.getElementById('collaborateur').innerHTML;document.getElementById('formulaire').submit()
		}
		else 
		{
		document.formulaire.submit();
		}
	}
} 

function IsNumeric(sText)

{
   var ValidChars = "0123456789.,";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }
   

	
 
</script> 
<script language="JavaScript" src="popcalendar.js"></script>
<script language="JavaScript" src="../ajax.js"></script>
 <script type="text/javascript"
	src="../js/jquery.js">
	</script> 
<body vLink=#003366 aLink=#cc0000 link=#003366 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">
 
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top">
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  
  <tr><td> 
 <form name="formulaire" method="post" action="" onSubmit="return testsubmit()">
 
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
  
   <tr >
    <td> <?php echo tab_vide(9); ?></td>
  </tr>  <tr>
    <td valign="top" id="div_rap_activite"><H3 class=formtitle><?php echo $tab_parametres['valid_imputation'];?></H3>
</td>
  </tr>
  <tr><td><?php echo tab_vide(9); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top" height="32">
    <td><?php 
	
	
  $liste_pnf = list_proj_non_facture();	

$query = "SELECT DISTINCT (project.ID), project.pname
			FROM project 
			left join tetd_project_validate on(tetd_project_validate.project = project.ID)
			left join imputation on(imputation.project = project.ID)
			where tetd_project_validate.user =" . $user . " AND  
				  project.ID  in(select na.source_node_id 
				from nodeassociation na
				where 
				na.source_node_entity = 'Project'
				and na.sink_node_entity = 'PermissionScheme'
				and na.association_type = 'ProjectScheme'
				and na.sink_node_id = 10220
				) ";
			  
 
	$query.=" ORDER BY project.pname";
	

//echo $tab_proj;die;
		

$listproj = array();
  $result = mysql_query($query);  ?>
  <select onChange="changePage2(this.form.projets.options[this.form.projets.options.selectedIndex].value)" 
            name="projets" id="projets" class="input-liste"  style="width:250px">
  <option value=""><?php echo $tab_parametres['liste_projet'];?></option>
  <?php $jj = 0;while($row = mysql_fetch_array($result)){ 
  	$listproj[$jj] = $row[0];
  $pro = $row[0];  $jj++;?>
  <option value="<?php echo $_SERVER['PHP_SELF']."?proj=".$row[0]; ?>" <?php if ((isset($proj)) and ($pro==$proj)){ echo "selected"; } ?>><?php echo $row[1]; ?>
  <?php } ?></option>
</select>&nbsp;&nbsp;<?php


 ?><select name="collab" id="collab" class="input-liste-collab">
<OPTION value="faux"><?php echo $tab_parametres['collab'];?></OPTION>
<?php 
if(!isset($proj)) { 
	$pp = $listproj;
}else{
	$pp =array('0'=>$proj);
}


// if((isset($watcher)) and ($watcher=="all"))
if(isset($proj)) { 
$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
AND userbase.username IN ".liste_group_user_f($pp)." 
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
<?php }
//}
 ?></select>&nbsp;&nbsp;&nbsp;<select name="annee" id="annee" class="input-annee" style="width:60px;">
		  <?php 
		    for ($i=2007;$i<=2019;$i++) 
{?>
            <option <?php if ((isset($annee)) and ($i==$annee))  { echo "selected=\"selected\""; } ?> value="<?php echo $i; ?>"><?php echo $i; ?> </option><?php } ?>
          </select>&nbsp;           <select name="mois" id="mois" class="input-annee" style="width:77px;" >
  <?php 
	for ($i=0;$i<12;$i++) 
{
if($i<9) { $idmois = '0'.($i+1); } else { $idmois = $i+1; }
?>
  <option value="<?php echo $idmois; ?>" <?php if((isset($mois)) and (($idmois)==$mois))
{ 
echo "selected"; 
} //elseif(($i+1)==date('m')) { echo "selected=\"selected\""; }
 ?>><?php echo nom_mois($i); ?></option>
  <?php } ?>
</select>&nbsp;<?php   //echo $tab_parametres['du']; ?> &nbsp;&nbsp;
<input type="hidden" name="Date" class="input-annee" value="<?php echo @FormatDateTime($Date,2) ?><?php if (isset($date)) { echo $date; } ?>"   />
      &nbsp;
      <input name="image" type="image" style='display:none;' onClick="popUpCalendar(this, this.form.Date,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
&nbsp;&nbsp;<?php //echo $tab_parametres['au']; ?>&nbsp;&nbsp;
<input type="hidden" name="Date2" class="input-annee" value="<?php echo @FormatDateTime($Date2,2) ?><?php if (isset($date2)) { echo $date2; } ?>" />
      &nbsp;
      <input name="image" type="image" style='display:none;' onClick="popUpCalendar(this, this.form.Date2,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
      &nbsp;&nbsp;<input name="proj" type="hidden" value="<?php echo $proj; ?>" /><input name="affich" type="hidden" value="afficher" />
 <input type="button" name="Affich" value=" "  onClick="gopage('<?php echo $_SERVER['PHP_SELF']; ?>')" class="buttonImg" />&nbsp;&nbsp;<input type="submit" name="exporter" value=" "  onClick="gopage('export_rapport_history.php')"  class="buttonExcelImg" /><input type="hidden" name="rapport_validation" id="rapport_validation" value="1" />
  <input type="hidden" name="proj" id="proj" value="<?php if(isset($proj)) { echo $proj; } ?>" /></td>
  </tr>
</table>
</td></tr>
<tr><td>
<div id="div_form">
 <table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><?php 

if((isset($affich)))
{

$tabbb = list_membershipbase ($id);
$tab_security = security($login, $proj); 
$select_issue ="SELECT jiraissue.ID,
						 jiraissue.SUMMARY,
						 imputation.Date,
						 round(sum(imputation.imputation),2) as som,
						 imputation.RAF,
						 jiraissue.ASSIGNEE,
						 userbase.ID,
						 jiraissue.pkey,
						 project.pname,
						 project.LEAD,
						 project.id
				FROM jiraissue, imputation, userbase, project
				WHERE project.ID = imputation.project ";
				
				if(isset($proj) and (!empty($proj))){
					$select_issue.=' AND project.ID ='.$proj;
				}else{
					$select_issue.=" AND project.ID in (SELECT DISTINCT (project.ID)
				FROM project 
				left join tetd_project_validate on(tetd_project_validate.project = project.ID)
				left join imputation on(imputation.project = project.ID)
				where tetd_project_validate.user =" . $user . " AND  
					  project.ID  in(select na.source_node_id 
					from nodeassociation na
					where 
					na.source_node_entity = 'Project'
					and na.sink_node_entity = 'PermissionScheme'
					and na.association_type = 'ProjectScheme'
					and na.sink_node_id = 10220
					)) ";
				}
				
				$select_issue.=" AND (jiraissue.security IN $tab_security OR jiraissue.security is NULL)
				AND imputation.user = userbase.ID
				AND imputation.issue = jiraissue.ID
				AND (imputation.imputation) is NOT NULL 
				AND userbase.ID  not in (select user from userbasestatus where status =0)
				";
if((isset($collab)) and ($collab!="faux")) { $select_issue.="and userbase.ID = '$collab' "; }
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$Date = format_date($Date);
$select_issue.="AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$Date2 = format_date($Date2);
$select_issue.="AND (imputation.Date) <=  '$Date2' "; }
if(isset($facturation) && $facturation >=0 ) { 
$select_issue.= " AND imputation.validation = $facturation "; 
}
$select_imp=$select_issue."GROUP BY imputation.ID 
				ORDER BY jiraissue.ASSIGNEE, imputation.Date";

$select_issue.="GROUP BY imputation.issue, imputation.user  
				ORDER BY imputation.user, imputation.Date";

$select_issue; 

$query_issue = mysql_query($select_issue) or die("erreur"); 
$nb_taches = mysql_num_rows($query_issue);

/********************************* Selectionne la portion d'enregistrements e afficher. *******************************/
$select_issue.=" LIMIT $limite,$nombre";
$query_issue = mysql_query($select_issue);
//echo $select_issue;



?> <?php if(((isset($verif_date1)) and ($verif_date1=='false')) or ((isset($verif_date2)) and ($verif_date2=='false'))) {  ?><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>
							<?php echo $tab_parametres['format_date_nonvalide '];?></b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php }
if($periode_validation=="faux") {  ?><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>
							<?php echo $tab_parametres['msg_periode_validation']; ?></b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php }
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {
if($compar_date=="faux")
{
echo tab_vide(20);
  ?><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b><?php echo $tab_parametres['datefin_sup_datedeb'];?></b></font></TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php }
 }
if ($nb_taches>0)
{
echo tab_vide(20);
$tot_raf=0;
 ?> 
<TABLE cellSpacing="1" cellPadding="1" width="80%" bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td id="id_project"><b>Projet</b></td>
        <td ><div id="id_task" ><b><?php echo $tab_parametres['taches'];?></b></div></td>
        <td><div id="id_collab" ><b><?php echo $tab_parametres['collab'];?></b></div></td>
        <td><div id="id_valideur" ><b><?php echo $tab_parametres['consom'];?></b></div></td>

        </tr>
  <?php 
  $tab_collab = array(); $i=0; $som_collab = 0; $som = 0; $somme_raf = 0;
  while ($tab = mysql_fetch_array($query_issue))
{
$issueid = $tab[0];
$userid = $tab[6];
$LEAD = $tab[9];
$validV = false;
$validD = false;
$verifValidateur = isvalideProject($tab[10],$_SESSION['id']);
if($verifValidateur > 0){
	$validV = true;
}
if($valid_projetct =='actif'){
	$validV = true;
}




$tab_collab[$i] = $tab[6]; 
$som = $som + format_number($tab[3]); 
?><?php if(($i>0) and ($tab_collab[$i]!=$tab_collab[$i-1])) { ?>
		 <tr valign="top" align="left" bgcolor="#E9E9E9">
		<td  >&nbsp;</td>
        <td id="div_td" >&nbsp;</td>
        <td align="right" class="titre2" id="div_td" ><span class="titre2"><b><?php echo $tab_parametres['sous_tot_collab']."&nbsp;&nbsp;".nom_prenom_user($tab_collab[$i-1]); ?></b></span></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($som_collab)); $som_collab = 0; ?></td>
        </tr>
		 <?php  }  $i++;  ?>
	  <tr valign="top" align="left" bgcolor="#FFFFFF" height="20">
	   
        <td class="titre2" id="div_td" valign="top"  ><?php 
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		$nb_imp = nbre_impu($tab[0],$verif_date1,$Date,$verif_date2,$Date2,$userid);
		//$nb_imp = (nbre_impu($tab[0])); 
		if ($nb_imp>0) { 
		?> 
		<a onClick="javascript:showHideRow('<?php echo $tab[7]."_".$tab[6]?>', '<?php echo $nb_imp?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" id="img_<?php echo $tab[7]."_".$tab[6]?>"/>&nbsp;&nbsp;<?php echo $tab[8]; ?> </a> <?php }else { ?><img src="<?php echo $img_path; ?>icone.jpg" />&nbsp;&nbsp;<?php echo $tab[8]; } //echo $nb_imp; ?>  </td>
		 <td class="titre2">&nbsp;&nbsp;<?php echo $tab[7]."&nbsp;:&nbsp;".$tab[1]; ?></td>
        <td class="titre2" valign="top" id="div_td">&nbsp;&nbsp;<?php echo nom_prenom_user($tab[6]); ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php $cmt=0; echo format_number_excel(format_number($tab[3])); $som_collab = $som_collab + format_number($tab[3]); ?></td>
       
        </tr>
		<?php if ($nb_imp>0) {
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		$query_imp = select_imp($tab[0],$tab[6],$verif_date1,$Date,$verif_date2,$Date2,$facturation);
		$nb_imput = mysql_num_rows($query_imp);
		$ig=1; 
		while($imp = mysql_fetch_array($query_imp))
		{$id_imputation = $imp[0];
		$verifImput = isvalideIssue($id_imputation);
		if($verifImput > 0){
			$validD = true;
		}else{
			$validD = false;
		}
		 if($ig==$nb_imput) { $img="arbor"; }
		else { $img = "arbo"; }

		 $val_validation = affich_validation($id_imputation); 
		 
		
		 ?>
   <tr id ="<?php echo $tab[7]."_".$tab[6]."_".$cmt; ?>" style="display:none"  bgcolor="#FCF2F2">
   <td  ><span class="style-rapport">&nbsp;</span></td>
	<td><span class="style-rapport">&nbsp;</span></td>
    <td><span class="style-rapport"><img src="<?php echo $img_path.$img; ?>.gif" align="absmiddle" hspace="0" border="0" />
        <?php echo "&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;".format_date2($imp['Date']);  ?>
    </span></td>
    <td  class="style-rapport" id="div_td" align="right"><?php echo format_number_excel(format_number($imp['imputation'])); ?></td>
   
    </tr><?php $ig++;  $cmt++;  } } ?>
		<?php if($i==$nb_taches) { ?>
		 <tr valign="top" align="left" bgcolor="#E9E9E9">
		 <td id="div_td">&nbsp;</td>
        <td id="div_td">&nbsp;</td>
        <td id="div_td" width="14%" align="left" class="titre2"><span class="titre2"><b><?php echo "&nbsp;&nbsp;".$tab_parametres['sous_tot_collab']."&nbsp;&nbsp;".nom_prenom_user($tab_collab[$i-1]); ?></b></span></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($som_collab)); ?></td>
        </tr><?php }  } ?>
	 
	  
	 <tr valign="top" align="left" id="texte-bleu12n" bgcolor="#E9E9E9">
	    <td align="right" class="style2" id="div_td" ><?php echo $tab_parametres['total'];?></td>
	    <td align="right" class="style2" id="div_td"></td>
	    <td align="right" class="style-rapport" id="div_td">&nbsp;</td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel(format_number($som)); ?></b></td>
	    
	    </tr> </TBODY></TABLE>
<table width="100%" border="0" cellpadding="10" cellspacing="10">
  <tr>
    <td align="center"><?php if($limite != 0) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limiteprecedente";
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; } 
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
echo '<a href="'.$page.'">Page précédente</a>';
} echo "<img src='../images/spacer.gif' height='1' width='50' />";
if($limitesuivante < $nb_taches) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limitesuivante";
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }    
    echo '<a href='.$page.'>Page Suivante</a>';
}
?></td>
  </tr>
</table>


  
<?php }
elseif($periode_validation=="vrai")
{
echo tab_vide(20);
?>
<TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>
							<?php echo $tab_parametres['pa_resultat'];?></b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php } 
}elseif((isset($affich)) and (empty($proj)) and (!empty($collab)))
{ 
$select_issue ="SELECT  project.ID, 
						project.pname, 
						min(imputation.Date) as min, 
						sum(imputation.imputation) as som, 
						imputation.RAF, 
						imputation.user, 
						userbase.ID,
						userbase.username,
						project.LEAD
				FROM project, imputation, userbase 
				WHERE   project.ID=imputation.project
						AND userbase.ID=imputation.user 
						AND userbase.ID = '$collab'
						AND imputation.imputation is NOT NULL 
						AND  project.ID not in(select na.source_node_id 
					from nodeassociation na
					where 
					na.source_node_entity = 'Project'
					and na.sink_node_entity = 'PermissionScheme'
					and na.association_type = 'ProjectScheme'
					and na.sink_node_id = 10220
					)";
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$Date = format_date($Date);
$select_issue.="AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$Date2 = format_date($Date2);
$select_issue.="AND (imputation.Date) <= '$Date2' "; }

$select_issue.="GROUP BY project.ID 
				ORDER BY project.pname, imputation.Date";
$select_issue; 
$query_issue = mysql_query($select_issue) or die("erreur"); 
$nb_taches = mysql_num_rows($query_issue);
$select_issue.=" LIMIT $limite,$nombre";
$query_issue = mysql_query($select_issue);

?> <?php if(((isset($verif_date1)) and ($verif_date1=='false')) or ((isset($verif_date2)) and ($verif_date2=='false'))) {  ?><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>
							<?php echo $tab_parametres['format_date_nonvalide'];?></b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE><br />
<?php }
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {
if($compar_date=="faux")
{
echo tab_vide(20);
  ?><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="1" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b><?php echo $tab_parametres['datefin_sup_datedeb'];?></b></font></TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
<?php }
 }
 

 
if ($nb_taches>0)
{
echo tab_vide(20);
 ?>
<br />
<br />
<table width="100%" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center"><?php if($limite != 0) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limiteprecedente";
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
    
    echo '<a href="'.$page.'">Page précédente</a>';
} echo "<img src='../images/spacer.gif' height='1' width='50' />";
if($limitesuivante < $nb_taches) {
echo $collab;
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limitesuivante";
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }    
    echo '<a href='.$page.'>Page Suivante</a>';
}
?></td>
  </tr>
</table>
  
<?php }
else
{
echo tab_vide(20);
?>
<?php } 
} 
  ?></td>
</tr>
</table><div id="collaborateur"><?php if((isset($collab)) and ($collab!="faux")) { ?><input type="hidden" name="collaborateur" id="collaborateur" value="<?php echo $collab; ?>" /> <?php } ?></div>
</div></td></tr>
</Table>

</form>


</Td></TR>
<tr>
  <td> 
    <?php include("bottom.php"); ?></td>
</tr>
</table>

</body>
<script language="javascript">
 function showHideRow(id,cmp){
for(i = 0; i< cmp; i++){
   var 	obj = document.getElementById(id+"_"+i);
  var img = document.getElementById("img_"+id);
  	if(obj.style.display == "")
	{
  		obj.style.display = "none";
		img.src = "<?php echo $img_path; ?>detail_plus.jpg";
  	}else{
  		obj.style.display = "";
		img.src = "<?php echo $img_path; ?>detail_moin.jpg";
  	}
  }
  // alert(cmp);
 }
</script>
</html>

