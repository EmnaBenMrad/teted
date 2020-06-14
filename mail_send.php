<?php
	include_once("Mail.php");//inclusion de la classe mail du package pear
	include_once("Mail/mime.php");//inclusion de la classe mime du subpackage mail

	/**Param�tres SMTP et port*/
	$params["host"] = "smtp.topnet.tn";//D�finition du serveur smtp
	$params["port"] = "25";//D�finition du port d'envoi
	
	
	 $html = '<table>
								<tr><td height="40" align="left" valign="middle"><font face="Arial, Helvetica, sans-serif" color="#404040" style="font-size:13px">Bonjour</font></td></tr>
								<tr><td height="40" align="left" valign="middle"><font face="Arial, Helvetica, sans-serif" color="#404040" style="font-size:13px">vos codes d\'acc&egrave;s &agrave; l\'enqu&ecirc;te sont les suivants :</font></td></tr>
								<tr>
								<td height="80">
								<table width="420" border="0" align="center" cellpadding="0" cellspacing="0">
								
								<tr>
								<td width="180" height="20" align="center" valign="bottom" bgcolor="#FFFFFF"><font face="Arial, Helvetica, sans-serif" color="#7c7b7b" style="font-size:12px"> Login :</font></td>
								<td width="1" rowspan="2" bgcolor="#FFFFFF"><img src="images/cartouche_filet.png" width="1" height="35" alt=""></td>
								<td width="184" align="center" valign="bottom" bgcolor="#FFFFFF"><font face="Arial, Helvetica, sans-serif" color="#7c7b7b" style="font-size:12px"> Mot de passe:</font></td>
								</tr>
								<tr>
								<td height="20" align="center" bgcolor="#FFFFFF"><font face="Arial, Helvetica, sans-serif" color="#404240" style="font-size:16px"><strong>test</strong></font></td>
								<td align="center" bgcolor="#FFFFFF"><font face="Arial, Helvetica, sans-serif" color="#404240" style="font-size:16px"><strong>test</strong></font></td>
								</tr>
								
								</table>
								<!-- identifiant/ password -->								
								</td>
								</tr>
								<tr><td height="40" align="left" valign="middle"><font face="Arial, Helvetica, sans-serif" color="#404040" style="font-size:13px">Cet email a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement, merci de ne pas y r&eacute;pondre</font></td></tr>
				            </table>';
	/** Envoi mail*/
	$recipients  			= "youssef.chelly@businessdecision.com";//youssef.chelly@businessdecision.comDestinataire du mail
	/** Additional headers (Ent�tes du mail)*/
	$headers["From"] 		= "youssef.chelly@businessdecision.com";//L'adresse d'envoi du mail
	$headers["To"]    		= "youssef.chelly@businessdecision.com";//L'adresse de reception du mail
	$headers["Subject"] 	= "Test mail pour youssef from Zeus";//Objet du mail

	$crlf = "\n";//Caract�re de retour � la ligne
	$mime = new Mail_mime($crlf);// Instanciation de la classe Mail_mime

	$mime->setHTMLBody($html);//Remplissage du corps
	@$message = $mime->get();//R�cup�ration du corps au format html
	@$headers = $mime->headers($headers);
	$mail_message =& Mail::factory('smtp', $params);//Injection des param�tres du serveur smtp et du port d'envoi
	if($mail_message->send ($recipients, $headers, $message)){
		echo "Envoy� avec succ�s";//Mail envoy�
	}
	else {
		echo "Erreur d'envoi";//Mail non envoy�
	}
?>