<?php
if($proj==10064)
{
$sql = "SELECT SUMMARY 
		FROM `jiraissue`, customfieldvalue 
		WHERE jiraissue.ID = customfieldvalue.ISSUE
		AND week(customfieldvalue.DATEVALUE) >= ".$week." AND week(jiraissue.DUEDATE) <= ".$week."
		AND customfieldvalue.CUSTOMFIELD = 10002
		AND REPORTER = '".$login."'
		ORDER BY customfieldvalue.DATEVALUE 
		";
$query = mysqli_query($sql);
while ($tab = mysql_fetch_row($query))
{
echo $tab[0];
}
}


?>