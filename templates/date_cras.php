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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['generer_cras']; ?></title>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<link href="../style/global.css" rel="stylesheet" type="text/css" />
<link href="../style/global-static.css" rel="stylesheet" type="text/css" />
<link href="../style/theme.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="cras.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="149">
  <tr valign="top">
    <td height="76" ><?php include("entete.php"); ?></td>
  </tr>
  <tr>
    <td height="9" valign="top"><?php echo tab_vide(9); ?></td>
  </tr>
  <tr>
    <td height="32" valign="top"><TABLE cellSpacing="0" cellPadding=1 width="100%" align="center" bgColor="#bbbbbb" border="0">
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
							<?php echo $tab_parametres['generer_cras'];?>
							</H3>                              </TD>
                          </TR></TBODY></TABLE></TD>
      </TBODY></TABLE></TD></TR></TBODY></TABLE></td>
  </tr>
  <tr>
    <td height="18"><span class="style2"><?php if($user_cras=="actif") { 
	 echo "&nbsp;&nbsp;".$tab_parametres['collab']."&nbsp;:&nbsp;";
	 $query1="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
 AND userbase.ID  not in (select user from userbasestatus where status =0) 
ORDER BY propertystring . propertyvalue ASC
";
$result1 = mysql_query($query1);
 
?><select name="collab" id="collab" class="input-liste-collab">
<OPTION value="faux"><?php echo $tab_parametres['collab'];?></OPTION>
		  <?php while($row1 = mysql_fetch_array($result1)){ 
		   $nom = nom_prenom_user($row1[0]); $coll = $row1[0]; ?>
<OPTION value="<?php echo $row1[0]; ?>"  ><?php echo $nom; ?></OPTION>
<?php 
} 
 ?></select>
	
	
<?php	} ?><?php echo $tab_parametres['annee'];?>&nbsp;
      :&nbsp;
        <select name="annee" id="annee" class="input-annee">
		  <?php for ($i=2007;$i<=2025;$i++) 
{ $default = date('Y'); ?>
          <option value="<?php echo $i; ?>" <?php if($i==$default) { echo "selected"; } ?>><?php echo $i; ?> </option><?php } ?>
        </select>
      </span><span class="style2">&nbsp;&nbsp;<?php echo $tab_parametres['mois'];?>&nbsp;:&nbsp;
      <select name="mois" id="mois" class="input-annee" style="width:80px;" >
        <option value=nothing><?php echo $tab_parametres['mois'];?></option>
        <?php for ($i=0;$i<12;$i++) 
{ 
?>
        <option value="<?php echo ($i+1); ?>" <?php if ($i== (date('n')-1)) { echo "selected"; } ?>  ><?php echo nom_mois($i); ?></option>
        <?php } ?>
      </select>
      </span>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="Submit" value="G&eacute;n&eacute;rer"  />
     <br /></td>
  </tr>
  <tr>
    <td></td>
  </tr>
</table>
</form><br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />


<?php include("bottom.php"); ?>
</body>
</html>
