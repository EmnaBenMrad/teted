<?php
	include_once("Mail.php");//inclusion de la classe mail du package pear
	include_once("Mail/mime.php");//inclusion de la classe mime du subpackage mail

	/**Param�tres SMTP et port*/
	$params["host"] = "smtp.orange.tn";//D�finition du serveur smtp
	$params["port"] = "25";//D�finition du port d'envoi
	
	/** Envoi mail*/
	$html 					= "Corps du mail";//Corps du message
	$recipients  			= "mohamed.hassib@businessdecision.com";//youssef.chelly@businessdecision.comDestinataire du mail
	/** Additional headers (Ent�tes du mail)*/
	$headers["From"] 		= "test_tetd@businessdecision.com";//L'adresse d'envoi du mail
	$headers["To"]    		= "ZZZZ@businessdecision.com";//L'adresse de reception du mail
	$headers["Subject"] 	= "Test mail pour youssef from 191_Orange";//Objet du mail

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