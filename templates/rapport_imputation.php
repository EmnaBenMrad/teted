

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
$page = "rapport_imputation";
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
if(isset($_REQUEST['y'])) { $annee=$_REQUEST['y']; } else { $annee=date('Y'); }
if(isset($_REQUEST['mm'])) { $mois=$_REQUEST['mm']; } else { $mois=date('m'); }
if(isset($_REQUEST['proj'])) { $proj=$_REQUEST['proj']; }  else { $proj=""; }
if(isset($_REQUEST['cat'])) { $cat=$_REQUEST['cat']; }  else { $cat=""; }
if(isset($_REQUEST['pg'])) { $pg=$_REQUEST['pg']; }  else { $pg=1; }

$fin_mois    = fin_mois($mois, $annee); 
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$date1       = $annee."-".$mois."-01";  $date2 = $annee."-".$mois."-".$fin_mois;
for($d=1; $d<=$fin_mois; $d++)
{
	if($d<10) { $date=$annee."-".$mois."-0".$d; }
	else { $date=$annee."-".$mois."-".$d; }
	$TDate[$d] = $date;
}

$listImp     = GetImputationUser($date1, $date2, $proj); 
while ($row = mysql_fetch_row($listImp) )
{
	$TSomImp[$row[1]][$row[2]]= $row[0];
}

$Sam_mois = sam_mois($mois,$annee); $nb_sam = count($Sam_mois);
$Dim_mois = dim_mois($mois,$annee); $nb_dim = count($Dim_mois);
$list_bd_users = liste1('BD-users');//Liste utilisateurs B&D Tunis
$listimp = liste2($date1, $date2);//Liste utilisateurs B&D Tunis qui ont imputes
$list_bd_maroc = liste1('BD-Maroc');//Liste utilisateurs B&D Maroc
$list = list_userbase_f($date1, $date2, $proj, $cat,"", $pg, $list_bd_users, $listimp ,$list_bd_maroc);

$nbpage = nbpage($date1, $date2, $proj, $cat,"", $list_bd_users, $listimp ,$list_bd_maroc); 
$nb_f = nbre_jour_fer($date1, $date2);

$nb_jour_trav = $fin_mois-($nb_sam+$nb_dim+$nb_f);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link href="../style/sortableTable.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['titre_rapport_imputation'];?></title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.quicksearch.js"></script>
<script language="javascript">
$(function(){
	$('input#id_search').quicksearch('table#myTable tbody tr');
	
});
//fonction pour le cas d'appui sur la touche entree
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
	var chaine =document.getElementById('div_form').innerHTML;
	chaine = RemoveAccents(chaine);
	document.getElementById('rapport_activite').value=chaine;
	document.getElementById('annee').value=document.getElementById('annee').innerHTML;
	document.getElementById('mois').value=document.getElementById('mois').innerHTML;
	document.getElementById('rapport_imputation').value=document.getElementById('rapport_imputation').innerHTML;
	}
	else 
	{
	document.form.submit();
	}
}

//fonction pour choisir l'action
function gopagef(page)
{
	document.getElementById('annees').value=$('select#annee').val();
	document.getElementById('moiss').value=$('select#mois').val();
	 document.form.action = page;
	 document.form.submit();
}

function changePagePaginate(page){
	//$('#pg-paginate').val(page);
	var mm =document.getElementById('moiss').value;
	var an =document.getElementById('annees').value;
	window.location = 'rapport_imputation.php?y='+an+'&mm='+mm+'&pg='+page;
}

function showDetailsP(idProj,idUser){
	
	 if($('#img_'+idUser+'_'+idProj).hasClass('visite-img-p')){
			$('#img_'+idUser+'_'+idProj).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
			$('#img_'+idUser+'_'+idProj).removeClass('visite-img-p');
			$('tr.tr-d-'+idUser+'-'+idProj).attr('style','display:none');
		}else{
			
				$('#img_'+idUser+'_'+idProj).addClass('visite-img-p');
				$('#img_'+idUser+'_'+idProj).attr('src',"<?php echo $img_path; ?>detail_moin.jpg");
				$('tr.tr-d-'+idUser+'-'+idProj).attr('style','');
			
		}
}
function showHideRow(id,cmp, id_user,username){
	 if($('#img_'+id).hasClass('visite-img')){
			$('#img_'+id).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
			$('#img_'+id).removeClass('visite-img');
			//alert($('tr.tr-'+id_user).length);
			$('tr.tr-a-'+id_user).attr('style','display:none');
			$('tr.tr-details-p').attr('style','display:none');
			$('.img_d_'+id_user).attr('src',"<?php echo $img_path; ?>detail_plus.jpg");
			$('.img_d_'+id_user).removeClass('visite-img-p');
		}else{
			
			$('#img_'+id).attr('src',"../images/load.gif");
			
			if(!$('#img_'+id).hasClass('show-detail')){
				$.post('rapport_imputation_user.php',{'annee':'<?php  echo $annee;?>','mois':'<?php  echo $mois;?>','proj':'<?php  echo $proj;?>','cat':'<?php  echo $cat;?>','idU':id_user,'nameU':username}, function(data){
					
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



</script>

</head>
<body><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top" >
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  <tr><td><br /><form action="rapport_imputation.php"  method="post" name ="form"><TABLE cellSpacing="0" cellPadding="0" width="100%" 
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
            name="annee" id="annee" class="input-annee">
		  <?php 
		  for ($i=2007;$i<=2025;$i++) 
{  ?>
            <option <?php if ($i==$annee) { echo "selected=\"selected\""; } ?> value="<?php echo $i; ?>"><?php echo $i; ?> </option><?php } ?>
          </select>&nbsp;
          <?php echo $tab_parametres['mois']; ?>&nbsp;:
			<select
            name="mois" id="mois" class="input-annee" style="width:80px;" >
  <?php 
	 for ($i=1;$i<=12;$i++) 
{ 
?>
  <option value="<?php echo $i; ?>" <?php if(($i)==$mois)
{ 
echo "selected"; 
}
 ?>><?php echo nom_mois($i-1); ?></option>
  <?php } ?>
</select>&nbsp;&nbsp;<input type="button" class="buttonImg"  value=" " name="Affich" onClick="gopagef('<?php echo $_SERVER['PHP_SELF']; ?>')">&nbsp; &nbsp;
		  <input type="submit" id="export-excel" name="exporter" class="buttonExcelImg" value=""  onClick="gopage('export_rapport.php')"  /><input type="hidden" name="rapport_activite" id="rapport_activite" value="1" /> <input type="hidden" name="mois" id="mois" value="<?php echo $mois; ?>" /> <input type="hidden" name="annee" id="annee" value="<?php echo $annee; ?>" /> <input type="hidden" name="pg" id="pageS" value="<?php echo $pg; ?>" /> <input type="hidden" name="mm" id="moiss" value="<?php echo $mois; ?>" /><input type="hidden" name="y" id="annees" value="<?php echo $annee; ?>" /><input type="hidden" name="rapport_imputation" id="rapport_imputation" value="<?php echo $page; ?>" /> 

</td>
                   </tr></TBODY></TABLE></TD></TR></TBODY></TABLE>		<input type='hidden' id='pg-paginate' name='pg' value='<?php echo $pg;?>'></input></form>
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
                            <TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font color="#CC0000"><b> <?php echo $tab_parametres['titre_rapport_imputation'];?> </b></font>                           </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>
  <br />   <div id="div_form">
  <form action="#">
			<fieldset>
				<b>Rechercher:</b>&nbsp;&nbsp;<input type="text" autofocus="" placeholder="Search" id="id_search" value="" name="search">
			</fieldset>
		</form>
<table id="myTable" cellpadding="1" cellspacing="1" class="table">
     <thead>
         <th axis="string" width="180" class="th"><?php echo $tab_parametres['collab'];?></th>
		<?php for ($j=1; $j<=$fin_mois; $j++) { ?>
        <th axis="number" class="th2" width="10" ><b><?php echo $j; ?></b></th><?php } ?>
        <th width="30" class="th" axis="number"><?php echo $tab_parametres['total'];?></th>
        <th width="30" class="th" axis="number"><?php echo $tab_parametres['delta'];?></th>
	<tbody>
<?php  $cmt=1;  foreach ($list as $key => $vUser) { 
$result = select_project_f($vUser['id'], $date1, $date2, $proj, $cat);
$nb_row = count($result);
	?>
<tr  id="<?php echo $cmt; ?>" bgcolor="#F8F7F7" onmouseover="this.bgColor='#C7C5C5';" onmouseout="this.bgColor='#F8F7F7'" class="tr-<?php echo $vUser['id']?>">

<td width="180" ><?php  if ($nb_row!=0)  { ?><a onclick="javascript:showHideRow('<?php echo $cmt; ?>', '<?php echo $nb_row; ?>','<?php echo $vUser['id'];?>','<?php echo $vUser['username'];?>')" style="cursor:hand"><img src="<?php echo $img_path; ?>detail_plus.jpg" hspace="2" id="img_<?php echo $cmt; ?>" height="9" width="9"/></a><?php } else { ?><img src="<?php echo $img_path; ?>spacer.gif" height="9" width="9" hspace="2" /><?php }
?>&nbsp;<b><?php echo $vUser['username']; ?></b></td>
<?php $cmp=1; $Total=0;
foreach ($TDate as $key => $vDate) {  
?>
<td  <?php if ((in_array($cmp, $Sam_mois)) or (in_array($cmp, $Dim_mois))) {
    echo "bgcolor='#FFFF33'"; 
} 
 ?> width="10" align="right"><span class="style-rapport"><?php if (isset($TSomImp[$vUser['username']][$vDate])) {
 $Imp = $TSomImp[$vUser['username']][$vDate]; $Total = $Total +  $Imp; echo $Imp; } else { echo "&nbsp;"; }  ?></span></td>
<?php
$cmp++;
}
$delta=$Total-$nb_jour_trav;
if($delta<0) { $color="#FF0000"; } else { $color="#006600"; }
?>
<td  align="right" ><span class="style-rapport"><?php echo $Total; ?></span></td>
<td  align="right" width="30" <?php if(isset($color)) {  ?> bgcolor="<?php echo $color; ?>"<?php } ?>><span class="style-rapport" style="color:#FFFFFF"><?php   echo $delta; ?></span></td>
</tr>


<?php  $cmt++; } ?>
</tbody>
</table>
</div>
</td></tr>
<tr><td>
<div class="center-wrap"><div class="carousel-pagination"><p><?php if($pg !='all'){?><a role="button" <?php if ($pg == 1){?>class="active" <?php }?> id="p-1" onclick='changePagePaginate(1)'><span>1</span></a><?php for( $i=2;$i<=$nbpage+1;$i++){ ?><a role="button" class=" <?php if ($pg == $i){ echo "active ";}?>paginate-page" id="p-<?php echo $i;?>" onclick="changePagePaginate('<?php echo $i;?>')"><span><?php echo $i;?></span></a><?php }?><a <?php if ($pg == 'all'){?>class="active" <?php }?> role="button" onclick='changePagePaginate("all")'><span>Tous</span></a><?php }else{?><a role="button"   onclick='changePagePaginate(1)'><span>Pagination</span></a><a <?php if ($pg == 'all'){?>class="active" <?php }?> role="button" onclick='changePagePaginate("all")'><span>Tous</span></a><?php }?></p></div></div>

</td></tr>
<tr valign="top" >
    <td height="76" ><?php include("bottom.php"); ?></td>
  </tr>
</table>
 
</body>
</html>
