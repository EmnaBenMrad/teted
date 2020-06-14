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
//echo $_REQUEST["rapport_activite"];
//echo $bodytag = str_replace("detail_plus.jpg", "spacer.gif", $rapport_activite);

if(isset($_REQUEST["rapport_activite"]))
{
$nom_fichier_excel = "rapport_activite";
if(isset($_REQUEST['proj']) and (!empty($_REQUEST['proj'])))
{
$nom_fichier_excel.= "_".pkey_proj($_REQUEST['proj']);
}
if((isset($_REQUEST['collaborateur'])) and (!empty($_REQUEST['collaborateur'])))
{
$nom_fichier_excel.= "_".login_user($_REQUEST['collaborateur']);
}
if((isset($_REQUEST['annee'])) and (!empty($_REQUEST['annee'])))
{
$nom_fichier_excel.= "_".$_REQUEST['annee'];
}
if((isset($_REQUEST['mois'])) and (!empty($_REQUEST['mois'])))
{
$nom_fichier_excel.= "_".$_REQUEST['mois'];
}
if(isset($_REQUEST['rapport_imputation'])) { $nom_fichier_excel="rapport_imputation_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
if(isset($_REQUEST['rapport_facturation'])) { $nom_fichier_excel="rapport_facturation_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
if(isset($_REQUEST['suivi_hors_projet'])) { $nom_fichier_excel="suivi_hors_projet_".$_REQUEST['type']."_".$_REQUEST['annee']."_".$_REQUEST['mois']; } 
//echo $_REQUEST['mois']; echo $_REQUEST['annee']; echo $_REQUEST['type'];
header('Content-disposition: attachement; filename="'.$nom_fichier_excel.'.xls"');
header("Content-Type: application/x-msexcel");
//header('Pragma: no-cache');
header('Expires: 0');

$rapport_activite = $_REQUEST["rapport_activite"];	
if(isset($_SESSION['exp_liste']) && $_SESSION['exp_liste'] !=""){
$rapport_activite = $_SESSION['exp_liste'];
}
//$rapport_activite = str_replace('$ligne_supp', "&nbsp;&nbsp;", $rapport_activite);	
$rapport_activite = str_replace("div_td", "div_td_rap", $rapport_activite);	
$rapport_activite = str_replace("detail_plus.jpg", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("arbo.gif", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("arbor.gif", "spacer.gif", $rapport_activite);
$rapport_activite = str_replace("detail_moin.jpg", "spacer.gif", $rapport_activite);
//$rapport_activite = htmlentities(utf8_encode($rapport_activite));
$rapport_activite = strtr($rapport_activite,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜ¯àâãäåçèéêëìíîï©£òóôõöùúûü~ÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaceeeeiiiioooooouuuuyyy');
//$rapport_activite = html_entity_decode($rapport_activite);
$rapport_activite = str_replace("&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le&nbsp;&nbsp;:&nbsp;&nbsp;", $rapport_activite);
?>
<?php include "../style/style_excel.html"; ?>
<table width="100%" border="0">
<TR valign="bottom" bgcolor="#FFFFFF">
          <TD vAlign="top" nowrap="nowrap" width="400"><A 
            href="suivi_imputation.php"><IMG class=logo 
            height=37 alt="Business&amp;Decision Tunisie" 
            src="<?php echo $img_path; ?>logo_bd_tn.png" 
            width=400 border="0"></A></TD>
          <TD vAlign="bottom" nowrap="nowrap" align="right" width="250" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: &nbsp;&nbsp;<?php echo nom_prenom_user($id); ?>&nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; </TD>
  </TR>

</table>
<br />
<?php
//echo $_REQUEST["ligne_supp"];
echo $rapport_activite;
}
 ?>
</body></html>