<?php $id=$_GET['id'];
$etapee=$_GET['etapee'];
if(isset($_GET['newissue'])) $newissue=$_GET['newissue'];
if(isset($_GET['ancien_samedi'])) $ancien_samedi=$_GET['ancien_samedi'];
if(isset($_GET['ancien_dimanche'])) $ancien_dimanche=$_GET['ancien_dimanche'];
if(isset($_GET['val_fer'])) $val_fer = $_GET['val_fer'];
if(isset($_GET['ancien_val_fer'])) $ancien_val_fer = $_GET['val_fer'];
if(isset($_GET['date_fer'])) $date_fer = $_GET['date_fer'];
$page=$_GET['page'];
 if($page==0) { $lien_page = "suivi_imputation.php?id="; }
 if($page==1) { $lien_page = "update_imputation.php?collab="; }
$y=$_GET['y'];
$raf=$_GET['raf'];
$comment=$_GET['comment'];
$mm=$_GET['mm'];
$week=$_GET['week'];
$proj=$_GET['proj'];
$sous_taches=$_GET['sous_taches'];
$user=$_GET['user'];
if(isset($_GET['date_sam']))  $date_sam =$_GET['date_sam'];
if(isset($_GET['samedi'])) $samedi=$_GET['samedi'];
if(isset($_GET['date_dim'])) $date_dim =$_GET['date_dim'];
if(isset($_GET['dimanche'])) $dimanche=$_GET['dimanche'];
include ("connexion.php");
include ("fonctions.php");

if($etapee=="insert")
{
	if(!empty($samedi))
		{
		$samedi = format_number($samedi);
		$var5=insert_imputation($proj, $sous_taches, $user, $date_sam, $samedi, $raf, $comment);
		$som_sam = $var5['somme'];
		$input_sam = $var5['input'];
		}
	if(!empty($dimanche))
		{
		$dimanche = format_number($dimanche);
		$var6=insert_imputation($proj, $sous_taches, $user, $date_dim, $dimanche, $raf, $comment);
		$som_dim = $var6['somme'];
		$input_dim = $var6['input'];
		}
if($sous_taches != "faux") { update_variable($sous_taches,$raf); }

}
if($etapee=="insert_fer")
{
	if(!empty($val_fer))
		{
		$val_fer = format_number($val_fer);
		$var7=insert_imputation($proj, $sous_taches, $user, $date_fer, $val_fer, $raf, $comment);
		$som_sam = $var7['somme'];
		$input_sam = $var7['input'];
		}
	
	if($sous_taches != "faux") { update_variable($sous_taches,$raf); }

}

if($etapee=="update_fer")
{
	if(!empty($val_fer) || !empty($ancien_val_fer))
		{
		$val_fer = format_number($val_fer);
		$var8=update_imputation($proj, $sous_taches,$newissue, $user, $date_fer, $val_fer, $ancien_val_fer, $raf, $comment);
		$som_sam = $var8['somme'];
		$input_sam = $var8['input'];
		}
	
	if($sous_taches != "faux") { update_variable($sous_taches,$raf); }

}
if($etapee=="update")
{ 
	if((!empty($samedi)) || (!empty($ancien_samedi)))
		{
		
		$samedi = format_number($samedi);
		$var5=update_imputation($proj, $sous_taches,$newissue, $user, $date_sam, $samedi, $ancien_samedi, $raf, $comment);
		$som_imp = $var5['somme'];
		$input_imp = $var5['input'];
		}
	if((!empty($dimanche)) || (!empty($ancien_dimanche)))
		{
		$dimanche = format_number($dimanche);
		$var5=update_imputation($proj, $sous_taches,$newissue, $user, $date_dim, $dimanche, $ancien_dimanche, $raf, $comment);
		$som_imp = $var5['somme'];
		$input_imp = $var5['input'];
		}

if($sous_taches != "faux") { update_variable($sous_taches,$raf); }
if(($sous_taches != "faux") and ($newissue != "faux")) 
{
	if($sous_taches!=$newissue)
	{
	update_variable($newissue,$raf);
	}
}
}
?>
<script language="javascript">

document.location.href='<?php echo $lien_page.$id."&mm=$mm&week=$week&y=$y"; ?>';
</script>
