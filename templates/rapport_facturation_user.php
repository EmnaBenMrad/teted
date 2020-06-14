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
}
$annee=$_POST['annee'];
$mois=$_POST['mois'];
$proj=$_POST['proj'];
$cat=$_POST['cat'];

//if((isset($mois)) and (strlen($mois==1))) { $mois = "0".$mois; }
$TSomProj=array();
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$fin_mois    = fin_mois($mois, $annee); 
$date1       = $annee."-".$mois."-01";  
$date2 = $annee."-".$mois."-".$fin_mois; 

$id_user = $_POST['idU'];
$username = $_POST['nameU'];

$facturable = sum_imputation_facturation_user($date1, $date2, $proj, $cat,$id_user);
$facturable2 = sum_imputation_facturation_proj_user($date1, $date2, $proj, $cat,$id_user);
while ($row = mysql_fetch_row($facturable) )
	{
	$TSomFact[$row[3]][$row[2]]= $row[0];
	}

while ($Tproj = mysql_fetch_row($facturable2) )
	{
	$validation = $Tproj[2]; if($validation==NULL)  { $validation=0; }
	$TSomProj[$Tproj[3]][$validation][$Tproj[5]]= $Tproj[0];
	}
 $cmp=1; $som_fac = 0;  $som_Nfac = 0; $som_Nvalid = 0; $som_abs = 0; $NProj="";
 
$result = select_project($id_user, $date1, $date2, $proj, $cat);
$nb_row = count($result);

	?>

<?php $html =""; if ((isset($TSomFact[$username][1])) or (isset($TSomFact[$username][2])) or (isset($TSomFact[$username][0])))  { 


?>
<?php foreach ($result as $key => $vProj) { 
 $Total=0; 	
 $html .='<tr  class="style-rapport tr-a-'.$id_user.'"  bgcolor="#FCF2F2"  onmouseover="this.bgColor='."'#C7C5C5'".'"
onmouseout="this.bgColor='."'#FCF2F2'".'">';
  $html .='<td class="style-rapport"  width="219"><img src="'.$img_path.'spacer.gif" height="9" width="9" hspace="10" />'.$vProj.'</td>';
$html .='<td align="right" width="173" class="style-rapport" >';
if (isset($TSomProj[$username][1][$vProj])) { 
$html .= $TSomProj[$username][1][$vProj]; 
$Total = $Total + $TSomProj[$username][1][$vProj]; } 
$html .='</td>';
$html .='<td align="right" width="173" class="style-rapport" >';
if (isset($TSomProj[$username][2][$vProj])) { $html .=$TSomProj[$username][2][$vProj]; $Total = $Total + $TSomProj[$username][2][$vProj]; }
$html .='</td>';
$html .='<td align="right" width="173" class="style-rapport" >';
if (isset($TSomProj[$username][0][$vProj])) { $html .= $TSomProj[$username][0][$vProj]; $Total = $Total + $TSomProj[$username][0][$vProj]; } 
$html .='</td>';
$html .='<td align="right" width="173" class="style-rapport" > </td>';
$html .='<td align="right" class="style-rapport" ><b>'. $Total.'</b></td>';
$html .='</tr>';
  } 
 
  ?>


<?php } $cmp++;
echo $html;exit;
//} ?>