 <link href="../style/style.css" rel="stylesheet" type="text/css" />  
  <?php include("connexion.php");
$requete = $_POST['requete'];
@$id = $_POST['idf'];

$lib = $_POST['lib']; 
if($lib=='nothing') {
?>
<script language="javascript">
document.location.href='gest_project.php?operation=ajouter';
</script>

<?php
die();
 }
// $lg est le parametre de langue et c est egal 1 pour le français
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de paramètrage***/

?>
<?php switch ($requete) {
    case "suppression":
$sql = "DELETE FROM  tetd_project_exclusive  where ID = '$id'";
$query = mysql_query($sql);
break;
    case "ajout":
$verif = "Select * From tetd_project_exclusive  where ID='$lib'";
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

<?php 
 die();
 }
 $project = mysql_query("SELECT pname FROM project WHERE ID = ".$lib."");
 $proj = mysql_result($project,0,'pname');
 $proj = str_replace("'", "\'",$proj);
$sql = "INSERT into  tetd_project_exclusive  values ('$lib','$proj')";
$query = mysql_query($sql);

break;
}
?>
<script language="javascript">
document.location.href='gest_project.php';
</script>