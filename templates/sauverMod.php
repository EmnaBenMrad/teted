<?php
  //On sort en cas de paramètre manquant ou invalide
  if(!isset($_GET['champ']) or empty($_GET['champ']) or !isset($_GET['valeur']) or (empty($_GET['valeur']) and 
  ($_GET['valeur'] != 0)) or !isset($_GET['echap']) or empty($_GET['echap']) or
  !isset($_GET['id']))
  {
    print "Erreur dans les paramètres fournis";
    exit;
  }
  
  require('connexion.php');
  include('fonctions.php');
  
  //Construction de la requête
  $valeur 	= $_GET['valeur'];
  $id			= $_GET['id'];
  $champ = $_GET['champ'];
  $sql  = "UPDATE `Imputation` SET imputation=";
  $sql2 = "INSERT INTO `Imputation` VALUES ";
  $sel  = "SELECT ID FROM imputation  ";

  //Il faut éventuellement formater la valeur fournie
  if($_GET['echap'] == "true")
  {
  $valeur = mysql_real_escape_string($valeur);
  $sql .= "'".$valeur."'";
  }
  else
    $sql .= $valeur;

  $tab = explode("||", $id);
  if($champ=='imp')
  {
  $proj = $tab[0]; 
  $issue = $tab[1]; 
  $user  = $tab[2]; 
  $date  = $tab[3];
  $raf   = $tab[4];
  $imp   = $tab[5];
  
    if(strlen($valeur)>0)
 { 
  //La somme des imputations
  $som_imp = som_imputation($user, $date, $date);
 
  if(isset($imp)) { $som = ($som_imp + $valeur) - $imp; }
  else { $som = $som_imp + $valeur; }
  $sql .= " WHERE issue=".$issue." and user = ".$user." and Date = '".$date."' ";
  $sql2.= " (NULL , '$proj', '$issue', '$user', '$date', '$valeur', '$raf', '0', '', '$date', '', '')";
  $sel.= " WHERE issue=".$issue." and user = ".$user." and Date = '".$date."'";
  $query = mysql_query($sel) or die("Erreur BDD : " . mysql_error());
  if(mysql_num_rows($query)!=0)
  {
  	if($som>1)
	{
	die('Le total de la saisie par jour est incorrect');
	}
	else 
	{
	 mysql_query($sql) or die("Erreur BDD  : " . mysql_error());
	 }
  }
  else 
  {
  mysql_query($sql2) or die("Erreur BDD  : " . mysql_error());
  }
   
  //Exécution de la requête
  }
  else 
  {
  $sql = "DELETE FROM imputation WHERE issue=".$issue." and user = ".$user." and Date = '".$date."'";
  mysql_query($sql);
  }
  
  }
  else 
  {
 
  $date1 = $tab[0];
  $date2 = $tab[1];
  $issue = $tab[2];
  $collab = $tab[3];
  //echo $valeur;
  if(strlen($valeur)==0) $valeur=0;
  $sql = "UPDATE `Imputation` 
  		  SET RAF=$valeur 
		  WHERE issue=".$issue." 
		     AND user = ".$collab." 
			 AND Date BETWEEN '".$date1."' AND '".$date2."'
 ";
	mysql_query($sql) or die("Erreur BDD : " . mysql_error());
  
  }
  require('./common-bottom.php');

?>