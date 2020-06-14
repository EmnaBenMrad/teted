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

$id = $_SESSION['id'];
$user = $_SESSION['id'];
include "connexion.php"; // la page de connexion ï¿½ la base jira
include "fonctions.php"; 

// $lg est le parametre de langue et c est egal 1 pour le franï¿½ais
$lg=1; $nb_type=0;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de parametrage***/
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
$user_cras = "inactif";
$liste_group = membershipbase($user);
$nb_group = count($liste_group);
$tab_group=array();
for($i=0;$i<$nb_group;$i++)
{
	$tab_group[$i] = $liste_group[$i];
}
for($j=0;$j<$nb_group;$j++)
{
	$list_group = $tab_group[$j];
	if(strtolower($list_group)=="td-tdbusers"){ $lien_bord = "actif"; }
	if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
	if(strtolower($list_group)=="bd-cra"){ $user_cras = "actif"; }
}
if(isset($_REQUEST['y'])) { $annee=$_REQUEST['y']; } else { $annee=date('Y'); }
if(isset($_REQUEST['mm'])) { $mois=$_REQUEST['mm']; } else { $mois=date('m'); }
if(isset($_REQUEST['type'])) { $type=$_REQUEST['type']; }   $proj=""; $cat="";
//if((isset($mois)) and (strlen($mois==1))) { $mois = "0".$mois; }
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$fin_mois    = fin_mois($mois, $annee); 
$date1       = $annee."-".$mois."-01";  
$date2 = $annee."-".$mois."-".$fin_mois;
if(isset($type)) { 
$List_type = type_issue($type);
$nb_type = count($List_type);
$listImp = imputation_proj($type, $date1, $date2);
while ($row = mysql_fetch_row($listImp) )
{
	$TSomImp[$row[4]][$row[3]]= $row[0];
}

}
$Tproj = Liste_proj_rapport();
$nb_p = count($Tproj);
$som=array();
$list = list_userbase($date1, $date2, $proj, $cat); 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
// fonction pour le cas d'appui sur la touche entr�e
function testsubmit() {
    if (document.formulaire.action=="") return false;
return true ;
}

//fonction pour choisir l'action
function gopage(page)
{

    document.form.action = page;
	if(page=="export_rapport.php")
	{
	document.getElementById('rapport_activite').value=document.getElementById('div_form').innerHTML;document.getElementById('form').submit()
	document.getElementById('annee').value=document.getElementById('annee').innerHTML;document.getElementById('form').submit()
	document.getElementById('mois').value=document.getElementById('mois').innerHTML;document.getElementById('form').submit()	
	document.getElementById('suivi_hors_projet').value=document.getElementById('suivi_hors_projet').innerHTML;document.getElementById('form').submit()
	}
	else 
	{
	document.form.submit();
	}
}
</script>

<script type="text/javascript" src="../js/mootools_1.2.js"></script>
<script type="text/javascript" src="../js/sortableTable.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['titre_conge'];?></title>
</head>
<body><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top" >
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  <tr><td><br /> <form action="rapport_projet.php" method="post" name ="form">	             
  <TABLE cellSpacing="0" cellPadding="0" width="100%" 
            bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD>
                  <TABLE align="right" cellSpacing="1" cellPadding="4" width="100%" 
                  bgColor="#bbbbbb" border="0">
                    <TBODY bgColor="#EFEFEF">
                   <tr height="20" valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
         <td height="10" colspan="13" align="left">
<?php echo $tab_parametres['annee'];?></b>&nbsp;:		             
					<select onchange="changePage1(this.options[this.options.selectedIndex].value) "
            name="annee" id="annee" class="input-annee">
		  <?php 
		  for ($i=2007;$i<=2019;$i++) 
{  ?>
            <option <?php if ($i==$annee) { echo "selected=\"selected\""; } ?> value="rapport_projet.php?y=<?php echo $i; if(isset($type)) { echo "&type=".$type; } ?>"><?php echo $i; ?> </option><?php } ?>
          </select>&nbsp;
          <b><?php echo $tab_parametres['mois']; ?></b>&nbsp;:
			<select onChange="changePage1(this.options[this.options.selectedIndex].value)" 
            name="mois" id="mois" class="input-annee" style="width:80px;" >
  <?php 
	 for ($i=1;$i<=12;$i++) 
{ 
?>
  <option value="rapport_projet.php?y=<?php echo $annee; ?>&mm=<?php echo $i; if(isset($type)) { echo "&type=".$type;} ?>" <?php if(($i)==$mois)
{ 
echo "selected"; 
}
 ?>><?php echo nom_mois($i-1); ?></option>
  <?php } ?>
</select>
			<select onchange="changePage1(this.options[this.options.selectedIndex].value)" 
            name="categorie" id="categorie" class="input-annee" style="width:150px;" >
              <option value="rapport_projet.php?y=<?php echo $annee; ?>&mm=<?php echo $mois; ?>"><?php echo $tab_parametres['liste_projet']; ?></option>
              <?php 
	 for ($i=0;$i<$nb_p;$i++) 
{  $categorie = $Tproj[$i]['id'];
  $name = $Tproj[$i]['name'];
?>
              <option value="rapport_projet.php?y=<?php echo $annee; ?>&mm=<?php echo $mois; ?>&type=<?php echo $categorie; ?>" <?php if((isset($type)) and ($type==$categorie)) echo "selected='selected'"; ?> ><?php echo $name; ?></option>
              <?php }  ?>
            </select>&nbsp;&nbsp;<input type="submit" name="exporter" value="Exporter vers Excel"  onClick="gopage('export_rapport.php')"  /><input type="hidden" name="rapport_activite" id="rapport_activite" value="1" /> <input type="hidden" name="mois" id="mois" value="<?php echo $mois; ?>" /> <input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>" /><input type="hidden" name="type" id="type" value="<?php echo $type; ?>" /><input type="hidden" name="suivi_hors_projet" id="suivi_hors_projet" value="suivi_hors_projet" />
		</td>
      </tr></TBODY></TABLE></TD></TR></TBODY></TABLE>		</form>
                   
 <br />                  
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
                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b> <?php echo $tab_parametres['titre_conge'];?> </b></font>                           </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>
	  
<div id="div_form">
<?php if(isset($type)) {
$width_table = ($nb_type*185)+150;
 ?>
<br /> 
<table cellSpacing="1" cellPadding="1" width="<?php echo $width_table; ?>" bgColor="#bbbbbb" border="0">
     <thead align="center" bgColor="#EFEFEF">
         <th width="120"><div align="center"><?php echo $tab_parametres['collab'];?></div></th>
		<?php for ($j=0; $j<$nb_type; $j++) { ?>
        <th width="185"><b><div align="center"><?php echo $List_type[$j]['name']; ?></div></b></th><?php } ?>
        <th width="30"><b><div align="center"><?php echo $tab_parametres['total'];?></div></b></th>
	</thead>

<tbody>
<?php foreach ($list as $key => $vUser) { 

	?>
<tr bgcolor="#FFFFFF"  onmouseover="this.bgColor='#C7C5C5';" 
onmouseout="this.bgColor='#FFFFFF'">

<td width="120" height="15">&nbsp;&nbsp;<b><?php echo $vUser; ?></b></td>
<?php $cmp=1; $Total=0; 
for ($j=0; $j<$nb_type; $j++) {   
?>
<td align="right" class="style-rapport"><div align="right"><?php if (isset($TSomImp[$vUser][$List_type[$j]['name']])) {
 $Imp = $TSomImp[$vUser][$List_type[$j]['name']]; $Total = $Total +  $Imp; echo $Imp; 
 if(!isset($som[$j])) { $som[$j]=0; }
$som[$j] = $som[$j]+$Imp;
  } else { echo "&nbsp;";  }   ?></div> </td>
<?php
//$cmp++;
}
?>
<td id="div_td" align="right" width="30" class="style-rapport"  ><b><?php echo $Total; ?></b></td>
</tr>
<?php } ?>

</tbody>

     <thead bgColor="#EFEFEF" align="right">
         <td width="100" id="div_td" align="right"><b><?php echo $tab_parametres['total'];?>&nbsp;</b></td>
		<?php $Tot=0; for ($j=0; $j<$nb_type; $j++) { if(!isset($som[$j])) { $som[$j]=0; } ?>
        <td id="div_td" align="right" class="style-rapport"><?php  $Tot = $som[$j]+$Tot; echo "<b>".$som[$j]."</b>"; ?></td><?php } ?>
        <td id="div_td" width="30" align="right" class="style-rapport"><?php echo "<b>".$Tot."</b>"; ?></td>
	</thead>
</table>
<?php } ?>
</div>
</td></tr>


<tr><td><table width="100%"><tr valign="top" >
    <td height="76" ><?php include("bottom.php"); ?></td>
</tr></table></td></tr>
</table>


        
</body>
</html>
