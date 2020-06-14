<?php
function update_variable($issue)
{
# Récupérer la valeur de la charge consommée
$sql_cons = "SELECT NUMBERVALUE
			 FROM customfieldvalue
			 WHERE customfield = 10020
			 AND ISSUE = ".$issue;
$req_cons = mysql_query($sql_cons);
$res_cons = mysql_fetch_object($req_cons);
$chge_cons = $res_cons->NUMBERVALUE;
if(empty($chge_cons)) { $chge_cons=0; }


# Récupérer la valeur de du RAF
$sql_raf = "SELECT NUMBERVALUE
			 FROM customfieldvalue
			 WHERE customfield = 10021
			 AND ISSUE = ".$issue;
$req_raf = mysql_query($sql_raf);
$res_raf = mysql_fetch_object($req_raf);
$chge_raf = $res_raf->NUMBERVALUE;
if(empty($chge_raf)) { $chge_raf=0; }

# Récupérer la charge prévue
$sql_prev = "SELECT NUMBERVALUE
			 FROM customfieldvalue
			 WHERE customfield = 10000
			 AND ISSUE = ".$issue;
$req_prev = mysql_query($sql_prev);
$res_prev = mysql_fetch_object($req_prev);
$chge_prev = $res_prev->NUMBERVALUE;
if(empty($chge_prev)) { $chge_prev=0; }

$chge_relle = $chge_cons + $chge_raf;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$chge_relle."' 
			 WHERE (ISSUE = $issue) and (customfield = 10215)");
if($chge_relle == 0) { $avanc = 0; }
else { $avanc     = ($chge_cons / $chge_relle) * 100; }

mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$avanc."' 
			 WHERE (ISSUE = $issue) and (customfield = 10081)");
			 
if($chge_relle == 0) { $prev = 0; }
else { $prev     = ($chge_raf / $chge_relle) * 100; }

mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$prev."' 
			 WHERE (ISSUE = $issue) and (customfield = 10082)");
			 
$vtion_chge = $chge_prev - $chge_relle;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion_chge."' 
			 WHERE (ISSUE = $issue) and (customfield = 10216)");

if($chge_prev == 0) { $vtion = 0; }
else { $vtion = ($vtion_chge / $chge_prev) * 100; }

mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion."' 
			 WHERE (ISSUE = $issue) and (customfield = 10217)");
}
?>