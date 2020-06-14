<link href="../style/style.css" rel="stylesheet" type="text/css" />  
<?php include("connexion.php");
include("fonctions.php");
$requete = $_POST['requete'];
@$champ = $_POST['champs'];
//@$ancien_champ = $_POST['ancien_champ'];
@$val_champ = $_POST['val_champ'];
//@$ancien_val_champs = $_POST['ancien_val_champs'];
/*$query = mysql_query("select $ancien_champ from parametres");
$val_champ = mysql_result($query, 0, $ancien_champ);
$type  = mysql_field_type($query, 0);
//$name  = mysql_field_name($query, 0);
$len   = mysql_field_len($query, 0);*/

?>
<?php 
switch ($requete) {
case "edition":
//$sql= "ALTER TABLE parametres CHANGE $ancien_champ $champ $type ( $len ) ";
//$query=mysql_query($sql);
$sql="UPDATE `parametres` SET `$champ` = '$val_champ' WHERE `parametres`.`id` =1 LIMIT 1";
$query=mysql_query($sql);
break;
case "ajout":
$sql="ALTER TABLE parametres ADD COLUMN $champ varchar(255);";
$query=mysql_query($sql);
$sql1="UPDATE `parametres` SET `$champ` = '$val_champ' WHERE `parametres`.`id` =1 LIMIT 1";
$query1=mysql_query($sql1);
break;
case "suppression":
$sql="ALTER TABLE `parametres` DROP `$champ` ";
$query=mysql_query($sql);
}
?>
<script language="javascript">
document.location.href='gest_parametres.php';
</script>