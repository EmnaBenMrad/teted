<?php 
/********Fontion qui calcule la date de début de semaine à partir de la date sysytème *********/
function date_sem ($n, $i, $y) 
{ 
 $prem_jour = mktime(0,0,0,date('m'),date('d')+$i-date('w')+1-$n*7,date('Y'));
 $datefin = date("$y-m-d", $prem_jour);
	return $datefin;
}
function date_sem_affiche ($n, $i, $y) 
{ 
 $prem_jour = mktime(0,0,0,date('m'),date('d')+$i-date('w')+1-$n*7,date('Y'));
 $datefin = date("d-m-$y", $prem_jour);
	return $datefin;
}

/*  w e e k n u m b e r  -------------------------------------- //
weeknumber retourne le nombre de semaine d'une date donnée (>1970, <2030)
// ------------------------------------------------------------ */ 

function weeknumber ($y, $m, $d) {
   $wn = strftime("%W",mktime(0,0,0,$m,$d,$y));
   $wn += 0; # wn might be a string value
   $firstdayofyear = getdate(mktime(0,0,0,1,1,$y));
   if ($firstdayofyear["wday"] != 1)    # if 1/1 is not a Monday, add 1
       $wn += 1;
   return ($wn);
}    # function weeknumber



/*  d a t e f r o m w e e k  ---------------------------------- //
Calcule la date correspondante à partir de weeknumber
// ------------------------------------------------------------ */ 

function datefromweek ($y, $w, $o) {

   $days = ($w - 1) * 7 + $o;

   $firstdayofyear = getdate(mktime(0,0,0,1,1,$y));
   if ($firstdayofyear["wday"] == 0) $firstdayofyear["wday"] += 7; 
# dans getdate, dimanche est 0 au lieu de 7
   $firstmonday = getdate(mktime(0,0,0,1,1-$firstdayofyear["wday"]+1,$y));
   $calcdate = getdate(mktime(0,0,0,$firstmonday["mon"], $firstmonday["mday"]+$days,$firstmonday["year"]));

   $date["year"] = $calcdate["year"];
   $var = $calcdate["mon"];
   $len_mois = strlen ($var);
   if($len_mois==1) 
   {
   $var = "0".$var;
   }
   $date["month"] = $var;
    $var_day = $calcdate["mday"];
	$len_day = strlen ($var_day);
   if($len_day==1) 
   {
   $var_day = "0".$var_day;
   }
   $date["day"] = $var_day;
   return ($date);

}    # function datefromweek 


//fonction de parcours d'un tableau date
function parcours_table ($date)
{
foreach ( $date as $ligne => $valeur ) { 
	$y = $date ['year'];
	$d = $date ['month'];
	$m = $date ['day'];
}
return array($y, $d, $m);
}

//La date du fin d'un mois donné
function fin_mois ($mois, $annee)
{
$date_fin = date("t",mktime(0,0,0,$mois,1,$annee)); // Affiche 31
return ($date_fin);
}

//Le nom du mois
function nom_mois($i)
{
$mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aôut", "Septembre", "Octobre", "Novembre", "Décembre");
return ($mois[$i]);
}
// Update de la table imputation avec les test necessaires
function update_imputation($proj, $sous_taches,$newissue, $user, $date, $jour,$ancien_jour,$raf,$comment)
{
$group = membershipbase ($user); $cond_som=0;
if (in_array ("bd-multi-facturable", $group)) {    $cond_som = 1;  }
if($sous_taches==$newissue)
{
$select = "select round(sum(imputation),2) as som from imputation where Date = '$date' and (user=$user) and (issue != $sous_taches)";
$select1 = mysql_query($select) or die ("erreur somme des imputations");
$som = mysql_fetch_row($select1);

	if (($som[0]!="") and ($som[0]!=0)) 
	{
	$ancien_imp=$som[0]+$jour;
	}
	else 
	{
	$ancien_imp=$jour;
	}
	if(($ancien_imp>1) and ($cond_som==0))
	{
	$somme="faux"; 
	$input = "vrai";
	//echo "impossible";
	} 
	else
	{
	$select2= "select * from imputation where Date = '$date' and (user=$user) and (issue = $sous_taches) and (project = $proj)";
	$sql = mysql_query($select2) or die ("erreur selection requete1");
	$nb=mysql_num_rows($sql);
		while ($t=mysql_fetch_row($sql))
		{
		$id_imp = $t[0];
		$imp = $t[5];
		}
		@$nlle_impu=$jour;
		$nlle_impu;
		if ($nb==0)
		{
		if(($jour != 0) or ($ancien_jour!=0)) {
		$requete1 = "INSERT INTO imputation  
		VALUES('', $proj, $sous_taches, $user, '$date', $jour, '$raf','0','$comment','".date('Y-m-d G:i:s')."','','')";
		$req1 = mysql_query($requete1) or die ("erreur insert requete2");
		//echo "insertion réussite"; 
		}
$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	@$number = $cus[5];
	}
@$nouv_number = ($number)+($jour);
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);

		$somme="vrai"; 
		$input = "faux";
		} 
		else
		{ 
		$requete = "UPDATE imputation SET imputation = '".$nlle_impu."', issue=".$sous_taches.", RAF = '$raf',  commentaire = '$comment', Date_imputation = '".date('Y-m-d G:i:s')."' WHERE ID = ".$id_imp;
		$req = mysql_query($requete) or die ("erreur update requete3");
		$somme="vrai"; 
		$input = "faux";
		//echo "mise à jour réussite";
$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	@$number = $cus[5];
	}
@$ajou = ($jour)-($ancien_jour);
@$nouv_number = ($number)+($ajou);
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);


		}
	}

}
else  // le else ca veux dire qu'on a changé la valeur de la tache
{	
//Selection de la ligne avec l'ancienne sous taches
//echo "Ancienne valeur &nbsp;&nbsp;".$sous_taches."Nouvelle valeur &nbsp;&nbsp;".$newissue;
$ancien = "select * from imputation where Date = '$date' and (user=$user) and (issue = $sous_taches) and (project = $proj)";
$req_ancien = mysql_query($ancien) or die ("erreur");
$nb_ancien=mysql_num_rows($req_ancien);
//Selection de la ligne avec la nouvelle sous taches
$nouv = "select * from imputation where Date = '$date' and (user=$user) and (issue = $newissue) and (project = $proj)";
$req_nouv = mysql_query($nouv) or die ("erreur");
$nb_nouv=mysql_num_rows($req_nouv);
		while ($tab=mysql_fetch_row($req_nouv))
		{
		$nouvid_imp = $tab[0]; 
		$nouv_imp = $tab[5];
		$ajou=($jour)+($nouv_imp);
		}

//Pas de valeur pour une ancienne imputation
if($nb_ancien==0)
{
	if($nb_nouv==0)
	{
			if($jour != 0) {
	 $requete = "INSERT INTO imputation VALUES ('', '$proj', '$newissue', '$user', '$date', '$jour', '$raf','0','$comment','".date('Y-m-d G:i:s')."','','')";
	$req = mysql_query($requete); }
$select_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	@$number = $cus[5];
	}
@$nouv_number = $number+$jour;
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $newissue) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);


	$input="faux";
	$somme="vrai";
	}
	else
	{
		if(($ajou>1) and ($cond_som==0))
		{
		$input="vrai";
		$somme="faux";
		}
		else
		{
		$requete="UPDATE imputation SET issue = $newissue, imputation = '$ajou', RAF ='$raf', commentaire = '$comment', Date_imputation = '".date('Y-m-d G:i:s')."' WHERE ID =$nouvid_imp";
		$req = mysql_query($requete);
$select_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	@$number = $cus[5];
	}
@$nouv_number = $number+$jour;
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $newissue) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);	
	$input="faux";
	$somme="vrai";
		}
	}
}	
else
{
	while ($tab1=mysql_fetch_row($req_ancien))
	{
	$ancienid_imp = $tab1[0]; 
	$ancien_imp = $tab1[5];
	}
	if($nb_nouv==0)
	{
	$requete = "Update imputation set issue=$newissue, imputation = '$jour', RAF = '$raf', commentaire = '$comment', Date_imputation = '".date('Y-m-d G:i:s')."' where ID = $ancienid_imp";
	$req=mysql_query($requete);
	$input="faux";
	$somme="vrai";
$ancien_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($ancien_custom);
while ($a_cus=mysql_fetch_row($req_custom))
	{
	@$ancien_number = $a_cus[5];
	}
@$nouv_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
$reqn_custom = mysql_query($nouv_custom);	
while ($cus=mysql_fetch_row($reqn_custom))
	{
	@$nouv_number = $cus[5];
	}
@$ajou_number = ($nouv_number)+($jour);
@$del_number = ($ancien_number)-($ancien_jour);

$ajou_field = "UPDATE customfieldvalue SET numbervalue = '$ajou_number' where (issue = $newissue) and (customfield = 10020)"; 
$ajou_f=mysql_query($ajou_field);

$del_field = "UPDATE customfieldvalue SET numbervalue = '$del_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$del_f=mysql_query($del_field);
	}
	else
	{
			if(($ajou>1) and ($cond_som==0))
		{
		$input="vrai";
		$somme="faux";
		}
		else
		{
		$requete="UPDATE imputation SET issue = $newissue, imputation = '$ajou', RAF = '$raf', commentaire = '$comment', Date_imputation = '".date('Y-m-d G:i:s')."' WHERE ID =$nouvid_imp";
		$req=mysql_query($requete);
		$delete="Delete from imputation where ID = $ancienid_imp";
		mysql_query("Delete from worklog where ID = $ancienid_imp");
		$req=mysql_query($delete);
$ancien_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($ancien_custom);
while ($a_cus=mysql_fetch_row($req_custom))
	{
	$ancien_number = $a_cus[5];
	}
$nouv_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
$reqn_custom = mysql_query($nouv_custom);	
while ($cus=mysql_fetch_row($reqn_custom))
	{
	$nouv_number = $cus[5];
	}
$ajou_number = ($nouv_number)+($jour);
$del_number = ($ancien_number)-($ancien_jour);

$ajou_field = "UPDATE customfieldvalue SET numbervalue = '$ajou_number' where (issue = $newissue) and (customfield = 10020)"; 
$ajou_f=mysql_query($ajou_field);

$del_field = "UPDATE customfieldvalue SET numbervalue = '$del_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$del_f=mysql_query($del_field);



		$input="faux";
		$somme="vrai";
		}


	}

}
}

$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
$del = mysql_query($delete);
return array('somme'=>$somme,'input'=>$input);
}

/******************* Fonction pour l'insertion des imputations**********************************************/
function insert_imputation($proj, $sous_taches, $user, $date, $jour,$raf, $comment)
{
$group = membershipbase ($user); $cond_som=0;
if (in_array ("bd-multi-facturable", $group)) {    $cond_som = 1;  }
if ($jour!=0)
{
$select = "select round(sum(imputation),2) as som from imputation where Date = '$date' and (user=$user)";
$select1 = mysql_query($select) or die ("erreur somme des imputations");
$som = mysql_fetch_row($select1);

if ($som[0]=="") 
{
$n_imp=$jour;
}else 
{
$n_imp=$som[0]+$jour;
}
if(($n_imp>1) and ($cond_som==0))
{
$somme="faux"; 
$input = "vrai";
} 
else
{
$select2= "select * from imputation where Date = '$date' and (user=$user) and (issue = $sous_taches)";
$sql = mysql_query($select2) or die ("Vérifier la date, le user et la tache");
$nb=mysql_num_rows($sql);
while ($t=mysql_fetch_row($sql))
{
$id_imp = $t[0];
$imp = $t[5];
}
@$nlle_impu=@$imp+$jour;
	if ($nb==0)
	{
	$requete1 = "INSERT INTO imputation  
	VALUES('', $proj, $sous_taches, $user, '$date', $jour, '$raf','0','$comment','".date('Y-m-d G:i:s')."','','')";
	$req1 = mysql_query($requete1) or die ("erreur insert requete2");
$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	$number = $cus[5];
	}
$nouv_number = @$number+$jour;
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);

	} else
	{ 
	$requete = "UPDATE imputation SET imputation = $nlle_impu, commentaire = '$comment', Date_imputation = '".date('Y-m-d G:i:s')."' WHERE ID = $id_imp";
	$req = mysql_query($requete) or die ("erreur update requete3");
$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
$req_custom = mysql_query($select_custom);
while ($cus=mysql_fetch_row($req_custom))
	{
	$id_custom = $cus[0]; 
	$number = $cus[5];
	}
$nouv_number = $number+$jour;
$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)"; 
$req_f=mysql_query($req_field);

	}
$somme="vrai"; 
$input = "faux";
}

}
else
{
$somme="vrai"; 
$input="faux";
}
$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
$del = mysql_query($delete);

return array('somme'=>$somme,'input'=>$input);
}
/*********************** fonction de rappel des imputations*/
function requeteselection ($d1,$d2,$id_tache,$user){
$requete="SELECT imputation. * , project.pname, jiraissue.summary
FROM imputation, jiraissue, userbase, project
WHERE imputation.Project = project.ID
AND imputation.issue = jiraissue.ID
and imputation.issue=".$id_tache."
AND imputation.user =".$user."
AND imputation.user = userbase.id
AND imputation.DATE BETWEEN '".$d1."' AND '".$d2."'
 ";
$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
$del = mysql_query($delete);
return @$requete ;
}

/***********************              MEDINI MOUNIRA Le 26-06-2008      ************************/
/********************     Requete simplifiée de la fonction resueteselection   ************************/
function requeteselection2 ($d1,$d2,$user){
$requete= "SELECT ID, Project, issue, user, Date, imputation, RAF, commentaire
		   FROM imputation
		   WHERE user =".$user."
				 AND DATE BETWEEN '".$d1."' AND '".$d2."'
 			";
			
$query= mysql_query($requete);
$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
$del = mysql_query($delete);
$Timp=array();
while($row = mysql_fetch_row($query))
{
$Timp[$row['2']][$row['3']][$row['4']]=$row['5'];
}
return ($Timp);
}


/************************************************************************************************/
//fonction qui calcul le total des imputations par date
function total_imputation($date, $user)
{
$sql = "SELECT sum( imputation ) FROM imputation WHERE Date = '$date' AND user =$user";
$query = mysql_query($sql);
$exe = mysql_fetch_row($query);
return ($exe) ; 
//print_r ($exe);
}

//fonction affiche un tableau d'alerte
function tab_alerte($chaine)
{
 echo '<TABLE cellSpacing=0 cellPadding=1 width="60%" align=center 
            bgColor=#bbbbbb border=0>
              <TR>
                <TD vAlign=top width="100%" colSpan=2>
                  <TABLE cellSpacing=0 cellPadding=4 width="100%" 
                  bgColor=#ffffff border=0>
                    <TBODY>
                    <TR>
                      <TD vAlign=top width="180%" bgColor=#EFEFEF>
                        <TABLE cellSpacing=0 cellPadding=0 width="100%">
                          <TBODY>
                          <TR>
                            <TD vAlign=top width="80%" bgColor=#EFEFEF align="center"><font color="#CC0000"><b>'.$chaine.'</b></font></TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>'; 

}

//fonction retourne le nom et le prénom d'un user à partir de son ID
function nom_prenom_user($id)
{
$user = "SELECT propertystring . * 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
AND userbase.id =$id";
$result = mysql_query($user);
$nb = mysql_num_rows($result);
if($nb!=0) {
$nom = mysql_result($result, 0, 'propertyvalue');
return($nom); } else return(false);
}

//fonction retourne l'email d'un user à partir de son ID
function email($id)
{
$user = "SELECT propertystring . * 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'email'
AND propertyentry.entity_id = userbase.id
AND userbase.id =$id";
$result = mysql_query($user);
$nb = mysql_num_rows($result);
if($nb!=0) {
$email = mysql_result($result, 0, 'propertyvalue');
return($email); } else return(false);
}


//fonction retourne les status traduit
function status($id_status)
{
$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'Status'
AND propertyentry.ENTITY_ID = $id_status
";
$result = mysql_query($user);
$nb = mysql_num_rows($result);
if($nb!=0) {
$stat = mysql_result($result, 0, 'propertyvalue');
return($stat); } else return(false);
}

//fonction retourne les priorités traduites
function priority($id_priority)
{
$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'Priority'
AND propertyentry.ENTITY_ID = $id_priority
";
$result = mysql_query($user);
$nb = mysql_num_rows($result);
if($nb!=0) {
$prio = mysql_result($result, 0, 'propertyvalue');
return($prio); } else return(false);
}

//fonction retourne les types traduits
function type($id_type)
{
$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'IssueType'
AND propertyentry.ENTITY_ID = $id_type";
$result = mysql_query($user);
$nb = mysql_num_rows($result);
if($nb!=0) {
$type = mysql_result($result, 0, 'propertyvalue');
return($type);
}
else return(false);
}


//fonction qui cré un tableau vide en fait c'est pour l'espace vertical
function tab_vide($height)
{
echo '<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center 
            border=0>
              <TR>
                <TD vAlign=top width="100%" colSpan=2><img src="../images/spacer.gif" width="1" height="'.$height.'" /></TD>
              </TR></TABLE>';
}


//fonction fixe le format d'un nbre
function format_number($str,$decimal_places='2',$decimal_padding="0"){
        /* firstly format number and shorten any extra decimal places */
        /* Note this will round off the number pre-format $str if you dont want this fucntionality */
        $str           =  number_format($str,$decimal_places,'.','');     // will return 12345.67
        $number       = explode('.',$str);
        $number[1]     = (isset($number[1]))?$number[1]:''; // to fix the PHP Notice error if str does not contain a decimal placing.
        $decimal     = str_pad($number[1],$decimal_places,$decimal_padding);
        return (float) $number[0].'.'.$decimal;
}

/************************************ Format des nombres à afficher sur excel ******************************/
function format_number_excel($str)
{
$str = sprintf('%.2f', $str); 
$str = str_replace(".",",",$str);
return($str);
}


/******************************** Fonction qui retourne les pkey d'un projet donné  ***************************/
function pkey_proj($id)
{
$pkey = "select pkey from project where ID = $id";
$query_pkey = mysql_query($pkey);
$nb = mysql_num_rows($query_pkey);
if($nb!=0) {
$key = mysql_result($query_pkey, 0, 'pkey');
return($key); } else return(false);
}



/******************************** Fonction qui retourne les login d'un user donné  ***************************/
function login_user($id)
{
$select_login = "select username from userbase where ID = '$id'";
$query_login = mysql_query($select_login);
$nb = mysql_num_rows($query_login);
if($nb!=0) { 
$log = mysql_result($query_login, 0, 'username');
return($log); }
else return(false);
}

function id_user($login_user)
{
$select_id = "select ID from userbase where username = '".$login_user."'";
$query_id = mysql_query($select_id) or die ("error");
$nb = mysql_num_rows($query_id);
if($nb!=0) {
$id = mysql_result($query_id, 0, 'ID');
return($id); } else return(false);
}

//fonction fixe le format d'une date et la rendre selon notre format dans la base
function format_date($date) 
{
$date = explode('/',$date); 
$date_format = $date[2]."-".$date[1]."-".$date[0];
return ($date_format);
}
function format_date2($date) 
{
$date = explode('-',$date); 
$date_format2 = $date[2]."-".$date[1]."-".$date[0];
return ($date_format2);
}
/************************fonction qui retourne le samedies et les dimanches d'un moi donné********/
/************************Rihab  ben smida le 18/04/2007*******************************************/

function sam_mois($mois,$an)
{

$nom_mois= 
        array( 
        1=>'Janvier', 
        'Février', 
        'Mars', 
        'Avril',          
        'Mai', 
        'Juin', 
        'Juillet', 
        'Août', 
        'Septembre', 
        'Octobre', 
        'Novembre', 
        'Décembre' 
        );  
$nbj=date("t", mktime(0,0,0,$mois, 1, $an)); 
//les samedi du mois
$samedi = 7 - date("w", mktime(0,0,0,$mois, 1, $an)); //Samedi 7 
$nbsam=round($nbj/7); 
for($i=0;$i<=$nbsam;$i++) 
{ 
if($samedi + 7*$i<=$nbj){ 
$sam_mois[]=$samedi + 7*$i; 
} 
} 
return ($sam_mois);
}
//echo 'Les samedi du mois de ',$nom_mois[$mois],' ',$an,' sont :<pre>'; 
//print_r($sam_mois); 

//les dimanche du mois
function dim_mois($mois,$an)
{

$nom_mois= 
        array( 
        1=>'Janvier', 
        'Février', 
        'Mars', 
        'Avril',          
        'Mai', 
        'Juin', 
        'Juillet', 
        'Août', 
        'Septembre', 
        'Octobre', 
        'Novembre', 
        'Décembre' 
        );  
$nbj=date("t", mktime(0,0,0,$mois, 1, $an)); 
$dimanche = 1 - date("w", mktime(0,0,0,$mois, 1, $an)); // 
if($dimanche<=0) { $dimanche=$dimanche+7; }
$nbdim=round($nbj/7); 
for($i=0;$i<=$nbdim;$i++) 
{ 
if($dimanche + 7*$i<=$nbj){ 
$dim_mois[]=$dimanche + 7*$i; 
} 
} 

return ($dim_mois);
}
function insert_pourcent_prev($sous_taches)
{
$select_prev = "SELECT (c1.numbervalue / c2.numbervalue)*100 AS prev
FROM customfieldvalue c1, customfieldvalue c2
WHERE c1.customfield =10021 AND c2.customfield =10000 AND c1.issue = $sous_taches AND c2.issue = $sous_taches";
$query_prev = mysql_query($select_prev);
$nbp = mysql_num_rows($query_prev);
if($nbp>0) {
$prevv = mysql_result($query_prev,0,'prev');
$prevv = format_number($prevv);
$select_cus = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10082)";
$sql_cus = mysql_query($select_cus);
$nb_cus = mysql_num_rows($sql_cus);
	$update_prev = "UPDATE `customfieldvalue` SET `PARENTKEY` = NULL ,
	`STRINGVALUE` = NULL ,
	`NUMBERVALUE` = '$prevv',
	`TEXTVALUE` = NULL ,
	`DATEVALUE` = NULL ,
	`VALUETYPE` = NULL where (issue = $sous_taches) and (customfield = 10082)";
	$query_up_prev = mysql_query($update_prev);
}
}

/*********   Fonction pour vérifier si le jour férié choisi est déja ds la base ou pas   **************/

function verif_jour_fer($sous_taches, $date) 
{

$j_f = "select SUMMARY from jiraissue where jiraissue.id = $sous_taches";
$q_j_f = mysql_query($j_f);
$summ = mysql_result($q_j_f, 0, 'SUMMARY');
//$nb_j_f = mysql_num_rows($q_j_f);
if ($summ == 'Jour férié')
{
$sql_fer = "select Date from jour_ferie where Date like '$date' ";
$query_fer = mysql_query($sql_fer);
$nb_fer = mysql_num_rows($query_fer);
return($nb_fer);
}
else
{
$nb = 1;
return($nb);
}
}
/*********   Fonction ID des demandes d'absences   *****************/

function id_proj($proj) 
{
$pname_proj = "select ID from project where pname like 'Demandes d\'absence'";
$query_pname = mysql_query($pname_proj) or die("erreur");
$nb = mysql_num_rows($query_pname);
if($nb!=0) { $pname_id = mysql_result($query_pname,0,'ID');
return($pname_id); } else  return(false);
}
/*********   Cette fonction envoie "True" si une date est valide et dans le cas contraire "False".******************/
function verif_date($Date)
{
$date1 = explode('/',$Date);
	if ((count($date1)==3) and ((is_numeric($date1[0])) and (is_numeric($date1[1])) and (is_numeric($date1[2]))))
	{ 
	$valide = checkdate($date1[1], $date1[0], $date1[2]);
		if($valide==1) { return("true"); } else { return("false"); }
	}
	else { return("false"); }
}
function nbre_impu($tache,$verif_date1,$Date,$verif_date2,$Date2,$user)
{
$select = "SELECT 
				imputation.imputation
		   From imputation
		   WHERE imputation.issue = ".$tache;

if((isset($user)) and ($user!='faux'))
{
$select.=" AND imputation.user = '$user' "; }

if((isset($verif_date1)) and ($verif_date1!='false'))
{
$select.=" AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$select.=" AND (imputation.Date) <=  '$Date2' "; }
//echo $select;
$query = mysql_query($select);
$nb = mysql_num_rows($query);
return($nb);
}
function nbre_task($project, $idcollab)
{
$select = "SELECT DISTINCT (issue)
		   FROM imputation
		   WHERE PROJECT =$project
		   AND user =$idcollab";
			
$query = mysql_query($select);
//echo $select;
$num = mysql_num_rows($query);
$task = mysql_fetch_array($query);
return($num);
}
function select_imp($issue,$username,$verif_date1,$Date,$verif_date2,$Date2,$facturation)
{
$select_imp = "SELECT DISTINCT(ID),
					  imputation.Date,
					  imputation,
					  RAF, imputation.user
			   FROM imputation
			   WHERE issue = $issue
";
if((isset($username)) and ($username!='faux'))
{
$select_imp.=" AND user = $username ";
}				
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$select_imp.=" AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$select_imp.="AND (imputation.Date) <=  '$Date2' "; }
if((isset($facturation)) and ($facturation!=0)) { 
$select_imp.= "AND imputation.validation = ".$facturation." "; 
}
$select_imp.="ORDER BY user, imputation.Date"; 
$query_imp = mysql_query($select_imp);
//echo $select_imp;
return($query_imp);
}
function select_task($proj,$collab,$verif_date1,$Date,$verif_date2,$Date2)
{
$select_issue ="SELECT 
		(jiraissue.ID), 
		jiraissue.SUMMARY, 
		imputation.Date, 
		round(sum(imputation.imputation),2) as som, 
		imputation.RAF, 
		jiraissue.ASSIGNEE, 
		userbase.ID, 
		jiraissue.pkey 
FROM 
		jiraissue, 
		imputation, 
		userbase, 
		project 
WHERE 
		project.ID = imputation.project AND project.ID = $proj 
		AND imputation.user = userbase.ID  AND userbase.ID = $collab
		AND imputation.issue = jiraissue.ID 
		AND (imputation.imputation) is NOT NULL
				";
if((isset($verif_date1)) and ($verif_date1!='false'))
{
$select_issue.="AND (imputation.Date) >=  '$Date' "; }
if((isset($verif_date2)) and ($verif_date2!='false'))
{
$select_issue.="AND (imputation.Date) <=  '$Date2' "; }
$select_imp=$select_issue."GROUP BY imputation.ID 
				ORDER BY jiraissue.pkey";
$select_issue.="GROUP BY jiraissue.ID 
				ORDER BY jiraissue.pkey";
$query_issue = mysql_query($select_issue) or die("erreur"); 
return($query_issue);
}






function comparaison_date($datea, $dateb)
{
$date1 = explode('/',$datea);
$date2 = explode('/',$dateb);
if($date1[2]>$date2[2])
{
return("true");
}
elseif($date1[2]<$date2[2])
{
return("faux");
}
elseif($date1[2]==$date2[2])
{
	if($date1[1]>$date2[1])
	{
	return("true");
	}
	elseif($date1[1]<$date2[1])
	{
	return("faux");
	}
	elseif($date1[1]==$date2[1])
	{
		if($date1[0]>$date2[0])
		{
		return("true");
		}
		elseif($date1[0]<$date2[0])
		{
		return("faux");
		}
		elseif($date1[0]==$date2[0])
		{
		return("faux");
		}
	}
}
}




/****requete de séléction ddes congé en fonction de user ,date et type congé *********/
function requeteconge($type,$id,$datedebut,$datefin){
$sql_congé="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue,issuetype
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = '$id'
AND jiraissue.issuetype=issuetype.ID
AND issuetype.ID= $type
AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
GROUP BY date ";

return ($sql_congé) ;
}
/****requete de séléction des formation en fonction de user ,date et type formation *********/
function requeteformation($type,$id,$datedebut,$datefin){
$sql_formation="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue,issuetype
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = $id
AND jiraissue.issuetype=issuetype.ID
AND issuetype.ID= $type
AND project.ID = '10083'
AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
GROUP BY date  ";

return ($sql_formation) ;
}
/****requete de séléction de commercial en fonction de user ,date  *********/
function requetecommercial($type,$id,$datedebut,$datefin){
$sql_commercial="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY,jiraissue.ID FROM imputation,project,jiraissue,issuetype
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = $id
AND jiraissue.issuetype=issuetype.ID
AND issuetype.ID= $type
AND project.ID = 10100
AND imputation.DATE BETWEEN '$datedebut' AND '$datefin'
GROUP BY jiraissue.ID  ";

return ($sql_commercial) ;
}

function requetecommercial1($type,$id,$issue,$datedebut,$datefin){
$sql_commercial1="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY,jiraissue.ID FROM imputation,project,jiraissue,issuetype
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = $id
AND jiraissue.issuetype=issuetype.ID
AND issuetype.ID= '$type'
AND project.ID = '10100'
AND jiraissue.ID= $issue
AND imputation.DATE BETWEEN '$datedebut' AND '$datefin'
GROUP BY date";

return ($sql_commercial1) ;
}
/****requete de séléction de marketing et management en fonction de user ,date  *********/
function requetemarketingmanagement($project,$id,$summary,$datedebut,$datefin){
$sql_marketingmanagement="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = '$id'
AND project.ID='$project'
AND jiraissue.SUMMARY = '$summary'
AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
GROUP BY date  ";

return ($sql_marketingmanagement) ;
}
function requetemarketingmanagement1($project,$id,$datedebut,$datefin){
$sql_marketingmanagement1="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue
WHERE imputation.Project =project.ID
AND imputation.issue = jiraissue.ID
AND imputation.user = $id
AND project.ID='$project'
AND imputation.DATE BETWEEN '$datedebut'AND '$datefin' 
GROUP BY jiraissue.ID ";

return ($sql_marketingmanagement1) ;
}

function security($user, $proj)
{
$iduser = id_user($user);
$groupofuser = list_membershipbase ($iduser);  
$req = "SELECT DISTINCT (ji.security)
			 FROM jiraissue ji, project p, schemeissuesecuritylevels sc, schemeissuesecurities ss
			 WHERE ji.project = p.ID
			 AND p.ID = $proj 
			 AND (
					 ji.security = sc.ID
					 AND sc.ID = ss.security
					 AND sc.scheme = ss.scheme
					 AND (
							ss.sec_parameter IN $groupofuser 
							OR ss.sec_parameter='$user' 
							OR (sec_type = 'reporter' and ji.REPORTER = '$user') 
							OR (sec_type = 'lead' and p.LEAD = '$user')
							OR (sec_type = 'assignee' and ji.ASSIGNEE = '$user')  
						 )
  				)" ; 
$result_security = mysql_query($req); $nb_security = mysql_num_rows($result_security);
$val_security = "("; $param_security = 1; 
while ($tab_security = mysql_fetch_array($result_security))
{
$val_security.= $tab_security[0];
if($param_security < $nb_security) { $val_security.= ", "; }
$param_security++;
}
$val_security.= ")";
if ($val_security != "()") { $val_security = $val_security; }
else { $val_security = "(0)"; } 
//Une requête pour selectionner les code de security pour les taches qui ont un role sur le projet.
$req2 = "SELECT j.SECURITY
			  FROM jiraissue j, schemeissuesecurities sh, projectroleactor p
			  WHERE j.security = sh.SECURITY
			  AND sec_type = 'projectrole'
			  AND p.PID = j.project
			  AND p.PID = ".$proj."
			  AND p.PROJECTROLEID = sh.sec_parameter
			  AND p.ROLETYPEPARAMETER = '".$user."'
			   ";
$sql_req2 = mysql_query($req2);
$nb_req2  = mysql_num_rows($sql_req2); $vsec="";
While($row = mysql_fetch_row($sql_req2))
{
$vsec = $vsec.$row[0].", ";
}
if($nb_req2!=0)
{
$val_security = str_replace("(","(".$vsec."",$val_security);
}
return($val_security);
}

function liste_group_user($proj)
{
$query ="SELECT DISTINCT(user_name)
FROM membershipbase
WHERE Group_Name
IN (SELECT DISTINCT (
perm_parameter
)
FROM schemepermissions
WHERE SCHEME = ( 
SELECT nodeassociation.sink_node_id
FROM project, nodeassociation
WHERE project.ID = nodeassociation.source_node_id
AND nodeassociation.SOURCE_NODE_ID = '".$proj."'
AND nodeassociation.SOURCE_NODE_ENTITY = 'Project'
AND nodeassociation.SINK_NODE_ENTITY = 'PermissionScheme' ) 
AND perm_parameter != 'Project-watcher')
";

$db_query = mysql_query($query);
$nb_user = mysql_num_rows($db_query);

$role = "SELECT DISTINCT (
membershipbase.user_name
)
FROM projectroleactor, schemepermissions, project, membershipbase
WHERE schemepermissions.PERMISSION =10
AND schemepermissions.perm_type = 'projectrole'
AND schemepermissions.perm_parameter = projectroleactor.PROJECTROLEID
AND projectroleactor.PID = project.ID
AND projectroleactor.PID = '$proj'
AND (
projectroleactor.ROLETYPEPARAMETER = membershipbase.user_name
OR projectroleactor.ROLETYPEPARAMETER = membershipbase.group_name
)
";
$query_role = mysql_query($role);
$nb_role = mysql_num_rows($query_role);
$nb = $nb_user + $nb_role;

$val_user = "";
if(($nb_user>0) or($nb_role>0))
{
$val_user = "("; $sc = 0; 
while( $tab_user=mysql_fetch_array($db_query)){
$val_user.= "'".$tab_user[0]."'";
if ($sc!=($nb-1)) { $val_user.=", "; }
$sc++;
}
while( $tab_role=mysql_fetch_array($query_role)){
$val_user.= "'".$tab_role[0]."'";
if ($sc!=($nb-1)) { $val_user.=", "; }
$sc++;
}
$val_user.=")";
}


if ($val_user != "()") { $val_user = $val_user; }
else { $val_user = "(0)"; }
return($val_user);

}

function som_imputation($tab, $date1, $date2)
{
$imp = "SELECT 
			round(sum(imputation.imputation), 2) as som 
		FROM 
			imputation 
		WHERE 
			(imputation.user = $tab)
			AND (Date BETWEEN '$date1' AND '$date2')";
$exe_imp = mysql_query($imp);
	while($tab_som = mysql_fetch_row($exe_imp) )
	{
	$som = $tab_som[0];
	}

return($som);
}

function affich_comment($issue, $user, $d1, $d7)
{
$comment = '';
$sql_comment = "SELECT 
			DISTINCT(commentaire) 
		FROM 
			imputation 
		WHERE 
			issue = ".$issue."
			AND user = '".$user."'
			AND Date BETWEEN '".$d1."' AND  '".$d7."'
			AND commentaire != '' ";
$query_comment = mysql_query($sql_comment);
	while($tab = mysql_fetch_row($query_comment) )
	{
	$comment = $tab[0];
	}

return($comment);
}
function affich_validation($id_imputation)
{
$sql_valid = "SELECT 
				validation 
			FROM 
				imputation 
			WHERE 
				ID = ".$id_imputation;
$query_valid = mysql_query($sql_valid);
$tab = mysql_fetch_array($query_valid);
$valid = $tab[0];
return($valid);
}

function update_variable($issue,$vraf)
{
			
$vraf = select_dernier_raf($issue,0);
# Si la semaine n'est pas celle en cours

$requete_jira="UPDATE 
			   customfieldvalue 
			   SET numbervalue='".$vraf."' 
			   WHERE issue=$issue 
			   AND customfield=(SELECT ID FROM customfield WHERE cfname ='RAF')";
$req_jira=mysql_query($requete_jira);

/********************* MAJ du temps Estimé (RAF) dans la table jiraissue **********************/
$w_RAF = $vraf * 60 * 60 * 8;
/*mysql_query("UPDATE jiraissue SET TIMEESTIMATE = '0' WHERE ID = ".$issue." AND TIMEESTIMATE IS NULL");
mysql_query("UPDATE jiraissue SET TIMEESTIMATE = ".$w_RAF." WHERE ID = ".$issue);*/
/********************* MAJ du temps Estimé (RAF) dans la table jiraissue **********************/



# Récupérer la valeur de la charge consommée
$sql_cons1 = "SELECT sum(imputation) as somme
			  FROM imputation
			  WHERE issue = ".$issue;
$req_cons1 = mysql_query($sql_cons1);
$res_cons1 = mysql_fetch_object($req_cons1);
$chge_cons = $res_cons1->somme;

/********************* MAJ du temps consommée  dans la table jiraissue **********************/
$w_CC = $chge_cons * 60 * 60 * 8;
mysql_query("UPDATE jiraissue SET TIMESPENT = '0' WHERE ID = ".$issue." AND TIMESPENT IS NULL");
mysql_query("UPDATE jiraissue SET TIMESPENT = ".$w_CC." WHERE ID = ".$issue);
/********************* MAJ du temps consommée dans la table jiraissue **********************/


mysql_query("UPDATE customfieldvalue SET NUMBERVALUE = ".$chge_cons."
			 WHERE issue = ".$issue." AND customfield = (SELECT ID FROM customfield WHERE cfname = 'Consommée')");

if(empty($chge_cons)) { $chge_cons=0; }


# Récupérer la valeur de du RAF
$sql_raf = "SELECT NUMBERVALUE
			 FROM customfieldvalue
			 WHERE customfield = (SELECT ID FROM customfield WHERE cfname = 'RAF')
			 AND ISSUE = ".$issue."
			 ";
$req_raf = mysql_query($sql_raf);
if(mysql_num_rows($req_raf ) !=0)
{
while ($row1 = mysql_fetch_row($req_raf)) { $chge_raf = $row1[0]; }

}
else {
$chge_raf=0; }

# Récupérer la charge prévue
$sql_prev = "SELECT NUMBERVALUE as NUMBERVALUE2
			 FROM customfieldvalue
			 WHERE customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge prévue')
			 AND ISSUE = ".$issue;
$req_prev = mysql_query($sql_prev);

if(mysql_num_rows($req_prev ) !=0)
{
while ($row = mysql_fetch_row($req_prev)) { $chge_prev = $row[0]; }
}
else {
$chge_prev=0; }

$chge_relle = $chge_cons + $chge_raf;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$chge_relle."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge réelle'))");
if($chge_relle == 0) { $avanc = 0; }
else { $avanc = ($chge_cons / $chge_relle) * 100; $avanc = format_number($avanc); }
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$avanc."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Avancement (%)'))");
			 
if($chge_relle == 0) { $prev = 0; }
else { $prev     = ($chge_raf / $chge_relle) * 100; $prev = format_number($prev); }

mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$prev."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Prévisionnel (%)'))");
			 
$vtion_chge = $chge_prev - $chge_relle;
mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion_chge."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation de charge'))");

if($chge_prev == 0) { $vtion = 0; }
else { $vtion = ($vtion_chge / $chge_prev) * 100; $vtion = format_number($vtion); }

mysql_query("UPDATE customfieldvalue 
			 SET NUMBERVALUE = '".$vtion."' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation (%)'
))");
}
function verif_comment($issue, $user, $date1, $date2)
{
$sql = "SELECT * FROM imputation WHERE (issue = ".$issue.") and (user = ".$user.") and date between '".$date1."' and '".$date2."' and imputation = '0' and RAF = '0'";
$req = mysql_query($sql);
$nb = mysql_num_rows($req);
return($nb);
}
function affiche_valide_par($id_imp){

	$sql = "SELECT valide_par FROM imputation WHERE ID = $id_imp";
	$query = mysql_query($sql) or die("erreur");
	$nb = mysql_num_rows($query);
	if($nb==0) { $nom_valid = ''; }
	else { $nom_valid = mysql_result($query, 0, 'valide_par'); }
	return($nom_valid);
}
function affiche_dat_val($id_imp){

	$sql = "SELECT Date_validation FROM imputation WHERE ID = $id_imp";
	$query = mysql_query($sql) or die("erreur");
	$nb = mysql_num_rows($query);
	if($nb==0) { $dat_valid = ''; }
	else { $dat_valid = mysql_result($query, 0, 'Date_validation'); }
	return($dat_valid);
}
function select_collab_issue($id_issue){
$select  = "SELECT distinct(user) from imputation where issue = $id_issue";
$query   = mysql_query($select);
//echo $nb = mysql_num_rows($query)."<br>";
$list_user="";
while ($list = mysql_fetch_row($query))
{
$list_user = $list_user."|".$list[0];
}
return($list_user);
}
function select_dernier_raf($issue,$collab)
{
$select_raf = "SELECT RAF, Date_imputation 
	  FROM imputation
	  WHERE issue = ".$issue."
	  AND Date = (SELECT max(Date) FROM imputation WHERE issue = ".$issue;
	  
if((isset($collab)) and ($collab!=0))	
 { $select_raf.=" AND user =".$collab." ) 
			AND user =".$collab."";	
 }
 else {
 $select_raf.=")";  }
//echo $select_raf;
$query_raf = mysql_query($select_raf);
$nb=mysql_num_rows($query_raf);
if($nb!=0) { $list = mysql_fetch_array($query_raf); $vraf=$list[0]; }
else { $vraf=0; }
return($vraf);
}
function list_proj_non_facture()
{
$list_proj = "("; $i=0;
$select = "SELECT ID 
FROM ` tetd_project_non_facturable` 
";
$query  = mysql_query($select) or die('error');
$nb = mysql_num_rows($query);
if($nb!=0) { 
while ($row = mysql_fetch_row($query))
{
$list_proj.=$row[0];
if($i!=($nb-1)) { $list_proj.=", "; }
$i++;
}
}
$list_proj.= ")";
$list_proj;
return($list_proj);
}


function issue_abscence ($date1, $date2, $login)
{
$sql = "SELECT jiraissue.ID, jiraissue.pkey, jiraissue.SUMMARY 
		 	 FROM `jiraissue`, customfieldvalue, issuestatus 
			  WHERE jiraissue.ID = customfieldvalue.ISSUE
			  AND ((customfieldvalue.DATEVALUE BETWEEN '".$date1."' AND '".$date2."') 
	   	 		OR (jiraissue.DUEDATE BETWEEN '".$date1."' AND '".$date2."')
		 		OR (customfieldvalue.DATEVALUE <= '".$date1."' AND jiraissue.DUEDATE >= '".$date2."')) 
			  AND customfieldvalue.CUSTOMFIELD = 10002
			  AND jiraissue.PROJECT =  10064
			  AND (issuestatus.ID = jiraissue.issuestatus)  
			  AND jiraissue.REPORTER = '".$login."'
			  AND (issuestatus.pname = 'Validated')
		  ";
$query = mysql_query($sql) or die('error') ;
$res = mysql_num_rows($query);
$fonc = $res."||".$sql;
return($fonc);

}


function duree_conge($id_issue)
{
$sql = "SELECT cf.NUMBERVALUE 
		FROM customfieldvalue cf, jiraissue ji
		WHERE cf.ISSUE = ji.ID
		AND ji.PROJECT = 10064
		AND ji.ID = ".$id_issue."
		AND cf.CUSTOMFIELD = 10035
		";
$query=mysql_query($sql);
$nb = mysql_num_rows($query);
if($nb > 0)
{ $duree = mysql_result($query, 0, 'NUMBERVALUE'); }
else { $duree=0; }
return($duree);
}


function periode_conge($id_issue, $date)
{
$date_debut="";
$date_fin = "";
$sql = "SELECT CAST(cf.DATEVALUE AS Date) as date_deb, CAST(ji.DUEDATE AS Date) as date_fin
		FROM customfieldvalue cf, jiraissue ji
		WHERE cf.ISSUE = ji.ID
		AND ji.PROJECT = 10064
		AND ji.ID = ".$id_issue."
		AND cf.CUSTOMFIELD = 10002
		";
$query=mysql_query($sql);
while ($row = mysql_fetch_row($query))
{
$date_debut = $row[0];
$date_fin = $row[1];
}

return($date_debut."||".$date_fin);
}



function som_imp($id_issue)
{
$sql1 = "SELECT round(sum(imputation),2) as somme
		FROM imputation 
		WHERE issue = ".$id_issue;
$query1=mysql_query($sql1) or die('error');
$nb1 = mysql_num_rows($query1);
if($nb1 > 0)
{ $som = mysql_result($query1, 0, 'somme'); }
else { $som=0; }

return($som);
}

/*********************************************************
@ author: mounira.medini@businessdecision.com
@ maintainer: fonction qui fourni la liste des groupes pour un user
@ para: id_user
@ since: 23-04-2008 21:00
**********************************************************/
function membershipbase ($iduser) 
{ 
  $sql1="select groupbase.groupname as groupe from groupbase,membershipbase,userbase 
  where groupbase.groupname=membershipbase.GROUP_NAME and membershipbase.USER_NAME=userbase.username
  and userbase.ID=".$iduser;
  $query=mysql_query($sql1);
  $i=0; $group =array();
  while( $tab=mysql_fetch_row($query)){
  $group[$i]=$tab[0];
  $i++;
  }
  return($group);
}

function list_membershipbase ($iduser) 
{ 
$liste_group = membershipbase($iduser);
$liste_group;
$nb_group = count($liste_group);
$tab_group=array();
$tabbb = "(";
for($i=0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
$tabbb.="'".$liste_group[$i]."'";
if($i!=($nb_group-1))
{ $tabbb.= ", "; }
}
$tabbb.= ")";
return($tabbb);
}

/**********************************************************************
@Date           : 23-04-2008 21:00
@Fonctionnement : Liste des schémas de permissions  pourun user données
**********************************************************************/ 
/*function schema_permission ($iduser, $login) 
{
$tabbb = list_membershipbase ($iduser);
$tab2 = str_replace("Project-watcher", "PW", $tabbb);
$schema_sql="SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '".$login."'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN".$tabbb;

$proj_sql = "SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '".$login."'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN".$tab2;
$schema_query = mysql_query($schema_sql);
$proj_query = mysql_query($proj_sql);
$nb_proj = mysql_num_rows($proj_query);
$nb_scheme = mysql_num_rows($schema_query);
if($nb_scheme>0)
{
$schema = "("; $sc = 0; 
while( $tab_schema=mysql_fetch_array($schema_query)){
$schema.= "'".$tab_schema[0]."'";
if ($sc!=($nb_scheme-1)) { $schema.=", "; }
$sc++;
}
$schema.=")";
}
return($schema);
}
*/

/***************************************************
@Date           : 23-04-2008 21:00
@Fonctionnement : Gestion des rôles sur les taches
****************************************************/ 
function gestion_role_task($iduser, $login)
{
$tabbb = list_membershipbase ($iduser);
 $tab2 = str_replace("Project-watcher", "PW", $tabbb);

$proj_sql = "SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '".$login."'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN".$tab2;
				
$proj_query = mysql_query($proj_sql);
$nb_proj = mysql_num_rows($proj_query);

$sql_role = "SELECT 
					DISTINCT (p.PID) 
			FROM 
					schemepermissions sc, 
					projectroleactor p 
			WHERE 
					sc.perm_type = 'projectrole'
					AND sc.PERMISSION =10
					AND sc.perm_parameter = p.PROJECTROLEID
					AND (p.ROLETYPEPARAMETER = '".$login."' 
					OR p.ROLETYPEPARAMETER IN ".$tab2.")"; 
$query_role = mysql_query($sql_role); 
$nb_role = mysql_num_rows($query_role);
if(($nb_proj>0) or($nb_role>0))
{
$tab_imputation = "("; $sc = 0; 
while( $tab_proj=mysql_fetch_row($proj_query)){
$tab_imputation.= "'".$tab_proj[0]."'";
if ($sc!=($nb_proj-1)) { $tab_imputation.=", "; }
$sc++;
}
while( $tab_role=mysql_fetch_row($query_role)){
if ($sc!=($nb_proj-1)) { 
$sc++;
}
}
$tab_imputation.=")";
}
return($tab_imputation);
}
/***************************************************
@Date           : 23-04-2008 21:00
@Fonctionnement : Gestion des rôles sur les projets
****************************************************/ 


function gestion_role($iduser, $login)
{


$tabbb = list_membershipbase ($iduser);
$tab2 = str_replace("Project-watcher", "PW", $tabbb);
$tab_imputation = gestion_role_task($iduser, $login);
$query = "Select 
				distinct(project.id), project.pname
			  FROM 
				project, nodeassociation, permissionscheme
			  WHERE 
				project.ID = nodeassociation.SOURCE_NODE_ID
				AND SOURCE_NODE_ENTITY = 'Project'
				AND project.id NOT IN (SELECT ID FROM tetd_project_exclusive)
				AND SINK_NODE_ENTITY = 'PermissionScheme'
				AND SINK_NODE_ID IN ".$tab_imputation." 
				ORDER BY project.pname"; 
				
$result = mysql_query($query);	

$nbproj = mysql_num_rows($result);
$sql_role = "SELECT 
					DISTINCT (p.PID) 
			  FROM 
					schemepermissions sc, 
					projectroleactor p 
			WHERE 
					sc.perm_type = 'projectrole'
					AND sc.PERMISSION =10
					AND p.PID NOT IN (SELECT ID FROM tetd_project_exclusive)
					AND sc.perm_parameter = p.PROJECTROLEID
					AND (p.ROLETYPEPARAMETER = '".$login."' 
					OR p.ROLETYPEPARAMETER IN ".$tab2.")";
$query_role = mysql_query($sql_role);
$nb_role = mysql_num_rows($query_role);

 $nb_project = $nbproj + $nb_role;	
if($nb_project>0)
{ 
$tab_proj = "("; $sc = 0; 

while( $tab_projet=mysql_fetch_array($result))
 {
 $tab_proj.= "'".$tab_projet[0]."'";
if ($sc!=($nb_project-1)) { $tab_proj.=", "; }
$sc++;
 }

while( $tab_proj2=mysql_fetch_array($query_role)){
$tab_proj.= "'".$tab_proj2[0]."'"; 
if ($sc!=($nb_project-1)) { $tab_proj.=", "; }
$sc++;
}

$tab_proj.=")";
} else { $tab_proj = "('')"; }

return($tab_proj);
}
function member_jira_user($user)
{
$log = login_user($user);
$sql = "SELECT ID 
		FROM membershipbase 
		WHERE `GROUP_NAME` LIKE 'BD-users'
		AND USER_NAME = '".$log."'";
		
$query = mysql_query($sql);
$nb = mysql_num_rows($query);
if($nb == 0) { return ('false'); }
else { return ('true'); }

}
function check_date_conge($date, $imputation, $issue)
{

$periode = periode_conge($issue, $date);
$date_deb = substr($periode, 0, 10); 
$date_fin = substr($periode, 12, 10);  

if((($date<$date_deb) or ($date>$date_fin)) and ($imputation!=0))
{
return("faux");
}
else
{
return("vrai");
}
}
?>