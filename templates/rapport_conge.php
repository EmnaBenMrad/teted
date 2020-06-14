<?php
session_start();// On d�marre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
 
session_destroy();  // On d�truit la session
header("Location: login.php");
 }
//si le le login n'est pas fourni par session donc redirection vers la page login.php
if (!isset($_SESSION['login'])) 
{
  header("Location: login.php");
}

$id = $_SESSION['id'];
$user = $_SESSION['id'];
include "connexion.php"; // la page de connexion � la base jira
include "fonctions.php"; 

// $lg est le parametre de langue et c est egal 1 pour le fran�ais
$lg=1;
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
if(isset($_REQUEST['type'])) { $type=$_REQUEST['type']; }  else { $type = '10064'; }
$proj=""; $cat="";
//if((isset($mois)) and (strlen($mois==1))) { $mois = "0".$mois; }
$long_mois = strlen($mois); if((isset($mois)) and ($long_mois==1)) $mois = "0".$mois;
$fin_mois    = fin_mois($mois, $annee); 
$date1       = $annee."-".$mois."-01";  
$date2 = $annee."-".$mois."-".$fin_mois;
$List_type = type_issue($type);

$listImp = imputation_proj($type, $date1, $date2);
while ($row = mysql_fetch_row($listImp) )
{
	$TSomImp[$row[4]][$row[3]]= $row[0];
}
$Tproj = Liste_proj_rapport();
$nb_p = count($Tproj);
$list = list_userbase($date1, $date2, $proj, $cat); 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="../js/mootools_1.2.js"></script>
<script type="text/javascript" src="../js/sortableTable.js"></script>
<link href="../style/sortableTable.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Rapport des imputations</title>
</head>
<body><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top" >
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  <tr><td>

		<div id="example">
		<form action="rapport_conge.php" method="post" onSubmit="return loginCheck(this);" name ="form">Filter: 
					<select onchange=changePage1(this.form.annee.options[this.form.annee.options.selectedIndex].value) 
            name="annee" id="annee" class="input-annee">
		  <?php 
		  for ($i=2007;$i<=2019;$i++) 
{  ?>
            <option <?php if ($i==$annee) { echo "selected=\"selected\""; } ?> value="rapport_conge.php?y=<?php echo $i; ?>"><?php echo $i; ?> </option><?php } ?>
          </select>
			<select onChange="changePage1(this.form.mois.options[this.form.mois.options.selectedIndex].value)" 
            name="mois" id="mois" class="input-annee" style="width:80px;" >
  <?php 
	 for ($i=1;$i<=12;$i++) 
{ 
?>
  <option value="rapport_conge.php?y=<?php echo $annee; ?>&mm=<?php echo $i; ?>" <?php if(($i)==$mois)
{ 
echo "selected"; 
}
 ?>><?php echo nom_mois($i-1); ?></option>
  <?php } ?>
</select><select onChange="changePage1(this.form.categorie.options[this.form.categorie.options.selectedIndex].value)" 
            name="categorie" id="categorie" class="input-annee" style="width:150px;" >
  <option>Choix du projet</option>
  <?php 
	 for ($i=0;$i<$nb_p;$i++) 
{  $categorie = $Tproj[$i]['id'];
  $name = $Tproj[$i]['name'];
?>
  <option value="rapport_conge.php?y=<?php echo $annee; ?>&mm=<?php echo $mois; ?>&type=<?php echo $categorie; ?>" <?php if((isset($type)) and ($type==$categorie)) echo "selected='selected'"; ?> ><?php echo $name; ?></option>
  <?php }  ?>
</select>
					<input type="submit" value="Submit" />
					<input type="reset" value="Clear" />
				</form>
			<div class="tableFilter"><h3 class="example">Rechercher Par Utilisateur :</h3>
		  		<form id="tableFilter" onsubmit="myTable.filter(this.id); return false;">Filter: 
					<select id="column">
		  				<option value="0">Username</option>
						<option value="<?php $id_tot = $fin_mois + 1; echo $id_tot;  ?>">Total</option>
						<option value="<?php $id_del = $fin_mois + 2; echo $id_del; ?>">Delta</option>
					</select>
					<input type="text" id="keyword" />
					<input type="submit" value="Submit" />
					<input type="reset" value="Clear" />
				</form>
		  </div><br /> 
<table id="myTable" cellpadding="0" class="table">
     <thead>
         <th axis="string" width="100" class="th">Username</th>
		<?php for ($j=0; $j<$nb_p; $j++) { ?>
        <th axis="number" class="th2"><b><?php echo $List_type[$j]['name']; ?></b></th><?php } ?>
        <th axis="number" class="th">Total</th>
	</thead>

<tbody>
<?php foreach ($list as $key => $vUser) { 

	?>
<tr id="<?php echo $key+1; ?>" class="tr">

<td class="td" width="100"><?php echo $vUser; ?></td>
<?php $cmp=1; $Total=0; 
for ($j=0; $j<$nb_p; $j++) {   
?>
<td class="rightAlign td" width="250"><?php if (isset($TSomImp[$vUser][$List_type[$j]['name']])) { 
 $Imp = $TSomImp[$vUser][$List_type[$j]['name']]; $Total = $Total +  $Imp; echo $Imp; } else { echo "<img src='../images/spacer.gif' border=0/>"; }   ?></td>
<?php
//$cmp++;
}
?>
<td class="rightAlign td"><?php echo $Total; ?></td>
</tr>
<?php } ?>

</tbody>


</table>
</td></tr></table></div>
<table width="100%"><tr valign="top" >
    <td height="76" ><?php include("bottom.php"); ?></td>
  </tr></table>
<script type="text/javascript">
			var myTable = {};
			window.addEvent('domready', function(){
				myTable = new sortableTable('myTable', {overCls: 'over', onClick: function(){alert(this.id)}});
			});
		</script>
  </body>
</html>
