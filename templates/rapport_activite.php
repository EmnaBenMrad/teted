<?php
session_start();// On démarre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
session_destroy();  // On détruit la session
header("Location: login.php");
 }
//si le le login n'est pas fourni par session donc redirection vers la page login.php

$id_absence = 10064; // constante .:. L'ID du projet demande d'abscence

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
//$facturation=-1;
$nb_taches_assi = "";
  /*******************Asma OUESLATI 27/03/2007 
code lié aux paramétrage des pages 
*****************/
// $lg est le parametre de langue et c est egal 1 pour le français
$lg=1;
$sql_parametres="SELECT * 
				 FROM parametres 
				 WHERE langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de paramètrage***/
$img_path = $tab_parametres['img_path'];
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
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
}

$login = $_SESSION['login']; //récupération de la variable de session
//@$semaine = $_GET['semaine']; 
@$proj = $_REQUEST['proj'];
@$collab = $_REQUEST['collab'];
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
$nombre = 1000;  // on va afficher 5 résultats par page.
if (!isset($limite)) $limite = 0; // si on arrive sur la page pour la première fois 
               // on met limite à 0.
if(isset($_REQUEST['limite'])) { $limite = $_REQUEST['limite'] ; } 
if(isset($_REQUEST['affich'])) { $affich = $_REQUEST['affich'] ; }  
$limitesuivante = $limite + $nombre;
$limiteprecedente = $limite - $nombre;
if(isset($_REQUEST['pid'])) { $proj = $_REQUEST['pid']; }
@$af = $_REQUEST['af'];
if((isset($af)) and ($af==1)) { $affich = "afficher"; }
if(isset($_REQUEST['cid'])) { $collab = $_REQUEST['cid']; }
if(isset($_REQUEST['d1'])) { $Date = urldecode($_REQUEST['d1']); }
if(isset($_REQUEST['d2'])) { $Date2 = urldecode($_REQUEST['d2']); }
?>
<!------------------------ MEDINI Mounira Le 23/05/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['rapp_activi'];?></title>
<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style>
<?php include "../style/style_excel.html"; ?>
</head>

 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste déroulante -----------------//--><!--
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
// fonction pour le cas d'appui sur la touche entrée
function testsubmit() {
    if (document.formulaire.action=="") return false;
return true ;
}

//fonction pour choisir l'action
function gopage(page)
{
    document.formulaire.action = page;
	
	if(page=="export_rapport.php")
	{
	document.getElementById('rapport_activite').value=document.getElementById('div_form').innerHTML;document.getElementById('formulaire').submit()
	document.getElementById('proj').value=document.getElementById('proj').innerHTML;document.getElementById('formulaire').submit()
	document.getElementById('collaborateur').value=document.getElementById('collaborateur').innerHTML;document.getElementById('formulaire').submit()
	}
	else 
	{
	document.formulaire.submit();
	}
}
</script>
<script language="JavaScript" src="popcalendar.js"></script>
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
    <td valign="top" id="div_rap_activite"><H3 class=formtitle><?php echo $tab_parametres['rapp_activi'];?></H3>
</td>
  </tr>
  <tr><td><?php echo tab_vide(9); ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top" height="32">
    <td><?php 
 if((isset($watcher)) and ($watcher=="all"))
 {
$query = " SELECT 
                DISTINCT(project.ID),
				project.pname 
				  FROM project where 
				  project.ID not in(select na.source_node_id 
				from nodeassociation na
				where 
				na.source_node_entity = 'Project'
				and na.sink_node_entity = 'PermissionScheme'
				and na.association_type = 'ProjectScheme'
				and na.sink_node_id = 10220
				) order by project.pname ";
				
 }
 else {
$tab_proj = gestion_role($id, $login);
  $query = "Select 
				distinct(project.id), project.pname
			  FROM 
				project
			  WHERE 
				project.ID IN ".$tab_proj." 
				ORDER BY project.pname";
		}
		
  $result = mysql_query($query); ?>
  <select onchange="changePage2(this.form.projets.options[this.form.projets.options.selectedIndex].value)" 
            name="projets" id="projets" class="input-liste" style="width:300px">
  <option value="<?php echo "rapport_activite.php"; ?>"><?php echo $tab_parametres['liste_projet'];?></option>
  <?php while($row = mysql_fetch_array($result)){ 
  $pro = $row[0];  

?>
  <option value="<?php echo "rapport_activite.php?proj=".$row[0]; ?>" <?php if ((isset($proj)) and ($pro==$proj)){ echo "selected"; } ?>><?php echo $row[1]; ?>
  <?php } ?></option>
</select>&nbsp;&nbsp;<?php 
if((!isset($watcher)) or ($watcher!="all"))
{


 ?><select name="collab" id="collab" class="input-liste-collab">
<OPTION value="faux"><?php echo $tab_parametres['collab'];?></OPTION>
<?php 
// if((isset($watcher)) and ($watcher=="all"))

if(isset($proj)) { 
$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id ";

if($proj==$id_absence) { 
$query1.=" AND userbase.username = '$login' ";

}
else { 
$query1.=" and userbase.username IN ".liste_group_user($proj);
 }
 $query1 .=" AND userbase.ID  not in (select user from userbasestatus where status =0) ";
 $query1.=" ORDER BY propertyvalue ASC ";

		$result1 = mysql_query($query1);
		$nb = mysql_num_rows($result);
?>
		  <?php while($row1 = mysql_fetch_array($result1)){ 
		   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; ?>
<OPTION value="<?php echo $row1[0]; ?>" <?php if ((isset($collab)) and ($collab==$coll)){ echo "selected"; } ?> ><?php echo $nom; ?></OPTION>
<?php }
} 
 ?></select><?php  } ?><?php
//echo "<br>".liste_group_user($proj)."<br>";
if((isset($watcher)) and ($watcher=="all"))
{
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
";
if((isset($proj)) and (!empty($proj))) { 
$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
and userbase.username IN ".liste_group_user($proj); }
$query1 .=" AND userbase.ID  not in (select user from userbasestatus where status =0) ";
$query1.=" ORDER BY propertystring . propertyvalue ASC";	
$result1 = mysql_query($query1);
		
 ?><select name="collab" id="collab" class="input-liste-collab">
<OPTION value="faux"><?php echo $tab_parametres['collab'];?></OPTION>
		  <?php while($row1 = mysql_fetch_array($result1)){ 
		   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; ?>
<OPTION value="<?php echo $row1[0]; ?>" <?php if ((isset($collab)) and ($collab==$coll)){ echo "selected"; } ?> ><?php echo $nom; ?></OPTION>
<?php 
} 
 ?></select><?php } ?>&nbsp;

<?php echo $tab_parametres['du']; ?> :&nbsp;&nbsp;
<input type="text" name="Date" class="input-annee" value="<?php echo @FormatDateTime($Date,2) ?><?php if (isset($date)) { echo $date; } ?>" />
      &nbsp;
      <input name="image" type="image" onclick="popUpCalendar(this, this.form.Date,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
&nbsp;&nbsp;<?php echo $tab_parametres['au']; ?>:&nbsp;&nbsp;
<input type="text" name="Date2" class="input-annee" value="<?php echo @FormatDateTime($Date2,2) ?><?php if (isset($date2)) { echo $date2; } ?>" />
      &nbsp;
      <input name="image" type="image" onclick="popUpCalendar(this, this.form.Date2,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
      &nbsp;&nbsp;<input name="proj" type="hidden" value="<?php echo $proj; ?>" /><input name="affich" type="hidden" value="afficher" />
 <input type="submit" name="Affich" value="<?php echo $tab_parametres['affich'];?>"  onClick="gopage('rapport_activite.php')" />
  <input type="submit" name="exporter" value="Exporter vers Excel"  onClick="gopage('export_rapport.php')"  /><input type="hidden" name="rapport_activite" id="rapport_activite" value="1" />
  <input type="hidden" name="proj" id="proj" value="<?php echo $proj; ?>" />
  </td>
  </tr>
</table>
</td></tr>
<tr><td>
<div id="div_form">
 <table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td>
  
  
  
  <?php 

if((isset($affich)) and (!empty($proj)))
{
$tab_security = security($login, $proj); 
$select_issue ="SELECT (jiraissue.ID),
						 jiraissue.SUMMARY,
						 imputation.Date,
						 round(sum(imputation.imputation),2) as som,
						 imputation.RAF,
						 jiraissue.ASSIGNEE,
						 userbase.ID,
						 jiraissue.pkey
				FROM jiraissue, imputation, userbase, project
				WHERE project.ID = imputation.project
				AND project.ID = $proj
				AND (jiraissue.security IN $tab_security OR jiraissue.security is NULL)
				AND imputation.user = userbase.ID
				AND imputation.issue = jiraissue.ID
				AND (imputation.imputation) is NOT NULL  
				AND userbase.ID  not in (select user from userbasestatus where status =0) 
				";
if(((!isset($watcher)) or ($watcher!="all")) and ($proj==$id_absence))
{
$select_issue.=" AND jiraissue.REPORTER = '$login' ";

}
if((isset($collab)) and ($collab!="faux")) { $select_issue.="and userbase.ID = '$collab' "; }
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$Date = format_date($Date);
$select_issue.="AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$Date2 = format_date($Date2);
$select_issue.="AND (imputation.Date) <=  '$Date2' "; }
$assigne_null =$select_issue."AND jiraissue.ASSIGNEE is NULL ";
$select_issue.="AND jiraissue.ASSIGNEE is NOT NULL ";
 
$select_imp=$select_issue."GROUP BY imputation.ID 
				ORDER BY jiraissue.ASSIGNEE, imputation.Date";
$select_issue.="GROUP BY imputation.issue, imputation.user  
				ORDER BY imputation.user, imputation.issue";

$assigne_imp=$assigne_null."GROUP BY imputation.ID 
				ORDER BY imputation.Date";
$assigne_null.="GROUP BY imputation.issue  
				ORDER BY imputation.issue, imputation.Date";

$query_issue = mysql_query($select_issue) or die("erreur"); 
$nb_taches = mysql_num_rows($query_issue);

$query_assign = mysql_query($assigne_null) or die("erreur"); 
$nb_taches_assi = mysql_num_rows($query_assign);
/********************************* Sélectionne la portion d'enregistrements à afficher. *******************************/
/*$select_issue.=" LIMIT $limite,$nombre";
$query_issue = mysql_query($select_issue);*/
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
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b><?php echo $tab_parametres['format_date_nonvalide '];?></b></font>                        </TD>
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
/********************************************************************************************************/
if ($nb_taches_assi>0)
{
echo tab_vide(20);
$tot_raf=0;
 }
/********************************************************************************************************/
if (($nb_taches>0) or ($nb_taches_assi>0))
{
echo tab_vide(20);
$tot_raf=0;
 ?> 
<TABLE cellSpacing="1" cellPadding="1" width="100%" bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td width="42%" id="div_td"><b>T&acirc;che/Sous-T&acirc;che</b></td>
        <td width="20%" id="div_td"><b><?php echo $tab_parametres['collab'];?></b></td>
        <td width="9%" id="div_td"><b>Consomm&eacute;e</b></td>
        <td width="9%" id="div_td"><b><?php echo $tab_parametres['RAF'];?></b></td>
        </tr>
  <?php 
  $tab_collab = array(); $i=0; $som_collab = 0; $som = 0; $somme_raf = 0; 
  while ($tab = mysql_fetch_array($query_issue))
{
$userid = $tab[6];
$tab_collab[$i] = $tab[6]; 
$som = $som + format_number($tab[3]); 
?><?php if(($i>0) and ($tab_collab[$i]!=$tab_collab[$i-1])) { ?>
		 <tr valign="top" align="left" bgcolor="#E9E9E9">
        <td id="div_td" >&nbsp;</td>
        <td align="right" class="titre2" id="div_td" ><span class="titre2"><b><?php echo $tab_parametres['sous_tot_collab']."&nbsp;&nbsp;".nom_prenom_user($tab_collab[$i-1]); ?></b></span></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($som_collab)); $som_collab = 0; ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel($somme_raf); $tot_raf = $tot_raf+$somme_raf; $somme_raf = 0; ?></td>
        </tr>
		 <?php  }  $i++;  ?>
	  <tr valign="top" align="left" bgcolor="#FFFFFF" height="20">
        <td class="titre2" id="div_td"  ><?php 
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		$nb_imp = nbre_impu($tab[0],$verif_date1,$Date,$verif_date2,$Date2,$userid);
		//$nb_imp = (nbre_impu($tab[0])); 
		if ($nb_imp>0) { 
		?> 
		<a onclick="javascript:showHideRow('<?php echo $tab[7]."_".$tab[6]?>', '<?php echo $nb_imp?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" id="img_<?php echo $tab[7]."_".$tab[6]?>"/>&nbsp;&nbsp;<?php echo $tab[7]."&nbsp;:&nbsp;".$tab[1]; ?> </a> <?php }else { ?><img src="<?php echo $img_path; ?>icone.jpg" />&nbsp;&nbsp;<?php echo $tab[7]."&nbsp;:&nbsp;".$tab[1]; } //echo $nb_imp; ?> </td>
        <td class="titre2" valign="top" id="div_td">&nbsp;&nbsp;<?php echo nom_prenom_user($tab[6]); ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($tab[3])); $som_collab = $som_collab + format_number($tab[3]); ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php 
$select_raf = "SELECT  
					imputation.RAF 
			   FROM imputation, jiraissue, project, userbase 
			   WHERE project.ID = imputation.project AND project.ID = $proj
			   AND jiraissue.ID = imputation.issue AND jiraissue.ID = ".$tab[0]."
			   AND userbase.ID = imputation.user AND userbase.id = '".$tab[6]."'
			   AND imputation.Date = (SELECT max(imputation.Date) FROM imputation WHERE imputation.issue = ".$tab[0]."
			   AND imputation.user = '".$tab[6]."')"	; 
$query_raf = mysql_query($select_raf); $som_raf=0; 
while($raf = mysql_fetch_array($query_raf)) { $som_raf = $som_raf + $raf[0];  }
 echo format_number_excel($som_raf); $somme_raf = $somme_raf + $som_raf; 
 ?></td>
        </tr>
		<?php if ($nb_imp>0) {
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; } 
		$query_imp = select_imp($tab[0],$tab[6],$verif_date1,$Date,$verif_date2,$Date2);

		$nb_imput = mysql_num_rows($query_imp);
		$ig=1; $cmt=0; $sem = ''; $add_raf=0;
		while($imp = mysql_fetch_array($query_imp))
		{
		$date_imp = explode('-',$imp['Date']);
		$y_imp = $date_imp[0]; $m_imp = $date_imp[1]; $d_imp = $date_imp[2];
		$week_number = weeknumber ($y_imp, $m_imp, $d_imp);
		//$dd_imp = datefromweek ($y_imp, $week_number, '0'); $df_imp = datefromweek ($y_imp, $week_number, '6');
		if($sem != $week_number) { $sem = $week_number; $ech_raf = "vrai"; $add_raf=$add_raf+$imp['RAF']; } else { $ech_raf = "faux"; }
		 if($ig==$nb_imput) { $img="arbor"; }
		else { $img = "arbo"; } 
		 ?>
   <tr id ="<?php echo $tab[7]."_".$tab[6]."_".$cmt; ?>" style="display:none"  bgcolor="#FCF2F2">
<td><span class="style-rapport">&nbsp;</span></td>
    <td><span class="style-rapport"><img src="<?php echo $img_path.$img; ?>.gif" align="absmiddle" hspace="0" border="0" />
        <?php  echo format_date2($imp['Date']); ?>
    </span></td>
    <td class="style-rapport" align="right"><?php echo format_number_excel(format_number($imp['imputation'])); ?></td>
    <td class="style-rapport" align="right"><?php  if ($ech_raf=="vrai") { echo format_number_excel($imp['RAF']); 		$rraf = $imp['RAF'];
	} ?></td>
  </tr><?php $ig++;  $cmt++;  } } ?>
		<?php if($i==$nb_taches) { ?>
		 <tr valign="top" align="left" bgcolor="#E9E9E9">
        <td id="div_td">&nbsp;</td>
        <td id="div_td" width="20%" align="right" class="titre2"><span class="titre2"><b><?php echo $tab_parametres['sous_tot_collab']."&nbsp;&nbsp;".nom_prenom_user($tab_collab[$i-1]); ?></b></span></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($som_collab)); ?></td>
        <td class="style-rapport" align="right"  id="div_td"><?php $tot_raf = $tot_raf+$somme_raf;  echo format_number_excel($somme_raf);  ?></td>
        </tr><?php }  } $somme_raf = 0; ?>
<?php 
  $tab_collab = array(); $i=0; $som_collab = 0; $som_assign = 0; $somme_raf = 0; 
  while ($tab = mysql_fetch_array($query_assign))
{
$userid = $tab[6];
$tab_collab[$i] = $tab[6]; 
$som_assign = $som_assign + format_number($tab[3]); 
?>
		 
		
	  <tr valign="top" align="left" bgcolor="#FFFFFF" height="20">
        <td class="titre2" id="div_td"  ><?php 
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		if(!isset($collab)) { $collab="faux"; }
		$nb_imp = nbre_impu($tab[0],$verif_date1,$Date,$verif_date2,$Date2,$collab);
		//$nb_imp = (nbre_impu($tab[0])); 
		if ($nb_imp>0) { 
		?> 
		<a onclick="javascript:showHideRow('<?php echo $tab[7]."_".$tab[6]?>', '<?php echo $nb_imp?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" id="img_<?php echo $tab[7]."_".$tab[6]?>"/>&nbsp;&nbsp;<?php echo $tab[7]."&nbsp;:&nbsp;".$tab[1]; ?> </a> <?php }else { ?><img src="<?php echo $img_path; ?>icone.jpg" />&nbsp;&nbsp;<?php echo $tab[7]."&nbsp;:&nbsp;".$tab[1]; } //echo $nb_imp; ?> </td>
        <td class="titre2" valign="top" id="div_td">&nbsp;&nbsp;<?php echo "NON ASSIGNEE"; ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($tab[3])); $som_collab = $som_collab + format_number($tab[3]); ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php 


if((isset($collab)) and ($collab!="faux")) { $collab_raf = $collab; } else { $collab_raf=0; }
 $som_raf = select_dernier_raf($tab[0],0);
 echo format_number_excel($som_raf); $somme_raf = $somme_raf + $som_raf; 
 ?></td>
        </tr>
		<?php if ($nb_imp>0) {
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		if(!isset($collab)) { $collab="faux"; }
		$query_imp = select_imp($tab[0],$collab,$verif_date1,$Date,$verif_date2,$Date2);
		$nb_imput = mysql_num_rows($query_imp);
		$ig=1; $cmt=0; $sem = ''; $add_raf=0;
		while($imp = mysql_fetch_array($query_imp))
		{
		$date_imp = explode('-',$imp['Date']);
		$y_imp = $date_imp[0]; $m_imp = $date_imp[1]; $d_imp = $date_imp[2];
		$week_number = weeknumber ($y_imp, $m_imp, $d_imp);
		//$dd_imp = datefromweek ($y_imp, $week_number, '0'); $df_imp = datefromweek ($y_imp, $week_number, '6');
		if($sem != $week_number) { $sem = $week_number; $ech_raf = "vrai"; $add_raf=$add_raf+$imp['RAF']; } else { $ech_raf = "faux"; }
		 if($ig==$nb_imput) { $img="arbor"; }
		else { $img = "arbo"; } 
		 ?>
   <tr id ="<?php echo $tab[7]."_".$tab[6]."_".$cmt; ?>" style="display:none"  bgcolor="#FCF2F2">
<td align="right"><span class="style-rapport">&nbsp;
  <?php  echo nom_prenom_user($imp[4]);  ?>&nbsp;&nbsp;&nbsp;
</span></td>
    <td><span class="style-rapport"><img src="<?php echo $img_path.$img; ?>.gif" align="absmiddle" hspace="0" border="0" />
        <?php  echo format_date2($imp['Date']); ?>
    </span></td>
    <td class="style-rapport" align="right"><?php echo format_number_excel(format_number($imp['imputation'])); ?></td>
    <td class="style-rapport" align="right"><?php  if ($ech_raf=="vrai") { echo format_number_excel($imp['RAF']); 		$rraf = $imp['RAF'];
	} ?></td>
  </tr><?php $ig++;  $cmt++;  } }   }  ?>	 
	 <?php if($nb_taches_assi!=0) { ?> 
	 <tr valign="top" align="left" id="texte-bleu12n" bgcolor="#E9E9E9">
	    <td align="right" class="style2" id="div_td" >&nbsp;</td>
	    <td align="right" class="style2" id="div_td"><span class="titre2"><b><?php echo $tab_parametres['sous_tot_collab']."&nbsp;&nbsp;NON ASSIGNEE"; ?></b></span></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel(format_number($som_assign)); ?></b></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel($somme_raf); ?></b></td>
	    </tr><?php } ?> <tr valign="top" align="left" id="texte-bleu12n" bgcolor="#E9E9E9">
	    <td align="right" class="style2" id="div_td" >&nbsp;</td>
	    <td align="right" class="style2" id="div_td"><span class="titre2"><b><?php echo "Total"; ?></b></span></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel(format_number($som+$som_assign)); ?></b></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel($tot_raf+$somme_raf); ?></b></td>
	    </tr></TBODY></TABLE>
<table width="100%" border="0" cellpadding="10" cellspacing="10">
  <tr>
    <td align="center"><?php if($limite != 0) {
$page = "rapport_activite.php";
$page.="?limite=$limiteprecedente";
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; } 
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
echo '<a href="'.$page.'">Page précédente</a>';
} echo "<img src='../images/spacer.gif' height='1' width='50' />";
if($limitesuivante < $nb_taches) {
$page = "rapport_activite.php";
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
elseif($nb_taches_assi==0)
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
						userbase.username
				FROM project, imputation, userbase, jiraissue 
				WHERE   project.ID=imputation.project
						AND userbase.ID=imputation.user 
						AND userbase.ID = '$collab'
						AND imputation.issue = jiraissue.ID
						AND jiraissue.project = project.ID
						AND imputation.imputation is NOT NULL ";
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
//echo $select_issue; 
$query_issue = mysql_query($select_issue) or die("erreur"); 
$nb_taches = mysql_num_rows($query_issue);


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
 ?> <TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
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
                            <TD vAlign=top width="80%" bgColor="#EFEFEF"><font color="#CC0000"><b>
							&nbsp;<?php echo $tab_parametres['list_proj_de'];?> "<?php echo nom_prenom_user($collab); ?>"</b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE><br />
<br />
<TABLE cellSpacing="1" cellPadding="1" width="100%" bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td width="53%" align="center" id="div_td"><b><?php echo $tab_parametres['taches'];?></b></td>
        <td width="23%" align="center" id="div_td"><b><?php echo $tab_parametres['collab'];?></b></td>
        <td width="8%" align="center" id="div_td"><b><?php echo $tab_parametres['date'];?></b></td>
        <td width="9%" align="center" id="div_td"><b><?php echo $tab_parametres['consom'];?></b></td>
        <td width="7%" align="center" id="div_td"><b><?php echo $tab_parametres['RAF'];?></b></td>
        </tr>
  <?php 
  $tab_proj = array(); $nom_proj = array(); $i=0; $som_collab = 0; $som = 0; $somme_raf = 0; $sous_tot_raf=0;
  		$tot_raf = 0;
  while ($tab = mysql_fetch_array($query_issue))
{
$tab_proj[$i] = $tab[0];
$nom_proj[$i] = $tab[1];
 
$som = $som + format_number($tab[3]); 
?><?php if(($i>0) and ($tab_proj[$i]!=$tab_proj[$i-1])) { ?>
		 <?php  }  $i++;  ?>
	  <tr valign="top" align="left" bgcolor="#FFFFFF">
        <td class="titre2" id="div_td" ><?php $nb_task = nbre_task($tab[0], $tab[6]); if($nb_task>0) { ?> <a onclick="javascript:showHideRow('<?php echo $tab[0]."_".$tab[6]?>','<?php echo $nb_task; ?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" hspace="10" id="img_<?php echo $tab[0]."_".$tab[6]?>"/><?php echo $tab[1]; ?> </a> <?php }  ?> </td>
        <td class="titre2" valign="top" id="div_td">&nbsp;&nbsp;<?php echo nom_prenom_user($tab[6]); ?></td>
        <td align="center" class="style-rapport" nowrap="nowrap" id="div_td"><?php echo format_date2($tab[2]); ?></td>
        <td align="right" class="style-rapport" id="div_td"><?php echo format_number_excel(format_number($tab[3])); $som_collab = $som_collab + format_number($tab[3]); ?></td>
        <td align="right" class="style-rapport" id="div_td" ><?php 
$raf_issue   = "SELECT DISTINCT (issue)
			    FROM imputation
		        WHERE project =$tab[0]
			    AND user ='$tab[5]'";
$query_raf_is= mysql_query($raf_issue);
$val_raf1=0; 
while ($rtab = mysql_fetch_array($query_raf_is))
{
/***********************************************/
$select_raf1 = "SELECT  
					RAF
			   FROM imputation 
			   WHERE imputation.issue = $rtab[0]
			   AND imputation.RAF is NOT NULL
			   AND imputation.RAF != 0
			   AND imputation.user = '$tab[5]'
			   AND imputation.Date = (SELECT max(Date) FROM imputation WHERE imputation.issue = $rtab[0]
			   AND imputation.user = '$tab[5]' )
			   GROUP BY WEEK(imputation.Date)"; 
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$select_raf1.=" AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$select_raf1.=" AND (imputation.Date) <=  '$Date2' "; }


$query_raf1 = mysql_query($select_raf1);  
while($raf1 = mysql_fetch_array($query_raf1)) { $val_raf1 = $val_raf1 + $raf1[0];  }
/***********************************************/
}
echo $val_raf1;
 ?></td>
        </tr>
		<?php if ($nb_task>0) { 
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		$query_task = select_task($tab[0],$tab[6],$verif_date1,$Date,$verif_date2,$Date2);
		$nb_task=mysql_num_rows($query_task);
		$ig=1; $cmt=0;
		while ($task = mysql_fetch_array($query_task))
		{
		if($ig==$nb_task) { $img="arbor"; }
		else { $img = "arbo"; }
		 ?>
		  <tr id ="<?php echo $tab[0]."_".$tab[6]."_".$cmt; ?>" style="display:none"  bgcolor="#FCF2F2">
    <td colspan="3"  class="texte-bleu11n">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo $img_path.$img; ?>.gif" height="20" width="23" align="absmiddle" hspace="0" /><?php echo $task[7]."&nbsp;&nbsp;:&nbsp;&nbsp;".$task[1]; //print_r($task); 
	 ?></td>
    <td class="style-rapport" align="right"><?php echo format_number_excel(format_number($task[3])); ?></td>
    <td id="texte-bleu12n" align="right"><?php 
$select_raf = "SELECT  
					RAF
			   FROM imputation 
			   WHERE imputation.issue = $task[0]
			   AND imputation.RAF is NOT NULL
			   AND imputation.RAF != 0
			   AND imputation.user = '$task[6]'
			   AND imputation.Date = (SELECT max(Date) FROM imputation WHERE imputation.issue = $task[0]
			   AND imputation.user = '$task[6]' )
			   GROUP BY WEEK(imputation.Date)"; 
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$select_raf.=" AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$select_raf.=" AND (imputation.Date) <=  '$Date2' "; }


$query_raf = mysql_query($select_raf); $val_raf=0; 
while($raf = mysql_fetch_array($query_raf)) { $val_raf = $val_raf + $raf[0];  }
if($val_raf!=0) { echo format_number_excel($val_raf); $tot_raf = $tot_raf + $val_raf; }

  $sous_tot_raf = $sous_tot_raf + $val_raf; 
 ?></td>
  </tr>
		 <?php $ig++; $cmt++; }
}
?>
		<?php if($i==$nb_taches) { ?><?php } } ?>
	 
	  
	 <tr valign="top" align="left" id="texte-bleu12n" bgcolor="#E9E9E9">
	    <td align="right" class="titre2" id="div_td" ><b><?php echo $tab_parametres['total'];?></b></td>
	    <td align="right" class="style2" id="div_td"></td>
	    <td align="center" id="div_td"></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php echo format_number_excel(format_number($som)); ?></b></td>
	    <td align="right" class="style-rapport" id="div_td"><b><?php  echo format_number_excel(format_number($tot_raf));  ?>
	    </b></td>
	    </tr> </TBODY></TABLE>
 <table width="100%" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center"><?php if($limite != 0) {
$page = "rapport_activite.php";
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
$page = "rapport_activite.php";
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
elseif($nb_taches_assi==0)
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
                    <TR id = "seif">
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>
							<?php echo $tab_parametres['pa_resultat'];?></b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>
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
   //alert(cmp);
for(i = 0; i< cmp; i++){
//alert (id+"_"+i);
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

 }
</script>
</html>

