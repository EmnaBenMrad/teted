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
$html="";
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
<?php 
	
	
  $liste_pnf = list_proj_non_facture();	

 $html .='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>'.chaineformat($tab_parametres['rapp_activi']).'</title>
<!--<link href="../style/style_excel.css" rel="stylesheet" type="text/css" />-->
<!--<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>-->
<!--<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>-->
<!--<LINK href="../style/global.css" type=text/css rel=StyleSheet>-->
</head>
<body vLink=#003366 aLink=#cc0000 link=#003366 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">';

 $html.='<div id="div_form">
 <table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td>';

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



?> <?php if(((isset($verif_date1)) and ($verif_date1=='false')) or ((isset($verif_date2)) and ($verif_date2=='false'))) {  
$html.='
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
							'.chaineformat($tab_parametres['format_date_nonvalide ']).'</b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>';
 }
if($periode_validation=="faux") {  
$html.='<TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
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
							'.chaineformat($tab_parametres['msg_periode_validation']).'</b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>';
}
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {
if($compar_date=="faux")
{
$html.= tab_vide(20);
 
 $html.=' 
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
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>'.chaineformat($tab_parametres['datefin_sup_datedeb']).'</b></font></TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>';
 }
 }
if ($nb_taches>0)
{
$html.=tab_vide(20);
$tot_raf=0;
$html.='
<TABLE cellSpacing="1" cellPadding="1" width="80%" bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td id="id_project"><b>Projet</b></td>
        <td ><div id="id_task" ><b>'.chaineformat($tab_parametres['taches']).'</b></div></td>
        <td><div id="id_collab" ><b>'.chaineformat($tab_parametres['collab']).'</b></div></td>
        <td><div id="id_valideur" ><b>'.chaineformat($tab_parametres['consom']).'</b></div></td>

        </tr>';

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
?><?php if(($i>0) and ($tab_collab[$i]!=$tab_collab[$i-1])) { 
		$html.=' <tr valign="top" align="left" bgcolor="#E9E9E9">
		<td  >&nbsp;</td>
        <td id="div_td" >&nbsp;</td>
        <td align="right" class="titre2" id="div_td" ><span class="titre2"><b>'.chaineformat($tab_parametres['sous_tot_collab'])."&nbsp;&nbsp;".chaineformat(nom_prenom_user($tab_collab[$i-1])).'</b></span></td>
        <td align="right" class="style-rapport" id="div_td">'.format_number_excel(format_number($som_collab)); 
		$som_collab = 0; 
       $html.=' </td>
        </tr>';
		  }  $i++; 
	$html.='  <tr valign="top" align="left" bgcolor="#FFFFFF" height="20">
	   
        <td class="titre2" id="div_td" valign="top"  >';
		if(!isset($verif_date1)) { $verif_date1="false"; $Date=""; }
		if(!isset($verif_date2)) { $verif_date2="false"; $Date2=""; }
		$nb_imp = nbre_impu($tab[0],$verif_date1,$Date,$verif_date2,$Date2,$userid);
		//$nb_imp = (nbre_impu($tab[0])); 
		if ($nb_imp>0) { 
		
		$html.='<a style="cursor:hand"><img src="'.$img_path.'detail_plus.jpg" id="img_'.$tab[7]."_".$tab[6].'"/>&nbsp;&nbsp;'.$tab[8].'</a>';  }else { 
		$html.='<img src="'.$img_path.'icone.jpg" />&nbsp;&nbsp;'.$tab[8]; } //echo $nb_imp; 
		
		$html.='</td>
		 <td class="titre2">&nbsp;&nbsp;'.chaineformat($tab[7])."&nbsp;:&nbsp;".chaineformat($tab[1]).'</td>
        <td class="titre2" valign="top" id="div_td">&nbsp;&nbsp;'.chaineformat(nom_prenom_user($tab[6])).'</td>
        <td align="right" class="style-rapport" id="div_td">';
		
		 $cmt=0; $html.= format_number_excel(format_number($tab[3]));
         $som_collab = $som_collab + format_number($tab[3]); 
         
         $html.='</td>
       
        </tr>';
		 if ($nb_imp>0) {
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
		 
		
		 
	 $html.='	 
   <tr id ="'.$tab[7]."_".$tab[6]."_".$cmt.'"   bgcolor="#FCF2F2">
   <td  ><span class="style-rapport">&nbsp;</span></td>
	<td><span class="style-rapport">&nbsp;</span></td>
    <td><span class="style-rapport"><img src="'.$img_path.$img.'.gif" align="absmiddle" hspace="0" border="0" />
        '."&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;".format_date2($imp['Date']).'
    </span></td>
    <td  class="style-rapport" id="div_td" align="right">'.format_number_excel(format_number($imp['imputation'])).'</td>
   
    </tr>';$ig++;  $cmt++;  } } ?>
		<?php if($i==$nb_taches) { 
		 $html.='
		 <tr valign="top" align="left" bgcolor="#E9E9E9">
		 <td id="div_td">&nbsp;</td>
        <td id="div_td">&nbsp;</td>
        <td id="div_td" width="14%" align="left" class="titre2"><span class="titre2"><b>'."&nbsp;&nbsp;".chaineformat($tab_parametres['sous_tot_collab'])."&nbsp;&nbsp;".chaineformat(nom_prenom_user($tab_collab[$i-1])).'</b></span></td>
        <td align="right" class="style-rapport" id="div_td">'.format_number_excel(format_number($som_collab)).'</td>
        </tr>'; }  } 
	 
	  $html.=' 
	 <tr valign="top" align="left" id="texte-bleu12n" bgcolor="#E9E9E9">
	    <td align="right" class="style2" id="div_td" >'.chaineformat($tab_parametres['total']).'</td>
	    <td align="right" class="style2" id="div_td"></td>
	    <td align="right" class="style-rapport" id="div_td">&nbsp;</td>
	    <td align="right" class="style-rapport" id="div_td"><b>'.format_number_excel(format_number($som)).'</b></td>
	    
	    </tr> </TBODY></TABLE>
<table width="100%" border="0" cellpadding="10" cellspacing="10">
  <tr>
    <td align="center">';
	   if($limite != 0) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limiteprecedente";
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; } 
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
}  $html.= "<img src='../images/spacer.gif' height='1' width='50' />";
if($limitesuivante < $nb_taches) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limitesuivante";
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }    
}

 $html.='</td>
  </tr>
</table>';


  
 }
elseif($periode_validation=="vrai")
{
 $html.= tab_vide(20);

 $html.='
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
							'.chaineformat($tab_parametres['pa_resultat']).'</b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>';
} 
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

?> <?php if(((isset($verif_date1)) and ($verif_date1=='false')) or ((isset($verif_date2)) and ($verif_date2=='false'))) {  

 $html.='<TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
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
							'.chaineformat($tab_parametres['format_date_nonvalide']).'</b></font>                        </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE><br />';
}
if(((isset($verif_date1)) and ($verif_date1!='false')) and ((isset($verif_date2)) and ($verif_date2!='false'))) {
if($compar_date=="faux")
{
 $html.= tab_vide(20);
  $html.='<TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
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
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="center"><font color="#CC0000"><b>'.chaineformat($tab_parametres['datefin_sup_datedeb']).'</b></font></TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE>';
 }
 }
 

 
if ($nb_taches>0)
{
 $html.= tab_vide(20);
 
  $html.='
<br />
<br />
<table width="100%" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center">'; if($limite != 0) {
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limiteprecedente";
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
    
     $html.= '<a href="'.$page.'">Page précédente</a>';
}  $html.= "<img src='../images/spacer.gif' height='1' width='50' />";
if($limitesuivante < $nb_taches) {
 $html.= $collab;
$page = $_SERVER['PHP_SELF'];
$page.="?limite=$limitesuivante";
if(isset($Date)) { $d1=urlencode($Date); $page.="&d1=$d1"; }
if(isset($Date2)) { $d2=urlencode($Date2); $page.="&d2=$d2"; }    
if(isset($proj)) { $page.="&pid=$proj"; }
if(isset($affich)) { $page.="&af=1"; }
if(isset($collab)) { $page.="&cid=$collab"; }    
     $html.= '<a href='.$page.'>Page Suivante</a>';
}
$html.='</td>
  </tr>
</table>';
  
 }
else
{
 $html.= tab_vide(20);
?>
<?php } 
} 

   $html.='</td>
</tr>
</table><div id="collaborateur">';
  if((isset($collab)) and ($collab!="faux")) { $html.='<input type="hidden" name="collaborateur" id="collaborateur" value="'.$collab;'" />';  } 
   $html.=' </div>
</div>';


 if(isset($html) && $html !="")
{
$nom_fichier_excel = "rapport_histo_projet";
if(isset($_REQUEST['proj']) and (!empty($_REQUEST['proj'])))
{
$nom_fichier_excel.= "_".pkey_proj($_REQUEST['proj']);
}
if((isset($_REQUEST['collaborateur'])) and (!empty($_REQUEST['collaborateur'])))
{
$nom_fichier_excel.= "_".login_user($_REQUEST['collaborateur']);
}
if((isset($_REQUEST['y'])) and (!empty($_REQUEST['y'])))
{
$nom_fichier_excel.= "_".$_REQUEST['y'];
}
if((isset($_REQUEST['mm'])) and (!empty($_REQUEST['mm'])))
{
$nom_fichier_excel.= "_".$_REQUEST['mois'];
}
//echo $_REQUEST['mois']; echo $_REQUEST['annee']; echo $_REQUEST['type'];
header('Content-disposition: attachement; filename="'.$nom_fichier_excel.'.xls"');
header("Content-Type: application/x-msexcel");
//header('Pragma: no-cache');
header('Expires: 0');

$rapport_activite = $html;	
//$rapport_activite = str_replace('$ligne_supp', "&nbsp;&nbsp;", $rapport_activite);	
$rapport_activite = str_replace("div_td", "div_td_rap", $rapport_activite);	
$rapport_activite = str_replace("detail_plus.jpg", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("arbo.gif", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("arbor.gif", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("detail_moin.jpg", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", $rapport_activite);
?>
<?php include "../style/style_excel.html"; ?>
<table width="100%" border="0">
<TR valign="bottom" bgcolor="#FFFFFF">
          <TD vAlign="top" nowrap="nowrap" width="400"><A 
            href="suivi_imputation.php"><IMG class=logo 
            height=37 alt="Business&amp;Decision Tunisie" 
            src="'.$img_path; ?>logo_bd_tn.png" 
            width=400 border="0"></A></TD>
          <TD vAlign="bottom" nowrap="nowrap" align="right" width="250" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: &nbsp;&nbsp;<?php echo nom_prenom_user($id); ?>&nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; </TD>
  </TR>

</table>
<br />
<?php
//echo $_REQUEST["ligne_supp"];
echo $rapport_activite;
}
 ?>
</body></html>

