
<?php
include("connexion.php");
include("fonctions.php");

#Select de tous les taches dans la table imputations
$imputation = "SELECT DISTINCT (issue), round( sum( imputation ) , 2 ) AS somme
			   FROM imputation  
               GROUP BY issue";
$query_imp  = mysql_query($imputation) ; 
while ($row = mysql_fetch_row($query_imp))
{
$issue = $row[0];
$chge_cons = $row[1];
//echo "<br>";
// Selection du Dernier RAF pour chaque tache
$vraf = select_dernier_raf($issue,0);
echo "<br>Le RAF = ".$vraf;
echo "<br>La charge consomm�e = ".$chge_cons;
echo "<br>";

// Mise � jour du customfield : Le RAF
$requete_jira="UPDATE 
			   customfieldvalue 
			   SET numbervalue='".$vraf."' 
			   WHERE issue=".$issue."
			   AND customfield=(SELECT ID FROM customfield WHERE cfname ='RAF')";
$req_jira=mysql_query($requete_jira);

// Mise � jour du customfield : La charge Consomm�e	
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$chge_cons."'
			 WHERE issue = ".$issue." 
			 AND customfield = (SELECT ID FROM customfield WHERE cfname = 'Consomm�e')");
if(empty($chge_cons)) { $chge_cons=0; }

# R�cup�rer la charge pr�vue
$sql_prev = "SELECT NUMBERVALUE as NUMBERVALUE2
			 FROM customfieldvalue
			 WHERE customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge pr�vue')
			 AND ISSUE = ".$issue;
$req_prev = mysql_query($sql_prev);
$nb_prev = mysql_num_rows($req_prev);
if($nb_prev>0) {
$res_prev = mysql_fetch_object($req_prev); 
$chge_prev = $res_prev->NUMBERVALUE2;
 }
else { $res_prev = 0; }

if(empty($chge_prev)) { $chge_prev=0; }

$chge_relle = $chge_cons + $vraf;
echo "Charge r�elle = ".$chge_relle;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$chge_relle."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge r�elle'))");
if($chge_relle == 0) { $avanc = 0; }
else { $avanc = ($chge_cons / $chge_relle) * 100; $avanc = format_number($avanc); }
echo "<br>Avancement = ".$avanc;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$avanc."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Avancement (%)'))");
			 
if($chge_relle == 0) { $prev = 0; }
else { $prev     = ($vraf / $chge_relle) * 100; $prev = format_number($prev); }
echo "<br>La charge pr�vue  = ".$chge_prev;
echo "<br>Pr�visionnel = ".$prev;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$prev."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Pr�visionnel (%)'))");
			 
$vtion_chge = $chge_prev - $chge_relle;
echo "<br>Variation de charge".$vtion_chge;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion_chge."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation de charge'))");

if($chge_prev == 0) { $vtion = 0; }
else { $vtion = ($vtion_chge / $chge_prev) * 100; $vtion = format_number($vtion); }
echo "<br>Variation de charge %".$vtion;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation (%)'
))");
echo "<hr>";
}

?>

