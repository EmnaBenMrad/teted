 <link href="../style/style.css" rel="stylesheet" type="text/css" />  
  <?php include("connexion.php");
$requete = $_POST['requete'];
@$id = $_POST['idf'];
if(isset($_POST['id_status'])) { $id_status = $_POST['id_status']; }
if(isset($_POST['lib'])) { 
 $libelle = $_POST['lib']; 
$libelle = str_replace("'", "\'", $libelle); // fonction qui replace un caractère par un autre
$libelle = str_replace("é", "&eacute;", $libelle);
$libelle = str_replace("è", "&egrave;", $libelle);
$libelle = str_replace("à", "&agrave;", $libelle);
$libelle = str_replace("â", "&acirc;", $libelle);
$libelle = str_replace("î", "&icirc;", $libelle); }
// $lg est le parametre de langue et c est egal 1 pour le français
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de paramètrage***/

?>
<?php switch ($requete) {
case "edition":
$sql = "update status_ferme  SET libelle = '$libelle' where ID = '$id'";
$query = mysql_query($sql);
break;
    case "suppression":
$sql = "DELETE FROM  status_ferme  where ID = '$id'";
$query = mysql_query($sql);
break;
    case "ajout":
$verif = "Select * From status_ferme  where ID=".$id_status;
$exe = mysql_query($verif);
$nb = mysql_num_rows($exe);
//echo $nb;
if ($nb>0){?>
<title>V&eacute;rifier</title><br/>
<br/>
<br/>
<table width='80%' align='center'>
              <tr class='jiraformheader'>
                <td align='center' valign='top' ><span class="txte-rouge11b"><?php echo $tab_parametres['verif_val_existe'];?><br />
                </span>
                <div align = 'center'><a href='javascript:history.back()' class="liens-bleu10b"><?php echo $tab_parametres['retour'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;</div> </td>
  </tr>
            </table>

<?php die();
 }
 $ferme = mysql_query("SELECT pname FROM issuestatus WHERE ID=".$id_status."");	
 $libelle = mysql_result($ferme, 0, 'pname');
$sql = "INSERT into status_ferme  values (".$id_status.",'".$libelle."')";
$query = mysql_query($sql);

break;
}
?>
<script language="javascript">
document.location.href='gest_status.php';
</script>