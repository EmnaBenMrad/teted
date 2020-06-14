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
$login = $_SESSION['login'];
 include "connexion.php"; 
 include "fonctions.php"; 
 include "function_date.php";
  /*******************Asma OUESLATI 27/03/2007 
code lie aux parametrage des pages 
*****************/
// $lg est le parametre de langue et c est egal 1 pour le francais
$lg=1;
$sql_parametres="SELECT * 
				 FROM parametres 
				 WHERE langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de parametrage***/
$img_path = $tab_parametres['img_path'];
//$ligne_supp = $_REQUEST["ligne_supp"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['rapp_activi'];?></title>
<!--<link href="../style/style_excel.css" rel="stylesheet" type="text/css" />-->
<!--<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>-->
<!--<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>-->
<!--<LINK href="../style/global.css" type=text/css rel=StyleSheet>-->
</head>
<body vLink=#003366 aLink=#cc0000 link=#003366 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">
<?php
//echo $_REQUEST["rapport_validation"];
//echo $bodytag = str_replace("detail_plus.jpg", "spacer.gif", $rapport_validation);

if(isset($_REQUEST["rapport_validation"]))
{
$nom_fichier_excel = "rapport_validation";
if(isset($_REQUEST['proj']) and (!empty($_REQUEST['proj'])))
{
$nom_fichier_excel.= "_".pkey_proj($_REQUEST['proj']);
}
if((isset($_REQUEST['collaborateur'])) and (!empty($_REQUEST['collaborateur'])))
{
$nom_fichier_excel.= "_".login_user($_REQUEST['collaborateur']);
}
header('Content-disposition: attachement; filename="'.$nom_fichier_excel.'.xls"');
header("Content-Type: application/x-msexcel");
header('Pragma: no-cache');
header('Expires: 0');

$rapport_validation = $_REQUEST["rapport_validation"];	

$rapport_validation = ereg_replace("\"", "", $rapport_validation); 

//$rapport_validation = str_replace('$ligne_supp', "&nbsp;&nbsp;", $rapport_validation);	
$rapport_validation = str_replace("div_td", "div_td_rap", $rapport_validation);	
$rapport_validation = str_replace("Non facturable&nbsp;&nbsp;", "", $rapport_validation);
$rapport_validation = str_replace("Facturable&nbsp;&nbsp;", "", $rapport_validation);
$rapport_validation = str_replace("../images/", $img_path, $rapport_validation);
$rapport_validation = str_replace("type=radio", "type=hidden", $rapport_validation);
$rapport_validation = str_replace("<SELECT", "<input type=hidden", $rapport_validation);
$rapport_validation = str_replace("<select", "<input type=hidden", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=0>None</OPTION>", "", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=0 selected=selected>None</OPTION>", "None", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=0 selected>None</OPTION>", "None", $rapport_validation);

$rapport_validation = str_replace("<OPTION value=1>Facturable</OPTION>", "", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=1 selected=selected>Facturable</OPTION>", "Facturable", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=1 selected>Facturable</OPTION>", "Facturable", $rapport_validation);


$rapport_validation = str_replace("<OPTION value=2>Non facturable</OPTION>", "", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=2 selected=selected>Non facturable</OPTION>", "Non facturable", $rapport_validation);
$rapport_validation = str_replace("<OPTION value=2 selected>Non facturable</OPTION>", "Non facturable", $rapport_validation);

$rapport_validation = str_replace("</select>", "&nbsp;", $rapport_validation);
$rapport_validation = str_replace("</SELECT>", "&nbsp;", $rapport_validation);
$rapport_validation = str_replace("nowrap=nowrap", "nowrap=nowrap  style=visibility:hidden", $rapport_validation);
$rapport_validation = str_replace("&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", $rapport_validation);
$rapport_validation = str_replace("<b>Facturable&nbsp;:</b>", "", $rapport_validation);
$rapport_validation = str_replace("<img", "<input type=hidden", $rapport_validation);

?>
<?php include "../style/style_excel.html"; ?>
<table width="100%" border="0">
<TR valign="bottom" bgcolor="#FFFFFF">
          <TD vAlign="top" nowrap="nowrap" width="5%"><A 
            href="suivi_imputation.php"><IMG class=logo 
            height=37 alt="Business&amp;Decision Tunisie" 
            src="<?php echo $img_path; ?>logo_bd_tn.png" 
            width=400 border="0"></A></TD>
          <TD vAlign="bottom" nowrap="nowrap" width="65%">&nbsp;</TD>
          <TD vAlign="bottom" nowrap="nowrap" align="right" width="30%" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: &nbsp;&nbsp;<?php echo nom_prenom_user($id); ?>&nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; </TD>
  </TR>

</table>
<br />
<?php
//echo $_REQUEST["ligne_supp"];
echo $rapport_validation;
}
 ?>
</body></html>