<?php
session_start();// On démarre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
 
session_destroy();  // On détruit la session
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
 include "connexion.php"; // la page de connexion à la base jira
 include "fonctions.php"; 
/*******************Asma OUESLATI 27/03/2007 
code lié aux paramétrage des pages 
*****************/
// $lg est le parametre de langue et c est egal 1 pour le français
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
$i=0; $j=0;
$lien_adm = "inactif";


/**fin requete de paramètrage***/
?>
<!------------------------ MEDINI Mounira Le 06/03/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['admin'];?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>
<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>
<LINK href="../style/global.css" type=text/css rel=StyleSheet>
<LINK href="../style/theme.css" type=text/css rel=StyleSheet>


<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style></head>

 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste déroulante -----------------//--><!--
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
<body vLink=#003366 aLink=#cc0000 link=#003366 bgColor=#f0f0f0 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">
<?php
$val_fer = 0;
$ancien_val_fer = 0;
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
$liste_group = membershipbase($id);
$nb_group = count($liste_group);
$tab_group=array();;
for($i==0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j==0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if($list_group=="TD-tdbusers"){ $lien_bord = "actif"; }
if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
}
if($lien_adm=="inactif") {
?>
<script language="javascript">document.location.href="../templates/login.php"</script>
<?php
}
$msg_sam = "false";
$msg_dim = "false";
$test_week="faux";
$update_week="faux";
$ins_trav_fer="faux";
$up_trav_fer="faux";
if(isset($_POST['week']))
{ $week = $_POST['week']; } 
elseif(isset($_GET['week'])) { $week = $_GET['week']; }
else { $week = date('W'); } 
if(isset($_POST['y']))
{ $y = $_POST['y']; }
elseif(isset($_GET['y'])) { $y = $_GET['y']; }
else { $y = date('Y'); } 
if(($y<2007) || ($y>2025)) { include("erreur.php");
die();
}
if(isset($_POST['mm']))
{ $mm = $_POST['mm']; }
elseif(isset($_GET['mm'])){ $mm = $_GET['mm']; }
else { $mm = date('n'); }
if(($mm<1) || ($mm>12)) { include("erreur.php");
 die();
 }
if(($week<1) || ($week>54)) { include("erreur.php");
die();
}
 $login = $_SESSION['login']; //récupération de la variable de session
//@$semaine = $_GET['semaine']; 
if((isset($_GET['week'])) || (isset($_POST['week'])) )
{
$exe = (datefromweek ($y, $week, '0'));
$exe1 = (datefromweek ($y, $week, '1'));
$exe2 = (datefromweek ($y, $week, '2'));
$exe3 = (datefromweek ($y, $week, '3'));
$exe4 = (datefromweek ($y, $week, '4'));
$exe5 = (datefromweek ($y, $week, '5'));
$exe6 = (datefromweek ($y, $week, '6'));
$date1 = $exe['year']."-".$exe['month']."-".$exe['day'];
$date7 = $exe6['year']."-".$exe6['month']."-".$exe6['day'];
$date1_affich = $exe['day']."-".$exe['month']."-".$exe['year'];
$date2 = $exe6['year']."-".$exe6['month']."-".$exe1['day'];
$date2_affich = $exe6['day']."-".$exe6['month']."-".$exe1['year'];
$var = $exe['year'];
$var = substr($var,2,2);
$d1 = $exe['year']."-".$exe['month']."-".$exe['day'];
$d1_affich = $exe['day']."-".$exe['month']."-".$var;
$d2 = $exe1['year']."-".$exe1['month']."-".$var;
$d2_affich = $exe1['day']."-".$exe1['month']."-".$var;
$d3 = $exe2['year']."-".$exe2['month']."-".$exe2['day'];
$d3_affich = $exe2['day']."-".$exe2['month']."-".$var;
$d4 = $exe3['year']."-".$exe3['month']."-".$exe3['day'];
$d4_affich = $exe3['day']."-".$exe3['month']."-".$var;
$d5 = $exe4['year']."-".$exe4['month']."-".$exe4['day'];
$d5_affich = $exe4['day']."-".$exe4['month']."-".$var;
$d6 = $exe5['year']."-".$exe5['month']."-".$exe5['day'];
$d6_affich = $exe5['day']."-".$exe5['month']."-".$var;
$d7 = $exe6['year']."-".$exe6['month']."-".$exe6['day'];
$d7_affich = $exe6['day']."-".$exe6['month']."-".$var;
//$year = strtr($exe['year'], ":", "/");
}
?>

<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("entete.php"); ?></td>
  </tr>
<TABLE cellSpacing=0 cellPadding=0 width="100%" bgColor=#ffffff border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="170" bgColor="#f0f0f0">
      <?php include("menu-admin.php"); ?>      <BR></TD>
    <TD width=1 bgColor=#bbbbbb><IMG height=1 
      src="/spacer.gif" 
    width=1></TD>
    <TD vAlign=top>
      <TABLE cellSpacing="0" cellPadding="10" width="100%" border="0">
        <TBODY>
        <TR>
          <TD>
            <TABLE class="jiraform  maxWidth">
              <TBODY>
              <TR>
                <TD class="jiraformheader"> <H3 class=formtitle><?php echo $tab_parametres['admin'];?> </H3></TD></TR>
              <TR>
                <TD class=jiraformbody>
                  <P><?php echo $tab_parametres['welcom_admin'];?></P></TD></TR></TBODY></TABLE>
				  <br/><br/><br/><br/>
            </TD>
        </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<DIV class=footer>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=#bbbbbb><IMG height=1 
      src="../images/spacer.gif" 
      width=100 border=0></TD></TR>
  <TR></TR>
  <TR>
    <TD 
    background="Projects - Business&amp;Decision Tunisie_fichiers/border_bottom.gif" 
    height=12><IMG height=1 
      src="../images/spacer.gif" width=1 
      border=0></TD></TR></TBODY></TABLE>
  
 </DIV></table>
 <?php include("bottom.php"); ?>   
  </body>
</html>

