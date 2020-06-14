<?php
include_once("Mail.php");
include_once("Mail/mime.php"); // La fonction Mime de php est utilisée pour envoyer des mails au format html

include("connexion.php");
include("fonctions.php");
$y = date('Y');
$week = date('W') ;
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
$headers["Subject"] = "Imputations Time&Decision incomplètes";
$params["host"]    = 'smtp.topnet.tn';
$params["port"] = "25";
/*******************************************   Requetage et boucle pour l'envoie de mail   *********************/
/********************************       MEDINI Mounira Le 06-03-2008 15h00      ********************************/

$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
$user = "SELECT DISTINCT (user.ID), user.username
		 FROM userbase user, propertyentry pe, propertystring ps
		 WHERE pe.ENTITY_NAME = 'OSUser'
		 AND pe.entity_ID = user.ID
		 AND pe.Property_key = 'jira.meta.Actif'
		 AND pe.ID = ps.ID
		 AND ps.propertyvalue = 'Oui'
		 ";
$req_user = mysql_query($user) or die (mysql_error);
$nb = mysql_num_rows($req_user);

while ($tab = mysql_fetch_array($req_user))
{
$som = som_jour_imputation($tab[0], $yesterday);
if(empty($som)) { $som = 0; }
	if($som < 1)
	{
		$nom =  nom_prenom_user($tab[0]);
		$recipients = email($tab[0]);
		$message = '
		<EM>Bonjour '.$nom.'</EM><BR>
		<EM><P>Vos imputations de la journ&eacute;e pr&eacute;c&eacute;dente sont incompletes Veuillez les compl&eacute;ter</P><P> Merci </P> <P>http://tn.businessdecision.com/tetd</P> Ce message est g&eacute;n&eacute;r&eacute; automatiquement veuillez ne pas r&eacute;pondre directement &agrave; l\'exp&eacute;diteur</P></EM>';
		$your_html_message = $entete.$message.$bottom;
		$crlf = "\r\n";
		$mime = new Mail_mime($crlf);
		$mime->setHTMLBody($your_html_message);
		echo $message;
		@$message = $mime->get();
		@$headers = $mime->headers($headers);
		
		$mail_message =& Mail::factory('smtp', $params);
		$mail_message->send ($recipients, $headers, $message);
	
	}
}
?> 


