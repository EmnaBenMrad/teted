<script type="text/javascript" src="../js/jquery.js"></script>
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

$id = $_SESSION['id'];
$user = $_SESSION['id'];
include "connexion.php"; // la page de connexion a la base jira
include "fonctions.php"; 

// $lg est le parametre de langue et c est egal 1 pour le francais
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de parametrage***/
$img_path = $tab_parametres['img_path'];
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
}//var_dump($_REQUEST);die;
if(isset($_REQUEST['y'])) { $annee=$_REQUEST['y']; } else { $annee=date('Y'); }
if(isset($_REQUEST['mm'])) { $mois=$_REQUEST['mm']; } else { $mois=date('m'); }
if(isset($_REQUEST['proj'])) { $proj=$_REQUEST['proj']; }  else { $proj=""; }
if(isset($_REQUEST['cat'])) { $cat=$_REQUEST['cat']; }  else { $cat=""; }

//if((isset($mois)) and (strlen($mois==1))) { $mois = "0".$mois; }
$TSomProj=array();
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$fin_mois    = fin_mois($mois, $annee); 
$date1       = $annee."-".$mois."-01";  
$date2 = $annee."-".$mois."-".$fin_mois; 
$TAbs = total_abscence($date1, $date2);
$facturable = sum_imputation_facturation($date1, $date2, $proj, $cat);
$facturable2 = sum_imputation_facturation_proj($date1, $date2, $proj, $cat);
while ($row = mysql_fetch_row($facturable) )
	{
	$TSomFact[$row[3]][$row[2]]= $row[0];
	}

while ($Tproj = mysql_fetch_row($facturable2) )
	{
	$validation = $Tproj[2]; if($validation==NULL)  { $validation=0; }
	$TSomProj[$Tproj[3]][$validation][$Tproj[5]]= $Tproj[0];
	}


/*print_r($TSomNValid); 

echo "<br><br><hr><br><br>"; print_r($TSomFact);
echo "<br><br><hr><br><br>"; print_r($TSomNFact);*/
//$list = list_userbase($date1, $date2, $proj, $cat); 

$list_bd_users = liste1('BD-users');//Liste utilisateurs B&D Tunis
$listimp = liste2($date1, $date2);//Liste utilisateurs B&D Tunis qui ont imputes
$list_bd_maroc = liste1('BD-Maroc');//Liste utilisateurs B&D Maroc
$list = list_userbase_f($date1, $date2, $proj, $cat,"", "all", $list_bd_users, $listimp ,$list_bd_maroc);

$Tproj = GetListProject($cat);
$Tcat  = GetListCategorie();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">

function showHideRow(id,cmp, id_user,username){
	if($('#img_'+id).hasClass('visite-img')){
		$('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
		$('#img_'+id).removeClass('visite-img');
		//alert($('tr.tr-'+id_user).length);
		$('tr.tr-a-'+id_user).attr('style','display:none');
	}else{
		
		$('#img_'+id).attr('src',"../images/load.gif");
		
		if(!$('#img_'+id).hasClass('show-detail')){
			$.post('rapport_facturation_user.php',{'annee':'<?php  echo $annee;?>','mois':'<?php  echo $mois;?>','proj':'<?php  echo $proj;?>','cat':'<?php  echo $cat;?>','idU':id_user,'nameU':username}, function(data){
				
				$('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
				$('#img_'+id).addClass('visite-img');
				$('#img_'+id).addClass('show-detail');
				$(data).insertAfter($('tr.tr-'+id_user));
				$('tr.tr-'+id_user).attr('style','');
			});
		}else{
			$('#img_'+id).addClass('visite-img');
			$('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
			$('tr.tr-a-'+id_user).attr('style','');
		}
	}
}
// fonction pour le cas d'appui sur la touche entree
function testsubmit() {
    if (document.formulaire.action=="") return false;
return true ;
}

                

//fonction pour choisir l'action
function gopage(page)
{

    document.form.action = page;
	if(page=="export_rapport_facturation.php")
	{
	document.getElementById('annee').value=$('#annees').val();
	document.getElementById('mois').value=$('#moiss').val();
	document.getElementById('projs').value=$('#projselect').val();
	document.getElementById('cats').value=$('#catselect').val();
	document.getElementById('rapport_facturation').value=document.getElementById('rapport_facturation').innerHTML;
	}
	else 
	{
	document.form.submit();
	}
}

//fonction pour choisir l'action
function gopagef(page)
{
	document.getElementById('annee').value=$('#annees').val();
	document.getElementById('mois').value=$('#moiss').val();
	document.getElementById('projs').value=$('#projselect').val();
	document.getElementById('cats').value=$('#catselect').val();
	 document.form.action = page;
	 document.form.submit();
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['titre_facturation'];?></title>
</head>
<body><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top" >
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  <tr><td><br /><form action="rapport_facturation.php" method="post" onSubmit="return loginCheck(this);" name ="form">
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
<?php echo $tab_parametres['annee'];?>&nbsp;:		            &nbsp;&nbsp;
					<select
            name="annee" id="annees" class="input-annee">
		  <?php 
		  for ($i=2007;$i<=2025;$i++) 
{  ?>
            <option <?php if ($i==$annee) { echo "selected=\"selected\""; } ?> value="<?php echo $i;?>"><?php echo $i; ?> </option><?php } ?>
          </select>&nbsp;
          <?php echo $tab_parametres['mois']; ?>&nbsp;:
			<select 
            name="mois" id="moiss" class="input-annee" style="width:80px;" >
  <?php 
	 for ($i=1;$i<=12;$i++) 
{ 
?>
  <option value="<?php echo $i;?>" <?php if(($i)==$mois)
{ 
echo "selected"; 
}
 ?>><?php echo nom_mois($i-1); ?></option>
  <?php } ?>
</select>&nbsp;&nbsp;
          <?php echo $tab_parametres['client']; ?>&nbsp;:
			&nbsp;<select 
            name="categorie" id="catselect" class="input-annee" style="width:250px;" >
 <option value=""><?php echo $tab_parametres['choix_client']; ?></option> <?php 
	 foreach ($Tcat as $ccle => $vCategorie) { 
$Categorie = explode("||", $vCategorie);
?>
  <option value="<?php echo $Categorie[0]; ?>" <?php if(($Categorie[0])==$cat)
{ 
echo "selected"; 
}
 ?>><?php echo $Categorie[1]; ?></option>
  <?php } ?>
</select>&nbsp;
          <?php echo $tab_parametres['projet']; ?>&nbsp;<select 
            name="project" id="projselect" class="input-annee" style="width:250px;" >
  <option value=""><?php echo $tab_parametres['liste_projet']; ?></option>
  <?php 
	 foreach ($Tproj as $cle => $vProject) { 

$Project = explode("||", $vProject);
?>
  <option value="<?php echo $Project[0];?>" <?php if(($Project[0])==$proj)
{ 
echo "selected"; 
}
 ?>><?php echo $Project[1]; ?></option>
  <?php } ?>
</select>&nbsp;&nbsp;<input type="button" class="buttonImg"  value=" " name="Affich" onClick="gopagef('<?php echo $_SERVER['PHP_SELF']; ?>')">&nbsp; &nbsp;  
		  <input type="submit" id="export-excel" name="exporter" class="buttonExcelImg" value=""   onClick="gopage('export_rapport_facturation.php')"  /><input type="hidden" name="rapport_activite" id="rapport_activite" value="1" /><input type="hidden" name="cat" id="cats" value="<?php echo $cat; ?>" /> <input type="hidden" name="proj" id="projs" value="<?php echo $proj; ?>" /> <input type="hidden" name="mm" id="mois" value="<?php echo $mois; ?>" /><input type="hidden" name="y" id="annee" value="<?php echo $annee; ?>" /> <input type="hidden" name="rapport_facturation" id="rapport_facturation" value="rapport_facturation" /> 

</td>
                   </tr></TBODY></TABLE></TD></TR></TBODY></TABLE>		</form><br />

<div id="div_form">

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
                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b> <?php echo $tab_parametres['titre_facturation'];?> </b></font>                           </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE><?php echo tab_vide(20); ?>

<table cellSpacing="1" cellPadding="1" width="100%" bgColor="#bbbbbb" border="0">
     <thead align="center" bgColor="#EFEFEF">
         <th  id="div_td" width="55%" ><b>
         <div align="center"><?php echo $tab_parametres['collab'];?></div></b></th>
        <th id="div_td" width="10%"><b><div align="center"><?php echo $tab_parametres['facturable'];?></div></b></th>
        <th id="div_td" width="10%"><b><div align="center"><?php echo $tab_parametres['non_facturable'];?></div></b></th>
        <th id="div_td" width="10%"><b><div align="center"><?php echo $tab_parametres['non_valide'];?></div></b></th>
        <th id="div_td" width="10%"><b><div align="center"><?php echo $tab_parametres['absence'];?></div></b></th>
        <th id="div_td" width="5%"><b><div align="center"><?php echo $tab_parametres['total'];?></div></b></th>
	<tbody>

 
<?php $cmp=1; $som_fac = 0;  $som_Nfac = 0; $som_Nvalid = 0; $som_abs = 0; $NProj="";
foreach ($list as $key => $vUser) { 




 //$tab0=array(); $tab1=array(); $tab2=array(); $NProj=""; 
$result=array();
if(array_key_exists($vUser['username'], $TSomProj))
{
$id_user = id_user($vUser['username']);

$result = select_project($id_user, $date1, $date2, $proj, $cat);
}

$nb_row = count($result);
	?>
<tr bgcolor="#FFFFFF"   class="tr-<?php echo $vUser['id']?>" onmouseover="this.bgColor='#C7C5C5';" onmouseout="this.bgColor='#FFFFFF'">

<td class="td" width="55%"><?php  if ($nb_row!=0)  { ?><a onclick="javascript:showHideRow('<?php echo $cmp; ?>', '<?php echo $nb_row; ?>','<?php echo $vUser['id'];?>','<?php echo $vUser['username'];?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" hspace="2" id="img_<?php echo $cmp; ?>" height="9" width="9"/></a><?php } else { ?><img src="<?php echo $img_path; ?>spacer.gif" height="9" width="9" hspace="2" /><?php }
?>&nbsp;<b><?php echo $vUser['username']; ?></b></td>
<?php  $Total=0; 
?>
<td  width="10%"  align="right" class="style-rapport"><?php if (isset($TSomFact[$vUser['username']][1])) {  echo $TSomFact[$vUser['username']][1]; $Total = $Total + $TSomFact[$vUser['username']][1]; $som_fac = $som_fac+$TSomFact[$vUser['username']][1]; } ?> </td>
<td id="div_td" align="right"  width="10%" class="style-rapport" ><?php if (isset($TSomFact[$vUser['username']][2])) { echo $TSomFact[$vUser['username']][2]; $Total = $Total + $TSomFact[$vUser['username']][2]; $som_Nfac = $som_Nfac+$TSomFact[$vUser['username']][2]; } ?></td>
<td id="div_td"  align="right" width="10%" class="style-rapport"><?php if (isset($TSomFact[$vUser['username']][0])) { echo $TSomFact[$vUser['username']][0]; $Total = $Total + $TSomFact[$vUser['username']][0]; $som_Nvalid = $som_Nvalid+$TSomFact[$vUser['username']][0]; } ?></td>
<td id="div_td" align="right"  width="10%" class="style-rapport"><?php if(isset($TAbs[$vUser['username']])) { echo $TAbs[$vUser['username']]; $som_abs = $som_abs+$TAbs[$vUser['username']]; $Total = $Total + $TAbs[$vUser['username']]; }  ?></td>
<td id="div_td" align="right"class="style-rapport" width="5%"  ><b><?php echo $Total;   ?></b></td>
</tr>

<?php $cmp++;
} ?>
<tr  class="style-rapport"  bgcolor="#FCF2F2">

<td class="style-rapport"  align="right"><b><?php echo $tab_parametres['total'];?>&nbsp;&nbsp;</b> </td>

<td align="right" width="173" class="style-rapport"  ><b><?php echo $som_fac; ?></b></td>
<td align="right" width="173" class="style-rapport"  ><b><?php echo $som_Nfac; ?></b></td>
<td align="right" width="173"  class="style-rapport" ><b><?php echo $som_Nvalid; ?></b></td>
<td align="right" width="173"  class="style-rapport" ><b><?php echo $som_abs; ?></b></td>
<td align="right" ><b><?php $Somme = $som_fac+$som_Nfac+$som_Nvalid+$som_abs; echo $Somme; ?></b></td>
</tr>
</tbody>
</table>
</div>


<tr><td><table width="100%"><tr valign="top" >
    <td height="76" ><?php include("bottom.php"); ?></td>
</tr></table></td></tr>
</td></tr>

</table>

 <script language="javascript">

</script>       
</body>
</html>
