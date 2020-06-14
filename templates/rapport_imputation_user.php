<?php 
session_start();// On d�marre la session
$id = $_SESSION['id'];
$user = $_SESSION['id'];
include "connexion.php"; // la page de connexion � la base jira
include "fonctions.php"; 
$page = "rapport_imputation";
// $lg est le parametre de langue et c est egal 1 pour le fran�ais

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

$annee=$_POST['annee'];
$mois=$_POST['mois'];
$proj=$_POST['proj'];
$cat=$_POST['cat'];


$fin_mois    = fin_mois($mois, $annee); 
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$date1       = $annee."-".$mois."-01";  $date2 = $annee."-".$mois."-".$fin_mois;
for($d=1; $d<=$fin_mois; $d++)
{
	if($d<10) { $date=$annee."-".$mois."-0".$d; }
	else { $date=$annee."-".$mois."-".$d; }
	$TDate[$d] = $date;
}


$Sam_mois = sam_mois($mois,$annee); $nb_sam = count($Sam_mois);
$Dim_mois = dim_mois($mois,$annee); $nb_dim = count($Dim_mois);
$nb_f = nbre_jour_fer($date1, $date2);
$nb_jour_trav = $fin_mois-($nb_sam+$nb_dim+$nb_f);
$id_user = $_POST['idU'];
$username = $_POST['nameU'];
$Timp = GetDetailImputation_user($date1, $date2, $proj,$id_user); //print_r($Timp);
?>
<?php 
$result = select_project_s($id_user, $date1, $date2, $proj, $cat);
$nb_row = count($result);
	?>

<?php $html ="";$Tot_proj=0; foreach ($result as $key => $vProj) {
$html .='<tr  class="style-rapport tr-a-'.$id_user.'"  bgcolor="#FCF2F2"  onmouseover="this.bgColor='."'#C7C5C5'".'"
onmouseout="this.bgColor='."'#FCF2F2'".'">';
$html .='<td class="style-rapport" nowrap="nowrap"  width="180" ><img src="'.$img_path.'spacer.gif" height="9" width="8" hspace="2" /><img src="'.$img_path.'detail_plus.jpg" height="9" width="9" hspace="2" id="img_'.$id_user.'_'.$vProj['id'].'" class="img_d_'.$id_user.'" onclick="showDetailsP('.$vProj['id'].','.$id_user.')"/>'.$vProj['pname'].'</td>';

for ($c=1; $c<=count($TDate); $c++) {  $vDate=$TDate[$c];

$html .= '<td  class="style-rapport"';
 if ((in_array($c, $Sam_mois)) or (in_array($c, $Dim_mois))) {
    $html .= "bgcolor='#FFFF33'"; } 

    $html.= 'width="10" align="right">';
    if(isset($Timp[$username][$vProj['pname']][$vDate])) { 
    $imputation = $Timp[$username][$vProj['pname']][$vDate]; 
    $html.= $imputation; 
    $Tot_proj=$Tot_proj+$imputation;  
    } else {  $html.="&nbsp;"; }  
    $html.= '</td>';

}
 $html.='<td class="style-rapport"    align="right" >'.$Tot_proj.'</td>
<td class="style-rapport"   align="right" width="30" >&nbsp;</td>
';

$html .='</tr>';

$details = GetDetailImputation_user_details($date1, $date2, $vProj['id'],$id_user,$username);
//var_dump($details);die;
$details1 = Getissueprojet_user($date1, $date2, $vProj['id'],$id_user,$username);
foreach ($details1 as $key2 => $v2) {
    	$html .='<tr  class="style-rapport tr-d-'.$id_user.'-'.$vProj['id'].' tr-details-p"  bgcolor="#FCF2F2"  onmouseover="this.bgColor='."'#C7C5C5'".'"
onmouseout="this.bgColor='."'#FCF2F2'".'"  style="display:none">';
$html .='<td class="style-rapport" nowrap="nowrap"  width="180" ><img src="'.$img_path.'spacer.gif" height="9" width="19" hspace="2" /><img src="../images/right.gif" height="7" width="7" hspace="2" />'.$v2['tache'].'</td>';

for ($c=1; $c<=count($TDate); $c++) {  $vDate=$TDate[$c];

$html .= '<td  class="style-rapport"';
 if ((in_array($c, $Sam_mois)) or (in_array($c, $Dim_mois))) {
    $html .= "bgcolor='#FFFF33'"; } 

    $html.= 'width="10" align="right">';
    if(isset($details[$username][$vProj['pname']][$v2['id']]['tmp'][$vDate])) { 
    $imputation = $details[$username][$vProj['pname']][$v2['id']]['tmp'][$vDate];
    
    $html.= $imputation; 
    $Tot_proj=$Tot_proj+$imputation;  
    } else {  $html.="&nbsp;"; }  
    $html.= '</td>';
 
 
}
$html.='<td class="style-rapport"    align="right" ></td>
<td class="style-rapport"   align="right" width="30" >&nbsp;</td>
';
$html .='</tr>'; 


}
 $Tot_proj=0; } 
 echo $html;exit;