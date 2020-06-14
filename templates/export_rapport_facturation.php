<?php //var_dump($_REQUEST);die;
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
 include "function_date.php";
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
}
//var_dump($_REQUEST);die;
if(isset($_REQUEST['y'])&& $_REQUEST['y']!="" ){ $annee=$_REQUEST['y']; } else { $annee=date('Y'); }
if(isset($_REQUEST['mm'])&& $_REQUEST['mm']!="" ) { $mois=$_REQUEST['mm']; } else { $mois=date('m'); }
if(isset($_REQUEST['proj'])&& $_REQUEST['proj']!="" ) { $proj=$_REQUEST['proj']; }  else { $proj=""; }
if(isset($_REQUEST['cat'])&& $_REQUEST['cat']!="" ) { $cat=$_REQUEST['cat']; }  else { $cat=""; }

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
//var_dump($date1);var_dump($date2);var_dump($proj);var_dump($cat);var_dump($TSomProj);die;

/*print_r($TSomNValid); 

echo "<br><br><hr><br><br>"; print_r($TSomFact);
echo "<br><br><hr><br><br>"; print_r($TSomNFact);*/
$list_bd_users = liste1('BD-users');//Liste utilisateurs B&D Tunis
$listimp = liste2($date1, $date2);//Liste utilisateurs B&D Tunis qui ont imputes
$list_bd_maroc = liste1('BD-Maroc');//Liste utilisateurs B&D Maroc
$list = list_userbase_f($date1, $date2, $proj, $cat,"", "all", $list_bd_users, $listimp ,$list_bd_maroc);
//var_dump($list);die;
$Tproj = GetListProject($cat);
$Tcat  = GetListCategorie();
 $html ='';
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
 $html .='<div id="div_form">

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
                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b>'.$tab_parametres['titre_facturation'].'</b></font> </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>'.tab_vide(20);
 $html .='
<table cellSpacing="1" cellPadding="1" width="100%" bgColor="#bbbbbb" border="0">
     <thead align="center" bgColor="#EFEFEF">
         <th  id="div_td" width="55%" ><b>
         <div align="center">'.$tab_parametres['collab'].'</div></b></th>
        <th id="div_td" width="10%"><b><div align="center">'.chaineformat($tab_parametres['facturable']).'</div></b></th>
        <th id="div_td" width="10%"><b><div align="center">'.chaineformat($tab_parametres['non_facturable']).'</div></b></th>
        <th id="div_td" width="10%"><b><div align="center">'.chaineformat($tab_parametres['non_valide']).'</div></b></th>
        <th id="div_td" width="10%"><b><div align="center">'.$tab_parametres['absence'].'</div></b></th>
        <th id="div_td" width="5%"><b><div align="center">'.$tab_parametres['total'].'</div></b></th>
	<tbody>';

 
 $cmp=1; $som_fac = 0;  $som_Nfac = 0; $som_Nvalid = 0; $som_abs = 0; $NProj="";
foreach ($list as $key => $vUser) { 




 //$tab0=array(); $tab1=array(); $tab2=array(); $NProj=""; 
$result=array();
if(array_key_exists($vUser['username'], $TSomProj))
{
$id_user = id_user($vUser['username']);
$result = select_project($id_user, $date1, $date2, $proj, $cat);
}

$nb_row = count($result);
 $html .='
<tr bgcolor="#FFFFFF">

<td class="td" width="55%">';


 $html .='<a style="cursor:hand">&nbsp;<b>'.$vUser['username'].'</b></td>';
  $Total=0; 
 if ($nb_row!=0)  {
 $html .='
<td  width="10%"  align="right" class="style-rapport">';
  if (isset($TSomFact[$vUser['username']][1])) {  
 $html .=$TSomFact[$vUser['username']][1]; $Total = $Total + $TSomFact[$vUser['username']][1]; $som_fac = $som_fac+$TSomFact[$vUser['username']][1]; 
  }else{ $html .='&nbsp;';} 
 }else{ //$html .='&nbsp;';
 }
 
 $html .='</td>
<td id="div_td" align="right"  width="10%" class="style-rapport" >';
 if (isset($TSomFact[$vUser['username']][2])) { 
 $html .= $TSomFact[$vUser['username']][2]; 
 $Total = $Total + $TSomFact[$vUser['username']][2]; $som_Nfac = $som_Nfac+$TSomFact[$vUser['username']][2]; 
 }else{ $html .='&nbsp;';} $html .='</td>
<td id="div_td"  align="right" width="10%" class="style-rapport">';
 
 if (isset($TSomFact[$vUser['username']][0])) { 
 	$html .=$TSomFact[$vUser['username']][0]; 
 	$Total = $Total + $TSomFact[$vUser['username']][0]; $som_Nvalid = $som_Nvalid+$TSomFact[$vUser['username']][0];
  }else{ $html .='&nbsp;';} 
  $html .='</td>
<td id="div_td" align="right"  width="10%" class="style-rapport">';
  
 if(isset($TAbs[$vUser['username']])) { 
	$html .=$TAbs[$vUser['username']];
	 $som_abs = $som_abs+$TAbs[$vUser['username']]; 
	 $Total = $Total + $TAbs[$vUser['username']]; 
 }else{ $html .='&nbsp;';}  $html .='</td>
<td id="div_td" align="right"class="style-rapport" width="5%"  ><b>&nbsp;</b></td>
</tr>';
 if ((isset($TSomFact[$vUser['username']][1])) or (isset($TSomFact[$vUser['username']][2])) or (isset($TSomFact[$vUser['username']][0])))  { 


?>
<?php foreach ($result as $key => $vProj) { 
	
$html .='<tr id="'.$cmp."_".$key.'"  class="style-rapport"  bgcolor="#FCF2F2" >

<td class="style-rapport"  width="219"><img src="'.$img_path.'spacer.gif" height="9" width="9" hspace="10" />'.chaineformat($vProj).'</td>';
  $Total=0;  
$html .='
<td align="right" width="173" class="style-rapport" >';
 if (isset($TSomProj[$vUser['username']][1][$vProj])) { 
$html .=$TSomProj[$vUser['username']][1][$vProj];
 $Total = $Total + $TSomProj[$vUser['username']][1][$vProj];
 }else{ $html .='&nbsp;';}  $html .='</td>
<td align="right" width="173" class="style-rapport" >';
 if (isset($TSomProj[$vUser['username']][2][$vProj])) { 
 $html .=$TSomProj[$vUser['username']][2][$vProj]; 
 $Total = $Total + $TSomProj[$vUser['username']][2][$vProj];
  }else{ $html .='&nbsp;';} $html .='</td>
<td align="right" width="173" class="style-rapport" >';
  
  if (isset($TSomProj[$vUser['username']][0][$vProj])) { 
  $html .=$TSomProj[$vUser['username']][0][$vProj]; 
  $Total = $Total + $TSomProj[$vUser['username']][0][$vProj];
   }else{ $html .='&nbsp;';} $html .='</td>
<td align="right" width="173" class="style-rapport" >&nbsp; </td>
<td align="right" class="style-rapport" ><b>'.$Total.'</b></td>
</tr>';



 } ?>


<?php } $cmp++;
} 
$html .='
<tr  class="style-rapport"  bgcolor="#FCF2F2">

<td class="style-rapport"  align="right"><b>'.$tab_parametres['total'].'&nbsp;&nbsp;</b> </td>

<td align="right" width="173" class="style-rapport"  ><b>'.$som_fac.'</b></td>
<td align="right" width="173" class="style-rapport"  ><b>'.$som_Nfac.'</b></td>
<td align="right" width="173"  class="style-rapport" ><b>'.$som_Nvalid.'</b></td>
<td align="right" width="173"  class="style-rapport" ><b>'.$som_abs.'</b></td>
<td align="right" ><b>';
 $Somme = $som_fac+$som_Nfac+$som_Nvalid+$som_abs; 
 $html .=$Somme; 
 $html .='</b></td>
</tr>
</tbody>
</table>
</div>';

 if(isset($html) && $html !="")
{
$nom_fichier_excel = "rapport_activite";
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
if(isset($_REQUEST['rapport_imputation'])) { $nom_fichier_excel="rapport_imputation_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
if(isset($_REQUEST['rapport_facturation'])) { $nom_fichier_excel="rapport_facturation_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
if(isset($_REQUEST['suivi_hors_projet'])) { $nom_fichier_excel="suivi_hors_projet_".$_REQUEST['type']."_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
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
            src="<?php echo $img_path; ?>logo_bd_tn.png" 
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

