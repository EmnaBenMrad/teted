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
$login = $_SESSION['login']; 
 include "connexion.php"; // la page de connexion � la base jira
 include "fonctions.php";
  include "function_date.php";
/*******************Asma OUESLATI 27/03/2007 
code li� aux param�trage des pages 
*****************/
// $lg est le parametre de langue et c est egal 1 pour le fran�ais
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de param�trage***/
?>
<!------------------------ MEDINI Mounira Le 02/04/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['suivi_avancement'];?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>
<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>
<LINK href="../style/global.css" type=text/css rel=StyleSheet>

<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style></head>
<!---------------- javascript pour l'action sur la liste d�roulante -----------------//-->
 <SCRIPT language=JavaScript><!--
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
			</SCRIPT><script language="JavaScript" src="popcalendar.js"></script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<?php $val_fer = 0;
$ancien_val_fer = 0;
$i = 0;
$j=0; 

//code li� aux groupes aux quelles il fait partie le user actuel pour affichage du menu 
$lien_bord = "inactif";
$lien_adm = "inactif";
$group = liste_group($id);
$nb_group = count($group);
for($j==0;$j<$nb_group;$j++)
{
$list_group = $group[$j];
if($list_group=="TD-tdbusers"){ $lien_bord = "actif"; }
if($list_group=="TD-Administrators"){ $lien_adm = "actif"; }
}
/**fin requete de groupe***/
//code li� � la r�cup�ration des param�tres que se sois par la m�thode get ou aussi par post
$dat_sys = date('Y')."-".date('m')."-".date('d');
//if (isset($_GET['type'])) { $type = $_GET['type'];  }
//if (isset($_GET['statu'])) { $statu = $_GET['statu']; }
if (isset($_GET['proj'])) { $proj = $_GET['proj']; $_SESSION['proj'] = $proj; }
if (isset($_GET['tache'])) { $tache = $_GET['tache']; $_SESSION['tache'] = $tache;  }
//if (isset($_GET['collaborateur'])) { $collaborateur = $_GET['collaborateur'];  }
//if (isset($_GET['priorite'])) { $priorite = $_GET['priorite'];  }
//if (isset($_GET['date'])) { $date = $_GET['Date']; }
//if (isset($_GET['date1'])) { $date = $_GET['Date2'];  }
if (isset($_POST['type'])) { $type = $_POST['types']; $_SESSION['type'] = $type; }
if (isset($_POST['statu'])) { $statu = $_POST['status']; $_SESSION['statu'] = $statu; }
if (isset($_POST['proj'])) { $proj = $_POST['proj']; $_SESSION['proj'] = $proj; }
if (isset($_POST['tache'])) { $tache = $_POST['tache']; $_SESSION['tache'] = $tache; }
if (isset($_POST['collaborateur'])) { $collaborateur = $_POST['collab']; $_SESSION['collaborateur'] = $collaborateur; }
if (isset($_POST['priorite'])) { $priorite = $_POST['priority']; $_SESSION['priorite'] = $priorite;  }
if (isset($_POST['Date'])) { $date = $_POST['Date']; $_SESSION['date'] = $date; }
if (isset($_POST['Date2'])) { $date2 = $_POST['Date2']; $_SESSION['date2'] = $date2;  }

if (isset($_GET['orderp'])) { $orderp = $_GET['orderp']; $proj = $_SESSION['proj'] ; $tache = $_SESSION['tache']; $type = $_SESSION['type']; $statu = $_SESSION['statu'] ; $collaborateur = $_SESSION['collaborateur']; $priorite = $_SESSION['priorite']; $date = $_SESSION['date']; $date2 = $_SESSION['date2']; }
if ((isset($_GET['orderp'])) && (($_GET['orderp'] != "ASC") && ($_GET['orderp'] != "DESC")))
{ $orderp = "ASC"; }
if (isset($_GET['ordert'])) { $ordert = $_GET['ordert'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['ordert'])) && (($_GET['ordert'] != "ASC") && ($_GET['ordert'] != "DESC")))
{ $ordert = "ASC"; }
if (isset($_GET['orderdd'])) { $orderdd = $_GET['orderdd'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['orderdd'])) && (($_GET['orderdd'] != "ASC") && ($_GET['orderdd'] != "DESC")))
{ $orderdd = "ASC"; }
if (isset($_GET['orderuser'])) { $orderuser = $_GET['orderuser'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['orderuser'])) && (($_GET['orderuser'] != "ASC") && ($_GET['orderuser'] != "DESC")))
{ $orderuser = "ASC"; }
if (isset($_GET['orderdf'])) { $orderdf = $_GET['orderdf'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['orderdf'])) && (($_GET['orderdf'] != "ASC") && ($_GET['orderdf'] != "DESC")))
{ $orderdf = "ASC"; }
if (isset($_GET['ordercp'])) { $ordercp = $_GET['ordercp'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['ordercp'])) && (($_GET['ordercp'] != "ASC") && ($_GET['ordercp'] != "DESC")))
{ $ordercp = "ASC"; }
if (isset($_GET['ordercc'])) { $ordercc = $_GET['ordercc'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['ordercc'])) && (($_GET['ordercc'] != "ASC") && ($_GET['ordercc'] != "DESC")))
{ $ordercc = "ASC"; }
if (isset($_GET['orderav'])) { $orderav = $_GET['orderav'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2'];}
if ((isset($_GET['orderav'])) && (($_GET['orderav'] != "ASC") && ($_GET['orderav'] != "DESC")))
{ $orderav = "ASC"; }
if (isset($_GET['orderraf'])) { $orderraf = $_GET['orderraf']; @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['orderraf'])) && (($_GET['orderraf'] != "ASC") && ($_GET['orderraf'] != "DESC")))
{ $orderraf = "ASC"; }
if (isset($_GET['orderpre'])) { $orderpre = $_GET['orderpre'];  @$proj = $_SESSION['proj'] ; @$tache = $_SESSION['tache']; @$type = $_SESSION['type']; @$statu = $_SESSION['statu'] ; @$collaborateur = $_SESSION['collaborateur']; @$priorite = $_SESSION['priorite']; @$date = $_SESSION['date']; @$date2 = $_SESSION['date2']; }
if ((isset($_GET['orderpre'])) && (($_GET['orderpre'] != "ASC") && ($_GET['orderpre'] != "DESC")))
{ $orderpre = "ASC"; }
/**fin de r�cup�ration des param�tres GET et POST***/
$i=0;
//echo $lien."<br>"; 
  ?>
<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("entete.php"); ?></td>
  </tr>
  <tr><td><?php echo tab_vide(9); ?></td></tr>
  <tr><td><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing="0" cellPadding="4" width="100%" 
                  bgColor=#ffffff border="0">
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#EFEFEF">
                        <TABLE cellSpacing="0" cellPadding="0" width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor="#EFEFEF" align="left"><H3 class=formtitle>
							<?php echo $tab_parametres['suivi_avancement'];?>
							</H3>                              </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE></td></tr>
				  
				  <tr><td><form action="suivi_avancement.php" method="post" onSubmit="return loginCheck(this);" ><?php echo tab_vide(1); //c une fonction pour donner l'espacement vertical 

if($lien_bord=="actif") {
$query="SELECT DISTINCT(project.ID),pname from project"; 
}
else {
$query="SELECT DISTINCT(project.ID),project.pname from project, jiraissue where jiraissue.ASSIGNEE ='$login' AND project.ID = jiraissue.project order by project.ID "; }
 $result = mysql_query($query);

 //echo mysql_num_rows($result);
?>

<?php if($lien_bord=="actif") {
$requete_proj = "SELECT distinct(project.ID), project.pname from project, jiraissue where 1 ";
}
else {
$requete_proj = "SELECT DISTINCT(project.ID),project.pname from project, jiraissue where jiraissue.ASSIGNEE ='$login' AND project.ID = jiraissue.project ";}
if((isset($proj)) and ($proj!="all") and (!empty($proj)))
{
$requete_proj.="AND project.ID = $proj ";
}
if((isset($collaborateur)) and ($collaborateur!="all") and (!empty($collaborateur)))
{
$requete_proj.="AND (project.ID = jiraissue.PROJECT) AND (jiraissue.ASSIGNEE = (select username from userbase where ID = $collaborateur)) ";
}
if(isset($orderp))
{
if($orderp=="DESC") { $orderbyp = "ASC"; }
if($orderp=="ASC") { $orderbyp = "DESC"; }
if(empty($orderp)) { $orderbyp = "ASC"; }
$requete_proj.="order by project.pname ".$orderbyp;
} 
$rrrrr = mysql_query($requete_proj);
$nb_projet = mysql_num_rows($rrrrr);
while ($tab = mysql_fetch_array($rrrrr))
{
$projet = $tab[1];
$requete_imp = "SELECT (jiraissue.ID), jiraissue.SUMMARY, min(imputation.Date) as min, max(imputation.Date) as max, sum(imputation.imputation) as som, imputation.RAF, customfieldvalue.NUMBERVALUE as number, (sum(imputation.imputation) / customfieldvalue.NUMBERVALUE) as ch_con, (imputation.RAF/ customfieldvalue.NUMBERVALUE) as ch_prev, jiraissue.ASSIGNEE
FROM jiraissue
LEFT JOIN imputation ON (jiraissue.ID = imputation.issue)
INNER JOIN issuestatus ON (issuestatus.pname != 'Closed' AND issuestatus.ID = jiraissue.issuestatus) 
INNER JOIN customfieldvalue ON (customfieldvalue.issue = jiraissue.ID AND customfieldvalue.customfield = 10000)
where (jiraissue.PROJECT = $tab[0]) ";
if((isset($tache)) and ($tache!="all") and (!empty($tache))) {
$requete_imp.=" and (jiraissue.ID='$tache') ";
}
if((isset($statu)) and ($statu!="all") and (!empty($statu)))
{
$requete_imp.="AND (jiraissue.issuestatus = '$statu') ";
}
if((isset($type)) and ($type!="all") and (!empty($type)))
{
$requete_imp.="AND (jiraissue.issuetype = '$type') ";
}
if((isset($priorite)) and ($priorite!="all") and (!empty($priorite)))
{
$requete_imp.="AND (jiraissue.PRIORITY = '$priorite') ";
}
if((isset($collaborateur)) and ($collaborateur!="all") and (!empty($collaborateur)))
{
$login_user = "select username from userbase where ID = $collaborateur";
$exe_login = mysql_query($login_user);
$login_collab = mysql_result($exe_login, 0, 'username');
$requete_imp.="AND (jiraissue.ASSIGNEE = '$login_collab') ";
}
if(((isset($date)) && (isset($date2))) && (($date!="") && ($date2!="")))
{
$date1 = format_date($date);
$date7 = format_date($date2);
$requete_imp.="AND imputation.DATE
BETWEEN '".$date1."'
AND '".$date7."' ";
}
$requete_imp.="group by jiraissue.ID ";
if(isset($ordert))
{
if($ordert=="DESC") { $orderbyt = "ASC"; }
if($ordert=="ASC") { $orderbyt = "DESC"; }
$requete_imp.="order by (jiraissue.SUMMARY) ".$orderbyt;
}
else
{
	if(isset($orderdd))
	{
	if($orderdd=="DESC") { $orderbydd = "ASC"; }
	if($orderdd=="ASC") { $orderbydd = "DESC"; }
	$requete_imp.="order by min ".$orderbydd;
	}
	if(isset($orderuser))
	{
	if($orderuser=="DESC") { $orderbydf = "ASC"; }
	if($orderuser=="ASC") { $orderbydf = "DESC"; }
	$requete_imp.="order by max ".$orderbydf;
	}
	if(isset($orderdf))
	{
	if($orderdf=="DESC") { $orderbydf = "ASC"; }
	if($orderdf=="ASC") { $orderbydf = "DESC"; }
	$requete_imp.="order by max ".$orderbydf;
	}
	if(isset($ordercp))
	{
	if($ordercp=="DESC") { $orderbycp = "ASC"; }
	if($ordercp=="ASC") { $orderbycp = "DESC"; }
	$requete_imp.="order by number ".$orderbycp;
	}
	if(isset($ordercc))
	{
	if($ordercc=="DESC") { $orderbycc = "ASC"; }
	if($ordercc=="ASC") {  $orderbycc = "DESC"; }
	$requete_imp.="order by som ".$orderbycc;
	}
	if(isset($orderav))
	{
	if($orderav=="DESC") { $orderbyav = "ASC"; }
	if($orderav=="ASC") {  $orderbyav = "DESC"; }
	$requete_imp.="order by ch_con ".$orderbyav;
	}
	if(isset($orderraf))
	{
	if($orderraf=="DESC") { $orderbyraf = "ASC"; }
	if($orderraf=="ASC") {  $orderbyraf = "DESC"; }
	$requete_imp.="order by imputation.RAF ".$orderbyraf;
	}
	if(isset($orderpre))
	{
	if($orderpre=="DESC") { $orderbypre = "ASC"; }
	if($orderpre=="ASC") {  $orderbypre = "DESC"; }
	$requete_imp.="order by ch_prev ".$orderbypre;
	}

}
//echo $requete_imp;
$select_imp = mysql_query($requete_imp);
$nb_tache = mysql_num_rows($select_imp);
}
?>
				    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
<table border="0" align="left" cellspacing="10" cellpadding="0" width="102%">
  <tr>
    <td height="30"><select onchange="changePage2(this.form.projets.options[this.form.projets.options.selectedIndex].value)" 
            name="projets" id="projets" class="input-tache">
  <option value="<?php echo "suivi_avancement.php?proj=all"; ?>"><?php echo $tab_parametres['liste_projet'];?></option>
  <?php while($row = mysql_fetch_array($result)){ 
  $pro = $row[0];  

?>
  <option value="<?php echo "suivi_avancement.php?proj=".$row[0]; ?>" <?php if ((isset($proj)) and ($pro==$proj)){ echo "selected"; } ?>><?php echo $row[1]; ?>
  <?php } ?></option>
</select>	&nbsp;&nbsp;&nbsp;
<select name="taches" id="taches" class="input-long" onchange="changePage2(this.form.taches.options[this.form.taches.options.selectedIndex].value)">
<OPTION value="<?php echo "suivi_avancement.php?tache=all&proj=$proj"; ?>"><?php echo $tab_parametres['liste_tache'];?></OPTION>
<?php if (isset($proj)) 
		{ 
		 $query1="SELECT * from project, jiraissue, issuestatus where project.ID ='$proj' AND (project.ID = jiraissue.project) AND (issuestatus.ID = jiraissue.issuestatus) AND (issuestatus.pname NOT IN (select libelle from status_ferme))";
		  $result1 = mysql_query($query1);

		  ?>

		  
		 		   <?php while($row1 = mysql_fetch_array($result1)){ ?>
<OPTION value="<?php echo "suivi_avancement.php?tache=".$row1[8]."&proj=$proj"; ?>" <?php if ((isset($tache)) and ($tache==$row1[8])) { echo "selected"; }?> ><?php echo $row1['SUMMARY']; ?></OPTION>

<?php }
} 
 ?></select></td>
    </tr>
  <tr>
    <td height="30">
	<div id="ligne"><?php $query="SELECT * from issuetype";
$result = mysql_query($query);
	?>
	<select name="types" class="input-liste-courte"  style="width:140px;" >
<OPTION value="all">Type</OPTION>

		  <?php while($row = mysql_fetch_array($result)){ 
	  $typess = type($row[0]);
$myvar = str_replace(array("\n","\r"),"<br>",$typess);
$typ = explode("<br>", $myvar);

if(!empty($typ[0])) {	  ?>
<OPTION value="<?php echo $row[0]; ?>" <?php if ((isset($type)) and ($type==$row[0])) { echo "selected='selected'"; }?> ><?php print_r($typ[0]); ?></OPTION>
<?php } 
}
 ?></select>
	&nbsp;&nbsp;&nbsp;
	<?php $query2="SELECT ID from issuestatus";
$result2 = mysql_query($query2);		
?>
<select name="status" class="input-liste-courte">
      <option value="all">Status</option>
      <?php while($row2 = mysql_fetch_array($result2)){ 
	  $var = status($row2[0]);
$myvar = str_replace(array("\n","\r"),"<br>",$var);
$status = explode("<br>", $myvar);
if(!empty($status[0])) {	  ?>
<OPTION value="<?php echo $row2[0]; ?>" <?php if ((isset($statu)) and ($statu==$row2[0])) { echo "selected"; }?> ><?php print_r($status[0]); ?></OPTION>
      <?php } }  ?></option>
    </select>
	&nbsp;&nbsp;&nbsp;<?php $query3="SELECT * from priority";
$result3 = mysql_query($query3);?>
<select name="priority" class="input-liste-courte">
      <option value="all">Priorit�</option>
      <?php while($row3 = mysql_fetch_array($result3)){ 
$prio = priority($row3[0]);
$mypri = str_replace(array("\n","\r"),"<br>",$prio);
$priority = explode("<br>", $mypri);
if(!empty($priority[0])) {	  ?>
<OPTION value="<?php echo $row3[0]; ?>" <?php if ((isset($priorite)) and ($priorite==$row3[0])) { echo "selected"; }?> ><?php print_r($priority[0]); ?></OPTION>
      <?php }
} 
 ?>
    </select>&nbsp;&nbsp;&nbsp;
<?php //$query2="SELECT * from issuestatus";
 		$query1="SELECT DISTINCT(userbase.ID), username from userbase ORDER BY username ASC  ";
		$result1 = mysql_query($query1);
		?>
		<select name="collab" class="input-liste-collab">
      <option value="all">Collaborateur</option>	
      <?php while($row1 = mysql_fetch_array($result1)){ $nom = nom_prenom_user($row1[0]); ?>
      <option value="<?php echo $row1[0]; ?>" <?php if ((isset($collaborateur)) and ($collaborateur==$row1[0])) { echo "selected"; }?>  ><?php echo $nom; ?></option>
      <?php }  ?>  </select>
&nbsp;&nbsp;

<?php echo $tab_parametres['du']; ?> :&nbsp;&nbsp;
<input type="text" name="Date" class="input-annee" value="<?php echo @FormatDateTime($Date,2) ?><?php if (isset($date)) { echo $date; } ?>" />
      &nbsp;
      <input name="image" type="image" onclick="popUpCalendar(this, this.form.Date,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
&nbsp;&nbsp;<?php echo $tab_parametres['au']; ?>:&nbsp;&nbsp;
<input type="text" name="Date2" class="input-annee" value="<?php echo @FormatDateTime($Date2,2) ?><?php if (isset($date2)) { echo $date2; } ?>" />
      &nbsp;
      <input name="image" type="image" onclick="popUpCalendar(this, this.form.Date2,'dd/mm/yyyy');return false;" src="../images/calendar.gif" alt="Cliquez pour choisir une Date !" align="middle" />
 <input name="proj" type="hidden" <?php if(isset($proj)) { ?>value="<?php echo $proj; ?>"<?php } ?> />
<input name="tache" type="hidden" <?php if(isset($tache)) { ?> value="<?php echo $tache; ?>"<?php } ?> />
<input name="statu" type="hidden" <?php if(isset($statu)) { ?>  value="<?php echo $statu; ?>"<?php } ?> />
<input name="priorite" type="hidden" <?php if(isset($priorite)) { ?>  value="<?php echo $priorite; ?>"<?php } ?> />
<input name="type" type="hidden" <?php if(isset($type)) { ?>  value="<?php echo $type; ?>"<?php } ?> />
<input name="collaborateur" type="hidden" <?php if(isset($collaborateur)) { ?> value="<?php echo $collaborateur; ?>"<?php } ?> />
<input name="date" type="hidden" <?php if(isset($date)) { ?> value="<?php echo $date; ?>" <?php } ?> />
<input name="date2" type="hidden" <?php if(isset($date2)) { ?> value="<?php echo $date2; ?>" <?php } ?> /><input type="submit" name="Submit" value="Ok" /></div>  </td>
    </tr>
</table>
                        </td>
                      </tr>
					  <tr>
                        <td>    

<?php echo tab_vide(25);
if(($nb_projet>0) && ($nb_tache>0))
{
 ?><TABLE cellSpacing="0" cellPadding="0" width="100%" align="center" bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD>
                  <TABLE cellSpacing="1" cellPadding="4" width="100%" bgColor="#bbbbbb" border="0">
                    <TBODY>
                    <tr valign="middle" align="center" bgColor="#EFEFEF">
        <td width="148" vAlign="middle" class="txte-bleu10b"><?php if(isset($orderp)) 
	{
	if($orderp=="ASC") { $orderp="DESC"; }
	elseif($orderp=="DESC") { $orderp="ASC";}
	}
if(!isset($orderp)) { 
$orderp="DESC";} 
?><a href="<?php echo "suivi_avancement.php?orderp=$orderp"; ?>" class="liensbleu10b"><?php echo $tab_parametres['projet'];?></a></td>
        <td width="340" class="txte-bleu10b"><?php if(isset($ordert)) 
	{
	if($ordert=="ASC") { $ordert="DESC"; }
	elseif($ordert=="DESC") { $ordert="ASC";}
	}
if(!isset($ordert)) { 
$orderrr = "suivi_avancement.php?ordert=DESC";
} 
else { $orderrr = "suivi_avancement.php?ordert=$ordert"; }

?><a href="<?php echo $orderrr; ?>" class="liensbleu10b"><?php echo $tab_parametres['taches'];?> </a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($orderuser)) 
	{
	if($orderuser=="ASC") { $orderuser="DESC"; }
	elseif($orderuser=="DESC") { $orderuser="ASC";}
	}
if(!isset($orderuser)) { 
$orderus = "suivi_avancement.php?orderuser=DESC";
} 
else { $orderus = "suivi_avancement.php?orderuser=$orderuser"; }

?><a href="<?php echo $orderus; ?>" class="liensbleu10b"><?php echo $tab_parametres['utilisateur'];?></a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($orderdd)) 
	{
	if($orderdd=="ASC") { $orderdd="DESC"; }
	elseif($orderdd=="DESC") { $orderdd="ASC";}
	}
if(!isset($orderdd)) { 
$orderrr = "suivi_avancement.php?orderdd=DESC";
} 
else { $orderrr = "suivi_avancement.php?orderdd=$orderdd"; }

?><a href="<?php echo $orderrr; ?>" class="liensbleu10b"><?php echo $tab_parametres['date_deb'];?></a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($orderdf)) 
	{
	if($orderdf=="ASC") { $orderdf="DESC"; }
	elseif($orderdf=="DESC") { $orderdf="ASC";}
	}
if(!isset($orderdf)) { 
$order2 = "suivi_avancement.php?orderdf=DESC";
} 
else { $order2 = "suivi_avancement.php?orderdf=$orderdf"; }

?><a href="<?php echo $order2; ?>" class="liensbleu10b"><?php echo $tab_parametres['date_fin'];?></a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($ordercp)) 
	{
	if($ordercp=="ASC") { $ordercp="DESC"; }
	elseif($ordercp=="DESC") { $ordercp="ASC";}
	}
if(!isset($ordercp)) { 
$orderrp = "suivi_avancement.php?ordercp=DESC";
} 
else { $orderrp = "suivi_avancement.php?ordercp=$ordercp"; }

?><a href="<?php echo $orderrp; ?>" class="liensbleu10b"><?php echo $tab_parametres['Chge_Prev'];?> </a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($ordercc)) 
	{
	if($ordercc=="ASC") { $ordercc="DESC"; }
	elseif($ordercc=="DESC") { $ordercc="ASC";}
	}
if(!isset($ordercc)) { 
$orderc = "suivi_avancement.php?ordercc=DESC";
} 
else { $orderc = "suivi_avancement.php?ordercc=$ordercc"; }

?><a href="<?php echo $orderc; ?>" class="liensbleu10b"><?php echo $tab_parametres['Chge_Cons'];?> </a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
	if(isset($orderraf)) 
	{
	if($orderraf=="ASC") { $orderraf="DESC"; }
	elseif($orderraf=="DESC") { $orderraf="ASC";}
	}
if(!isset($orderraf)) { 
$orderaf = "suivi_avancement.php?orderraf=DESC";
} 
else { $orderaf = "suivi_avancement.php?orderraf=$orderraf"; }

?><a href="<?php echo $orderaf; ?>" class="liensbleu10b"><?php echo $tab_parametres['RAF'];?></a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
if(isset($orderav)) 
	{
	if($orderav=="ASC") { $orderav="DESC"; }
	elseif($orderav=="DESC") { $orderav="ASC";}
	}
if(!isset($orderav)) { 
$orderrav = "suivi_avancement.php?orderav=DESC";
} 
else { $orderrav = "suivi_avancement.php?orderav=$orderav"; }

?><a href="<?php echo $orderrav; ?>" class="liensbleu10b"><?php echo $tab_parametres['Avanc'];?></a></td>
        <td id="width-avan"><?php //code pour basculer entre l'ordre asc et dsc
if(isset($orderpre)) 
	{
	if($orderpre=="ASC") { $orderpre="DESC"; }
	elseif($orderpre=="DESC") { $orderpre="ASC";}
	}
if(!isset($orderpre)) { 
$orderprev = "suivi_avancement.php?orderpre=DESC";
} 
else { $orderprev = "suivi_avancement.php?orderpre=$orderpre"; }

?><a href="<?php echo $orderprev; ?>" class="liensbleu10b"><?php echo $tab_parametres['Prev'];?></a></td>
        </tr>
   <?php if($lien_bord=="actif") {
$requete_proj = "SELECT distinct(project.ID), project.pname from project, jiraissue where 1 ";
}
else {
$requete_proj = "SELECT DISTINCT(project.ID),project.pname from project, jiraissue where jiraissue.ASSIGNEE ='$login' AND project.ID = jiraissue.project ";}
if((isset($proj)) and ($proj!="all") and (!empty($proj)))
{
$requete_proj.="AND project.ID = $proj ";
}
if((isset($collaborateur)) and ($collaborateur!="all") and (!empty($collaborateur)))
{
$requete_proj.="AND (project.ID = jiraissue.PROJECT) AND (jiraissue.ASSIGNEE = (select username from userbase where ID = $collaborateur)) ";
}
if(isset($orderp))
{
if($orderp=="DESC") { $orderbyp = "ASC"; }
if($orderp=="ASC") { $orderbyp = "DESC"; }
if(empty($orderp)) { $orderbyp = "ASC"; }
$requete_proj.="order by project.pname ".$orderbyp;
} 
//echo $requete_proj;
$select_proj = mysql_query($requete_proj);
//echo mysql_num_rows($select_proj);
$i=0;
while ($tab=mysql_fetch_row($select_proj))
{
$projet = $tab[1];
$requete_imp = "SELECT (jiraissue.ID), jiraissue.SUMMARY, min(imputation.Date) as min, max(imputation.Date) as max, sum(imputation.imputation) as som, imputation.RAF, customfieldvalue.NUMBERVALUE as number, (sum(imputation.imputation) / customfieldvalue.NUMBERVALUE) as ch_con, (imputation.RAF/ customfieldvalue.NUMBERVALUE) as ch_prev, jiraissue.ASSIGNEE
FROM jiraissue
LEFT JOIN imputation ON (jiraissue.ID = imputation.issue)
INNER JOIN issuestatus ON (issuestatus.pname != 'Closed' AND issuestatus.ID = jiraissue.issuestatus) 
INNER JOIN customfieldvalue ON (customfieldvalue.issue = jiraissue.ID AND customfieldvalue.customfield = 10000)
where (jiraissue.PROJECT = $tab[0]) ";
if((isset($tache)) and ($tache!="all") and (!empty($tache))) {
$requete_imp.=" and (jiraissue.ID='$tache') ";
}
if((isset($statu)) and ($statu!="all") and (!empty($statu)))
{
$requete_imp.="AND (jiraissue.issuestatus = '$statu') ";
}
if((isset($type)) and ($type!="all") and (!empty($type)))
{
$requete_imp.="AND (jiraissue.issuetype = '$type') ";
}
if((isset($priorite)) and ($priorite!="all") and (!empty($priorite)))
{
$requete_imp.="AND (jiraissue.PRIORITY = '$priorite') ";
}
if((isset($collaborateur)) and ($collaborateur!="all") and (!empty($collaborateur)))
{
$login_user = "select username from userbase where ID = $collaborateur";
$exe_login = mysql_query($login_user);
$login_collab = mysql_result($exe_login, 0, 'username');
$requete_imp.="AND (jiraissue.ASSIGNEE = '$login_collab') ";
}
if(((isset($date)) && (isset($date2))) && (($date!="") && ($date2!="")))
{
$date1 = format_date($date);
$date7 = format_date($date2);
$requete_imp.="AND imputation.DATE
BETWEEN '".$date1."'
AND '".$date7."' ";
}
$requete_imp.="group by jiraissue.ID ";
if(isset($ordert))
{
if($ordert=="DESC") { $orderbyt = "ASC"; }
if($ordert=="ASC") { $orderbyt = "DESC"; }
$requete_imp.="order by jiraissue.SUMMARY ".$orderbyt;
}
else
{
	if(isset($orderdd))
	{
	if($orderdd=="DESC") { $orderbydd = "ASC"; }
	if($orderdd=="ASC") { $orderbydd = "DESC"; }
	$requete_imp.="order by min ".$orderbydd;
	}
	if(isset($orderuser))
	{
	if($orderuser=="DESC") { $orderbydf = "ASC"; }
	if($orderuser=="ASC") { $orderbydf = "DESC"; }
	$requete_imp.="order by jiraissue.ASSIGNEE ".$orderbydf;
	}
	if(isset($orderdf))
	{
	if($orderdf=="DESC") { $orderbydf = "ASC"; }
	if($orderdf=="ASC") { $orderbydf = "DESC"; }
	$requete_imp.="order by max ".$orderbydf;
	}
	if(isset($ordercp))
	{
	if($ordercp=="DESC") { $orderbycp = "ASC"; }
	if($ordercp=="ASC") { $orderbycp = "DESC"; }
	$requete_imp.="order by number ".$orderbycp;
	}
	if(isset($ordercc))
	{
	if($ordercc=="DESC") { $orderbycc = "ASC"; }
	if($ordercc=="ASC") {  $orderbycc = "DESC"; }
	$requete_imp.="order by som ".$orderbycc;
	}
	if(isset($orderav))
	{
	if($orderav=="DESC") { $orderbyav = "ASC"; }
	if($orderav=="ASC") {  $orderbyav = "DESC"; }
	$requete_imp.="order by ch_con ".$orderbyav;
	}
	if(isset($orderraf))
	{
	if($orderraf=="DESC") { $orderbyraf = "ASC"; }
	if($orderraf=="ASC") {  $orderbyraf = "DESC"; }
	$requete_imp.="order by imputation.RAF ".$orderbyraf;
	}
	if(isset($orderpre))
	{
	if($orderpre=="DESC") { $orderbypre = "ASC"; }
	if($orderpre=="ASC") {  $orderbypre = "DESC"; }
	$requete_imp.="order by ch_prev ".$orderbypre;
	}

}
//echo $requete_imp;
$select_imp = mysql_query($requete_imp);
$nb_tache = mysql_num_rows($select_imp);
$tab_dd = array();
$tab_df = array();
$tab_ci = array();
$tab_cc = array();
$tab_raf = array();
$var_tab = 0;
while($row = mysql_fetch_array($select_imp)) {
	$tab_dd[$var_tab] = $row[2];
	$tab_df[$var_tab] = $row[3];
	$tab_ci[$var_tab] = $row[6];
	$tab_cc[$var_tab] = $row[4];
	$tab_raf[$var_tab] = $row[5];
	$var_tab++;
if((!empty($row[6])) && ($row[4]!=0)) { 
$avanc = ($row[4]/$row[6]); 	$avance = format_number($avanc);
$pour_avanc = ($avance*100);
	if($pour_avanc>100) { $bg = "FFFFF0";  $colora = "990000"; $celcolor = "F5CFC3"; } 
	else {  $bg = "FFFFFF"; $colora = "003366";  $celcolor = "FFFFFF"; }
	
}
else {  $pour_avanc = 0; 

$bg = "FFFFFF"; $colora = "003366";  $celcolor = "FFFFFF";
}
if(!isset($bg)) { $bg="FFFFFF"; $celcolor = "FFFFFF";}

if($row[3]>$dat_sys) { $bg = "FFFFF0"; $colord = "990000"; $celcolord = "F5CFC3"; }
	else {   $colord = "003366";  $celcolord = "FFFFFF"; }

if(($celcolord== "FFFFFF") && ($celcolor == "F5CFC3")) { $celcolord="FFFFF0"; }
if(($celcolord== "F5CFC3") && ($celcolor == "FFFFFF")) { $celcolor="FFFFF0"; }
$pre = ($row[5]/$row[6]); 	$prev = format_number($pre);
$pour_prev = ($prev*100);
?>   
	  <tr bgcolor="<?php echo $bg; ?>" valign="top" align="left" id="texte-bleu12n">
        <td id="texte-bleu12n" nowrap="nowrap" ><?php echo $projet; ?></td>
        <td id="texte-bleu12n" width="340"><?php echo $row[1]; ?></td>
        <td align="center" id="texte-bleu12n"><?php if(empty($row[9])) { ?><font color="#CC0000"><b> - </b></font> <?php }?> <div align="left"><?php echo $row[9]; ?></div></td>
        <td align="center" id="texte-bleu12n"><?php if(empty($row[2])) { ?><font color="#CC0000"><b> - </b></font> <?php } ?> <?php echo $row[2]; ?></td>
        <td align="center" id="texte-bleu12n" bgcolor="<?php echo $celcolord; ?>"><?php if(empty($row[3])) { ?><font color="#CC0000"><b> - </b></font> <?php } ?><font color="<?php echo $colord; ?>"><?php echo $row[3]; ?></font></td>
        <td align="center" id="texte-bleu12n"><?php if(empty($row[6])) { ?><font color="#CC0000"><b> - </b></font> <?php } ?> <div align="right"><?php echo format_number($row[6]); $ch_prev = $row[6];  ?></div></td>
        <td align="center" id="texte-bleu12n"><?php if(empty($row[4])) { ?><font color="#CC0000"><b> - </b></font> <?php } ?><div align="right"> <?php echo $row[4]; $ch_con = $row[4];  ?></div></td>
        <td align="center" id="texte-bleu12n"><?php if((empty($row[5])) || ($row[5]==0)) { ?><font color="#CC0000"><b> - </b></font> <?php } else { ?> <div align="right"><?php echo $row[5]; ?> </div> <?php } $raf = $row[5];   ?></td>
        <td align="center" id="texte-bleu12n" bgcolor="<?php echo $celcolor; ?>"><font color="<?php echo $colora; ?>" ><div align="right"><?php echo $pour_avanc."%";  ?></div></font></td>
        <td align="right" id="texte-bleu12n"><?php if((!empty($ch_prev)) && ($raf!=0)) { $prev = ($row[5]/$row[6]); ?><div align="right"> <?php echo $pour_prev."%"; ?> </div><?php } else { echo "0%"; } ?> </td>
	  </tr>
	 
	  
	  <?php } }?> <tr bgcolor="<?php echo $bg; ?>" valign="top" align="left" id="texte-bleu12n">
	    <td id="texte-bleu12n" nowrap="nowrap" >&nbsp;</td>
	    <td align="right" class="style2"><?php echo $tab_parametres['total'];?> : </td>
	    <td align="center" id="texte-bleu12n">&nbsp;</td>
	    <td align="center" id="texte-bleu12n">
		<?php /*$v=0; $vv=0; $vari = count($tab_dd); 
		$vv=$tab_dd[0];
		for($v==1; $v<($vari-1); $v++) {
		 echo $tab_dd[$v]."<br>";
		if ($vv>$tab_dd[$v])
		{ $vv=$tab_dd[$v]; }
		//if($tab_dd[$v]<$tab_dd[$v+1]) {
		//$vv=$tab_dd[$v]; $tab_dd[$v]=$tab_dd[$v+1]; $tab_dd[$v+1] = $vv; 		}
		
		//echo $tab_dd[$vari-1]."<br>";
		}
	 echo $vv;*/
		?></td>
	    <td align="center" id="texte-bleu12n" bgcolor="<?php echo $celcolord; ?>">&nbsp;</td>
	    <td align="right" id="texte-bleu12n"><b><?php $v=0; $ci=0;
		$vari = count($tab_ci);  
		for($v==0; $v<($vari); $v++) {
		 $ci+= $tab_ci[$v];
		 //echo $v1."<br>";
		//echo $v."**".$tab_ci[$v]."<br>";
		}
	 echo $ci;
		?></b></td>
	    <td align="right" id="texte-bleu12n"><b><?php $v=0; $cc=0;
		$vari = count($tab_cc);  
		for($v==0; $v<($vari); $v++) {
		 $cc+= $tab_cc[$v];
		 //echo $v1."<br>";
		//echo $v."**".$tab_ci[$v]."<br>";
		}
	 echo $cc;
		?></b></td>
	    <td align="right" id="texte-bleu12n"><b><?php $v=0; $raf=0;
		$vari = count($tab_raf);  
		for($v==0; $v<($vari); $v++) {
		 $raf+= $tab_raf[$v];
		 //echo $v1."<br>";
		//echo $v."**".$tab_ci[$v]."<br>";
		}
	 echo $raf;
		?></b></td>
	    <td align="right" id="texte-bleu12n" ><b><?php $pavan = ($cc/$ci)*100; echo format_number($pavan)."%";  ?></b></td>
	    <td align="right" id="texte-bleu12n"><b><?php $pprev = ($raf/$ci)*100; echo format_number($pprev)."%";  ?></b></td>
	    </tr> </TBODY></TABLE> </TD></TR></TBODY></TABLE><?php } ?></td>
                      </tr>
                    </table>
                  </form>

<br />
<br />
<br />
</td></tr>
</table>

</body>
</html>

