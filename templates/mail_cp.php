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
$headers["From"] = 'Sami BELHADJ<sami.belhadj@businessdecision.com>';
$headers["To"]    = 'do-not-repley@timedecision.com'; 
$headers["Subject"] = "Imputations Time&Decision incomplètes";
$params["host"]    = 'smtp.planet.tn';
$params["port"] = "25";
$nom_pw = "Sami BELHADJ";
$email_pw = "sami.belhadj@businessdecision.com";
/*******************************************   Requetage et boucle pour l'envoie de mail   *********************/
/********************************       MEDINI Mounira Le 06-03-2008 15h00      ********************************/


$sql   = "SELECT m.USER_NAME, u.ID 
		FROM membershipbase m, userbase u 
		WHERE GROUP_NAME LIKE 'BD_CP'
		AND m.USER_NAME = u.username
		"; 
$query = mysql_query($sql);
while ($cp = mysql_fetch_array($query))
{
$cp_name = $cp[0];
# Pour chaque chef de projet voir la liste des projets qu'il les gèrent.
$proj_cp = "SELECT ID, pname 
			FROM project 
			WHERE LEAD = '".$cp_name."'";
$req_cp  = mysql_query($proj_cp);
while ($listp = mysql_fetch_array($req_cp))
{
$pid   = $listp[0];
$pname = $listp[1];
$i=0;
# Pour chaque projets voir les imputations pour ce projets avec validation = 0
$imp_p = "SELECT DISTINCT(user), issue
		  FROM imputation
		  WHERE project = ".$pid."
		  AND validation = 0"; 
$sql_i = mysql_query($imp_p);
$nb    = mysql_num_rows($sql_i);
if($nb>0)
{
$pname = str_replace("'", "\'", $pname);
$nom   = nom_prenom_user($cp[1]);
recipients = email ($cp[1]);

$message = '
<EM>Bonjour '.$nom.'</EM><BR>
<EM><P>Vous avez s&ucirc;rement oublier de valider des imputations sur le projet <b>'.$pname.'</b></P><P> Merci de vérifier SVP!! </P> <P>Administrateur Time&Decision</P> Ce message est g&eacute;n&eacute;r&eacute; automatiquement veuillez ne pas r&eacute;pondre directement &agrave; l\'exp&eacute;diteur</P></EM>';

$message_pw = '
<EM>Bonjour '.$nom_pw.'</EM><BR>
<EM><P>'.$nom.' a s&ucirc;rement oublier de valider des imputations sur le projet <b>'.$pname.'</b></P><P> Merci de vérifier SVP!! </P> <P>Administrateur Time&Decision</P> Ce message est g&eacute;n&eacute;r&eacute; automatiquement veuillez ne pas r&eacute;pondre directement &agrave; l\'exp&eacute;diteur</P></EM>';


$your_html_message = $entete.$message.$bottom."<br>";
$pw_html_message = $entete.$message_pw.$bottom."<hr>";
$crlf = "\r\n";
$mime = new Mail_mime($crlf);

$mime->setHTMLBody($your_html_message);

@$message = $mime->get();
@$headers = $mime->headers($headers);

$mail_message =& Mail::factory('smtp', $params);
$mail_message->send ($recipients, $headers, $message);

}
}
}
?> 



