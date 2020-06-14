<link href="../style/style.css" rel="stylesheet" type="text/css" />  
<?php include("connexion.php");
include("fonctions.php");
$requete = $_POST['requete'];
@$id = $_POST['idf'];
$date = $_POST['Date'];
$cherch = strpos($date,"-");
if (!is_integer($cherch)) {
$date = format_date($date);
  }
$libelle = $_POST['lib'];
$libelle = str_replace("'", "\'", $libelle); // fonction qui replace un caractère par un autre
$libelle = str_replace("é", "&eacute;", $libelle);
$libelle = str_replace("è", "&egrave;", $libelle);
$libelle = str_replace("à", "&agrave;", $libelle);
$libelle = str_replace("â", "&acirc;", $libelle);
$libelle = str_replace("î", "&icirc;", $libelle);


?>
<?php switch ($requete) {
case "edition":
$verif = "Select * From jour_ferie where (libelle='$libelle') and (ID != $id)";
$exe = mysql_query($verif);
$nb = mysql_num_rows($exe);
//echo $nb;
if ($nb>0){
?>


<table width='80%' align='center'>
              <tr class='jiraformheader'>
                <td align='center' valign='top' ><span class="txte-rouge11b"><?php echo $tab_parametres['verif_val_existe'];?><br />
                </span>
                <div align = 'center'><a href='javascript:history.back()' class="liens-bleu10b"><?php echo $tab_parametres['retour'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;</div> </td>
  </tr>
            </table>

 <?php die();
 }	
$sql = "update jour_ferie SET Date = '$date', libelle = '$libelle' where ID = '$id'";
$query = mysql_query($sql);
break;
    case "suppression":
$sql = "DELETE FROM  jour_ferie where ID = '$id'";
$query = mysql_query($sql);
break;
    case "ajout":
$verif = "Select * From jour_ferie where libelle='$libelle'";
$exe = mysql_query($verif);
$nb = mysql_num_rows($exe);
//echo $nb;
if ($nb>0){
?>
<table width='80%' align='center'>
              <tr class='jiraformheader'>
                <td align='center' valign='top' ><span class="txte-rouge11b"><?php echo $tab_parametres['verif_val_existe'];?><br />
                </span>
                <div align = 'center'><a href='javascript:history.back()' class="liens-bleu10b"><?php echo $tab_parametres['retour'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;</div> </td>
  </tr>
            </table>

<?php die();
 }	
$sql = "INSERT into jour_ferie values ('','$date','$libelle')";
$query = mysql_query($sql);

break;
}
?>
<script language="javascript">
document.location.href='gest_jour_fer.php';
</script>