<?php
include_once("Mail.php");
include_once("Mail/mime.php"); // La fonction Mime de php est utilisée pour envoyer des mails au format html

include("connexion.php");
include("fonctions.php");
/***************************** *  Partie entête valable pour tous le monde   ***********************************/
$entete = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"      "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <title>Imputations Time&Decision incomplètes</title>
</head>
<body>';
$bottom = '</body> </html>';
$headers["From"] = 'Neila Benzina<neila.benzina@businessdecision.com>';
$headers["To"]    = 'do-not-repley@timedecision.com'; 
$headers["Subject"] = "Liste des consultants .:. Imputations Time&Decision incomplètes";
$params["host"]    = 'smtp.planet.tn';
$params["port"] = "25";

$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
$manager_bu =  manager_bu('MBU-BIO');
var_dump($manager_bu);
$date = date('d-m-Y', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
$req_user = member_bu('BU-BIO');

$imputation='<table cellspacing="1" cellpadding="4" border="0" bgcolor="#bbbbbb">
				<tbody>
				<tr bgcolor="#efefef" align="center" valign="middle" style="color:#000000;
font-family:Verdana,Arial,Helvetica,sans-serif;
font-size:11px;
font-weight:bold;
height:25px;
">
					<td style="width:180px;">Consultant</td>
					<td style="width:150px;">Somme des imputations</td>
					</tr>';
while ($tab = mysql_fetch_array($req_user))
{

$som = som_jour_imputation($tab[0], $yesterday);

if(empty($som)) { $som = 0; }
if($som < 1)
{
	$nom =  nom_prenom_user($tab[0]);
	//echo $nom."<br>";
	$imputation.="<tr bgcolor='#ffffff' align='left' valign='middle' style='color:#000000;
font-family:Arial,Helvetica,sans-serif;
font-size:11px;
font-size-adjust:none;
font-stretch:normal;
font-style:normal;
font-variant:normal;
font-weight:normal;
line-height:normal;
vertical-align:middle;'><td>".$nom."</td><td>".$som."</td></tr>";
}
}
$imputation.="</tbody></table>";
//print $imputation;
for($i=0; $i<count($manager_bu); $i++){
	$nom =  nom_prenom_user($manager_bu[$i]);
	$recipients = email($manager_bu[$i]);
	$message = '
	<EM>Bonjour '.$nom.'</EM><BR>
	<EM><P>Vous trouvez ci-dessous la liste des consultants qui n\'ont pas finalis&eacute; leurs imputations pour la date du '.$date.'</P></EM>';
	$message.="<br>".$imputation."<br>";
	$your_html_message = $entete.$message.$bottom;
	$crlf = "\r\n";
	$mime = new Mail_mime($crlf);
	$mime->setHTMLBody($your_html_message);
	
	@$message = $mime->get();
	@$headers = $mime->headers($headers);
	
	$mail_message =& Mail::factory('smtp', $params);
	$mail_message->send ($recipients, $headers, $message);

}
?> 
