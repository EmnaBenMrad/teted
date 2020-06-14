<?php
session_start();// On dï¿½marre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
session_destroy();  // On dï¿½truit la session
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

  /*******************Asma OUESLATI 27/03/2007 
code lies au parametrages des pages 
*****************/
// $lg est le parametre de langue et c est egal 1 pour le franï¿½ais
$lg=1;
$sql_parametres="SELECT * 
				 FROM parametres 
				 WHERE langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de paramï¿½trage***/
$img_path = $tab_parametres['img_path'];
$i = 0;
$j=0; 
$valid_imputation = "inactif";
$liste_group = membershipbase($id);
$nb_group = count($liste_group);
$tab_group=array();
for($i==0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j==0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if(strtolower($list_group)=="project-watcher"){ $watcher = "all"; }
if(strtolower($list_group)=="td-administrators"){ $valid_imputation = "actif"; }
if(strtolower($list_group)=="bd-cp"){ $valid_imputation = "actif"; }
}
if($valid_imputation=="inactif") {
?>
<script language="javascript">document.location.href="../templates/login.php"</script>
<?php
}
$login = $_SESSION['login']; //rï¿½cupï¿½ration de la variable de session
//@$semaine = $_GET['semaine']; 
if(isset($_REQUEST['collab'])) { $collab = $_REQUEST['collab']; $_SESSION['collab'] = $collab; }
if(isset($_REQUEST['m'])) { $m = $_REQUEST['m']; } else { $m=date('m'); }
if(isset($_REQUEST['y'])) { $y = $_REQUEST['y']; } else { $y=date('Y'); }
if(isset($_REQUEST['week'])) { $week = $_REQUEST['week']; } else { $week=date('W'); }

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
.input-liste-collab1 {	BORDER-RIGHT: #9284CA 1px solid;
	BORDER-TOP: #9284CA 1px solid;
	BORDER-LEFT: #9284CA 1px solid;
	COLOR: #274F7A ;
	BORDER-BOTTOM: #9284CA 1px solid;
	height: 15px;
	width: 120px;
	FONT: 11px Arial, Helvetica, sans-serif;
	vertical-align:middle;
}
-->
</style>

</head>

 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste dï¿½roulante -----------------//--><!--
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

<script language="JavaScript" src="../ajax.js"></script>
<body vLink=#003366 aLink=#cc0000 link=#003366 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top">
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  
  <tr><td>
 <form name="formulaire" method="post" action="" onSubmit="return verifform()">
 
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
  
   <tr >
    <td> <?php echo tab_vide(9); ?></td>
  </tr>  <tr>
    <td valign="top"><H3 class=formtitle><?php echo $tab_parametres['valid_imputation'];?></H3></td>
  </tr>
  <tr><td><?php echo tab_vide(9); ?>
<TABLE cellSpacing="0" cellPadding="0" width="100%" 
            bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD>
                  <TABLE align="right" cellSpacing="1" cellPadding="4" width="100%" 
                  bgColor="#bbbbbb" border="0">
                    <TBODY bgColor="#EFEFEF">
                   <tr height="20" valign="bottom" class="txte-bleu10b" bgcolor="#EFEFEF">
         <td height="10" colspan="13" align="left"><?php /********** mettre les mois de l'année dans un tableau ***********/
$mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aôut", "Septembre", "Octobre", "Novembre", "Décembre");
?>
		  <?php echo $tab_parametres['annee'];?>&nbsp;: 
		  <select onchange=changePage1(this.form.annee.options[this.form.annee.options.selectedIndex].value) 
            name="annee" id="annee" class="input-annee">
		  <?php 
		  for ($i=2007;$i<=2019;$i++) 
{ ?>
            <option <?php if ($i==$y) { echo "selected=\"selected\""; } ?> value="update_imputation.php?y=<?php echo $i; ?>"><?php echo $i; ?> </option><?php } ?>
          </select>          &nbsp;&nbsp;<input name="y" id="y" type="hidden" value="<?php echo $y; ?>" />
          <?php echo $tab_parametres['mois'];?>&nbsp;:
           <select onChange="changePage1(this.form.mois.options[this.form.mois.options.selectedIndex].value)" 
            name="mois" id="mois" class="input-annee" style="width:80px;" >
  <option value=nothing><?php echo $tab_parametres['mois'];?></option>
  <?php 
	 for ($i=0;$i<12;$i++) 
{ 
?>
  <option value="update_imputation.php?m=<?php echo ($i+1); ?>&y=<?php echo $y; ?>" <?php if(($i+1)==$m)
{ 
echo "selected"; 
}
 ?>><?php echo nom_mois($i); ?></option>
  <?php } ?>
</select><input name="m" id="m" type="hidden" value="<?php echo $m; ?>" /> 
        &nbsp; &nbsp;<?php echo $tab_parametres['semaine'];?>&nbsp;:
          <select onChange="changePage1(this.form.semaine.options[this.form.semaine.options.selectedIndex].value)" name="semaine" id="semaine" class="input-annee"><?php $d=01;
$fin_mois = fin_mois ($m, $y);
$prem_sem = weeknumber ($y, $m, $d); //Le numéro de semaine du début du mois
$fin_sem = weeknumber ($y, $m, $fin_mois);
for ($i=$prem_sem; $i<=$fin_sem; $i++) {

?>
 <option  value="<?php echo "update_imputation.php?m=$m&week=$i&y=$y"; ?>" <?php if ($i==$week) { echo "selected=\"selected\""; } ?>><?php echo $i; ?> </option><?php } ?>
  </select> 
          &nbsp;
          <?php echo $tab_parametres['collab'];?>&nbsp;:&nbsp;<select name="collab" id="collab" class="input-liste-collab1">
            <option value="faux"><?php echo $tab_parametres['collab'];?></option>
            <?php 
// if((isset($watcher)) and ($watcher=="all"))
$query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
ORDER BY propertyvalue ASC ";
		$result1 = mysql_query($query1);
		$nb = mysql_num_rows($result);
?>
            <?php while($row1 = mysql_fetch_array($result1)){ 
		   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; ?>
            <option value="<?php echo $row1[0]; ?>" <?php if (((isset($collab)) and ($collab==$coll)) or ((isset($_SESSION['collab'])) and ($_SESSION['collab']==$coll)) ){ echo "selected"; }  ?> ><?php echo $nom; ?></option>
            <?php }

 ?>
          </select>
          &nbsp;<input name="week" id="week" type="hidden" value="<?php echo $week; ?>" />  
         <span class="txte-bleu10b"> <?php $prem_sem = weeknumber ($y, $m, $d); //Le numéro de semaine du début du mois
$fin_sem = weeknumber ($y, $m, $fin_mois); //Le numéro de semaine de la fin du mois
if(($week > $fin_sem) || ($week < $prem_sem))
{
$week = $prem_sem;
}
$dat1 = (datefromweek ($y, $week, '0'));
$dat2 = (datefromweek ($y, $week, '6'));
echo $tab_parametres['du']." ".": ".$dat1_aff = $dat1['day']."-".$dat1['month']."-".$dat1['year'];
echo "  ".$tab_parametres['au']." ".": ".$dat2_aff = $dat2['day']."-".$dat2['month']."-".$dat2['year'];
 ?>
         <input type="submit" name="Affich" value=" "  onclick="gopage('<?php echo $_SERVER['PHP_SELF']; ?>')" class="buttonImg" />
         </span></td>
                   </tr></TBODY></TABLE></TD></TR></TBODY></TABLE>
</td></tr>
<tr><td><?php echo tab_vide(12); 
?>
  <?php /**********************************MEDINI Mounira le 29/04/2008*************************************/
/****************************************************************************************************/
//requète de sélection des imputations 
if((isset($week)) and (isset($y)) and (isset($m)) and (isset($collab)))
{
$exe = (datefromweek ($y, $week, '0'));
$date1 = $exe['year']."-".$exe['month']."-".$exe['day'];

$exe7 = (datefromweek ($y, $week, '6'));
$date7 = $exe7['year']."-".$exe7['month']."-".$exe7['day'];

$r="SELECT DISTINCT (imputation.issue), project.ID, project.pname,jiraissue.pkey, jiraissue.summary
	FROM imputation, jiraissue, userbase, project
	WHERE imputation.Project = project.ID
	AND imputation.issue = jiraissue.ID
	AND imputation.user =".$collab."
	AND imputation.user = userbase.id
	AND imputation.DATE BETWEEN '".$date1."' AND '".$date7."' ";
$r.= "ORDER BY (project.pname)";
$q= mysql_query($r);
$nb_enreg=mysql_num_rows($q);
//$tab=mysql_fetch_array($q);
if ($nb_enreg!=0){?>
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
                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b><?php echo $tab_parametres['recapitulation_imputation'];?></b></font>                           </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>
<TABLE cellSpacing="0" cellPadding="0" width="100%" align="center" border="0">
              <TR>
                <TD vAlign="top" width="100%" colSpan="2"><img src="../images/spacer.gif" width="1" height="12" /></TD>
              </TR></TABLE>
	<div id="erreur"></div> 
  <TABLE cellSpacing="0" cellPadding="0" width="100%" align="center" 
            bgColor="#bbbbbb" border="0">
              
			  <TBODY>
              <TR>
                <TD>
                  <TABLE cellSpacing="1" cellPadding="4" width="100%" 
                  bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td><div id="titre-projets" ><?php echo $tab_parametres['projet'];?></div></td>
        <td><div id="titre-taches"><?php echo $tab_parametres['taches'];?></div></td>
       <td><div  id="titre-Jour"><b><?php echo $tab_parametres['lundi'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '0'));
$var = $exe['year'];
$var = substr($var,2,2);
$d1 = $exe['year']."-".$exe['month']."-".$exe['day'];
$date1 = $exe['year']."-".$exe['month']."-".$exe['day'];

echo $d1_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_lun" id="date_lun" type="hidden" value="<?php echo $d1; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['mardi'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '1'));
$var = $exe['year'];
$var = substr($var,2,2);
$d2 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d2_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_mar" id="date_mar" type="hidden" value="<?php echo $d2; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['mercredi'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '2'));
$var = $exe['year'];
$var = substr($var,2,2);
$d3 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d3_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_mer" id="date_mer" type="hidden" value="<?php echo $d3; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['jeudi'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '3'));
$var = $exe['year'];
$var = substr($var,2,2);
$d4 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d4_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_jeu" id="date_jeu" type="hidden" value="<?php echo $d4; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['vendredi'];?></b><br />

          <?php $exe = (datefromweek ($y, $week, '4'));
$var = $exe['year'];
$var = substr($var,2,2);
$d5 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d5_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_ven" id="date_ven" type="hidden" value="<?php echo $d5; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['samedi'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '5'));
$var = $exe['year'];
$var = substr($var,2,2);
$d6 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d6_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_sam" id="date_sam" type="hidden" value="<?php echo $d6; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['dimanche'];?></b><br />
          <?php $exe = (datefromweek ($y, $week, '6'));
$var = $exe['year'];
$var = substr($var,2,2);
$d7 = $exe['year']."-".$exe['month']."-".$exe['day'];
$date7 = $exe['year']."-".$exe['month']."-".$exe['day'];
echo $d7_affich = $exe['day']."-".$exe['month'];
 ?>
          <input name="date_dim" id="date_dim" type="hidden" value="<?php echo $d7; ?>" /></div></td>
        <td><div id="titre-Jour"><b><?php echo $tab_parametres['RAF'];?></b><br />
        </div></td>
        <td><div id="titre-comment"><b><?php echo $tab_parametres['comment'];?></b></div></td>
        <td colspan="2"><div id="titre-action"><b><?php echo $tab_parametres['operation'];?></b></div></td>
        </tr>
      
	  <?php while ($tab=mysql_fetch_array($q)){ 
	    $id_tache=$tab['issue'];
$requete1 = requeteselection($d1,$d1,$id_tache);
$query1= mysql_query($requete1);
$tab1=mysql_fetch_array($query1);

$requete2 = requeteselection($d2,$d2,$id_tache);
$query2= mysql_query($requete2);
$tab2=mysql_fetch_array($query2);


$requete3 = requeteselection($d3,$d3,$id_tache);
$query3= mysql_query($requete3);
$tab3=mysql_fetch_array($query3);

$requete4 = requeteselection($d4,$d4,$id_tache);
$query4= mysql_query($requete4);
$tab4=mysql_fetch_array($query4);

$requete5 = requeteselection($d5,$d5,$id_tache);
$query5= mysql_query($requete5);
$tab5=mysql_fetch_array($query5);

$requete6 = requeteselection($d6,$d6,$id_tache);
$query6= mysql_query($requete6);
$tab6=mysql_fetch_array($query6);

$requete7 = requeteselection($d7,$d7,$id_tache);
$query7= mysql_query($requete7);
$tab7=mysql_fetch_array($query7);	  
	  
$raf = requeteselection($d1,$d7,$id_tache);
$query_raf= mysql_query($raf);
$tab_raf=mysql_fetch_array($query_raf);		   
$issu_id = $tab[0];
if ((isset($bg)) and ($bg==$issu_id))
{ 
$class = "x1";
$class1 = "titre1";
 }
else
{ 
$class = "x2";
$class1 = "titre2";
 }
	  ?>
	  
	  
	  
	  <tr bgcolor="#FFFFFF" valign="middle" align="center">
        <td vAlign="top" class="<?php echo $class1; ?>" align="left"><?php echo $tab['pname']; $project=$tab[1]; ?></td>
        <td align="left" vAlign="top" class="<?php echo $class1; ?>" ><?php echo $tab['pkey']."&nbsp;:&nbsp;".$tab['summary']; $issue=$tab[0];?></td>
        <td id="imp-<?php echo $tab[0]; ?>"  class="cellule" onDblClick="inlineMod(<?php echo $tab[0]; ?>, this, 'imp', 'Nombre')"><input type="texte" name="lund1" id="lund1" class="input-jour"  value="<?php if($tab1['imputation'] != '0.00') { echo $tab1['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="mar1" id="mar1" class="input-jour"  value="<?php if($tab2['imputation'] != '0.00') { echo $tab2['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="mer1" id="mer1" class="input-jour" value="<?php if($tab3['imputation'] != '0.00') { echo $tab3['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="jeu1" id="jeu1" class="input-jour" value="<?php if($tab4['imputation'] != '0.00') { echo $tab4['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="ven1" id="ven1" class="input-jour" value="<?php if($tab5['imputation'] != '0.00') { echo $tab5['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="sam1" id="sam1" class="input-jour" value="<?php if($tab6['imputation'] != '0.00') { echo $tab6['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="dim1" id="dim1" class="input-jour" value="<?php if($tab7['imputation'] != '0.00') { echo $tab7['imputation']; } ?>"/>
          &nbsp;</td>
        <td class="<?php echo $class; ?>"><input type="texte" name="raf1" id="raf1" class="input-jour" value="<?php echo $tab_raf['RAF'];  ?>"  />
          &nbsp;</td>
<td align="left" class="<?php echo $class1; ?>"><?php echo affich_comment($issue, $user, $d1, $d7); ?></td>		
<td width="21" align="center"><input name="date1" id="date1" type="hidden" value="<?php echo $date1; ?>" /><input name="project" id="project" type="hidden" value="<?php echo $project;?>" /><input name="date7" id="date7" type="hidden" value="<?php echo $date7;?>" /><input name="issue"  id="issue" type="hidden" value="<?php echo $issue;?>" />
		  <a href="<?php echo "supression.php?id=$user&amp;project=$project&amp;issue=$issue&amp;mm=$mm&amp;semaine=$week&amp;y=$y"; ?>"><img src="../images/supprimer.gif" border="0" title="<?php echo $tab_parametres['supprimer']; ?>" /></a></td>
		<td width="21" align="center"><input name="dd1" id="dd1" type="hidden" value="<?php echo $date1; ?>" /><input name="etape2" id="etape2" type="hidden" value="modif" />
          <a href="<?php echo "suivi_imputation.php?etap=mod&amp;proj=$project&amp;issue=$issue&amp;mm=$mm&amp;week=$week&amp;y=$y"; ?>"><img src="../images/modifier.gif"  border="0" alt="Modifier" title="<?php echo $tab_parametres['edit']; ?>" /></a></td>
		</tr> <?php } ?> <tr bgcolor="#FFFFFF" valign="middle" align="left">
        <td colspan="2" vAlign="top" align="right" class="style2"><?php echo $tab_parametres['total']?> : </td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d1, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; } ?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d2, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d3, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d4, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d5, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d6, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d7, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
        <td id="titre-RAF" align="right"><input type="hidden" name="raf1" id="raf1" class="input-jour" readonly="true" /></td>
		<td colspan="3" align="right" >&nbsp;</td>
		</tr></TBODY></TABLE> </TD></TR></TBODY></TABLE><?php } } ?>
        
        
        </td>
</tr>
</Table>
</form>


</Td></TR>
<tr>
  <td><?php include("bottom.php"); ?></td>
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

