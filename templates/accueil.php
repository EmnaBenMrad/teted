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
include ("connexion.php");
include("fonctions.php");
$id = $_SESSION['id'];
$user = $_SESSION['id'];
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['Accueil'];?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<link href="../style/global.css" rel="stylesheet" type="text/css" />
<link href="../style/theme.css" rel="stylesheet" type="text/css" />
<link href="../style/global-static.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
    /*if (! isset($_SESSION['tableau']) ) {
		
        // Création de tableaux associatifs
        $tab1 = array('Nom' => 'jira-users');
        $tab2 = array('Nom' => 'DGI-manager');
        $tab3 = array('Nom' => 'jira-users');
        // Création d'un tableau de tableaux
        $tab4 = array( $tab1, $tab2);
        // Ajout à la fin d'un tableau
        $tab4[] = $tab3;
        $_SESSION['tableau'] = $tab4;
		}*/
?>   
<?php
$i = 0;
$j=0;  
      foreach ($_SESSION['groupetab'] as $ligne) {    //parcourir le tableau qui contien les variables de session
		
		  $list_group = $ligne['Nom']; 
  $group  = 'users'; 
  $position = strpos($list_group, $group); 

  $dev  = 'developpers'; 
  $posdev = strpos($list_group, $dev); 
if ($posdev > 1) { 
      $developper = "true";  
      } 

  if ($position != false) { 
      $j++;  
      } 
		//echo  $ligne['Nom'];
		$i++;
		  }  
		     ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
<tr valign="middle"><td>
<table cellpadding=0 cellspacing="0" class="centred borderedBoxBlack" width=400>
    <tr cellpadding=0 cellspacing=0 border=0 width=400 bgcolor=#ffffff>
        <td><table>
            <td valign="bottom" width=200><a href="/secure/"><img src="../images/logo_bd_tn.png" width="400" height="37" border=0 alt="Business&Decision Tunisie"></a></td>
            <td valign="bottom" width=200 align="right">
                <a href="http://www.atlassian.com/software/jira/docs/v3.4/index.html?clicked=jirahelp" target="_jirahelp"></a>



&nbsp;            </td>
        </table></td>
    </tr>
    <tr cellpadding=0 cellspacing=0 border=0 width=400 height=5 bgcolor=#cc0000>
      	<td><img src="/images/border/spacer.gif" width=5 height=5 border=0></td>
    </tr>

    <tr><td>
       	<table cellpadding=6 cellspacing=0 border=0 width=421   background="images/baground.gif">
<tr>
    <td width="409" align="right" height="15" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: <?php echo nom_prenom_user($id); ?>&nbsp; &nbsp;<A 
            title="Se déconnecter et annuler léventuelle authentification automatique." 
            href="suivi_imputation.php?logout=logout" class="liens-bleu10n" style="padding-bottom:4px;"><?php echo $tab_parametres['deconnecter'];?></A>&nbsp;  </td>
</tr>
<tr>
    <td height="260"><table height="180" width="100%" border="0" cellpadding="0" cellspacing="0">
                    
                    <tr valign="top">
                      <td width="44%" align="right" class="style2"><?php if (($i!=$j) && ($developper = "true"))
						{
 ?><br />
                        <br />
                        <br />
                          <a href="suivi_imputation.php" class="liens-bleu10b"><?php echo $tab_parametres['saisie_temps'];?><br /></a><a href="suivi_imputation.php"><img src="../images/reveil.gif" width="50" height="51" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php } ?></td>
                      <td width="6%" >&nbsp;</td>
                      <td width="50%" ><br />
                        <br />
                        <br />
                        <br />                        <a href="tab_bord.php" class="liens-bleu10b">                        <?php echo $tab_parametres['tableau_bord'];?></a> <br />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tab_bord.php"><img src="../images/tab_bord.gif" width="50" height="51" border="0" /></a></td>
                    </tr>
                    
                    
                </table>
    
    </td>
</tr>
</table>
    </td></tr>
</table>

<div class="fullyCentered">
    <span class="small">
	    
		    </font>

	<p>&nbsp;</p>
</div>

</td></tr>

</table>
</body>
</html>
