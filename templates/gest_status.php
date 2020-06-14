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
<!------------------------ Dridi Med Talel Le 18/04/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['gest_stat'];?></title>
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
			</SCRIPT>
			<STYLE type="text/css">
<!--
SELECT, INPUT, TABLE { font-family : Verdana; font-size : 11px; color : #000033; }
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
          <TD align="center">
            <TABLE class="jiraform  maxWidth">
              <TBODY>
              <TR>
                <TD valign="top" class="jiraformheader"><H3 class=formtitle><?php echo $tab_parametres['gest_stat'];?></H3></TD></TR>
              <TR>
                <TD valign="top" class=jiraformbody><?php echo $tab_parametres['page_gest_stat'];?></TD>
              </TR></TBODY></TABLE>
           <?php // Affichage de l'erreur si le champs existe d�ja
		   if (isset ($_GET['operation'])) {
		   echo "<div align = 'right'><a href='javascript:history.back()' class='liens-bleu10b'>Retour</a>&nbsp;&nbsp;&nbsp;&nbsp;</div>";
@$idf=$_GET['idf'];
switch ($_GET['operation']) {
    case "ajouter";
        ?>
           <form id="form1" name="form1" method="post" action="status.php">
<table class="jiraform" width="60%" align="center">
              <tr class='jiraformheader'>
                <td colspan='2' align='center' valign='top' class="style2" ><?php echo $tab_parametres['ajout'];?></td>
                </tr>
			               <tr>
                <td valign='top'><?php echo $tab_parametres['lib'];?></td>
                <td valign='top'><?php $query2="SELECT * from issuestatus"; // Ajout de Status
$result2 = mysql_query($query2); ?>
<select name="id_status" class="input-liste-courte">
      <option value="nothing">Status</option>
      <?php while($row2 = mysql_fetch_array($result2)){ ?>
<OPTION value="<?php echo $row2['ID']; ?>" ><?php echo $row2['pname']; ?></OPTION>
 <?php } ?></option>
</select></td>
              </tr>
            </table>
<span style="width:80%; text-align:center">
<input name="idf3" type="hidden" value="<?php echo $idf; ?>" />
<input name="requete" type="hidden" value="ajout" /><br/>
<input name="Editer" type="submit" value="<?php echo $tab_parametres['ajout'];?> >>" />
</span>
			</form><?php break;
    case "editer";
	?>
		<form id="form2" name="form2" method="post" action="status.php">
	<?php $query="SELECT * FROM status_ferme where ID=$idf"; // Edition de Status
$sql=mysql_query($query);
$idf =  mysql_result($sql, 0, 'ID');
$libelle = mysql_result($sql, 0, 'libelle');
       ?><table class="jiraform" width="60%" align="center">
              <tr class='jiraformheader'>
                <td colspan='2' align='center' valign='top' class="style2" ><?php echo $tab_parametres['edit'];?></td>
                </tr>
			               <tr>
                <td valign='top'><?php echo $tab_parametres['lib'];?></td>
                <td valign='top'><input type='text' name='lib' value="<?php echo $libelle; ?>" /></td>
              </tr>
              
            </table>
            <span style="width:80%; text-align:center">
            <input name="idf" type="hidden" value="<?php echo $idf; ?>" />
            <input name="requete" type="hidden" value="edition" /><br/>
            <input name="Editer" type="submit" value="<?php echo $tab_parametres['edit'];?> >>" id="searchButton" />
            </span>
		</form>
              <?php break;
    case "supprimer";
	?>
	<form id="form3" name="form3" method="post" action="status.php">
	<?php $query="SELECT * FROM status_ferme where ID=$idf"; // Suoppression de Status
$sql=mysql_query($query);
$libelle = mysql_result($sql, 0, 'libelle');
        ?><table class="jiraform" width="60%" align="center">
              <tr class='jiraformheader'>
                <td colspan='2' align='center' valign='top' class="style2" ><?php echo $tab_parametres['supp_stat'];?></td>
                </tr>
			               <tr>
                <td valign='top'><?php echo $tab_parametres['lib'];?></td>
                <td valign='top'><input type='text' name='lib' value="<?php echo $libelle; ?>" /></td>
              </tr>
              
            </table>
	<span style="width:80%; text-align:center">
	<input name="idf" type="hidden" value="<?php echo $idf; ?>" />
      <input name="requete" type="hidden" value="suppression" /><br/>
      <input name="Editer" type="submit" value="Supprimer >>" />
	</span>
	</form>
            <?php break;
    
}}
else 
{
?>
<br/>
<br/>
<br/>
            <table width="60%" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#bbbbbb">
              <tr class="jiraformheader">
                <td align="center" valign="top" class="style2" ><?php echo $tab_parametres['lib'];?></td>
                <td align="center" valign="top"  class="style2"><?php echo $tab_parametres['edit'];?></td>
                <td align="center" valign="top" class="style2" ><?php echo $tab_parametres['supprimer'];?></td>
              </tr>
			              <?php $query="SELECT * FROM status_ferme "; // Affichge de la liste des Status
			$result1 = mysql_query($query);
			while ($row1 = mysql_fetch_array($result1))
			{
			
 ?>
              <tr bgcolor="#FFFFFF">
                <td valign="top"><?php echo($row1[1]);?></td>
                <td align="center" valign="top">
                                   <a href="gest_status.php?operation=editer&idf=<?php echo($row1[0]);?>" class="liens-bleu10b"><img src="../images/modifier.gif"  border="0" alt="Modifier" title="Modifier" /></a></td>
                <td align="center" valign="top"> <a href="gest_status.php?operation=supprimer&idf=<?php echo($row1[0]);?>" class="liens-bleu10b"><img src="../images/supprimer.gif"  border="0" /></a></td>
              </tr><?php } ?>
            </table>
            <br />
                <br />
             
              <table width="0%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td> <a href="gest_status.php?operation=ajouter" class="liens-bleu10b"><img src="../images/valider.gif" width="20" height="20"  border="0" /></a></td>
                </tr>
              </table>
              <br />
            
			  <?php } ?>
			
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
<?php include("bottom.php"); ?>  
 
    
  </body>
</html>

