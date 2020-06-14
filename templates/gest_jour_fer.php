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
<!------------------------ Dridi Med Talel Le 16/04/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['gest_jour_fer'];?></title>
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

 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste d�roulante -----------------//--><!--
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
			
<STYLE type="text/css">
<!--
SELECT, INPUT, TABLE { font-family : Verdana; font-size : 10px; color : #000033; }
.TITRE { font-family : Verdana; font-size : 12px; color : #000033; }
-->
</STYLE>

<body vLink=#003366 aLink=#cc0000 link=#003366 bgColor=#f0f0f0 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">
<?php
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
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
if($list_group=="TD-tdbusers"){ $lien_bord = "actif"; }
if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
}
if($lien_adm=="inactif") {
?>
<script language="javascript">document.location.href="../templates/login.php"</script>
<?php
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
       <?php include("menu-admin.php"); ?>       <BR></TD>
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
                <TD valign="top" class="jiraformheader">
                  <H3 class=formtitle><?php echo $tab_parametres['gest_jour_fer'];?></H3></TD></TR>
              <TR>

                <TD valign="top" class=jiraformbody>
                 <?php echo $tab_parametres['page_gest_fer'];?></TD>
              </TR></TBODY></TABLE>
           <br/>
<br/>
<br/>
            <table width="60%" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#bbbbbb" >
              <tr class="jiraformheader">
                <td align="center" valign="top" class="style2" ><?php echo $tab_parametres['lib'];?></td>
                <td align="center" valign="top" class="style2"><?php echo $tab_parametres['date'];?></td>
                <td align="center" valign="top" class="style2"><?php echo $tab_parametres['edit'];?></td>
                <td align="center" valign="top" class="style2"><?php echo $tab_parametres['supprimer'];?></td>
              </tr>
			              <?php $query="SELECT * FROM jour_ferie "; 
			$result1 = mysql_query($query);
			while ($row1 = mysql_fetch_array($result1))
			{
			
 ?>
              <tr bgcolor="#FFFFFF">
                <td valign="top"><?php echo($row1[2]);?></td>
                <td valign="top"><?php $ddate = $row1[1];  echo format_date2($ddate);?></td>
                <td align="center" valign="top">
                                   <a href="edit_jour_fer.php?operation=editer&idf=<?php echo($row1[0]);?>"  class="liens-bleu10b"><img src="../images/modifier.gif"  border="0" alt="Modifier" title="Modifier" /></a></td>
                <td align="center" valign="top">
                                   <a href="edit_jour_fer.php?operation=supprimer&idf=<?php echo($row1[0]);?>"  class="liens-bleu10b"><img src="../images/supprimer.gif"  border="0" /></a></td>
              </tr><?php } ?>
            </table>
            <br />
<br />

<table width="0%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><a href="edit_jour_fer.php?operation=ajouter" class="liens-bleu10b"><img src="../images/valider.gif" width="20" height="20"  border="0" /></a></td>
  </tr>
</table></TD>
        </TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
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
<?php include("bottom.php"); ?> 
    
  </body>
</html>

