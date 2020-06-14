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
/**fin requete de paramètrage***/
?>
<!------------------------ Dridi Talel le 02/04/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Analyse croisée</title>
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
<!---------------- javascript pour l'action sur la liste déroulante -----------------//-->
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
			</SCRIPT>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
  <tr>
    <td><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR valign="bottom">
          <TD vAlign=top noWrap width="5%"><A 
            href="http://apollon:8080/secure/Dashboard.jspa"><IMG class=logo 
            height=37 alt="Business&amp;Decision Tunisie" 
            src="../images/logo_bd_tn.png" 
            width=400 border=0></A></TD>
          <TD vAlign=bottom noWrap width="65%">&nbsp;</TD>
          <TD vAlign=bottom noWrap align=right width="30%" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: &nbsp;&nbsp;<?php echo nom_prenom_user($id); ?>&nbsp;&nbsp;&nbsp;<A 
            title="Se déconnecter et annuler léventuelle authentification automatique." 
            href="suivi_imputation.php?logout=logout" class="liens-bleu10n"><?php echo $tab_parametres['deconnecter'];?></A> &nbsp; &nbsp; </TD>
        </TR></TBODY></TABLE></td>
  </tr>
   <tr bgcolor="#CC0000" height="10">
    <td><img src="../images/spacer.gif" height="10" /></td>
  </tr> 
   <tr >
    <td>&nbsp;</td>
  </tr> <tr>
    <td><br/>
	<TABLE cellSpacing=0 cellPadding=1 width="100%" align=center 
            bgColor=#bbbbbb border=0>
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing=0 cellPadding=4 width="100%" 
                  bgColor=#ffffff border=0>
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor=#f0f0f0>
                        <TABLE cellSpacing=0 cellPadding=0 width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor=#f0f0f0 align="left"><H3 class=formtitle><?php echo $tab_parametres['analyse_croise'];?></H3>                              </TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>
	
	<br/>
	<?php print_r($_POST);?> <form name="form1" method="post">
      <TABLE width="300" border=0 align="center" cellPadding=0 cellSpacing=0  
            bgColor=#bbbbbb>
			
              <TBODY>
              <TR>
                <TD>
                  <TABLE align="right" cellSpacing="1" cellPadding="4" width="300" 
                  bgColor="#bbbbbb" border="0">
                    <TBODY bgColor="#f0f0f0">
                   <tr valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
         <td width="100"  align="center" valign="middle">Axe X</td>
                   <td width="100"  align="center"><br /><select name="axex" class="input-annee">
<option value="Projet" <?php if (@$_POST['axex']=='Projet') {echo 'selected';}?>>Projet</option>
<option value="Tache" <?php if (@$_POST['axex']=='Tache') {echo 'selected';}?>>Tache</option>
<option value="Collaborateur" <?php if (@$_POST['axex']=='Collaborateur') {echo 'selected';}?>>Collaborateur</option>
<option value="Priorite" <?php if (@$_POST['axex']=='Priorite') {echo 'selected';}?>>Prioritè</option>
<option value="Type" <?php if (@$_POST['axex']=='Type') {echo 'selected';}?> >Type</option>
<option value="Status" <?php if (@$_POST['axex']=='Status') {echo 'selected';}?>>Status</option>
                    </select><br /><br />
			
					</td>
                   </tr>
                   <tr valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
                     <td width="100" align="center" valign="middle">Axe Y </td>
                     <td width="100"  align="center"><br /><select name="axey" class="input-annee">
<option value="Projet" <?php if (@$_POST['axey']=='Projet') {echo 'selected';}?>>Projet</option>
<option value="Tache" <?php if (@$_POST['axey']=='Tache') {echo 'selected';}?>>Tache</option>
<option value="Collaborateur" <?php if (@$_POST['axey']=='Collaborateur') {echo 'selected';}?>>Collaborateur</option>
<option value="Priorite" <?php if (@$_POST['axey']=='Priorite') {echo 'selected';}?>>Prioritè</option>
<option value="Type" <?php if (@$_POST['axey']=='Type') {echo 'selected';}?> >Type</option>
<option value="Status" <?php if (@$_POST['axey']=='Status') {echo 'selected';}?>>Status</option>
                    </select><br /><br />					
<?php
if (isset ($_POST['axex'])) {
$sqlx="SELECT * FROM ";
switch ($_POST['axex']) {
    case "Projet":
        $sqlx.="project";
		break;
    case "Collaborateur":
        $sqlx.="userbase";
		break;
    case "Tache":
        $sqlx.="jiraissue";
		break;
    case "Priorite":
        $sqlx.="priority";
		break;
    case "Type":
        $sqlx.="issuetype";
		break;
    case "Status":
        $sqlx.="issuestatus";
		break;
}}
if (isset ($_POST['axey'])) {
$sqly="SELECT * FROM ";
switch ($_POST['axey']) {
    case "Projet":
        $sqly.="project";
		break;
    case "Collaborateur":
        $sqly.="userbase";
		break;
    case "Tache":
        $sqly.="jiraissue";
		break;
    case "Priorite":
        $sqly.="priority";
		break;
    case "Type":
        $sqly.="issuetype";
		break;
    case "Status":
        $sqly.="issuestatus";
		break;
}}

?>
				
		</td>
                     </tr>
                   <tr  valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
                     <td width="100"   align="center" valign="middle">Mesure</td>
                     <td width="100"  align="center"><br />
                       <select    name="mesure" class="input-annee">
              <option >Consommé</option>
<option value="RAF" <?php if (@$_POST['mesure']=='RAF') {echo 'selected';}?> >RAF</option>
<option value="Prevu" <?php if (@$_POST['mesure']=='Prevu') {echo 'selected';}?> >Prévu</option>
<option value="Avancement" <?php if (@$_POST['mesure']=='Avancement') {echo 'selected';}?> >Avancement</option>
<option value="Previsionel" <?php if (@$_POST['mesure']=='Previsionel') {echo 'selected';}?> >Prévisionel</option>
                    </select><br /><br />					
		</td>
                     </tr>
                   <tr  valign="middle" class="txte-bleu10b" bgcolor="#EFEFEF">
                     <td width="100" align="center" valign="middle">Total</td>
                     <td width="100"  align="center"><br /><select name="total" class="input-annee">
             
<option value="oui" <?php if (@$_POST['total']=='oui') {echo 'selected';}?>  >Oui</option>
<option value="non" <?php if (@$_POST['total']=='non') {echo 'selected';}?>  >Non</option>

                    </select><br /><br />
	</td>
                     </tr>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE><br/><br/>
           <TABLE cellSpacing=0 cellPadding=1 width="100%" align=center 
            bgColor=#bbbbbb border=0>
              <TBODY>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing=0 cellPadding=4 width="100%" 
                  bgColor=#ffffff border=0>
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor="#f0f0f0">
                        <TABLE cellSpacing=0 cellPadding=0 width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign="top" width="100%" bgColor="#f0f0f0" align="center"><input type="submit" name="Submit" value="Soummetre"/></TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE></form>
      <br/><br/><?php if ((isset($_POST['axex'])) && (isset($_POST['axey'])) ) {
	   ?>
      <TABLE cellSpacing="0" cellPadding="0" width="100%" align="center" 
            bgColor="#bbbbbb" border="0">
              <TBODY>
              <TR>
                <TD>
                  <TABLE cellSpacing=1 cellPadding=4 width="100%" 
                  bgColor="#bbbbbb" border="0">
                    <TBODY>       <tr valign="middle" align="center" bgColor=#f0f0f0>
					 
        <td width="150" vAlign=middle bgcolor="#f0f0f0" >&nbsp;</td>
		<?php $resultx=mysql_query($sqlx);
	  	  while ($x=mysql_fetch_array($resultx)) {
	  if (($_POST['axex']=='Projet') || ($_POST['axex']=='Collaborateur') ) { $val=$x[1]; }
  	  if (($_POST['axex']=='Type') || ($_POST['axex']=='Status') || ($_POST['axex']=='Priorite') ) { $val=$x[2]; }
      if (($_POST['axex']=='Tache')) { $val=$x[6]; }
	   ?> 
            
			   <td width="250" bgcolor="#FFFFFF" ><?php echo @$val; ?></td> <?php }?>
        </tr>
     
	  
	  
	   <?php $resulty=mysql_query($sqly);
	   while ($x=mysql_fetch_array($resulty)) {
	  if (($_POST['axey']=='Projet') || ($_POST['axey']=='Collaborateur') ) { $val=$x[1]; }
  	  if (($_POST['axey']=='Type') || ($_POST['axey']=='Status') || ($_POST['axey']=='Priorite') ) { $val=$x[2]; }
      if (($_POST['axey']=='Tache')) { $val=$x[6]; }
	   ?>     
	  <tr bgcolor="#FFFFFF" valign="middle" align="left">
        <td vAlign=top><?php echo $val; 
		?></td>
        <td vAlign=top>&nbsp;</td>
      </tr><?php }?>  </TBODY></TABLE> </TD></TR></TBODY></TABLE><?php }?>
      </Td>
        </tr>

    </td>
  </tr>
</body>
</html>