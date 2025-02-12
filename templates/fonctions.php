<?php
/**
 * date_sem: Fontion qui calcule la date de d�but de semaine � partir de la date sysytme
 *
 * @param Integer $n
 * @param Integer $i
 * @param Integer $y
 * @return String
 */
function date_sem($n, $i, $y) {
	$prem_jour = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) + $i - date ( 'w' ) + 1 - $n * 7, date ( 'Y' ) );
	$datefin = date ( "$y-m-d", $prem_jour );
	return $datefin;
}

/**
 * date_sem_affiche : Fontion qui calcule la date de fin de semaine � partir de la date sysytme
 *
 * @param Integer $n
 * @param Integer $i
 * @param Integer $y
 * @return String
 */
function date_sem_affiche($n, $i, $y) {
	$prem_jour = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) + $i - date ( 'w' ) + 1 - $n * 7, date ( 'Y' ) );
	$datefin = date ( "d-m-$y", $prem_jour );
	return $datefin;
}

/**
 * getListOfWeek : 
 *
 * @param Integer $mois
 * @param Integer $annee
 * @return Integer
 */

 function week_of_month($date) {
    $date_parts = explode('-', $date);
    $date_parts[2] = '01';
    $first_of_month = implode('-', $date_parts);
    $day_of_first = date('N', strtotime($first_of_month));
    $day_of_month = date('j', strtotime($date));
    return floor(($day_of_first + $day_of_month - 1) / 7) + 1;
}

function getListOfWeek($month, $year){

	
	$days_in_month = date("t", mktime(0, 0, 0, $month, 1, $year));
    $weeks_in_month = 1;
    $aTabFinal = array();
	$aTabFinalTmp =array();
    //loop through month
    for ($day=1; $day<=$days_in_month; $day++) {
	    $test_date = $year."-".str_pad($month, 2, '0', STR_PAD_LEFT)."-" . str_pad($day, 2, '0', STR_PAD_LEFT);
		$week = date('W', strtotime($test_date));
	    $aTabFinalTmp[(int)$week] =  (int)$week;
	}	 
	
	
	if(!empty($aTabFinalTmp)){
		foreach ($aTabFinalTmp as $key=>$value){
			$aTabFinal[] = $value;
		}
	}
 
	/*
	$firstWeek = weeknumber($annee, $mois, 01);
	$jour=1;
	
	
	$aaa =fin_mois($mois, $annee);
	
	//$lastWeek  = weeknumber($annee, $mois, fin_mois($mois, $annee)); 
	for($i=$jour; $i<=$aaa; $i=$i+7){
	$j = $i+7;


		$semaine[] = weeknumber($annee, $mois, $i);
		if($i < $aaa && $j >$aaa ) {
			$semaine[] = weeknumber($annee, $mois, $aaa);
			//$semaine = array_slice($semaine, 1);
		}
		
		//echo "<br>".$i."----".fin_mois($mois, $annee)."<br>";
	} 
	//semaine 53 /d�cembre
	if(!empty($semaine[4]) && !empty($semaine[5]) && $semaine[4] == $semaine[5] && $semaine[4] == 1 && $semaine[3] == 52){
		$semaine[4] = 53;
	}
	
	$nbsemaine = count($semaine);
	$getdays = getDaysInWeek ($semaine[$nbsemaine-1], $annee);
	$nbdays = count($getdays);
	$tab = explode("-", $getdays[$nbdays-1]);
	$fin_mois = fin_mois($mois, $annee);
	if(($fin_mois>$tab[2]) && ($tab[1]==$mois)){
		
		
		if($mois<12) { $mois = $mois+1;
		$lundi = lund_mois($mois, $annee);
		if($lundi[0] != "7")	$semaine[] = weeknumber($annee, $mois, $i);
		}
		
		else { $mois = 01; $annee = $annee+1; }
		
	}
	$aTabFinal = array();
	if(is_array($semaine) && sizeof($semaine)>0){
		foreach ($semaine as $k=>$v){
			if(!in_array($v, $aTabFinal)){
				$aTab = getMonthByWeek($v, $annee);
				if(is_array($aTab) && sizeof($aTab) >0){
					$array1 = explode('-',$aTab[0]);
					$array2 = explode('-',$aTab[sizeof($aTab)-1]);
					if(sizeof($array1) > 2 && sizeof($array2)>2){
						if(($array1[1] == $mois && $array1[0] == $annee) or ($array2[1] == $mois && $array2[0] == $annee)){
							$aTabFinal[] = $v;
						}
					}else{
						$aTabFinal[] = $v;
					}
				}
			}
		}
	}*/
	return $aTabFinal;
}

function compareD($d1, $d2){
	$date1 = explode ( '-', $d1 );
	$date2 = explode ( '-', $d2 );
	if($date1[0].$date1[1].$date1[2] > $date2[0].$date2[1].$date2[2]){
		return true;
	}else{
		return false;
	}
}

function typeDate($date){
	$aDate = explode('-', $date);
	$m = $aDate[1];
	$y = $aDate[0];
	$d = $aDate[2];
	$tab_jours = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	return $tab_jours[date('w', mktime(0,0,0,$m,$d,$y))];
}

/**
 * lund_mois : recepurer les lundi du mois
 *
 * @param Integer $mois
 * @param Integer $an
 * @return mixed
 */
function lund_mois($mois, $an) {
	
	$nom_mois = array (1 => 'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre' );
	$nbj = date ( "t", mktime ( 0, 0, 0, $mois, 1, $an ) );
	$lundi = 1 - date ( "w", mktime ( 0, 0, 0, $mois, 1, $an ) ); // 
	if ($lundi <= 0) {
		$lundi = $lundi + 7;
	}
	$nblun = round ( $nbj / 7 );
	for($i = 0; $i <= $nblun; $i ++) {
		if ($lundi + 7 * $i <= $nbj) {
			$lund_mois [] = $lundi + 7 * $i;
		}
	}
	
	return ($lund_mois);
}


function getJours($datedeb,$datefin){
    $nb_jours=0;
    $dated=explode('-',$datedeb);
    $datef=explode('-',$datefin);
    $timestampcurr=mktime(0,0,0,$dated[1],$dated[2],$dated[0]);
    $timestampf=mktime(0,0,0,$datef[1],$datef[2],$datef[0]);
    while($timestampcurr<$timestampf){
 
      if((date('w',$timestampcurr)!=0)&&(date('w',$timestampcurr)!=6)){
        $nb_jours++;
      }
$timestampcurr=mktime(0,0,0,date('m',$timestampcurr),(date('d',$timestampcurr)+1)   ,date('Y',$timestampcurr));
 
    }
return $nb_jours;
}


/**
 * weeknumber retourne le nombre de semaine d'une date donnee (>1970, <2030)
 *
 * @param Integer $annee
 * @param Integer $mois
 * @param Integer $jour
 * @return Integer
 */
function weeknumber($annee, $mois, $jour) {
	/*$wn = strftime ( "%W", mktime ( 0, 0, 0, $m, $d, $y ) );
	$wn += 0; # wn might be a string value
	$firstdayofyear = getdate ( mktime ( 0, 0, 0, 1, 1, $y ) );
	if ($firstdayofyear ["wday"] != 1) # if 1/1 is not a Monday, add 1
		$wn += 1;
	return ($wn);*/
if (date("w",mktime(12,0,0,$mois,$jour,$annee))==0) // Dimanche
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-3*24*60*60;
    else if (date("w",mktime(12,0,0,$mois,$jour,$annee))<4) // du Lundi au Mercredi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)+(4-date("w",mktime(12,0,0,$mois,$jour,$annee)))*24*60*60;
    else if (date("w",mktime(12,0,0,$mois,$jour,$annee))>4) // du Vendredi au Samedi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-(date("w",mktime(12,0,0,$mois,$jour,$annee))-4)*24*60*60;
    else // Jeudi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee);
    
    // Definition du premier Jeudi de l'annee
    if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==0) // Dimanche
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+4*24*60*60;
    }
    else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))<4) // du Lundi au Mercredi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(4-date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine))))*24*60*60;
    }
    else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))>4) // du Vendredi au Samedi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(7-(date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))-4))*24*60*60;
    }
    else // Jeudi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine));
    }
        
    // Definition du numero de semaine: nb de jours entre "premier Jeudi de l'annee" et "Jeudi de la semaine";
    $numeroSemaine =     ( 
                    ( 
                        date("z",mktime(12,0,0,date("m",$jeudiSemaine),date("d",$jeudiSemaine),date("Y",$jeudiSemaine))) 
                        -
                        date("z",mktime(12,0,0,date("m",$premierJeudiAnnee),date("d",$premierJeudiAnnee),date("Y",$premierJeudiAnnee))) 
                    ) / 7 
                ) + 1;
    
    // Cas particulier de la semaine 53
    if ($numeroSemaine==53)
    {
        // Les annees qui commence un Jeudi et les annees bissextiles commencant un Mercredi en possède 53
        if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==4 || (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==3 && date("z",mktime(12,0,0,12,31,date("Y",$jeudiSemaine)))==365))
        {
            $numeroSemaine = 53;
        }
        else
        {
            $numeroSemaine = 1;
        }
    }
        
    //echo $jour."-".$mois."-".$annee." (".date("d-m-Y",$premierJeudiAnnee)." - ".date("d-m-Y",$jeudiSemaine).") -> ".$numeroSemaine."<BR>";      
    return ($numeroSemaine);
} # function weeknumber



/**
 * Calcule la date correspondante � partir de weeknumber
 *
 * @param Integer $y
 * @param Integer $w
 * @param Integer $o
 * @return String
 */
function datefromweek($y, $w, $o) {
	
	$days = ($w - 1) * 7 + $o;
	
	$firstdayofyear = getdate ( mktime ( 0, 0, 0, 1, 1, $y ) );
	
	if ($firstdayofyear ["wday"] == 0) {
		$firstdayofyear ["wday"] += 7;
	}
	if($firstdayofyear ["wday"] > 1 ) {
		$days = ($w) * 7 + $o;
	}
		
		# dans getdate, dimanche est 0 au lieu de 7
	$firstmonday = getdate ( mktime ( 0, 0, 0, 1, 1 - $firstdayofyear ["wday"] + 1, $y ) );
	$calcdate = getdate ( mktime ( 0, 0, 0, $firstmonday ["mon"], $firstmonday ["mday"] + $days, $firstmonday ["year"] ) );
	 
	$date ["year"] = $calcdate ["year"];
	$var = $calcdate ["mon"];
	$len_mois = strlen ( $var );
	if ($len_mois == 1) {
		$var = "0" . $var;
	}
	$date ["month"] = $var;
	$var_day = $calcdate ["mday"];
	$len_day = strlen ( $var_day );
	if ($len_day == 1) {
		$var_day = "0" . $var_day;
	}
	$date ["day"] = $var_day;
	return ($date);
	
} # function datefromweek 

/**
 * getDaysInWeek: les jours du weekend selectionnee
 *
 * @param Integer $weekNumber
 * @param Integer $year
 * @return mixed
 */
function getDaysInWeek ($weekNumber, $year) {


if($weekNumber < 10){
   $weekNumber = "0".$weekNumber;
}
$dayTimes = array ();
for($day=1; $day<=7; $day++)
{
    $dayTimes[] = date('Y-m-d', strtotime($year."W".$weekNumber.$day));
}
	// Count from '0104' because January 4th is always in week 1
	// (according to ISO 8601).
	/*$time = strtotime($year . '0104 +' . ($weekNumber - 1)
	. ' weeks');
	
	
	// Get the time of the first day of the week
	$mondayTime = strtotime('-' . 0 . ' days', $time) - (date('w', $time) - 1) * 86400;
	
	// Get the times of days 0 -> 6
	$dayTimes = array ();
	for ($i = 0; $i < 7; ++$i) {
	$dayTimes[] = date('Y-m-d', strtotime('+' . $i . ' days', $mondayTime));
	}*/
	// Return timestamps for mon-sun.
	return $dayTimes;
}


/**
 * getMonthByWeek: les jours du weekend selectionnee
 *
 * @param Integer $weekNumber
 * @param Integer $year
 * @return mixed
 */
function getMonthByWeek ($weekNumber, $year) {
	// Count from '0104' because January 4th is always in week 1
	// (according to ISO 8601).
	$time = strtotime($year . '0104 +' . ($weekNumber - 1)
	. ' weeks');
	
	
	// Get the time of the first day of the week
	$mondayTime = strtotime('-' . 0 . ' days', $time) - (date('w', $time) - 1) * 86400;
	
	$dayTimes = array ();
	for ($i = 0; $i < 7; ++$i) {
	$dayTimes[] = date('Y-m-d', strtotime('+' . $i . ' days', $mondayTime));
	}
	
	// Return timestamps for mon-sun.
	return $dayTimes;
}

/**
 * fonction de parcours d'un tableau date
 *
 * @param String $date
 * @return mixed
 */
function parcours_table($date) {
	foreach ( $date as $ligne => $valeur ) {
		$y = $date ['year'];
		$d = $date ['month'];
		$m = $date ['day'];
	}
	return array ($y, $d, $m );
}

//La date du fin d'un mois donn�
function fin_mois($mois, $annee) {
	$date_fin = date ( "t", mktime ( 0, 0, 0, $mois, 1, $annee ) ); // Affiche 31
	return ($date_fin);
}

//Le nom du mois
function nom_mois($i) {
	$mois = array ("Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "A&ocirc;ut", "Septembre", "Octobre", "Novembre", "D&eacute;cembre" );
	return ($mois [$i]);
}
// Update de la table imputation avec les test necessaires
function update_imputation($proj, $sous_taches, $newissue, $user, $date, $jour, $ancien_jour, $raf, $comment) {
	$group = membershipbase ( $user );
	$cond_som = 0;
	if (in_array ( "bd-multi-facturable", $group )) {
		$cond_som = 1;
	}
	if ($sous_taches == $newissue) {
		$select = "select round(sum(imputation),2) as som from imputation where Date = '$date' and (user=$user) and (issue != $sous_taches)";
		$select1 = mysql_query ( $select ) or die ( "erreur somme des imputations" );
		$som = mysql_fetch_row ( $select1 );
		
		if (($som [0] != "") and ($som [0] != 0)) {
			$ancien_imp = $som [0] + $jour;
		} else {
			$ancien_imp = $jour;
		}
		if (($ancien_imp > 1) and ($cond_som == 0)) {
			$somme = "faux";
			$input = "vrai";
			//echo "impossible";
		} else {
			$select2 = "SELECT * 
			   FROM imputation 
			   WHERE Date = '$date' 
			   		 AND (user=$user) 
					 AND (issue = $sous_taches) 
					 AND (project = $proj)";
			$sql = mysql_query ( $select2 ) or die ( "erreur selection requete1" );
			$nb = mysql_num_rows ( $sql );

			while ( $t = mysql_fetch_row ( $sql ) ) {
				$id_imp = $t [0];
				$imp = $t [5];
			}
			@$nlle_impu = $jour;
			//$nlle_impu;
			if ($nb == 0) {
				if (($jour != 0) or ($ancien_jour != 0)) {
					$role = getDefaultRole ( $proj, $user );
					//$fact = getFacturation ( $proj );
					$fact = 0;
					$requete1 = "INSERT INTO imputation  
		VALUES('', $proj, $sous_taches, $user, '$date', $jour, '$raf','$fact','$comment','" . date ( 'Y-m-d G:i:s' ) . "','','',$role
		)";
					if (! $req1 = mysql_query ( $requete1 )) {
						$error = "Erreur d'insertion d'imputation! v�rifiez que vous avez un r�le par d�faut pour le projet en cours";
					}
					//echo "insertion r�ussie"; 
				}
				$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
				$req_custom = mysql_query ( $select_custom );
				while ( $cus = mysql_fetch_row ( $req_custom ) ) {
					$id_custom = $cus [0];
					@$number = $cus [5];
				}
				@$nouv_number = ($number) + ($jour);
				$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)";
				$req_f = mysql_query ( $req_field );
				
				$somme = "vrai";
				$input = "faux";
			} else {
				$requete = "UPDATE imputation SET imputation = '" . $nlle_impu . "', issue=" . $sous_taches . ", RAF = '$raf',  commentaire = '$comment', Date_imputation = '" . date ( 'Y-m-d G:i:s' ) . "' WHERE ID = " . $id_imp;
				$req = mysql_query ( $requete ) or die ( "erreur update requete3" );
				$somme = "vrai";
				$input = "faux";
				//echo "mise � jour r�ussite";
				$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
				$req_custom = mysql_query ( $select_custom );
				while ( $cus = mysql_fetch_row ( $req_custom ) ) {
					$id_custom = $cus [0];
					@$number = $cus [5];
				}
				@$ajou = ($jour) - ($ancien_jour);
				@$nouv_number = ($number) + ($ajou);
				$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)";
				$req_f = mysql_query ( $req_field );
			
			}
		}
	
	} else // le else ca veux dire qu'on a chang� la valeur de la tache
{
		//Selection de la ligne avec l'ancienne sous taches
		//echo "Ancienne valeur &nbsp;&nbsp;".$sous_taches."Nouvelle valeur &nbsp;&nbsp;".$newissue;
		$ancien = "select * from imputation where Date = '$date' and (user=$user) and (issue = $sous_taches) and (project = $proj)";
		$req_ancien = mysql_query ( $ancien ) or die ( "erreur" );
		$nb_ancien = mysql_num_rows ( $req_ancien );
		//Selection de la ligne avec la nouvelle sous taches
		$nouv = "select * from imputation where Date = '$date' and (user=$user) and (issue = $newissue) and (project = $proj)";
		$req_nouv = mysql_query ( $nouv ) or die ( "erreur" );
		$nb_nouv = mysql_num_rows ( $req_nouv );
		while ( $tab = mysql_fetch_row ( $req_nouv ) ) {
			$nouvid_imp = $tab [0];
			$nouv_imp = $tab [5];
			$ajou = ($jour) + ($nouv_imp);
		}
		
		//Pas de valeur pour une ancienne imputation
		if ($nb_ancien == 0) {
			if ($nb_nouv == 0) {
				if ($jour != 0) {
					$role = getDefaultRole ( $proj, $user );
					//$fact = getFacturation ( $proj );
					$fact = 0;
					$requete = "INSERT INTO imputation VALUES ('', '$proj', '$newissue', '$user', '$date', '$jour', '$raf','$fact','$comment','" . date ( 'Y-m-d G:i:s' ) . "','','',$role)";
					if (! $req = mysql_query ( $requete )) {
						$error = "Erreur d'insertion d'imputation! v�rifiez que vous avez un r�le par d�faut pour le projet en cours";
					}
				}
				$select_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
				$req_custom = mysql_query ( $select_custom );
				while ( $cus = mysql_fetch_row ( $req_custom ) ) {
					$id_custom = $cus [0];
					@$number = $cus [5];
				}
				@$nouv_number = $number + $jour;
				$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $newissue) and (customfield = 10020)";
				$req_f = mysql_query ( $req_field );
				
				$input = "faux";
				$somme = "vrai";
			} else {
				if (($ajou > 1) and ($cond_som == 0)) {
					$input = "vrai";
					$somme = "faux";
				} else {
					$requete = "UPDATE imputation SET issue = $newissue, imputation = '$ajou', RAF ='$raf', commentaire = '$comment', Date_imputation = '" . date ( 'Y-m-d G:i:s' ) . "' WHERE ID =$nouvid_imp";
					$req = mysql_query ( $requete );
					$select_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
					$req_custom = mysql_query ( $select_custom );
					while ( $cus = mysql_fetch_row ( $req_custom ) ) {
						$id_custom = $cus [0];
						@$number = $cus [5];
					}
					@$nouv_number = $number + $jour;
					$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $newissue) and (customfield = 10020)";
					$req_f = mysql_query ( $req_field );
					$input = "faux";
					$somme = "vrai";
				}
			}
		} else {
			while ( $tab1 = mysql_fetch_row ( $req_ancien ) ) {
				$ancienid_imp = $tab1 [0];
				$ancien_imp = $tab1 [5];
			}
			if ($nb_nouv == 0) {
				$requete = "Update imputation set issue=$newissue, imputation = '$jour', RAF = '$raf', commentaire = '$comment', Date_imputation = '" . date ( 'Y-m-d G:i:s' ) . "' where ID = $ancienid_imp";
				$req = mysql_query ( $requete );
				$input = "faux";
				$somme = "vrai";
				$ancien_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
				$req_custom = mysql_query ( $ancien_custom );
				while ( $a_cus = mysql_fetch_row ( $req_custom ) ) {
					@$ancien_number = $a_cus [5];
				}
				@$nouv_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
				$reqn_custom = mysql_query ( $nouv_custom );
				while ( $cus = mysql_fetch_row ( $reqn_custom ) ) {
					@$nouv_number = $cus [5];
				}
				@$ajou_number = ($nouv_number) + ($jour);
				@$del_number = ($ancien_number) - ($ancien_jour);
				
				$ajou_field = "UPDATE customfieldvalue SET numbervalue = '$ajou_number' where (issue = $newissue) and (customfield = 10020)";
				$ajou_f = mysql_query ( $ajou_field );
				
				$del_field = "UPDATE customfieldvalue SET numbervalue = '$del_number' where (issue = $sous_taches) and (customfield = 10020)";
				$del_f = mysql_query ( $del_field );
			} else {
				if (($ajou > 1) and ($cond_som == 0)) {
					$input = "vrai";
					$somme = "faux";
				} else {
					$requete = "UPDATE imputation SET issue = $newissue, imputation = '$ajou', RAF = '$raf', commentaire = '$comment', Date_imputation = '" . date ( 'Y-m-d G:i:s' ) . "' WHERE ID =$nouvid_imp";
					$req = mysql_query ( $requete );
					$delete = "Delete from imputation where ID = $ancienid_imp";
					mysql_query ( "Delete from worklog where ID = $ancienid_imp" );
					$req = mysql_query ( $delete );
					$ancien_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
					$req_custom = mysql_query ( $ancien_custom );
					while ( $a_cus = mysql_fetch_row ( $req_custom ) ) {
						$ancien_number = $a_cus [5];
					}
					$nouv_custom = "select * from customfieldvalue where (issue = $newissue) and (customfield = 10020)";
					$reqn_custom = mysql_query ( $nouv_custom );
					while ( $cus = mysql_fetch_row ( $reqn_custom ) ) {
						$nouv_number = $cus [5];
					}
					$ajou_number = ($nouv_number) + ($jour);
					$del_number = ($ancien_number) - ($ancien_jour);
					
					$ajou_field = "UPDATE customfieldvalue SET numbervalue = '$ajou_number' where (issue = $newissue) and (customfield = 10020)";
					$ajou_f = mysql_query ( $ajou_field );
					
					$del_field = "UPDATE customfieldvalue SET numbervalue = '$del_number' where (issue = $sous_taches) and (customfield = 10020)";
					$del_f = mysql_query ( $del_field );
					
					$input = "faux";
					$somme = "vrai";
				}
			
			}
		
		}
	}
	
	$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
	$del = mysql_query ( $delete );
	return array ('somme' => $somme, 'input' => $input, 'error' => @$error );
}

/**
 * updateCollab : update collabs
 *
 * @param Integer $id
 * @param Integer $status
 * @return booleen
 */
function updateCollab($id,$status){
	$sql = "delete from userbasestatus where user=".$id;
	$del = mysql_query ( $sql );
	$sql = "insert into userbasestatus values ('',$id,$status)";
	$insert = mysql_query ( $sql );
	return true;
}

/**
 * updategroupB: update groups
 *
 * @param Integer $id
 * @param Integer $status
 * @return booleen
 */
function updategroupB($id,$status){
	$sql = "delete from groupbasestatus where 	id =".$id;
	$del = mysql_query ( $sql );
	$sql = "insert into groupbasestatus values ($id,$status)";
	$insert = mysql_query ( $sql );
	return true;
}

/**
 * isvalideProject
 *
 * @param Integer $proj
 * @param Integer $iduser
 * @return Integer
 */
function isvalideProject($proj,$iduser){
	$sql = "select * from tetd_project_validate where project=$proj AND user=$iduser";
	
	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb = mysql_num_rows ( $exec );
	
	return $nb;
}


/**
 * isvalideGroup
 *
 * @param Integer $group
 * @param Integer $iduser
 * @return Integer
 */
function isvalideGroup($proj,$iduser){
	$sql = "select * from tetd_group_validate where groupe=$proj AND user=$iduser";
	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb = mysql_num_rows ( $exec );
	
	return $nb;
}

/**
 * isvalideIssue
 *
 * @param Integer $issue
 * @return Integer
 */
function isvalideIssue($issue){
	$sql = "select * from imputation_validate where issue=$issue";
	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb = mysql_num_rows ( $exec );
	return $nb;
}

/**
 * isvalideIssue_f
 *
 * @param integer $issue
 * @return integer
 */
function isvalideIssue_f($issue){
	$sql = "select validation from imputation where ID='".$issue."' and (validation >=1 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";
	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb = mysql_num_rows ( $exec );
	return $nb;
}

/**
 * isvalideIssueP : is valide issue (tache)
 *
 * @param integer $proj
 * @param String $date
 * @param integer $user
 * @return integer
 */
function isvalideIssueP($proj, $date, $user){
	
	$sql = "select ID from imputation where Project='$proj' and date ='$date' and user='$user'";

	$exec = mysql_query ( $sql ) or die ( "error" );
	
	while ( $tab = mysql_fetch_row ( $exec ) ) {
		$sql2 = "select validation from imputation where ID='".$tab [0]."' and (validation >=1 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";
		$res =  mysql_query ( $sql2 ) or die ( "error" );
		$nb = mysql_num_rows ( $res );
		if($nb > 0){
			$verif = 1;
		}else{
			$verif = 0;
		}
		
	}
	return $verif;
}

/**
 *getTotNonValide: imputation non valide
 *
 * @param Integer $user
 * @param String $date1
 * @param String $date2
 * @return Double
 */
function getTotNonValide($user, $date1, $date2, $proj =""){
	$sql = "select round(sum(imputation),2) as som from imputation where date >='$date1' and date <='$date2' and user='$user' and (validation = 0 and (Date_validation ='0000-00-00' or Date_validation IS NULL))";

	if($proj !=""){
		$sql .=" and project ='$proj'";
	}
	
	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb1 = mysql_num_rows ( $exec );
	
	if ($nb1 > 0) {
		$som = mysql_result ( $exec, 0, 'som' );
	} else {
		$som = 0;
	}
	
	if($som == 'NULL' || $som == NULL){
		$som = 0;
	}
	
	return ($som);
	
}


/**
 *getTotNonValide: imputation non valide
 *
 * @param Integer $user
 * @param String $date1
 * @param String $date2
 * @return Double
 */
function getTotValide($user, $date1, $date2, $proj =""){
	$sql = "select round(sum(imputation),2) as som from imputation where date >='$date1' and date <='$date2' and user='$user' and (validation != 0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	if($proj !=""){
		$sql .=" and project ='$proj'";
	}

	$exec = mysql_query ( $sql ) or die ( "error" );
	$nb1 = mysql_num_rows ( $exec );
	
	if ($nb1 > 0) {
		$som = mysql_result ( $exec, 0, 'som' );
	} else {
		$som = 0;
	}
	
	if($som == 'NULL' || $som == NULL){
		$som = 0;
	}
	return ($som);
	
}
/**
 * isvalideIssueAll
 *
 * @param String $date
 * @param Integer $user
 * @return Integer
 */
function isvalideIssueAll( $date, $user){

	$sql = "select round(sum(imputation),2) as som from imputation where date ='$date' and user='$user' and (validation >0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	$exec = mysql_query ( $sql ) or die ( "error" );
	$tab = mysql_fetch_row ( $exec );
	
	
	$verif = 1;
	if($tab){
		if($tab[0] >= 1){
			$verif = 0;
		}
	}
	
	return $verif;
}

/**
 * isvalideIssueAllNonImp
 *
 * @param String $date
 * @param Integer $user
 * @return Integer
 */

function isvalideIssueAllNonImp( $date, $user){

	$sql = "select imputation as som from imputation where date ='$date' and user='$user' and (validation >0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	$exec = mysql_query ( $sql ) or die ( "error" );
	$tab = mysql_fetch_row ( $exec );
	$nb = mysql_num_rows ( $exec );
	
	
	$verif = 1;
	if($tab){
		if($nb > 0){
			$verif = 0;
		}
	}
	
	return $verif;
}


/**
 * verifValideImp
 *
 * @param String $date
 * @param String $date
 * @param Integer $user
 * @param Integer issue
 * @return Integer
 */

function verifValideImp( $date1,$date2, $user, $issue){

	$sql = "select ID from imputation where date >='$date1' and date <='$date2' and issue='$issue' and user='$user' and (validation >0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	$exec = mysql_query ( $sql ) or die ( "error" );
	$tab = mysql_fetch_row ( $exec );
	$nb = mysql_num_rows ( $exec );
	
	$sql2 = "select ID from imputation where date >='$date1' and date <='$date2' and issue='$issue' and user='$user' and (validation =0 and ( (Date_validation ='0000-00-00' or Date_validation IS NOT NULL)  and Date_validation IS NOT NULL))";

	$exec2 = mysql_query ( $sql2 ) or die ( "error" );
	$tab2 = mysql_fetch_row ( $exec2 );
	$nb2 = mysql_num_rows ( $exec2 );
	
	$verif = false;
	if($tab){
		if($nb > 0){
			$verif = true;
		}
		
	}
	if($tab2){
		if($nb2 > 0){
			$verif = false;
		}
	}
	
	return $verif;
}


/**
 * verifImpIsValide
 *
 * @param Integer $idImp
 * @return booleen
 */
function verifImpIsValide( $idImp){

	$sql = "select imputation as som from imputation where ID='$idImp' and (validation >0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	$exec = mysql_query ( $sql ) or die ( "error" );
	$tab = mysql_fetch_row ( $exec );
	$nb = mysql_num_rows ( $exec );
	
	$verif = false;
	if($tab){
		if($nb > 0){
			$verif = true;
		}
	}
	
	return $verif;
}

/**
 * verifImpIsValideDate
 *
 * @param String $date
 * @param Integer $user
 * @return Integer
 */
function verifImpIsValideDate( $date, $user){

	$sql = "select imputation as som from imputation where Date='$date' and user='$user' and (validation >0 or (Date_validation !='0000-00-00' and Date_validation IS NOT NULL))";

	$exec = mysql_query ( $sql ) or die ( "error" );
	$tab = mysql_fetch_row ( $exec );
	$nb = mysql_num_rows ( $exec );
	
	$verif = false;
	if($tab){
		if($nb > 0){
			$verif = true;
		}
	}
	
	return $verif;
}

/**
 * isnvalideIssueAll
 *
 * @param String $date
 * @param Integer $user
 * @return Integer
 */
function isnvalideIssueAll( $date, $user){

	
	
	$sql2 = "select round(sum(imputation),2) as som from imputation where date ='$date' and user='$user'  and (validation = 0 and (Date_validation ='0000-00-00' or Date_validation IS NULL)) ";

	$exec2 = mysql_query ( $sql2 ) or die ( "error" );
	$tab2 = mysql_fetch_row ( $exec2 );
	
	$verif = 1;
	if($tab2){
		if($tab2[0] <= 0){
			$verif = 0;
		}
	}
	
	return $verif;
}

/**
 * isImputIssueP
 *
 * @param String $date
 * @param Integer $user
 * @return Integer
 */
function isImputIssueP($date, $user){
	$select = "select distinct(ID) as som from imputation where date = '$date' and (user=$user)";
	$select1 = mysql_query ( $select ) or die ( "erreur somme des imputations" );
	$som = mysql_fetch_row ( $select1 );
	
	if($som && isset($som[0]) && $som[0] >=1){
		return 1;
	}else{
		return 0;
	}

}


/******************* Fonction pour l'insertion des imputations**********************************************/
function insert_imputation($proj, $sous_taches, $user, $date, $jour, $raf, $comment) {
	$group = membershipbase ( $user );
	$cond_som = 0;
	if (in_array ( "bd-multi-facturable", $group )) {
		$cond_som = 1;
	}
	if ($jour != 0) {
		$select = "select round(sum(imputation),2) as som from imputation where Date = '$date' and (user=$user)";
		$select1 = mysql_query ( $select ) or die ( "erreur somme des imputations" );
		$som = mysql_fetch_row ( $select1 );
		
		if ($som [0] == "") {
			$n_imp = $jour;
		} else {
			$n_imp = $som [0] + $jour;
		}
		if (($n_imp > 1) and ($cond_som == 0)) {
			$somme = "faux";
			$input = "vrai";
		} else {
			$select2 = "select * from imputation where Date = '$date' and (user=$user) and (issue = $sous_taches)";
			$sql = mysql_query ( $select2 ) or die ( "V�rifier la date, le user et la tache" );
			$nb = mysql_num_rows ( $sql );
			while ( $t = mysql_fetch_row ( $sql ) ) {
				$id_imp = $t [0];
				$imp = $t [5];
			}
			@$nlle_impu = @$imp + $jour;
			if ($nb == 0) {
				$role = getDefaultRole ( $proj, $user );
				//$fact = getFacturation ( $proj );
				$fact = 0;
				$requete1 = "INSERT INTO imputation  
	VALUES('', $proj, $sous_taches, $user, '$date', $jour, '$raf','$fact','$comment','" . date ( 'Y-m-d G:i:s' ) . "','','',$role)";
				
				if (! $req1 = mysql_query ( $requete1 )) {
					$error = "Erreur d'insertion d'imputation! v�rifiez que vous avez un r�le par d�faut pour le projet en cours";
				}
				$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
				$req_custom = mysql_query ( $select_custom );
				while ( $cus = mysql_fetch_row ( $req_custom ) ) {
					$id_custom = $cus [0];
					$number = $cus [5];
				}
				$nouv_number = @$number + $jour;
				$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)";
				$req_f = mysql_query ( $req_field );
			
			} else {
				$requete = "UPDATE imputation SET imputation = $nlle_impu, commentaire = '$comment', Date_imputation = '" . date ( 'Y-m-d G:i:s' ) . "' WHERE ID = $id_imp";
				$req = mysql_query ( $requete ) or die ( "erreur update requete3" );
				$select_custom = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10020)";
				$req_custom = mysql_query ( $select_custom );
				while ( $cus = mysql_fetch_row ( $req_custom ) ) {
					$id_custom = $cus [0];
					$number = $cus [5];
				}
				$nouv_number = $number + $jour;
				$req_field = "UPDATE customfieldvalue SET numbervalue = '$nouv_number' where (issue = $sous_taches) and (customfield = 10020)";
				$req_f = mysql_query ( $req_field );
			
			}
			$somme = "vrai";
			$input = "faux";
		}
	
	} else {
		$somme = "vrai";
		$input = "faux";
	}
	$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
	$del = mysql_query ( $delete );
	
	return array ('somme' => $somme, 'input' => $input, 'error' => @$error );
}


/*********************** fonction de rappel des imputations*/
function requeteselection($d1, $d2, $id_tache, $user) {
	$requete = "SELECT imputation. * , project.pname, jiraissue.summary
FROM imputation, jiraissue, userbase, project
WHERE imputation.Project = project.ID
AND imputation.issue = jiraissue.ID
and imputation.issue=" . $id_tache . "
AND imputation.user =" . $user . "
AND imputation.user = userbase.id
AND imputation.DATE BETWEEN '" . $d1 . "' AND '" . $d2 . "'
 ";
	$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
	$del = mysql_query ( $delete );
	return @$requete;
}

/***********************              MEDINI MOUNIRA Le 26-06-2008      ************************/
/********************     Requete simplifi�e de la fonction resueteselection   ************************/
function requeteselection2($d1, $d2, $user) {
	$requete = "SELECT ID, Project, issue, user, Date, imputation, RAF, commentaire
		   FROM imputation
		   WHERE user =" . $user . "
				 AND DATE BETWEEN '" . $d1 . "' AND '" . $d2 . "'
 			";
	
	$query = mysql_query ( $requete );
	$delete = "Delete from imputation where (imputation = '0.00') and (RAF = '0.00') and (commentaire = '')";
	$del = mysql_query ( $delete );
	$Timp = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$Timp [$row ['2']] [$row ['3']] [$row ['4']] = $row ['5'];
	}
	return ($Timp);
}

/************************************************************************************************/
//fonction qui calcul le total des imputations par date
function total_imputation($date, $user) {
	$sql = "SELECT sum( imputation ) FROM imputation WHERE Date = '$date' AND user =$user";
	$query = mysql_query ( $sql );
	$exe = mysql_fetch_row ( $query );
	return ($exe);
	//print_r ($exe);
}


function valideIssue($id, $login){
$date = date("Y-m-d H:i:s");
	$sql = "delete from imputation_validate where issue=".$id;
	$del = mysql_query ( $sql );
	$sql = "insert into imputation_validate values ('',$id,'$login','$date')";
	$insert = mysql_query ( $sql );
	return true;
}
//fonction affiche un tableau d'alerte
function tab_alerte($chaine) {
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
                            <TD vAlign=top width="80%" bgColor=#EFEFEF align="center"><font color="#CC0000"><b>' . $chaine . '</b></font></TD>
                          </TR></TBODY></TABLE></TD>
                  </TBODY></TABLE></TD></TR></TBODY></TABLE>';

}

//fonction retourne le nom et le pr�nom d'un user � partir de son ID
function nom_prenom_user($id) {
	$user = "SELECT propertystring . * 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'fullname'
AND propertyentry.entity_id = userbase.id
AND userbase.id =$id";
	$result = mysql_query ( $user );
	$nb = mysql_num_rows ( $result );
	if ($nb != 0) {
		$nom = mysql_result ( $result, 0, 'propertyvalue' );
		return ($nom);
	} else
		return (false);
}




//fonction retourne l'email d'un user � partir de son ID
function email($id) {
	$user = "SELECT propertystring . * 
FROM propertystring, propertyentry, userbase
WHERE propertystring.id = propertyentry.id
AND propertyentry.property_key = 'email'
AND propertyentry.entity_id = userbase.id
AND userbase.id =$id";
	$result = mysql_query ( $user );
	$nb = mysql_num_rows ( $result );
	if ($nb != 0) {
		$email = mysql_result ( $result, 0, 'propertyvalue' );
		return ($email);
	} else
		return (false);
}

//fonction retourne les status traduit
function status($id_status) {
	$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'Status'
AND propertyentry.ENTITY_ID = $id_status
";
	$result = mysql_query ( $user );
	$nb = mysql_num_rows ( $result );
	if ($nb != 0) {
		$stat = mysql_result ( $result, 0, 'propertyvalue' );
		return ($stat);
	} else
		return (false);
}

//fonction retourne les priorit�s traduites
function priority($id_priority) {
	$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'Priority'
AND propertyentry.ENTITY_ID = $id_priority
";
	$result = mysql_query ( $user );
	$nb = mysql_num_rows ( $result );
	if ($nb != 0) {
		$prio = mysql_result ( $result, 0, 'propertyvalue' );
		return ($prio);
	} else
		return (false);
}

//fonction retourne les types traduits
function type($id_type) {
	$user = "SELECT DISTINCT (
propertystring.ID
), propertystring. * , propertyentry. * 
FROM propertystring, propertyentry
WHERE propertystring.id = propertyentry.id
AND propertyentry.ENTITY_NAME = 'IssueType'
AND propertyentry.ENTITY_ID = $id_type";
	$result = mysql_query ( $user );
	$nb = mysql_num_rows ( $result );
	if ($nb != 0) {
		$type = mysql_result ( $result, 0, 'propertyvalue' );
		return ($type);
	} else
		return (false);
}

//fonction qui cr� un tableau vide en fait c'est pour l'espace vertical
function tab_vide($height) {
	echo '<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center 
            border=0>
              <TR>
                <TD vAlign=top width="100%" colSpan=2><img src="../images/spacer.gif" width="1" height="' . $height . '" /></TD>
              </TR></TABLE>';
}

//fonction fixe le format d'un nbre
function format_number($str, $decimal_places = '2', $decimal_padding = "0") {
	/* firstly format number and shorten any extra decimal places */
	/* Note this will round off the number pre-format $str if you dont want this fucntionality */
	$str = number_format ( $str, $decimal_places, '.', '' ); // will return 12345.67
	$number = explode ( '.', $str );
	$number [1] = (isset ( $number [1] )) ? $number [1] : ''; // to fix the PHP Notice error if str does not contain a decimal placing.
	$decimal = str_pad ( $number [1], $decimal_places, $decimal_padding );
	return ( float ) $number [0] . '.' . $decimal;
}

/************************************ Format des nombres � afficher sur excel ******************************/
function format_number_excel($str) {
	$str = sprintf ( '%.2f', $str );
	$str = str_replace ( ".", ",", $str );
	return ($str);
}

/******************************** Fonction qui retourne les pkey d'un projet donn�  ***************************/
function pkey_proj($id) {
	$pkey = "select pkey from project where ID = $id";
	$query_pkey = mysql_query ( $pkey );
	$nb = mysql_num_rows ( $query_pkey );
	if ($nb != 0) {
		$key = mysql_result ( $query_pkey, 0, 'pkey' );
		return ($key);
	} else
		return (false);
}

/******************************** Fonction qui retourne les login d'un user donn�  ***************************/
function login_user($id) {
	$select_login = "select username from userbase where ID = '$id'";
	$query_login = mysql_query ( $select_login );
	$nb = mysql_num_rows ( $query_login );
	if ($nb != 0) {
		$log = mysql_result ( $query_login, 0, 'username' );
		return ($log);
	} else
		return (false);
}

/**
 * id_user: get id user
 *
 * @param string $login_user
 * @return integer/booleen
 */
function id_user($login_user) {
	$select_id = "select ID from userbase where username = '" . $login_user . "'";
	$query_id = mysql_query ( $select_id ) or die ( "error" );
	$nb = mysql_num_rows ( $query_id );
	if ($nb != 0) {
		$id = mysql_result ( $query_id, 0, 'ID' );
		return ($id);
	} else
		return (false);
}

//fonction fixe le format d'une date et la rendre selon notre format dans la base
function format_date($date) {
	$date = explode ( '/', $date );
	$date_format = "";
	if(sizeof($date) > 2){
		$date_format = $date [2] . "-" . $date [1] . "-" . $date [0];
	}
	return ($date_format);
}

//fonction fixe le format d'une date et la rendre selon notre format dans la base
function format_date2($date) {
	$date = explode ( '-', $date );
	$date_format2 = "";
	if(sizeof($date) > 2){
		$date_format2 = $date [2] . "-" . $date [1] . "-" . $date [0];
	}
	return ($date_format2);
}
/************************fonction qui retourne le samedies et les dimanches d'un moi donn�********/
/************************Rihab  ben smida le 18/04/2007*******************************************/

function sam_mois($mois, $an) {
	
	$nom_mois = array (1 => 'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre' );
	$nbj = date ( "t", mktime ( 0, 0, 0, $mois, 1, $an ) );
	//les samedi du mois
	$samedi = 7 - date ( "w", mktime ( 0, 0, 0, $mois, 1, $an ) ); //Samedi 7 
	$nbsam = round ( $nbj / 7 );
	for($i = 0; $i <= $nbsam; $i ++) {
		if ($samedi + 7 * $i <= $nbj) {
			$sam_mois [] = $samedi + 7 * $i;
		}
	}
	return ($sam_mois);
}
//echo 'Les samedi du mois de ',$nom_mois[$mois],' ',$an,' sont :<pre>'; 
//print_r($sam_mois); 


//les dimanche du mois
function dim_mois($mois, $an) {
	
	$nom_mois = array (1 => 'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre' );
	$nbj = date ( "t", mktime ( 0, 0, 0, $mois, 1, $an ) );
	$dimanche = 1 - date ( "w", mktime ( 0, 0, 0, $mois, 1, $an ) ); // 
	if ($dimanche <= 0) {
		$dimanche = $dimanche + 7;
	}
	$nbdim = round ( $nbj / 7 );
	for($i = 0; $i <= $nbdim; $i ++) {
		if ($dimanche + 7 * $i <= $nbj) {
			$dim_mois [] = $dimanche + 7 * $i;
		}
	}
	
	return ($dim_mois);
}


/**
 * insert_pourcent_prev
 *
 * @param integer $sous_taches
 */
function insert_pourcent_prev($sous_taches) {
	
	$select_prev = "SELECT (c1.numbervalue / c2.numbervalue)*100 AS prev
					FROM customfieldvalue c1, customfieldvalue c2
					WHERE c1.customfield =10021 AND c2.customfield =10000 AND c1.issue = $sous_taches AND c2.issue = $sous_taches";
	$query_prev = mysql_query ( $select_prev );
	$nbp = mysql_num_rows ( $query_prev );
	
	if ($nbp > 0) {
		$prevv = mysql_result ( $query_prev, 0, 'prev' );
		$prevv = format_number ( $prevv );
		$select_cus = "select * from customfieldvalue where (issue = $sous_taches) and (customfield = 10082)";
		$sql_cus = mysql_query ( $select_cus );
		$nb_cus = mysql_num_rows ( $sql_cus );
		$update_prev = "UPDATE `customfieldvalue` SET `PARENTKEY` = NULL ,
	`STRINGVALUE` = NULL ,
	`NUMBERVALUE` = '$prevv',
	`TEXTVALUE` = NULL ,
	`DATEVALUE` = NULL ,
	`VALUETYPE` = NULL where (issue = $sous_taches) and (customfield = 10082)";
		$query_up_prev = mysql_query ( $update_prev );
	}
}

/*********   Fonction pour v�rifier si le jour f�ri� choisi est d�ja ds la base ou pas   **************/

function verif_jour_fer($sous_taches, $date) {
	
	$j_f = "select SUMMARY from jiraissue where jiraissue.id = $sous_taches";
	$q_j_f = mysql_query ( $j_f );
	$summ = mysql_result ( $q_j_f, 0, 'SUMMARY' );
	//$nb_j_f = mysql_num_rows($q_j_f);
	if ($summ == 'Jour f�ri�') {
		$sql_fer = "select Date from jour_ferie where Date like '$date' ";
		$query_fer = mysql_query ( $sql_fer );
		$nb_fer = mysql_num_rows ( $query_fer );
		return ($nb_fer);
	} else {
		$nb = 1;
		return ($nb);
	}
}
/*********   Fonction ID des demandes d'absences   *****************/

function id_proj($proj) {
	$pname_proj = "select ID from project where pname like 'Demandes d\'absence'";
	$query_pname = mysql_query ( $pname_proj ) or die ( "erreur" );
	$nb = mysql_num_rows ( $query_pname );
	if ($nb != 0) {
		$pname_id = mysql_result ( $query_pname, 0, 'ID' );
		return ($pname_id);
	} else
		return (false);
}
/*********   Cette fonction envoie "True" si une date est valide et dans le cas contraire "False".******************/
function verif_date($Date) {
	$date1 = explode ( '/', $Date );
	if ((count ( $date1 ) == 3) and ((is_numeric ( $date1 [0] )) and (is_numeric ( $date1 [1] )) and (is_numeric ( $date1 [2] )))) {
		$valide = checkdate ( $date1 [1], $date1 [0], $date1 [2] );
		if ($valide == 1) {
			return ("true");
		} else {
			return ("false");
		}
	} else {
		return ("false");
	}
}

/**
 * nbre_impu
 *
 * @param Integer $tache
 * @param booleen $verif_date1
 * @param String $Date
 * @param booleen $verif_date2
 * @param String $Date2
 * @param Integer $user
 * @return Integer
 */
function nbre_impu($tache, $verif_date1, $Date, $verif_date2, $Date2, $user) {
	$select = "SELECT 
				imputation.imputation
		   From imputation
		   WHERE imputation.issue = " . $tache;
	
	if ((isset ( $user )) and ($user != 'faux')) {
		$select .= " AND imputation.user = '$user' ";
	}
	
	if ((isset ( $verif_date1 )) and ($verif_date1 != 'false')) {
		$select .= " AND (imputation.Date) >=  '$Date' ";
	}
	if ((isset ( $verif_date2 )) and ($verif_date2 != 'false')) {
		$select .= " AND (imputation.Date) <=  '$Date2' ";
	}
	//echo $select;
	$query = mysql_query ( $select );
	$nb = mysql_num_rows ( $query );
	return ($nb);
}

/**
 * nbre_task: nombre de tache
 *
 * @param Integer $project
 * @param Integer $idcollab
 * @return Integer
 */
function nbre_task($project, $idcollab) {
	$select = "SELECT DISTINCT (issue)
		   FROM imputation
		   WHERE PROJECT =$project
		   AND user =$idcollab";
	
	$query = mysql_query ( $select );
	//echo $select;
	$num = mysql_num_rows ( $query );
	$task = mysql_fetch_array ( $query );
	return ($num);
}

/**
 * select_imp
 *
 * @param Integer $issue
 * @param String $username
 * @param booleen $verif_date1
 * @param String $Date
 * @param booleen $verif_date2
 * @param String $Date2
 * @param Integer $facturation
 * @return Integer
 */
function select_imp($issue, $username, $verif_date1, $Date, $verif_date2, $Date2, $facturation = -1) {
	$select_imp = "SELECT DISTINCT(ID),
					  imputation.Date,
					  imputation,
					  RAF, imputation.user, 
					  imputation.role
			   FROM imputation
			   WHERE issue = $issue
";
	if ((isset ( $username )) and ($username != 'faux')) {
		$select_imp .= " AND user = $username ";
	}
	if ((isset ( $verif_date1 )) and ($verif_date1 != 'false')) {
		$select_imp .= " AND (imputation.Date) >=  '$Date' ";
	}
	if ((isset ( $verif_date2 )) and ($verif_date2 != 'false')) {
		$select_imp .= "AND (imputation.Date) <=  '$Date2' ";
	}
	if (isset ( $facturation ) && $facturation >= 0) {
		$select_imp .= "AND imputation.validation = " . $facturation . " ";
	}
	$select_imp .= "ORDER BY user, imputation.Date";
	$query_imp = mysql_query ( $select_imp );
	return ($query_imp);
}

/**
 * select_task
 *
 * @param Integer $proj
 * @param Integer $collab
 * @param booleen $verif_date1
 * @param String $Date
 * @param booleen $verif_date2
 * @param String $Date2
 * @return String
 */
function select_task($proj, $collab, $verif_date1, $Date, $verif_date2, $Date2) {
	$select_issue = "SELECT 
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
	if ((isset ( $verif_date1 )) and ($verif_date1 != 'false')) {
		$select_issue .= "AND (imputation.Date) >=  '$Date' ";
	}
	if ((isset ( $verif_date2 )) and ($verif_date2 != 'false')) {
		$select_issue .= "AND (imputation.Date) <=  '$Date2' ";
	}
	$select_imp = $select_issue . "GROUP BY imputation.ID 
				ORDER BY jiraissue.pkey";
	$select_issue .= "GROUP BY jiraissue.ID 
				ORDER BY jiraissue.pkey";
	$query_issue = mysql_query ( $select_issue ) or die ( "erreur" );
	return ($query_issue);
}

/**
 * comparaison_date
 *
 * @param String $datea
 * @param String $dateb
 * @return String
 */
function comparaison_date($datea, $dateb) {
	$date1 = explode ( '/', $datea );
	$date2 = explode ( '/', $dateb );
	if ($date1 [2] > $date2 [2]) {
		return ("true");
	} elseif ($date1 [2] < $date2 [2]) {
		return ("faux");
	} elseif ($date1 [2] == $date2 [2]) {
		if ($date1 [1] > $date2 [1]) {
			return ("true");
		} elseif ($date1 [1] < $date2 [1]) {
			return ("faux");
		} elseif ($date1 [1] == $date2 [1]) {
			if ($date1 [0] > $date2 [0]) {
				return ("true");
			} elseif ($date1 [0] < $date2 [0]) {
				return ("faux");
			} elseif ($date1 [0] == $date2 [0]) {
				return ("faux");
			}
		}
	}
}

/****requete de s�l�ction ddes cong� en fonction de user ,date et type cong� *********/
function requeteconge($type, $id, $datedebut, $datefin) {
	$sql_conge = "  SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue,issuetype
					WHERE imputation.Project =project.ID
					AND imputation.issue = jiraissue.ID
					AND imputation.user = '$id'
					AND jiraissue.issuetype=issuetype.ID
					AND issuetype.ID= $type
					AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
					GROUP BY date ";
	
	return ($sql_conge);
}
/****requete de s�l�ction des formation en fonction de user ,date et type formation *********/
function requeteformation($type, $id, $datedebut, $datefin) {
	$sql_formation = "  SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue,issuetype
						WHERE imputation.Project =project.ID
						AND imputation.issue = jiraissue.ID
						AND imputation.user = $id
						AND jiraissue.issuetype=issuetype.ID
						AND issuetype.ID= $type
						AND project.ID = '10083'
						AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
						GROUP BY date  ";
	
	return ($sql_formation);
}
/****requete de s�l�ction de commercial en fonction de user ,date  *********/
function requetecommercial($type, $id, $datedebut, $datefin) {
	$sql_commercial = " SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY,jiraissue.ID FROM imputation,project,jiraissue,issuetype
						WHERE imputation.Project =project.ID
						AND imputation.issue = jiraissue.ID
						AND imputation.user = $id
						AND jiraissue.issuetype=issuetype.ID
						AND issuetype.ID= $type
						AND project.ID = 10100
						AND imputation.DATE BETWEEN '$datedebut' AND '$datefin'
						GROUP BY jiraissue.ID  ";
	
	return ($sql_commercial);
}
/****requete de s�l�ction de commercial en fonction de user ,date  *********/
function requetecommercial1($type, $id, $issue, $datedebut, $datefin) {
	$sql_commercial1 = "SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY,jiraissue.ID FROM imputation,project,jiraissue,issuetype
						WHERE imputation.Project =project.ID
						AND imputation.issue = jiraissue.ID
						AND imputation.user = $id
						AND jiraissue.issuetype=issuetype.ID
						AND issuetype.ID= '$type'
						AND project.ID = '10100'
						AND jiraissue.ID= $issue
						AND imputation.DATE BETWEEN '$datedebut' AND '$datefin'
						GROUP BY date";
	
	return ($sql_commercial1);
}
/****requete de s�l�ction de marketing et management en fonction de user ,date  *********/
function requetemarketingmanagement($project, $id, $summary, $datedebut, $datefin) {
	$sql_marketingmanagement = "SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue
								WHERE imputation.Project =project.ID
								AND imputation.issue = jiraissue.ID
								AND imputation.user = '$id'
								AND project.ID='$project'
								AND jiraissue.SUMMARY = '$summary'
								AND imputation.DATE BETWEEN '$datedebut' AND '$datefin' 
								GROUP BY date  ";
	
	return ($sql_marketingmanagement);
}
/****requete de s�l�ction de marketing et management en fonction de user ,date  *********/
function requetemarketingmanagement1($project, $id, $datedebut, $datefin) {
	$sql_marketingmanagement1 = "SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue
								WHERE imputation.Project =project.ID
								AND imputation.issue = jiraissue.ID
								AND imputation.user = $id
								AND project.ID='$project'
								AND imputation.DATE BETWEEN '$datedebut'AND '$datefin' 
								GROUP BY jiraissue.ID ";
	
	return ($sql_marketingmanagement1);
}
/**
 * security
 *
 * @param Integer $user
 * @param Integer $proj
 * @return String
 */
function security($user, $proj) {
	$iduser = id_user ( $user );
	$groupofuser = list_membershipbase ( $iduser );
	$val_security = "(0)";
	if($proj !='' && $iduser!=''){
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
		  				)";
			$result_security = mysql_query ( $req );
			$nb_security = mysql_num_rows ( $result_security );
			$val_security = "(";
			$param_security = 1;
			while ( $tab_security = mysql_fetch_array ( $result_security ) ) {
				$val_security .= $tab_security [0];
				if ($param_security < $nb_security) {
					$val_security .= ", ";
				}
				$param_security ++;
			}
			$val_security .= ")";
			if ($val_security != "()") {
				$val_security = $val_security;
			} else {
				$val_security = "(0)";
			}
			//Une requ�te pour selectionner les code de security pour les taches qui ont un role sur le projet.
			$req2 = "SELECT distinct(j.SECURITY)
					  FROM jiraissue j, schemeissuesecurities sh, projectroleactor p
					  WHERE j.security = sh.SECURITY
					  AND sec_type = 'projectrole'
					  AND p.PID = j.project
					  AND p.PID = " . $proj . "
					  AND p.PROJECTROLEID = sh.sec_parameter
					  AND p.ROLETYPEPARAMETER = '" . $user . "'
					   ";
			$sql_req2 = mysql_query ( $req2 );
			$nb_req2 = mysql_num_rows ( $sql_req2 );
			$vsec = "";
			While ( $row = mysql_fetch_row ( $sql_req2 ) ) {
				$vsec = $vsec . $row [0] . ", ";
			}
			if ($nb_req2 != 0) {
				$val_security = str_replace ( "(", "(" . $vsec . "", $val_security );
			}
	}
	return ($val_security);
}

function liste_group_user($proj) {
	$query = "  SELECT DISTINCT(user_name)
				FROM membershipbase
				WHERE Group_Name
						IN (SELECT DISTINCT (perm_parameter)
							FROM schemepermissions
							WHERE SCHEME = ( 
											SELECT nodeassociation.sink_node_id
											FROM project, nodeassociation
											WHERE project.ID = nodeassociation.source_node_id
											AND nodeassociation.SOURCE_NODE_ID = '" . $proj . "'
											AND nodeassociation.SOURCE_NODE_ENTITY = 'Project'
											AND nodeassociation.SINK_NODE_ENTITY = 'PermissionScheme' ) 
						    AND perm_parameter != 'Project-watcher')
				";
	
	$db_query = mysql_query ( $query );
	$nb_user = mysql_num_rows ( $db_query );
	
	$role = "   SELECT DISTINCT (
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
	$query_role = mysql_query ( $role );
	$nb_role = mysql_num_rows ( $query_role );
	$nb = $nb_user + $nb_role;
	
	$val_user = "";
	if (($nb_user > 0) or ($nb_role > 0)) {
		$val_user = "(";
		$sc = 0;
		while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
			$val_user .= "'" . $tab_user [0] . "'";
			if ($sc != ($nb - 1)) {
				$val_user .= ", ";
			}
			$sc ++;
		}
		while ( $tab_role = mysql_fetch_array ( $query_role ) ) {
			$val_user .= "'" . $tab_role [0] . "'";
			if ($sc != ($nb - 1)) {
				$val_user .= ", ";
			}
			$sc ++;
		}
		$val_user .= ")";
	}
	
	if ($val_user != "()") {
		$val_user = $val_user;
	} else {
		$val_user = "(0)";
	}
	return ($val_user);

}

/**
 * liste_group_user_f
 *
 * @param integer $proj
 * @return String
 */
function liste_group_user_f($proj) {
$liste = "";
if(is_array($proj)){
	for($j=0;$j<count($proj);$j++){
			$query = "SELECT DISTINCT(user_name)
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
		AND nodeassociation.SOURCE_NODE_ID ='".$proj[$j]."'
		AND nodeassociation.SOURCE_NODE_ENTITY = 'Project'
		AND nodeassociation.SINK_NODE_ENTITY = 'PermissionScheme' ) 
		AND perm_parameter != 'Project-watcher')
		";
			
			$db_query = mysql_query ( $query );
			$nb_user = mysql_num_rows ( $db_query );
			
			$role = "SELECT DISTINCT (
		membershipbase.user_name
		)
		FROM projectroleactor, schemepermissions, project, membershipbase
		WHERE schemepermissions.PERMISSION =10
		AND schemepermissions.perm_type = 'projectrole'
		AND schemepermissions.perm_parameter = projectroleactor.PROJECTROLEID
		AND projectroleactor.PID = project.ID
		AND projectroleactor.PID = '".$proj[$j]."'
		AND (
		projectroleactor.ROLETYPEPARAMETER = membershipbase.user_name
		OR projectroleactor.ROLETYPEPARAMETER = membershipbase.group_name
		)
		";
			$query_role = mysql_query ( $role );
			$nb_role = mysql_num_rows ( $query_role );
			$nb = $nb_user + $nb_role;
			
			$val_user = "";
			if (($nb_user > 0) or ($nb_role > 0)) {
				//$val_user = "(";
				$sc = 0;
				while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
					$val_user .= "'" . $tab_user [0] . "'";
					if ($sc != ($nb - 1)) {
						$val_user .= ", ";
					}
					$sc ++;
				}
				while ( $tab_role = mysql_fetch_array ( $query_role ) ) {
					$val_user .= "'" . $tab_role [0] . "'";
					if ($sc != ($nb - 1)) {
						$val_user .= ", ";
					}
					$sc ++;
				}
				//$val_user .= ")";
			}
			
			if ($val_user != "") {
				if($liste == ""){
					$liste = $val_user;
				}else{
					$liste .=','.$val_user;
				}
				
			} 
	}
	//echo $liste;die;
	if($liste !=""){
		return "($liste)";
	}else{
		return "(0)";
	}
}


}




/**
 * liste_group_user_f
 *
 * @param integer $proj
 * @return String
 */
function liste_group_user_f_array($proj) {
$liste = "";
$aTab = array();
if(is_array($proj)){
	for($j=0;$j<count($proj);$j++){
			$query = "SELECT DISTINCT(user_name)
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
		AND nodeassociation.SOURCE_NODE_ID ='".$proj[$j]."'
		AND nodeassociation.SOURCE_NODE_ENTITY = 'Project'
		AND nodeassociation.SINK_NODE_ENTITY = 'PermissionScheme' ) 
		AND perm_parameter != 'Project-watcher')
		";
			
			$db_query = mysql_query ( $query );
			$nb_user = mysql_num_rows ( $db_query );
			
			$role = "SELECT DISTINCT (
		membershipbase.user_name
		)
		FROM projectroleactor, schemepermissions, project, membershipbase
		WHERE schemepermissions.PERMISSION =10
		AND schemepermissions.perm_type = 'projectrole'
		AND schemepermissions.perm_parameter = projectroleactor.PROJECTROLEID
		AND projectroleactor.PID = project.ID
		AND projectroleactor.PID = '".$proj[$j]."'
		AND (
		projectroleactor.ROLETYPEPARAMETER = membershipbase.user_name
		OR projectroleactor.ROLETYPEPARAMETER = membershipbase.group_name
		)
		";
			$query_role = mysql_query ( $role );
			$nb_role = mysql_num_rows ( $query_role );
			$nb = $nb_user + $nb_role;
			
			$val_user = "";
			if (($nb_user > 0) or ($nb_role > 0)) {
				//$val_user = "(";
				$sc = 0;
				while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
					$aTab[$sc] = $tab_user [0];
					$sc ++;
				}
				while ( $tab_role = mysql_fetch_array ( $query_role ) ) {
					$aTab[$sc] = $tab_role [0];
					$sc ++;
				}
			}
			
			
		}
		
	}
	return $aTab;
}

/**
 * liste_group_user_bu
 *
 * @param Integer $bu
 * @return String
 */
function liste_group_user_bu($bu) {
$liste = "";


			$query = "SELECT 
			DISTINCT(userbase.ID), propertystring . propertyvalue 
		FROM 
			propertystring, propertyentry, userbase,groupbase, membershipbase 
		WHERE 
			propertystring.id = propertyentry.id 
			AND propertyentry.property_key = 'fullname' 
			AND propertyentry.entity_id = userbase.id
			AND groupbase.groupname=membershipbase.GROUP_NAME
			AND membershipbase.USER_NAME = userbase.username
			AND userbase.ID  not in (select user from userbasestatus where status =0) 
			
			";
			
			if($bu !=""){
				$query .=" AND groupbase.ID ='".$bu."' ";
			}else{
				$query .=" AND membershipbase.GROUP_NAME = 'BD-users'  ";
			}
		$query .="ORDER BY propertyvalue ASC ";
			
		$db_query = mysql_query ( $query );
		$nb_user = mysql_num_rows ( $db_query );
		$nb = $nb_user;	
			
			
			$val_user = "";
			if (($nb_user > 0)) {
				$sc = 0;
				while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
					$val_user .= "'" . $tab_user [0] . "'";
					if ($sc != ($nb - 1)) {
						$val_user .= ", ";
					}
					$sc ++;
				}
				
			}
			
			if ($val_user != "") {
				if($liste == ""){
					$liste = $val_user;
				}else{
					$liste .=','.$val_user;
				}
				
			} 
	
	//echo $liste;die;
	if($liste !=""){
		return "($liste)";
	}else{
		return "(0)";
	}

}



/**
 * liste_group_user_bu
 *
 * @param Integer $bu
 * @return String
 */
function liste_group_user_bu_array($bu) {
$liste = "";


			$query = "SELECT 
			DISTINCT(userbase.username), propertystring . propertyvalue 
		FROM 
			propertystring, propertyentry, userbase,groupbase, membershipbase 
		WHERE 
			propertystring.id = propertyentry.id 
			AND propertyentry.property_key = 'fullname' 
			AND propertyentry.entity_id = userbase.id
			AND groupbase.groupname=membershipbase.GROUP_NAME
			AND membershipbase.USER_NAME = userbase.username
			AND userbase.ID  not in (select user from userbasestatus where status =0) 
			
			";
			
			if($bu !=""){
				$query .=" AND groupbase.ID ='".$bu."' ";
			}else{
				$query .=" AND membershipbase.GROUP_NAME = 'BD-users'  ";
			}
		$query .="ORDER BY propertyvalue ASC ";

		$db_query = mysql_query ( $query );
		$nb_user = mysql_num_rows ( $db_query );
		$nb = $nb_user;	
		$aTab = array();	
		if($bu !=""){	
			$val_user = "";
			if (($nb_user > 0)) {
				$sc = 0;
				while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
					$aTab[$sc] = $tab_user [0];
					$sc++;
				}
				
			}
		}	
			
		return $aTab;
	

}

function liste_group_user_bu_proj($group,$proj){
	$aListGroup = array();$aListProj = array();$aTabFinal= array();
	
	if($group !=""){
		$aListGroup = liste_group_user_bu_array($group);
		
		if(isset($proj[0] )&& $proj[0]!=""){
		$aListProj = liste_group_user_f_array($proj);
		
			foreach ($aListGroup as $vG){
				if( in_array(($vG), $aListProj)){
					$aTabFinal[] = $vG;
				}
			}
		}elseif(isset($proj[0] )&& $proj[0]==""){
			$aTabFinal = $aListGroup;
		}
	}elseif(isset($proj[0] )&& $proj[0]!=""){
		$aTabFinal = liste_group_user_f_array($proj);
	}elseif($group == ""){
		
		$query1="SELECT 
				DISTINCT(userbase.username), propertystring . propertyvalue 
			FROM 
				propertystring, propertyentry, userbase, project, membershipbase 
			WHERE 
				propertystring.id = propertyentry.id 
				AND propertyentry.property_key = 'fullname' 
				AND propertyentry.entity_id = userbase.id
				AND membershipbase.USER_NAME = userbase.username
				AND membershipbase.GROUP_NAME = 'BD-users' 
				AND userbase.ID  not in (select user from userbasestatus where status =0) 
			ORDER BY propertyvalue ASC ";
		$db_query = mysql_query ( $query );
		$nb_user = mysql_num_rows ( $db_query );
		$nb = $nb_user;	
		$aTab = array();	
		if($bu !=""){	
			$val_user = "";
			if (($nb_user > 0)) {
				$sc = 0;
				while ( $tab_user = mysql_fetch_array ( $db_query ) ) {
					$aTabFinal[$sc] = $tab_user [0];
					$sc++;
				}
				
			}
		}	
			
	}
	
	return $aTabFinal;
}


/**
 * som_imputation
 *
 * @param mixed $tab
 * @param String $date1
 * @param String $date2
 * @return Double
 */
function som_imputation($tab, $date1, $date2) {
	$imp = "SELECT 
			round(sum(imputation.imputation), 2) as som 
		FROM 
			imputation 
		WHERE 
			(imputation.user = $tab)
			AND (Date BETWEEN '$date1' AND '$date2')";
	$exe_imp = mysql_query ( $imp );
	while ( $tab_som = mysql_fetch_row ( $exe_imp ) ) {
		$som = $tab_som [0];
	}
	
	return ($som);
}

/**
 * som_jour_imputation
 *
 * @param Integer $userId
 * @param String $date
 * @return Double
 */
function som_jour_imputation($userId, $date){
	$imp = "SELECT 
			round(sum(imputation.imputation), 2) as som 
		FROM 
			imputation 
		WHERE 
			imputation.user = ".$userId."
			AND Date like '".$date."'";
	$exe_imp = mysql_query ( $imp );
	while ( $tab_som = mysql_fetch_row ( $exe_imp ) ) {
		$som = $tab_som [0];
	}
	
	return ($som);
}
/**
 * member_bu
 * @param Integer $bu
 * @return String
 */
function member_bu($bu){
	$user = "SELECT DISTINCT (user.ID), user.username
		 FROM userbase user, propertyentry pe, propertystring ps, membershipbase gp
		 WHERE pe.ENTITY_NAME = 'OSUser'
		 	   AND pe.entity_ID = user.ID
		 	   AND pe.Property_key = 'jira.meta.Actif'
			   AND pe.ID = ps.ID
			   AND ps.propertyvalue = 'Oui'
			   AND user.username = gp.USER_NAME
			   AND GROUP_NAME = '".$bu."'
			   ORDER BY user.username";
	
	$req_user = mysql_query($user) or die (mysql_error);
	return $req_user;
}
/**
 * manager_bu
 *
 * @param Integer $bu
 * @return mixed
 */
function manager_bu($bu){
	$sqlManager = "SELECT DISTINCT (user.ID)
			 FROM userbase user, membershipbase gp
			 WHERE user.username = gp.USER_NAME
				   AND GROUP_NAME = '".$bu."'
				   ORDER BY user.username";
	$manager = mysql_query($sqlManager) or die (mysql_error);
	while ( $tab = mysql_fetch_array ( $manager ) ) {
			$listManager[] = $tab [0];
		}
	return $listManager;
}

/**
 * affich_comment
 *
 * @param Integer $issue
 * @param Integer $user
 * @param String $d1
 * @param String $d7
 * @return String
 */
function affich_comment($issue, $user, $d1, $d7) {
	$comment = '';
	$sql_comment = "SELECT 
			DISTINCT(commentaire) 
		FROM 
			imputation 
		WHERE 
			issue = " . $issue . "
			AND user = '" . $user . "'
			AND Date BETWEEN '" . $d1 . "' AND  '" . $d7 . "'
			AND commentaire != '' ";
	$query_comment = mysql_query ( $sql_comment );
	while ( $tab = mysql_fetch_row ( $query_comment ) ) {
		$comment = $tab [0];
	}
	
	return ($comment);
}
function affich_validation($id_imputation) {
	$sql_valid = "SELECT 
				validation 
			FROM 
				imputation 
			WHERE 
				ID = " . $id_imputation;
	$query_valid = mysql_query ( $sql_valid );
	$tab = mysql_fetch_array ( $query_valid );
	$valid = $tab [0];
	return ($valid);
}

function update_variable($issue, $vraf) {
	
	$vraf = select_dernier_raf ( $issue, 0 );
	# Si la semaine n'est pas celle en cours
	

	$requete_jira = "UPDATE 
			   customfieldvalue 
			   SET numbervalue='" . $vraf . "' 
			   WHERE issue=$issue 
			   AND customfield=(SELECT ID FROM customfield WHERE cfname ='RAF')";
	$req_jira = mysql_query ( $requete_jira );
	
	/********************* MAJ du temps Estim� (RAF) dans la table jiraissue **********************/
	$w_RAF = $vraf * 60 * 60 * 8;
	/*mysql_query("UPDATE jiraissue SET TIMEESTIMATE = '0' WHERE ID = ".$issue." AND TIMEESTIMATE IS NULL");
mysql_query("UPDATE jiraissue SET TIMEESTIMATE = ".$w_RAF." WHERE ID = ".$issue);*/
	/********************* MAJ du temps Estim� (RAF) dans la table jiraissue **********************/
	
	# R�cup�rer la valeur de la charge consomm�e
	$sql_cons1 = "SELECT sum(imputation) as somme
			  FROM imputation
			  WHERE issue = " . $issue;
	$req_cons1 = mysql_query ( $sql_cons1 );
	$res_cons1 = mysql_fetch_object ( $req_cons1 );
	$chge_cons = $res_cons1->somme;
	
	/********************* MAJ du temps consomm�e  dans la table jiraissue **********************/
	$w_CC = $chge_cons * 60 * 60 * 8;
	mysql_query ( "UPDATE jiraissue SET TIMESPENT = '0' WHERE ID = " . $issue . " AND TIMESPENT IS NULL" );
	mysql_query ( "UPDATE jiraissue SET TIMESPENT = " . $w_CC . " WHERE ID = " . $issue );
	/********************* MAJ du temps consomm�e dans la table jiraissue **********************/
	
	mysql_query ( "UPDATE customfieldvalue SET NUMBERVALUE = " . $chge_cons . "
			 WHERE issue = " . $issue . " AND customfield = (SELECT ID FROM customfield WHERE cfname = 'Consomm�e')" );
	
	if (empty ( $chge_cons )) {
		$chge_cons = 0;
	}
	
	# R�cup�rer la valeur de du RAF
	$sql_raf = "SELECT NUMBERVALUE
			 FROM customfieldvalue
			 WHERE customfield = (SELECT ID FROM customfield WHERE cfname = 'RAF')
			 AND ISSUE = " . $issue . "
			 ";
	$req_raf = mysql_query ( $sql_raf );
	if (mysql_num_rows ( $req_raf ) != 0) {
		while ( $row1 = mysql_fetch_row ( $req_raf ) ) {
			$chge_raf = $row1 [0];
		}
	
	} else {
		$chge_raf = 0;
	}
	
	# R�cup�rer la charge pr�vue
	$sql_prev = "SELECT NUMBERVALUE as NUMBERVALUE2
			 FROM customfieldvalue
			 WHERE customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge pr�vue')
			 AND ISSUE = " . $issue;
	$req_prev = mysql_query ( $sql_prev );
	
	if (mysql_num_rows ( $req_prev ) != 0) {
		while ( $row = mysql_fetch_row ( $req_prev ) ) {
			$chge_prev = $row [0];
		}
	} else {
		$chge_prev = 0;
	}
	
	$chge_relle = $chge_cons + $chge_raf;
	mysql_query ( "UPDATE customfieldvalue 
			 SET NUMBERVALUE = '" . $chge_relle . "' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Charge r�elle'))" );
	if ($chge_relle == 0) {
		$avanc = 0;
	} else {
		$avanc = ($chge_cons / $chge_relle) * 100;
		$avanc = format_number ( $avanc );
	}
	mysql_query ( "UPDATE customfieldvalue 
			 SET NUMBERVALUE = '" . $avanc . "' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Avancement (%)'))" );
	
	if ($chge_relle == 0) {
		$prev = 0;
	} else {
		$prev = ($chge_raf / $chge_relle) * 100;
		$prev = format_number ( $prev );
	}
	
	mysql_query ( "UPDATE customfieldvalue 
			 SET NUMBERVALUE = '" . $prev . "' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Pr�visionnel (%)'))" );
	
	$vtion_chge = $chge_prev - $chge_relle;
	mysql_query ( "UPDATE customfieldvalue 
			 SET NUMBERVALUE = '" . $vtion_chge . "' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation de charge'))" );
	
	if ($chge_prev == 0) {
		$vtion = 0;
	} else {
		$vtion = ($vtion_chge / $chge_prev) * 100;
		$vtion = format_number ( $vtion );
	}
	
	mysql_query ( "UPDATE customfieldvalue 
			 SET NUMBERVALUE = '" . $vtion . "' 
			 WHERE (ISSUE = $issue) and (customfield = (SELECT ID FROM customfield WHERE cfname = 'Variation (%)'
))" );
}
function verif_comment($issue, $user, $date1, $date2) {
	$sql = "SELECT * FROM imputation WHERE (issue = " . $issue . ") and (user = " . $user . ") and date between '" . $date1 . "' and '" . $date2 . "' and imputation = '0' and RAF = '0'";
	$req = mysql_query ( $sql );
	$nb = mysql_num_rows ( $req );
	return ($nb);
}
function affiche_valide_par($id_imp) {
	
	$sql = "SELECT valide_par FROM imputation WHERE ID = $id_imp";
	$query = mysql_query ( $sql ) or die ( "erreur" );
	$nb = mysql_num_rows ( $query );
	if ($nb == 0) {
		$nom_valid = '';
	} else {
		$nom_valid = mysql_result ( $query, 0, 'valide_par' );
	}
	return ($nom_valid);
}
function affiche_dat_val($id_imp) {
	
	$sql = "SELECT Date_validation FROM imputation WHERE ID = $id_imp";
	$query = mysql_query ( $sql ) or die ( "erreur" );
	$nb = mysql_num_rows ( $query );
	if ($nb == 0) {
		$dat_valid = '';
	} else {
		$dat_valid = mysql_result ( $query, 0, 'Date_validation' );
	}
	return ($dat_valid);
}
function select_collab_issue($id_issue) {
	$select = "SELECT distinct(user) from imputation where issue = $id_issue";
	$query = mysql_query ( $select );
	//echo $nb = mysql_num_rows($query)."<br>";
	$list_user = "";
	while ( $list = mysql_fetch_row ( $query ) ) {
		$list_user = $list_user . "|" . $list [0];
	}
	return ($list_user);
}
function select_dernier_raf($issue, $collab) {
	$select_raf = "SELECT RAF, Date_imputation 
	  FROM imputation
	  WHERE issue = " . $issue . "
	  AND Date = (SELECT max(Date) FROM imputation WHERE issue = " . $issue;
	
	if ((isset ( $collab )) and ($collab != 0)) {
		$select_raf .= " AND user =" . $collab . " ) 
			AND user =" . $collab . "";
	} else {
		$select_raf .= ")";
	}
	//echo $select_raf;
	$query_raf = mysql_query ( $select_raf );
	$nb = mysql_num_rows ( $query_raf );
	if ($nb != 0) {
		$list = mysql_fetch_array ( $query_raf );
		$vraf = $list [0];
	} else {
		$vraf = 0;
	}
	return ($vraf);
}
function list_proj_non_facture() {
	$list_proj = "(";
	$i = 0;
	$select = "SELECT ID 
FROM tetd_project_non_facturable
";
	$query = mysql_query ( $select ) or die ( 'error' );
	$nb = mysql_num_rows ( $query );
	if ($nb != 0) {
		while ( $row = mysql_fetch_row ( $query ) ) {
			$list_proj .= $row [0];
			if ($i != ($nb - 1)) {
				$list_proj .= ", ";
			}
			$i ++;
		}
	}
	$list_proj .= ")";
	$list_proj;
	return ($list_proj);
}


function list_proj_non_facturable() {
	$select = "SELECT ID 
FROM tetd_project_non_facturable
";
	
	$anF = array();
	$query = mysql_query ( $select ) or die ( 'error' );
	$nb = mysql_num_rows ( $query );
	if ($nb != 0) {
		$i = 0;
		while ( $tab = mysql_fetch_row ( $query ) ) {
			$anF[$i] = $tab [0];
			$i ++;
		}
	}
	
	return ($anF);
}

function issue_abscence($date1, $date2, $login) {
	$sql = "SELECT jiraissue.ID, jiraissue.pkey, jiraissue.SUMMARY 
		 	 FROM `jiraissue`, customfieldvalue, issuestatus 
			  WHERE jiraissue.ID = customfieldvalue.ISSUE
			  AND ((customfieldvalue.DATEVALUE BETWEEN '" . $date1 . "' AND '" . $date2 . "') 
	   	 		OR (jiraissue.DUEDATE BETWEEN '" . $date1 . "' AND '" . $date2 . "')
		 		OR (customfieldvalue.DATEVALUE <= '" . $date1 . "' AND jiraissue.DUEDATE >= '" . $date2 . "')) 
			  AND (customfieldvalue.CUSTOMFIELD = 10002 
			       OR customfieldvalue.CUSTOMFIELD = 10070)
			  AND jiraissue.PROJECT =  10064
			  AND (issuestatus.ID = jiraissue.issuestatus)  
			  AND jiraissue.REPORTER = '" . $login . "'
			  AND (issuestatus.pname = 'Validated')
		  ";
	$query = mysql_query ( $sql ) or die ( 'error' );
	$res = mysql_num_rows ( $query );
	$fonc = $res . "||" . $sql;
	return ($fonc);

}

function duree_conge($id_issue) {
	$sql = "SELECT cf.NUMBERVALUE 
		FROM customfieldvalue cf, jiraissue ji
		WHERE cf.ISSUE = ji.ID
		AND ji.PROJECT = 10064
		AND ji.ID = " . $id_issue . "
		AND cf.CUSTOMFIELD = 10035
		";
	$query = mysql_query ( $sql );
	$nb = mysql_num_rows ( $query );
	if ($nb > 0) {
		$duree = mysql_result ( $query, 0, 'NUMBERVALUE' );
	} else {
		$duree = 0;
	}
	return ($duree);
}

function periode_conge($id_issue, $date) {
	$date_debut = "";
	$date_fin = "";
	$issuetype = check_type_issue ( $id_issue );
	if ($issuetype != 9) {
		$sql = "SELECT CAST(cf.DATEVALUE AS Date) as date_deb, CAST(ji.DUEDATE AS Date) as date_fin
		FROM customfieldvalue cf, jiraissue ji
		WHERE cf.ISSUE = ji.ID
		AND ji.PROJECT = 10064
		AND ji.ID = " . $id_issue . "
		AND cf.CUSTOMFIELD = 10002
		";
		
		$query = mysql_query ( $sql );
		while ( $row = mysql_fetch_row ( $query ) ) {
			$date_debut = $row [0];
			$date_fin = $row [1];
		}
		
		return ($date_debut . "||" . $date_fin);
	
	} else {
		$sql = "SELECT CAST(cf.DATEVALUE AS Date) as date_deb
		FROM customfieldvalue cf
		WHERE cf.CUSTOMFIELD = 10070
	     	  AND ISSUE = " . $id_issue;
		$query = mysql_query ( $sql );
		while ( $row = mysql_fetch_row ( $query ) ) {
			$date_debut = $row [0];
		}
		
		return ($date_debut . "||" . $date_debut);
	}
}

function som_imp($id_issue) {
	$sql1 = "SELECT round(sum(imputation),2) as somme
		FROM imputation 
		WHERE issue = " . $id_issue;
	$query1 = mysql_query ( $sql1 ) or die ( 'error' );
	$nb1 = mysql_num_rows ( $query1 );
	if ($nb1 > 0) {
		$som = mysql_result ( $query1, 0, 'somme' );
	} else {
		$som = 0;
	}
	
	return ($som);
}

/**
@ maintainer: fonction qui fourni la liste des groupes pour un user
@ param: id_user
@ since: 23-04-2008 21:00
**/
function membershipbase($iduser) {
	$sql1 = "select groupbase.groupname as groupe from groupbase,membershipbase,userbase 
			  where groupbase.groupname=membershipbase.GROUP_NAME and membershipbase.USER_NAME=userbase.username
			  and userbase.ID=" . $iduser;
	$query = mysql_query ( $sql1 );
	$i = 0;
	$group = array ();
	while ( $tab = mysql_fetch_row ( $query ) ) {
		$group [$i] = $tab [0];
		$i ++;
	}
	return ($group);
}

/**
@ maintainer: fonction qui fourni la liste des groupes pour un user
@ param: id_user
**/

function list_membershipbase($iduser) {
	$liste_group = membershipbase ( $iduser );
	$liste_group;
	$nb_group = count ( $liste_group );
	$tab_group = array ();
	$tabbb = "(";
	for($i = 0; $i < $nb_group; $i ++) {
		$tab_group [$i] = $liste_group [$i];
		$tabbb .= "'" . $liste_group [$i] . "'";
		if ($i != ($nb_group - 1)) {
			$tabbb .= ", ";
		}
	}
	$tabbb .= ")";
	return ($tabbb);
}

/**
@ maintainer: fonction qui fourni la liste des groupes pour un user
@ param: id_user
**/
function listgroup($iduser) {
	$sql1 = "select groupbase.groupname as groupe from groupbase";
	$query = mysql_query ( $sql1 );
	$i = 0;
	$group = array ();
	while ( $tab = mysql_fetch_row ( $query ) ) {
		$group [$i] = $tab [0];
		$i ++;
	}
	return ($group);
}



/***************************************************
@Date           : 23-04-2008 21:00
@Fonctionnement : Gestion des rles sur les taches
 ****************************************************/
function gestion_role_task($iduser, $login) {
	$tabbb = list_membershipbase ( $iduser );
	$tab2 = str_replace ( "Project-watcher", "PW", $tabbb );
	
	$proj_sql = "SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '" . $login . "'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN" . $tab2;
	
	$proj_query = mysql_query ( $proj_sql );
	$nb_proj = mysql_num_rows ( $proj_query );
	
	$sql_role = "SELECT 
					DISTINCT (p.PID) 
			FROM 
					schemepermissions sc, 
					projectroleactor p 
			WHERE 
					sc.perm_type = 'projectrole'
					AND sc.PERMISSION =10
					AND sc.perm_parameter = p.PROJECTROLEID
					AND (p.ROLETYPEPARAMETER = '" . $login . "' 
					OR p.ROLETYPEPARAMETER IN " . $tab2 . ")";
	$query_role = mysql_query ( $sql_role );
	$nb_role = mysql_num_rows ( $query_role );
	if (($nb_proj > 0) or ($nb_role > 0)) {
		$tab_imputation = "(";
		$sc = 0;
		while ( $tab_proj = mysql_fetch_row ( $proj_query ) ) {
			$tab_imputation .= "'" . $tab_proj [0] . "'";
			if ($sc != ($nb_proj - 1)) {
				$tab_imputation .= ", ";
			}
			$sc ++;
		}
		while ( $tab_role = mysql_fetch_row ( $query_role ) ) {
			if ($sc != ($nb_proj - 1)) {
				$sc ++;
			}
		}
		$tab_imputation .= ")";
	}
	if ($tab_imputation == "()") {
		$tab_imputation = "('')";
	}
	return ($tab_imputation);
}
/***************************************************
@Date           : 23-04-2008 21:00
@Fonctionnement : Gestion des rles sur les projets
 ****************************************************/

function gestion_role($iduser, $login) {
	
	$tabbb = list_membershipbase ( $iduser );
	$tab2 = str_replace ( "Project-watcher", "PW", $tabbb );
	$tab_imputation = gestion_role_task ( $iduser, $login );
	$query = "Select 
				distinct(project.id), project.pname
			  FROM 
				project, nodeassociation, permissionscheme
			  WHERE 
				project.ID = nodeassociation.SOURCE_NODE_ID
				AND SOURCE_NODE_ENTITY = 'Project'
				AND project.id NOT IN (SELECT ID FROM tetd_project_exclusive)
				AND SINK_NODE_ENTITY = 'PermissionScheme'
				AND SINK_NODE_ID IN " . $tab_imputation . " 
				ORDER BY project.pname";
	//echo $query;die;
	$result = mysql_query ( $query ) or die ( 'error' );
	
	$nbproj = mysql_num_rows ( $result );
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
					AND (p.ROLETYPEPARAMETER = '" . $login . "' 
					OR p.ROLETYPEPARAMETER IN " . $tab2 . ")";
	
	//echo $sql_role;die;
	$query_role = mysql_query ( $sql_role );
	$nb_role = mysql_num_rows ( $query_role );
	
	$nb_project = $nbproj + $nb_role;
	if ($nb_project > 0) {
		$tab_proj = "(";
		$sc = 0;
		
		while ( $tab_projet = mysql_fetch_array ( $result ) ) {
			$tab_proj .= "'" . $tab_projet [0] . "'";
			if ($sc != ($nb_project - 1)) {
				$tab_proj .= ", ";
			}
			$sc ++;
		}
		
		while ( $tab_proj2 = mysql_fetch_array ( $query_role ) ) {
			$tab_proj .= "'" . $tab_proj2 [0] . "'";
			if ($sc != ($nb_project - 1)) {
				$tab_proj .= ", ";
			}
			$sc ++;
		}
		
		$tab_proj .= ")";
	} else {
		$tab_proj = "('')";
	}
	//echo $tab_proj;die;
	return ($tab_proj);
}

/**
 * member_jira_user : get membershibase user
 *
 * @param integer $user
 * @return booleen
 */
function member_jira_user($user) {
	$log = login_user ( $user );
	$sql = "SELECT ID 
		    FROM membershipbase 
		    WHERE `GROUP_NAME` LIKE 'BD-users'
		    AND USER_NAME = '" . $log . "'";
	
	$query = mysql_query ( $sql );
	$nb = mysql_num_rows ( $query );
	if ($nb == 0) {
		return ('false');
	} else {
		return ('true');
	}

}

/**
 * check_date_conge : dates de cong�
 *
 * @param string $date
 * @param float $imputation
 * @param integer $issue
 * @return string
 */
function check_date_conge($date, $imputation, $issue) {
	
	$periode = periode_conge ( $issue, $date );
	$date_deb = substr ( $periode, 0, 10 );
	$date_fin = substr ( $periode, 12, 10 );
	$type = check_type_issue ( $issue );
	if ($type == 9) {
		if ($date == $date_deb) {
			return ("vrai");
		} else {
			return ("faux");
		}
	} else {
		if ((($date < $date_deb) or ($date > $date_fin)) and ($imputation != 0)) {
			return ("faux");
		} else {
			return ("vrai");
		}
	}
}

/**
 * check_type_issue : type de t�che (issue)
 *
 * @param integer $issue
 * @return string
 */
function check_type_issue($issue) {
	$sql = "SELECT issuetype 
		FROM jiraissue
		WHERE ID = " . $issue;
	
	$query = mysql_query ( $sql );
	$issuetype = mysql_result ( $query, 0, 'issuetype' );
	return ($issuetype);
}

/**
 * total_abscence : total abscence
 *
 * @param string $date1
 * @param string $date2
 * @return mixed
 */
function total_abscence($date1, $date2) {
	$Abs = array ();
	
	$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, userbase.username as username
			FROM   imputation, userbase
			WHERE  imputation.user = userbase.ID
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   AND imputation.project = 10064
			GROUP  BY imputation.user
			ORDER  BY imputation.user ";
	
    $query = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query ) ) {
		$Abs [$row [1]] = $row [0];
	}
	return ($Abs);

}

/**
 * Somme des imputation
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $id_user identitifiant collaborateur
 */

function sum_imputation_facturation_proj($date1, $date2, $proj, $cat) {
	if ($cat != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username, imputation.project, project.pname 
				FROM   imputation, userbase, project, nodeassociation
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
				   AND project.ID = imputation.project
				   AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
				   AND project.ID = nodeassociation.SOURCE_NODE_ID
				   AND nodeassociation.SINK_NODE_ID = " . $cat . "
				GROUP  BY imputation.user, imputation.validation, imputation.project";
		if ($proj != "") {
			$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
			               imputation.validation, userbase.username, imputation.project, project.pname 
					FROM   imputation, userbase, project
					WHERE  imputation.user = userbase.ID
					   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
					   AND project.ID = imputation.project
					   AND project.ID = " . $proj . "
					GROUP  BY imputation.user, imputation.validation, imputation.project
					ORDER  BY imputation.user ";
		}
	} elseif ($proj != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username, imputation.project, project.pname 
				FROM   imputation, userbase, project
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
				   AND project.ID = imputation.project
				   AND project.ID = " . $proj . "
				GROUP  BY imputation.user, imputation.validation, imputation.project
				ORDER  BY imputation.user ";
	} else {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username, imputation.project, project.pname 
				FROM   imputation, userbase, project
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
				   AND project.ID = imputation.project
				GROUP  BY imputation.user, imputation.validation, imputation.project
				ORDER  BY imputation.user ";
	}
	$query = mysql_query ( $sql ) or die ( 'error' );
	return ($query);

}

/**
 * Somme des imputation
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $id_user identitifiant collaborateur
 */

function sum_imputation_facturation_proj_user($date1, $date2, $proj, $cat, $id_user="") {
	if ($cat != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username, imputation.project, project.pname 
		FROM   imputation, userbase, project, nodeassociation
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		   AND project.ID = imputation.project
		   AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		   AND project.ID = nodeassociation.SOURCE_NODE_ID
		   AND nodeassociation.SINK_NODE_ID = " . $cat;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation, imputation.project";
		if ($proj != "") {
			$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username, imputation.project, project.pname 
		FROM   imputation, userbase, project
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		   AND project.ID = imputation.project
		   AND project.ID = " . $proj;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation, imputation.project
		ORDER  BY imputation.user ";
		}
	} elseif ($proj != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username, imputation.project, project.pname 
		FROM   imputation, userbase, project
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		   AND project.ID = imputation.project
		   AND project.ID = " . $proj;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation, imputation.project
		ORDER  BY imputation.user ";
	} else {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username, imputation.project, project.pname 
		FROM   imputation, userbase, project
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		} 
		
		$sql .= " 
		   AND project.ID = imputation.project
		GROUP  BY imputation.user, imputation.validation, imputation.project
		ORDER  BY imputation.user ";
	}
	$query = mysql_query ( $sql ) or die ( 'error' );
	return ($query);

}

/**
 * Somme des imputations entre deux dates
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 */

function sum_imputation_facturation($date1, $date2, $proj, $cat) {
	
	if ($cat != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username 
				FROM   imputation, userbase, nodeassociation
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
				 AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
				   AND imputation.project = nodeassociation.SOURCE_NODE_ID
				   AND nodeassociation.SINK_NODE_ID = " . $cat . "
				GROUP  BY imputation.user, imputation.validation
				ORDER  BY imputation.user";
		if ($proj != "") {
			$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username 
				FROM   imputation, userbase
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
					AND imputation.project = " . $proj . "
				GROUP  BY imputation.user, imputation.validation
				ORDER  BY imputation.user ";
		
		}
	} elseif ($proj != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username 
				FROM   imputation, userbase
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
					AND imputation.project = " . $proj . "
				GROUP  BY imputation.user, imputation.validation
				ORDER  BY imputation.user ";
	} else {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
		               imputation.validation, userbase.username 
				FROM   imputation, userbase
				WHERE  imputation.user = userbase.ID
				   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		
				GROUP  BY imputation.user, imputation.validation
				ORDER  BY imputation.user ";
	}
	
	$query = mysql_query ( $sql ) or die ( 'errorr' );
	return ($query);

}


/**
 * Somme des imputations entre deux dates
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $id_user identitifiant collaborateur
 */

function sum_imputation_facturation_user($date1, $date2, $proj, $cat, $id_user="") {
	
	if ($cat != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username 
		FROM   imputation, userbase, nodeassociation
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		 AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		   AND imputation.project = nodeassociation.SOURCE_NODE_ID
		   AND nodeassociation.SINK_NODE_ID = " . $cat;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation
		ORDER  BY imputation.user";
		if ($proj != "") {
			$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username 
		FROM   imputation, userbase
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		   AND imputation.project = " . $proj;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation
		ORDER  BY imputation.user ";
		
		}
	} elseif ($proj != "") {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username 
		FROM   imputation, userbase
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			AND imputation.project = " . $proj;
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= " 
		GROUP  BY imputation.user, imputation.validation
		ORDER  BY imputation.user ";
	} else {
		$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, 
               imputation.validation, userbase.username 
		FROM   imputation, userbase
		WHERE  imputation.user = userbase.ID
		   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
		if ($id_user != "") {
			$sql .= " AND userbase.ID =".$id_user;
		}
		
		$sql .= "  

		GROUP  BY imputation.user, imputation.validation
		ORDER  BY imputation.user ";
	}
	
	$query = mysql_query ( $sql ) or die ( 'errorr' );
	return ($query);

}

/**
 * insertDataValidate
 *
 * @param mixed $data
 */
function insertDataValidate($data){
	$requete1 = "INSERT INTO tetd_project_validate  
		VALUES('', ".$data["idp"].", ".$data["idcollab"].")";
	
	if (! $req1 = mysql_query ( $requete1 )) {
		$error = "Erreur d'insertion";
	}
}
/**
 * deleteDataValidate
 *
 * @param integer $id
 * @param integer $id_proj
 */
function deleteDataValidate( $id, $id_proj){ 
	$requete1 = "DELETE from tetd_project_validate where user='".$id."'  and project='".$id_proj."'";
				
	if (! $req1 = mysql_query ( $requete1 )) {
		$error = "Erreur d'insertion";
	}
	echo 1;exit;
}


/**
 * deleteDataValidateGroupe
 *
 * @param integer $id
 * @param integer $id_group
 */
function deleteDataValidateGroupe( $id, $id_group){ 
	$requete1 = "DELETE from tetd_group_validate where user='".$id."'  and groupe='".$id_group."'";
				
	if (! $req1 = mysql_query ( $requete1 )) {
		$error = "Erreur d'insertion";
	}
	echo 1;exit;
}


/**
 * insertDataValidate
 *
 * @param mixed $data
 */
function insertDataValidateBu($data){
	$requete1 = "INSERT INTO tetd_group_validate  
		VALUES('', ".$data["idp"].", ".$data["idcollab"].")";
	
	if (! $req1 = mysql_query ( $requete1 )) {
		$error = "Erreur d'insertion";
	}
}
/**
 * deleteDataValidate
 *
 * @param integer $id
 * @param integer $id_proj
 */
function deleteDataValidateBu( $id, $bu){ 
	$requete1 = "DELETE from tetd_group_validate where user='".$id."'  and groupe='".$bu."'";
				
	if (! $req1 = mysql_query ( $requete1 )) {
		$error = "Erreur d'insertion";
	}
	echo 1;exit;
}



/**
 * imputation_proj: imputation du projet selectionne
 *
 * @param integer $IdProj
 * @param string $date1
 * @param string $date2
 * @return string
 */
function imputation_proj($IdProj, $date1, $date2) {
	
	$sql = "SELECT round( sum( imputation ) , 2 ) AS somme, imputation.user, imputation.issue, issuetype.pname, userbase.username
		  FROM jiraissue, imputation, issuetype, userbase
		  WHERE imputation.issue = jiraissue.ID
			AND imputation.project in(" . $IdProj . ")
			AND jiraissue.issuetype = issuetype.ID
			AND imputation.user = userbase.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			GROUP BY imputation.user, issuetype.pname
			ORDER BY imputation.user ";
	
	$query = mysql_query ( $sql ) or die ( 'error' );
	return ($query);
}

/**
 * Liste_proj_rapport
 *
 * @return mixed $Tproj
 */
function Liste_proj_rapport() {
	
	$sql = "SELECT ID, pname
		    FROM project 
		    WHERE project.pname = 'Demandes d\'absence'
			   OR project.pname = 'Hors projet'
			   OR project.pname = 'Formations'
			ORDER BY project.pname ";
	
	$query = mysql_query ( $sql ) or die ( 'error' );
	$p = 0;
	while ( $row = mysql_fetch_row ( $query ) ) {
		$Tproj [$p] ['id'] = $row [0];
		$Tproj [$p] ['name'] = $row [1];
		$p ++;
	}
	
	return ($Tproj);
}

/**
 * Liste des utilisateurs de time&decision
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $user_id identitifiant collaborateur
 */
function list_userbase($date1, $date2, $proj, $cat, $user_id = "") {
	
	if ($cat != "") {
		$sql = "SELECT DISTINCT(userbase.username) 
		  FROM userbase, imputation, nodeassociation 
		  WHERE userbase.ID = imputation.user
		  ";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND imputation.project = nodeassociation.SOURCE_NODE_ID
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			ORDER BY userbase.username";
		if ($proj != "") {
			$sql = "SELECT DISTINCT(userbase.username) FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user";
			if ($user_id != "")
				$sql .= " AND userbase.ID =  '" . $user_id . "'";
			$sql .= " AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
		}
	} elseif ($proj != "") {
		$sql = "SELECT DISTINCT(userbase.username) FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "   AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
	} 

	else {
		 $sql = "SELECT distinct (userbase.username) 
				FROM userbase 
				WHERE 
					(userbase.username in (select USER_NAME from membershipbase where GROUP_NAME = 'BD-users' ) 
					or userbase.id in (select distinct user from imputation where Date BETWEEN '" . $date1 . "' AND '" . $date2 . "') )
					and userbase.username not in (select USER_NAME from membershipbase where GROUP_NAME = 'BD-Maroc')
					 ";
		if ($user_id != ""){
			$sql .= "AND userbase.ID =  '" . $user_id . "'";
			
		}
		$sql .=" ORDER BY userbase.username";
	}
	$query = mysql_query ( $sql ) or die ( 'eror' );
	$i = 0;
	$tab = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$tab [$i] = $row [0];
		$i ++;
	}
	return ($tab);
}



/**
 * Liste des utilisateurs de time&decision
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $user_id identitifiant collaborateur
 * @param : (String) page
 * @param : (String) $liste1
 * @param : (String) $liste2
 * @param : (String) $liste3
 */

function list_userbase_f($date1, $date2, $proj, $cat, $user_id = "",$page = 1,$liste1, $liste2, $liste3, $gb ="") {

		$p = $page;
		$d = 0;
		$f = 10;
		$d = ($p -1)* $f;
		if ($cat != "") {
		$sql = "SELECT DISTINCT(userbase.username) ,userbase.ID
		  FROM userbase, imputation, nodeassociation 
		  WHERE userbase.ID = imputation.user  
		  AND userbase.ID  not in (select user from userbasestatus where status =0) 
		  ";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND imputation.project = nodeassociation.SOURCE_NODE_ID
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			ORDER BY userbase.username";
		if ($proj != "") {
			$sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user 
			   AND userbase.ID  not in (select user from userbasestatus where status =0) ";
			if ($user_id != "")
				$sql .= " AND userbase.ID =  '" . $user_id . "'";
			$sql .= " AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
		}
		
		if ($gb != "") {
			$sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user 
			   AND userbase.ID  not in (select user from userbasestatus where status =0) ";
			if ($gb != "") $sql .= " AND userbase.ID IN  " . $gb;
			$sql .= " AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
		}
	} elseif ($proj != "") {
		$sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user 
			   AND userbase.ID  not in (select user from userbasestatus where status =0) ";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "   AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username"; 
	} 
	elseif ($gb != "") {
		$sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user 
			   AND userbase.ID  not in (select user from userbasestatus where status =0) ";
		if ($gb != "") $sql .= " AND userbase.ID  IN  " . $gb ;
		$sql .= "   
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username"; 
	} 
	

	else {
		 $sql = "SELECT distinct (userbase.username),userbase.ID
				FROM userbase 
				WHERE 1=1 ";
		 
		 if($liste1 !="" && $liste2 !=""){
			 $sql .= "AND (userbase.username in (".$liste1.") or userbase.id in (".$liste2.") ) ";
		 }
		 
		 if($liste3!=""){
		 	$sql .= "AND  userbase.username not in (".$liste3.") ";
		 }
	
		if ($user_id != ""){
			$sql .= "AND userbase.ID =  '" . $user_id . "' ";
			
		}
		$sql .=" AND userbase.ID  not in (select user from userbasestatus where status =0) ";
		$sql .=" ORDER BY userbase.username";
	}
	if($p !='all')	{$sql .=" limit $d,$f";} 

	$query = mysql_query ( $sql ) or die ( 'eror1' );
	$i = 0;

	
	$tab = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$tab [$i]['username'] = $row [0];
		$tab [$i]['id'] = $row [1];
		$i ++;
	}
	
	return ($tab);
}

/**
 * Liste des utilisateurs de time&decision
 * @param : (String) $date1 date de d�but d'imputation
 * @param : (String) $date2 date de fin d'imputation
 * @param : (int) $proj identitifiant du projet
 * @param : (int) $cat 
 * @param : (int) $user_id identitifiant collaborateur
 * @param : (String) page
 * @param : (String) $liste1
 * @param : (String) $liste2
 * @param : (String) $liste3
 * @param : (int) $gb
 */

function list_userbase_fu($proj, $cat, $user_id = "",$page = 1,$liste1, $liste2, $liste3, $gb ="",$gbL="") {

		$p = $page;
		$d = 0;
		$f = 10;
		$d = ($p -1)* $f;
		$liste = "'atest'";
		$aListP = array('0'=>$proj);
		$aCollabs = array();
		if($gb !="" or $proj !=""){
			$aCollabs = liste_group_user_bu_proj($gbL,$aListP);
		}
		if(sizeof($aCollabs) > 0){
			foreach ($aCollabs as $sUsename){
				if($liste == ""){
					$liste = "'".$sUsename."'";
				}else{
					$liste .=",'".$sUsename."'";
				}
			}
		}
	
		if($user_id !="" or $gbL !="" or $proj !=""){
		  $sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase WHERE   userbase.id not in (select user from userbasestatus where status =0) ";
			if($user_id !=""){
				$sql .=" AND userbase.ID ='".$user_id ."' ";
			}elseif($gb !="" or $proj !=""){
				$sql .=" AND userbase.username IN (".$liste.")";
			}
			
			 $sql .=" ORDER BY userbase.username"; 
		}elseif($proj != ""){
			$pp = array('0'=>$proj);
			 $sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase WHERE   userbase.id not in (select user from userbasestatus where status =0) ";
			 $sql .=" AND userbase.username IN ".liste_group_user_f($pp);
			 $sql .=" ORDER BY userbase.username"; 
			
		}else {
		
		
				 $sql = "SELECT distinct (userbase.username),userbase.ID
						FROM userbase 
						WHERE 1=1 ";
				 
				 if($liste1 !="" && $liste2 !=""){
					 $sql .= "AND (userbase.username in (".$liste1.") or userbase.id in (".$liste2.") ) ";
				 }
				 
				 if($liste3!=""){
					$sql .= "AND  userbase.username not in (".$liste3.") ";
				 }
			
				if ($user_id != ""){
					$sql .= "AND userbase.ID =  '" . $user_id . "' ";
					
				}
				$sql .=" AND userbase.ID  not in (select user from userbasestatus where status =0) ";
				$sql .=" ORDER BY userbase.username";
		}
	
	if($p !='all')	{$sql .=" limit $d,$f";} 
		
	$query = mysql_query ( $sql ) or die ( 'eror1' );
	$i = 0;

	
	$tab = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$tab [$i]['username'] = $row [0];
		$tab [$i]['id'] = $row [1];
		$i ++;
	}
	
	return ($tab);
}




/**
 * Formater chaine (remplacer les caract�res sp�ciaux)
 * @param : (String) $chaine 
 * @return String
 */
function chaineformat($chaine){
 	$chaine = str_replace(array("@","�","�","�","�","�","�","�","�","�","�","�","�"), "a",
      str_replace(array("�","�","�","�","�","�","�","�"), "e",
      str_replace(array("�","�","�","�","�","�","�","�"), "u",
      str_replace(array("�","�","�","�","�","�","�","�"), "i",
      str_replace(array("�","�","�","�","�","�","�","�","�","�","�","�"), "o",
      str_replace(array("�","�"), "c",
      str_replace(array("�","�"), "n",$chaine)))))));
      return $chaine;
}

/**
 * nbpagefu : nombre de pages
 *
 * @param Integer $proj
 * @param Integer $cat
 * @param Integer $user_id
 * @param String $liste1
 * @param String $liste2
 * @param String $liste3
 * @param Integer $gb
 * @return Integer
 */
function nbpagefu($proj, $cat, $user_id = "", $liste1, $liste2, $liste3, $gb, $gbL="") {
		
	
		$liste = "'atest'";
		$aListP = array('0'=>$proj);
		$aCollabs = array();
		if($gb !="" or $proj !=""){
			$aCollabs = liste_group_user_bu_proj($gbL,$aListP);
		}
	
		if(sizeof($aCollabs) > 0){
			foreach ($aCollabs as $sUsename){
				if($liste == ""){
					$liste = "'".$sUsename."'";
				}else{
					$liste .=",'".$sUsename."'";
				}
			}
		}
	
		if($user_id !="" or $gbL !="" or $proj !=""){
		  $sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase WHERE   userbase.id not in (select user from userbasestatus where status =0) ";
			if($user_id !=""){
				$sql .=" AND userbase.ID ='".$user_id ."' ";
			}elseif($gb !="" or $proj !=""){
				$sql .=" AND userbase.username IN (".$liste.")";
			}
			
			 $sql .=" ORDER BY userbase.username"; 
		}elseif($proj != ""){
			$pp = array('0'=>$proj);
			 $sql = "SELECT DISTINCT(userbase.username),userbase.ID FROM userbase WHERE   userbase.id not in (select user from userbasestatus where status =0) ";
			 $sql .=" AND userbase.username IN ".liste_group_user_f($pp);
			 $sql .=" ORDER BY userbase.username"; 
			
		}else {
		
		
				 $sql = "SELECT distinct (userbase.username),userbase.ID
						FROM userbase 
						WHERE 1=1 ";
				 
				 if($liste1 !="" && $liste2 !=""){
					 $sql .= "AND (userbase.username in (".$liste1.") or userbase.id in (".$liste2.") ) ";
				 }
				 
				 if($liste3!=""){
					$sql .= "AND  userbase.username not in (".$liste3.") ";
				 }
			
				if ($user_id != ""){
					$sql .= "AND userbase.ID =  '" . $user_id . "' ";
					
				}
				$sql .=" AND userbase.ID  not in (select user from userbasestatus where status =0) ";
				$sql .=" ORDER BY userbase.username";
		}
	
		

	
	$query = mysql_query ( $sql ) or die ( 'error' );
	$nb = mysql_num_rows($query);
	
	return ($nb/10);
}

/**
 * nbpage : nombre de pages
 *
 * @param Integer $proj
 * @param Integer $cat
 * @param Integer $user_id
 * @param String $liste1
 * @param String $liste2
 * @param String $liste3
 * @return Integer
 */
function nbpage($date1, $date2, $proj, $cat, $user_id = "", $liste1, $liste2, $liste3) {
		
		if ($cat != "") {
		$sql = "SELECT DISTINCT(userbase.username) 
		  FROM userbase, imputation, nodeassociation 
		  WHERE userbase.ID = imputation.user
		  ";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND imputation.project = nodeassociation.SOURCE_NODE_ID
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			ORDER BY userbase.username";
		if ($proj != "") {
			$sql = "SELECT DISTINCT(userbase.username) FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user";
			if ($user_id != "")
				$sql .= " AND userbase.ID =  '" . $user_id . "'";
			$sql .= " AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
		}
	} elseif ($proj != "") {
		$sql = "SELECT DISTINCT(userbase.username) FROM userbase, imputation 
			   WHERE userbase.ID = imputation.user";
		if ($user_id != "")
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
		$sql .= "   AND imputation.project=" . $proj . "
			   AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			   ORDER BY userbase.username";
	} 

	else {
		
		 $sql = "SELECT distinct (userbase.username) 
				FROM userbase 
				WHERE 1=1 ";
		 if($liste1 !="" && $liste2 !=""){
			 $sql .= " AND (userbase.username in (".$liste1.") or userbase.id in (".$liste2.") ) ";
		 }
		 
		 if($liste3!=""){
		 	$sql .= " AND  userbase.username not in (".$liste3.") ";
		 } 
			
		if ($user_id != ""){
			$sql .= " AND userbase.ID =  '" . $user_id . "'";
			
		}
		$sql .=" ORDER BY userbase.username";
	}
	
	$query = mysql_query ( $sql ) or die ( 'error' );
	$nb = mysql_num_rows($query);
	
	return ($nb/10);
}

/**
 * liste1 : membershipbase
 *
 * @param String $type
 * @return String
 */

function liste1($type){
	$sql="select USER_NAME from membershipbase where GROUP_NAME = '$type' ";
	$query = mysql_query ( $sql ) or die ( 'eror2' );
	$liste = "";
	while ( $row = mysql_fetch_row ( $query ) ) {
		if($liste ==""){
			$liste = "'".$row [0]."'";
		}else{
			$liste.=",'".$row [0]."'";
		}
	}
	
	
	return $liste;
}

/**
 * liste2: imputation
 *
 * @param String $date1
 * @param String $date2
 * @return String
 */
function liste2($date1,$date2){
	$sql="select distinct user from imputation where Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	$query = mysql_query ( $sql ) or die ( 'eror3' );
	$liste = "";
	while ( $row = mysql_fetch_row ( $query ) ) {
		if($liste ==""){
			$liste = "'".$row [0]."'";
		}else{
			$liste.=",'".$row [0]."'";
		}
	}
	if($liste == "") $liste = '0';
	return $liste;
}

/**
 * GetImputationUser
 *
 * @param String $date1
 * @param String $date2
 * @param String $proj
 * @return String
 */
function GetImputationUser($date1, $date2, $proj) {
	$select = "SELECT 
				round( sum( imputation ) , 2 ) AS somme, 
				userbase.username, 
				imputation.Date, 
				imputation.user
			FROM userbase
				LEFT JOIN imputation ON imputation.user = userbase.ID
			WHERE imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	if (! empty ( $proj )) {
		$select .= " AND imputation.project=" . $proj;
	}
	 $select.= " GROUP BY imputation.Date, imputation.user
		   ORDER BY userbase.username, imputation.Date";
	 
	$query = mysql_query ( $select );
	return ($query);
}

/**
 * GetImputationUser_f
 *
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $pg
 * @return String
 */

function GetImputationUser_f($date1, $date2, $proj,$pg=1) {

	$d = 0;
	$f = 10;
	$d = ($pg -1)* $f;
	$select = "SELECT 
				round( sum( imputation ) , 2 ) AS somme, 
				userbase.username, 
				imputation.Date, 
				imputation.user
			FROM userbase
				LEFT JOIN imputation ON imputation.user = userbase.ID
			WHERE imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	if (! empty ( $proj )) {
		$select .= " AND imputation.project=" . $proj;
	}
	$select .= " GROUP BY imputation.Date, imputation.user
		   ORDER BY userbase.username, imputation.Date";
	if($pg !='all')	{$selectql .=" limit $d,$f";} 
	$query = mysql_query ( $select );
	return ($query);
}

/**
 * GetDetailImputation_user
 *
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $user_id
 * @return String
 */

function GetDetailImputation_user($date1, $date2, $proj, $user_id="") {
	
	$requete = "SELECT round(sum(imputation.imputation), 2), project.pname, imputation.user, imputation.Date,imputation.issue, jiraissue.pkey, jiraissue.SUMMARY ,imputation.ID,project.ID
			FROM project, imputation, jiraissue
			WHERE project.ID = imputation.project
			AND jiraissue.ID = imputation.issue
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	if($user_id!= ""){
		$requete .= " AND imputation.user=" . $user_id;
	}
	if ($proj != "") {
		$requete .= " AND imputation.project=" . $proj;
	}
	$requete .= " GROUP BY project.pname, imputation.user, imputation.Date";
	
	$query = mysql_query ( $requete );
	$Timp = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$login = login_user ( $row [2] );
		$proj = $row [1];
		$date = $row [3];
		$Timp [$login] [$proj] [$date] = $row [0];
		
	}
	return ($Timp);
}

/**
 * GetDetailImputation_user_details : details imputations user
 *
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $user_id
 * @param String $login
 * @return mixed
 */

function GetDetailImputation_user_details($date1, $date2, $proj, $user_id="",$login) {
	$requete = "SELECT  round(sum(imputation.imputation), 2), project.pname, imputation.user, imputation.Date,imputation.issue, jiraissue.pkey, jiraissue.SUMMARY,imputation.ID,project.ID,imputation.validation, imputation.valide_par
			FROM project, imputation, jiraissue
			WHERE project.ID = imputation.project
			AND jiraissue.ID = imputation.issue
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	if($user_id!= ""){
		$requete .= " AND imputation.user=" . $user_id;
	}
	if ($proj != "") {
		$requete .= " AND imputation.project=" . $proj;
	}
	$requete .= " GROUP BY project.pname, imputation.user, imputation.Date,imputation.issue";

	$query = mysql_query ( $requete );
	$Timp = array ();
	$i= 0;
	while ( $row = mysql_fetch_row ( $query ) ) {
		$proj = $row [1];
		$date = $row [3];
		$issue = $row [4];
		$Timp[$login] [$proj][$issue]['tmp'][$date] = $row [0];
		$Timp[$login] [$proj][$issue]['id'][$date] = $row [7];
		$Timp[$login] [$proj][$issue]['projid'][$date] = $row [8];
		$Timp[$login] [$proj][$issue]['tache'][$date] = $row [5] . "&nbsp;:&nbsp;" . $row [6];
		$Timp[$login] [$proj][$issue]['validation'][$date] = $row [9];
		$Timp[$login] [$proj][$issue]['valider_par'][$date] = $row [10];
		$i++;
	}
	return ($Timp);
}

/**
 * Getissueprojet_user
 *
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $user_id
 * @param String $login
 * @return mixed
 */
function Getissueprojet_user($date1, $date2, $proj, $user_id="",$login) {
	$requete = "select distinct(imputation.issue), jiraissue.pkey, jiraissue.SUMMARY 
			FROM project, imputation, jiraissue
			WHERE project.ID = imputation.project
			AND jiraissue.ID = imputation.issue
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	if($user_id!= ""){
		$requete .= " AND imputation.user=" . $user_id;
	}
	if ($proj != "") {
		$requete .= " AND imputation.project=" . $proj;
	}
	$requete .= " GROUP BY project.pname, imputation.user, imputation.Date,imputation.issue";

	$query = mysql_query ( $requete );
	$Timp = array ();
	$j= 0;
	$result = array();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j]['tache'] = $row [1]."  :".$row [2];
		$result [$j]['id'] = $row [0];
		$j ++;
	}
	return ($result);
}

/**
 * nbre_jour_fer : nombre de jour ferie
 *
 * @param String $date_deb
 * @param String $date_fin
 * @return Integer
 */
function nbre_jour_fer($date_deb, $date_fin) {
	
	$sql_jf = "SELECT * FROM `jour_ferie` where date between '$date_deb' and '$date_fin' ";
	$req_jf = mysql_query ( $sql_jf );
	$data_jf = mysql_fetch_array ( $req_jf );
	$nb_f = mysql_num_rows ( $req_jf );
	return ($nb_f);
}

/**
 * jour_fer : recuperer les jours ferie
 *
 * @param integer $mois
 * @param integer $annee
 * @return mixed
 */
function jour_fer($mois, $annee) {
	$ajFer = array();
	$sql_jf = "SELECT DATE_FORMAT(Date,'%d') as Date FROM `jour_ferie` where MONTH(Date) ='$mois' and YEAR(Date) ='$annee' ";
	$req_jf = mysql_query ( $sql_jf );
	$p = 0;
	$List = array();
	while ( $row = mysql_fetch_row ( $req_jf ) ) {
		$List [$p] = $row [0];
		$p ++;
	}
	return ($List);
}


function getSamedi ($mois, $annee) {
     $jour = 1;
    $cpt = 0;
    for($i =$jour; $i< 31; $i++){
    	 if(checkdate ($mois, $i, $annee) === true){
    	 $stamp = mktime (0,0,0,$mois,$i, $annee);
    	
	    	 if (date ('D', $stamp) === 'Sat'){
	            $cpt ++;
	        }
    	 }
    }
    return $cpt;
}



function getDimanche ($mois, $annee) {
    $jour = 1;
    $cpt = 0;
    for($i =$jour; $i< 31; $i++){
    	 if(checkdate ($mois, $i, $annee) === true){
    	 $stamp = mktime (0,0,0,$mois,$i, $annee);
    	
	    	 if (date ('D', $stamp) === 'Sun'){
	            $cpt ++;
	        }
    	 }
    }
     
    return $cpt;
}

/**
 * jour_ferD : recuperer les jours ferie
 *
 * @param integer $mois
 * @param integer $annee
 * @return mixed
 */
function jour_ferD($mois, $annee) {
	$ajFer = array();
	$sql_jf = "SELECT DATE_FORMAT(Date,'%Y-%m-%d') as Date FROM `jour_ferie` where MONTH(Date) ='$mois' and YEAR(Date) ='$annee' ";
	$req_jf = mysql_query ( $sql_jf );
	$p = 0;
	$List = array();
	while ( $row = mysql_fetch_row ( $req_jf ) ) {
		$List [$p] = $row [0];
		$p ++;
	}
	return ($List);
	
}


/**
 * type_issue : type de la t�che (issue)
 *
 * @param integer $IdProj
 * @return mixed
 */
function type_issue($IdProj) {
	
	$sql = "SELECT DISTINCT (issuetype.pname), issuetype.ID
		  FROM jiraissue, project, issuetype
		  WHERE project.ID = jiraissue.PROJECT
			    AND project.ID = '" . $IdProj . "'
			    AND jiraissue.issuetype = issuetype.ID";
	
	$query = mysql_query ( $sql );
	$p = 0;
	while ( $row = mysql_fetch_row ( $query ) ) {
		$List [$p] ['id'] = $row [1];
		$List [$p] ['name'] = $row [0];
		$p ++;
	}
	return ($List);
}


/**
 * jour_fer : recuperer les jours ferie
 *
 * @param integer $mois
 * @param integer $annee
 * @return mixed
 */
function jour_fer_format($mois, $annee) {
	$ajFer = array();
	$sql_jf = "SELECT Date FROM `jour_ferie` where MONTH(Date) ='$mois' and YEAR(Date) ='$annee' ";
	$req_jf = mysql_query ( $sql_jf );
	$p = 0;
	$List = array();
	while ( $row = mysql_fetch_row ( $req_jf ) ) {
		$List [$p] = $row [0];
		$p ++;
	}
	return ($List);
}

/**
 * GetDetailImputation :  details imputations
 *
 * @param String $date1
 * @param String $date2
 * @param String $proj
 * @return mixed
 */
function GetDetailImputation($date1, $date2, $proj) {
	$requete = "SELECT round(sum(imputation.imputation), 2), project.pname, imputation.user, imputation.Date
			FROM project, imputation, jiraissue
			WHERE project.ID = imputation.project
			AND jiraissue.ID = imputation.issue
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	
	if ($proj != "") {
		$requete .= " AND imputation.project=" . $proj;
	}
	$requete .= " GROUP BY project.pname, imputation.user, imputation.Date";
	$query = mysql_query ( $requete );
	$Timp = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$login = login_user ( $row [2] );
		$proj = $row [1];
		$date = $row [3];
		$Timp [$login] [$proj] [$date] = $row [0];
	}
	return ($Timp);
}

/**
 * GetDetailImputationIssue : details imputation d'une t�che
 *
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @return mixed
 */
function GetDetailImputationIssue($date1, $date2, $proj) {
	$requete = "SELECT round(sum(imputation.imputation), 2), 
					project.pname, 
					imputation.user,  
					imputation.Date, 
					jiraissue.ID,
					jiraissue.summary, 
					imputation.ID,
					round(sum(imputation.facturation), 2)
			FROM project, imputation, jiraissue
			WHERE project.ID = imputation.project
			AND jiraissue.ID = imputation.issue
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	
	if ($proj != "") {
		$requete .= " AND imputation.project=" . $proj;
	}
	$requete .= " GROUP BY project.pname, imputation.user, imputation.Date";
	
	$query = mysql_query ( $requete );
	$Timp = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$login = login_user ( $row [2] );
		$proj = $row [1];
		$date = $row [3];
		$issue_id = $row [4];
		$issue_name = $row [5];
		$Timp [$login] [$proj] [$issue_id] [$date] = array ($row [0], $row [7] );
		$Timp [$login] [$proj] [$issue_id] ["Label_Issue"] = $issue_name;
		$Timp [$login] [$proj] [$issue_id] ["Id_Imputation"] = $row [6];
	
	}
	return ($Timp);
}


/**
 * select_project : details project user
 *
 * @param Integer $id_user
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $cat
 * @return mixed
 */
function select_project($id_user, $date1, $date2, $proj, $cat) {
	if ($cat != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM imputation, project, nodeassociation
			WHERE project.ID = imputation.project
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND project.ID = nodeassociation.SOURCE_NODE_ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			AND imputation.user = " . $id_user;
		
		if ($proj != "") {
			$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . " 
			 AND imputation.user = " . $id_user;
		}
	} elseif ($proj != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . "
			 AND imputation.user = " . $id_user;
	} else {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	}
	
	$query = mysql_query ( $Req_proj );
	$j = 0;
	$result = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j] = $row [0];
		$j ++;
	}
	return ($result);
}


/**
 * select_project_s : details project user
 *
 * @param Integer $id_user
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $cat
 * @return mixed
 */
function select_project_s($id_user, $date1, $date2, $proj, $cat) {
	if ($cat != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM imputation, project, nodeassociation
			WHERE project.ID = imputation.project
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND project.ID = nodeassociation.SOURCE_NODE_ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			AND imputation.user = " . $id_user;
		
		if ($proj != "") {
			$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . " 
			 AND imputation.user = " . $id_user;
		}
	} elseif ($proj != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . "
			 AND imputation.user = " . $id_user;
	} else {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	}

	$query = mysql_query ( $Req_proj );
	$j = 0;
	$result = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j]['pname'] = $row [0];
		$result [$j]['id'] = $row [1];
		$j ++;
	}
	return ($result);
}

/**
 * select_project_f : details project user
 *
 * @param Integer $id_user
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $cat
 * @return mixed
 */
function select_project_f($id_user, $date1, $date2, $proj, $cat) {
	$Req_proj ="SELECT DISTINCT (imputation.user) 
			FROM imputation 
			WHERE imputation.user = ".$id_user." 
	 		AND imputation.Date BETWEEN '$date1' AND '$date2'";
	
	$query = mysql_query ( $Req_proj );
	$j = 0;
	$result = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j] = $row [0];
		$j ++;
	}
	return ($result);
}

/**
 * select_project_new : details project user
 *
 * @param Integer $id_user
 * @param String $date1
 * @param String $date2
 * @param Integer $proj
 * @param Integer $cat
 * @return mixed
 */
function select_project_new($id_user, $date1, $date2, $proj, $cat) {
	if ($cat != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM imputation, project, nodeassociation
			WHERE project.ID = imputation.project
			AND nodeassociation.SINK_NODE_ENTITY = 'ProjectCategory'
		    AND project.ID = nodeassociation.SOURCE_NODE_ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
		    AND nodeassociation.SINK_NODE_ID = " . $cat . "
			AND imputation.user = " . $id_user;
		
		if ($proj != "") {
			$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . " 
			 AND imputation.user = " . $id_user;
		}
	} elseif ($proj != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj . "
			 AND imputation.user = " . $id_user;
	} else {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project, imputation
			WHERE imputation.user = " . $id_user . "
			AND imputation.project = project.ID
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
	}
	$query = mysql_query ( $Req_proj );
	$j = 0;
	$result = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j] [0] = $row [0];
		$result [$j] [1] = $row [1];
		$j ++;
	}
	return ($result);
}

/**
 * GetListProject: get List project by category
 *
 * @param Integer $cat
 * @return mixed
 */
function GetListProject($cat) {
	$i = 0;
	$ListProj = array ();
	if ($cat == "") {
		$sql = "SELECT 
                DISTINCT(project.ID),
				project.pname 
				  FROM project where 
				  project.ID not in(select na.source_node_id 
				from nodeassociation na
				where 
				na.source_node_entity = 'Project'
				and na.sink_node_entity = 'PermissionScheme'
				and na.association_type = 'ProjectScheme'
				and na.sink_node_id = 10220
				) order by project.pname ";
	} else {
		$sql = "SELECT project.ID, project.pname
		  FROM project, nodeassociation 
		  WHERE SINK_NODE_ENTITY = 'ProjectCategory'
		  AND project.ID = nodeassociation.SOURCE_NODE_ID
		  AND SINK_NODE_ID = " . $cat . "
		  ORDER BY pname";
	
	}
	$query = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query ) ) {
		$ListProj [$i] = $row [0] . "||" . $row [1];
		$i ++;
	}
	return ($ListProj);
}


/**
 * GetListProjectHisto: get List historic project by category
 *
 * @param Integer $cat
 * @return mixed
 */
function GetListProjectHisto($user) {
	$i = 0;
	$ListProj = array ();
		$sql = "SELECT DISTINCT (project.ID), project.pname
			FROM project 
			left join tetd_project_validate on(tetd_project_validate.project = project.ID)
			left join imputation on(imputation.project = project.ID)
			where tetd_project_validate.user =" . $user . " AND  
				  project.ID  in(select na.source_node_id 
				from nodeassociation na
				where 
				na.source_node_entity = 'Project'
				and na.sink_node_entity = 'PermissionScheme'
				and na.association_type = 'ProjectScheme'
				and na.sink_node_id = 10220
				) order by project.pname ";
	
	$query = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query ) ) {
		$ListProj [$i] = $row [0] . "||" . $row [1];
		$i ++;
	}
	return ($ListProj);
}

/**
 *  select_project_histo: get List historic project by category and user
 *
 * @param Integer $id_user
 * @param String $date1
 * @param String $date2
 * @param String $proj
 * @param Integer $cat
 * @param Integer $user
 * @return mixed
 */

function select_project_histo($id_user, $date1, $date2, $proj, $cat,$user) {
	if ($proj != "") {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project 
			left join tetd_project_validate on(tetd_project_validate.project = project.ID)
			left join imputation on(imputation.project = project.ID)
			where tetd_project_validate.user =" . $user . "
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'
			 AND imputation.project=" . $proj." 
		AND imputation.user = " . $id_user;
	} else {
		$Req_proj = "SELECT DISTINCT (project.pname), project.ID
			FROM project 
			left join tetd_project_validate on(tetd_project_validate.project = project.ID)
			left join imputation on(imputation.project = project.ID)
			where tetd_project_validate.user =" . $user . "
			AND imputation.Date BETWEEN '" . $date1 . "' AND '" . $date2 . "'  
		AND imputation.user = " . $id_user;
	}
	
	$query = mysql_query ( $Req_proj );
	$j = 0;
	$result = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		$result [$j] = $row [0];
		$j ++;
	}
	return ($result);
}

/**
 * GetListCategorie : liste des categories
 *
 * @return mixed
 */
function GetListCategorie() {
	$i = 0;
	$ListCat = array ();
	$sql = "SELECT ID, cname
		  FROM projectcategory
		  ORDER BY cname";
	$query = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query ) ) {
		$ListCat [$i] = $row [0] . "||" . $row [1];
		$i ++;
	}
	return ($ListCat);
}


/**
 * retrouver les projet d'un collaborateur donn
 *
 * @param integer $idUser
 */
function getListProjets($idUser) {
	
	$i = 0;
	$ListProj = array ();
	if (is_numeric ( $idUser )) {
		$sql = "select distinct(prj.ID),prj.pname 
		 from project prj
		 INNER JOIN imputation imp on (prj.ID = imp.Project)
		 WHERE imp.user = " . $idUser;
		$query = mysql_query ( $sql );
		while ( $row = mysql_fetch_row ( $query ) ) {
			$ListProj [$i] ["ID"] = $row [0];
			$ListProj [$i] ["LABEL"] = $row [1];
			$i ++;
		}
	}
	return ($ListProj);
}

/**
 * url de contact
 *
 * @param mixed $aUrl
 * @param String $sKey
 * @param String $sValue
 * @return String $sUrl
 */
function myConcat($aUrl, $sKey, $sValue) {
	$sUrl = "";
	
	if (count ( $aUrl )) {
		$aTmp = array ();
		foreach ( $aUrl as $k => $v ) {
			if ($sKey != $k) {
				$aTmp [] = "$k=$v";
			}
		}
		$sLink = "";
		if (count ( $aTmp )) {
			$sUrl = implode ( "&", $aTmp );
			$sLink = "&";
		}
		$sUrl .= $sLink . $sKey . "=" . $sValue;
	}
	return $sUrl;
}

/**
 * getDefaultRole: Role par defaut
 *
 * @param Integer $projid
 * @param Integer $userid
 * @return String
 */
function getDefaultRole($projid, $userid) {
	$sqlRole = "SELECT id_project_role 
				FROM inv_appli_user_role
				WHERE id_project = '" . $projid . "' 
				AND id_user_base = '" . $userid . "'";
	$query1 = mysql_query ( $sqlRole );
	$row1 = mysql_fetch_row ( $query1 );
	return $row1 [0];
}

/**
 * getFacturation : facturation
 *
 * @param Integer $projid
 * @return Integer
 */
function getFacturation($projid) {
	$sqlfacturation = " SELECT count(*)
	                   FROM  tetd_project_non_facturable
	                   WHERE ID='$projid'";
	$query = mysql_query ( $sqlfacturation );
	$row1 = mysql_fetch_row ( $query );
	$fact = 0;
	if ($row1 [0] > 0) {
		$fact = 2;
	}
	return $fact;
}

/**
 * Enter listPojectRole : liste des projets
 *
 * @param Integer $userid
 * @param Integer $projid
 * @param Integer $identifiant
 * @param Integer $RoleId
 * @param String $listId
 */
function listPojectRole($userid, $projid, $identifiant, $RoleId, $listId) {
	
	$roleDefault = getDefaultRole ( $projid, $userid );
	if (! $RoleId) {
		$RoleId = $roleDefault;
	}
	//selection du login, et du groupe 
	$sqllogin = "SELECT proGroup.USER_NAME, proGroup.GROUP_NAME 
				FROM membershipbase proGroup
				LEFT JOIN userbase user on (user.username = proGroup.USER_NAME)
				WHERE user.ID = '$userid'";
	$query = mysql_query ( $sqllogin );
	$bAdd = false;
	$aCond = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		if (! $bAdd) {
			$aCond [] = "'" . $row [0] . "'";
			$bAdd = true;
		}
		$aCond [] = "'" . $row [1] . "'";
	}
	$cond = "(" . implode ( ",", $aCond ) . ")";
	
	$sql = " SELECT role.ID, role.NAME from projectrole role 
	         INNER JOIN projectroleactor roleactor on (role.ID = roleactor.PROJECTROLEID) 
	         AND roleactor.PID = '" . $projid . "'
	         AND roleactor.ROLETYPEPARAMETER IN $cond ";
	$inputHidden = "<input type=hidden id='hid" . $listId . "' value='$identifiant'/>";
	$inputDefaultRole = "<input type=hidden id='defaultRole" . $listId . "' value='$RoleId'/>";
	$select = '<select name="select_role_project" id="imp' . $listId . '"  class="input-valid" onclick="updateRole(\'' . $identifiant . '\', this.value)">';
	$query2 = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query2 ) ) {
		$select .= '<option value="' . $row [0] . '"';
		if ($RoleId == $row [0])
			$select .= 'selected';
		$select .= '>' . $row [1] . '</option>';
	}
	$select .= '</select>';
	echo $select . $inputHidden . $inputDefaultRole;
}

/**
 * validate_project : get projet valide
 *
 * @param Ineteger $id
 * @return mixed
 */
function validate_project($id){
	$sql = "SELECT distinct(user) from tetd_project_validate
				WHERE project= '$id'";
	
	$query = mysql_query ( $sql );
	$aUser = array ();
	$i = 0;
	while ( $row = mysql_fetch_row ( $query ) ) {
		$aUser[$i]['id'] = $row[0];
		$aUser[$i]['name'] = nom_prenom_user($row[0]);
		$i++;
	}
	return $aUser;
}


/**
 * validate_group : get groupe valideur
 *
 * @param Ineteger $id
 * @return mixed
 */
function validate_group($id){
	$sql = "SELECT distinct(user) from tetd_group_validate
				WHERE groupe= '$id'";
	$query = mysql_query ( $sql );
	$aUser = array ();
	$i = 0;
	while ( $row = mysql_fetch_row ( $query ) ) {
		$aUser[$i]['id'] = $row[0];
		$aUser[$i]['name'] = nom_prenom_user($row[0]);
		$i++;
	}
	return $aUser;
}


/**
 * listPojectRole_f
 *
 * @param Integer $userid
 * @param Integer $projid
 * @param Integer $identifiant
 * @param Integer $RoleId
 * @param String $listId
 * @param Integer $val_validation
 * @param booleen $valideV
 */
function listPojectRole_f($userid, $projid, $identifiant, $RoleId, $listId,$val_validation,$valideV) {
	
	$roleDefault = getDefaultRole ( $projid, $userid );
	if (! $RoleId) {
		$RoleId = $roleDefault;
	}
	
	$style1 ="";
	$style2 ="";
	
	 //if($val_validation == 0 && $validV){$style1 ='style="display:none"';}
	 //if($val_validation != 0 && !$valideV){$style2 ="disabled=disabled";$style1='';}
	 $style1 ='style="display:none"';
	 
	//selection du login, et du groupe 
	$sqllogin = "SELECT proGroup.USER_NAME, proGroup.GROUP_NAME 
				FROM membershipbase proGroup
				LEFT JOIN userbase user on (user.username = proGroup.USER_NAME)
				WHERE user.ID = '$userid'";
	$query = mysql_query ( $sqllogin );
	$bAdd = false;
	$aCond = array ();
	while ( $row = mysql_fetch_row ( $query ) ) {
		if (! $bAdd) {
			$aCond [] = "'" . $row [0] . "'";
			$bAdd = true;
		}
		$aCond [] = "'" . $row [1] . "'";
	}
	$cond = "(" . implode ( ",", $aCond ) . ")";
	
	$sql = " SELECT role.ID, role.NAME from projectrole role 
	         INNER JOIN projectroleactor roleactor on (role.ID = roleactor.PROJECTROLEID) 
	         AND roleactor.PID = '" . $projid . "'
	         AND roleactor.ROLETYPEPARAMETER IN $cond ";
	$inputHidden = "<input type=hidden id='hid" . $listId . "' value='$identifiant'/>";
	$inputDefaultRole = "<input type=hidden id='defaultRole" . $listId . "' value='$RoleId'/>";
	$select = '<select name="select_role_project" '.$style1.' '.$style2.' id="imp' . $listId . '"  class="input-valid select_'.$identifiant.'" onclick="updateRole(\'' . $identifiant . '\', this.value)">';
	$query2 = mysql_query ( $sql );
	while ( $row = mysql_fetch_row ( $query2 ) ) {
		$select .= '<option value="' . $row [0] . '"';
		if ($RoleId == $row [0])
			$select .= 'selected';
		$select .= '>' . $row [1] . '</option>';
	}
	$select .= '</select>';
	echo $select . $inputHidden . $inputDefaultRole;
}
?>
