<?php
session_start (); // On demarre la session

if (isset ( $_GET ['logout'] )) { // si l'utilisateur clike sur le lien se deconnecter
	session_destroy (); // On detruit la session
	header ( "Location: login.php" );
}
//si le le login n'est pas fourni par session donc redirection vers la page login.php
if (! isset ( $_SESSION ['login'] )) {
	header ( "Location: login.php" );
}

if ($_SESSION ['user_rapp'] == "actif") {
	echo '<script language="javascript">document.location.href="../templates/rapport_activite.php"</script>';
}
//echo "---".date_default_timezone_get()."---";

//print_r($_SESSION['groupetab']);
//$id = $_SESSION['id'];
$user = $_SESSION ['id'];
include "connexion.php";
include "fonctions.php";
/*getListOfWeek(04, 2010);
die();*/
/*******************Asma OUESLATI 27/03/2007
code lie aux parametrage des pages
 *****************/
// $lg est le parametre de langue et c est egal 1 pour le francais
$lg = 1;
$sql_parametres = "SELECT *
				 FROM parametres
				 WHERE langue=" . $lg;
$query_parametres = mysql_query ( $sql_parametres );
$tab_parametres = mysql_fetch_array ( $query_parametres );
/**fin requete de parametrage***/
$jour_max = $tab_parametres ['param_fermeture_imputation'];
$jour_cour = date ( 'd' );
$mois_cour = date ( 'm' );
$annee_cour = date ( 'Y' );
//if(!isset($y)) $y=$annee_cour;
if (isset ( $_GET ['y'] )) {
	$y = $_GET ['y'];
} else {
	$y = date ( "Y" );
}

if (($mois_cour == '01') && ($jour_cour < $jour_max)) {
	$annee_deb = $annee_cour - 1;
	$annee_fin = $annee_cour;
	if ($y == $annee_deb) {
		$mois_deb = $mois_fin = 12;
	} else {
		$mois_deb = $mois_fin = 01;
	}
	$mm = $mois_deb;
} else {
	if ($jour_cour < $jour_max) {
		$mois_deb = (date ( 'm' ) - 1);
	} else {
		$mois_deb = date ( 'm' );
	}
	$mois_fin = date ( 'm' );
	$annee_deb = date ( 'Y' );
	$annee_fin = date ( 'Y' );
}

$iMonth = date ( 'm' );
$iYear = date ( 'Y' );
$iYearNext = $iYear +1;


//exit();
$val_fer = 0;
$id_jour_fer = "";
$ancien_val_fer = 0;
$i = 0;
$j = 0;
$lien_bord = "inactif";
$lien_adm = "inactif";
$liste_group = membershipbase ( $user );
$_SESSION['liste_group'] = $liste_group;
$nb_group = count ( $liste_group );
$tab_group = array ();
for($i = 0; $i < $nb_group; $i ++) {
	$tab_group [$i] = $liste_group [$i];
}
for($j = 0; $j < $nb_group; $j ++) {
	$list_group = $tab_group [$j];
	if (strtolower ( $list_group ) == "td-tdbusers") {
		$lien_bord = "actif";
	}
	if (strtolower ( $list_group ) == "td-administrators") {
		$lien_adm = "actif";
	}
}

$msg_sam = "false";
$msg_dim = "false";
$test_week = "faux";
$update_week = "faux";
$ins_trav_fer = "faux";
$up_trav_fer = "faux";
$depasser_conge = "faux";
if (isset ( $_POST['week'] )) {
	$week = $_POST['week'];
} elseif (isset ( $_GET['week'] )) {
	$week = $_GET['week'];
} elseif(isset ( $_GET['mm']) && isset ( $_GET['y'])) {
	$week = weeknumber($_GET['y'], $_GET['mm'], '01');
	if($week>50 &&  $_GET['mm'] =='01') {
		$week = weeknumber($_GET['y']+1, 12, 31);
	}
}else {
	$week = weeknumber(date ( 'Y' ), date ( 'n' ), date( 'd'));
	if($week>50 &&  isset($_GET['mm']) && $_GET['mm'] =='01') {
		if(isset($_GET['y'])){
			$week = weeknumber($_GET['y']+1, 12, 31);
		}
	}
}

if (isset ( $_POST ['y'] )) {
	$y = $_POST ['y'];
} elseif (isset ( $_GET ['y'] )) {
	$y = $_GET ['y'];
} else {
	$y = date ( 'Y' );
}




if($iMonth ==12 && $y == $iYearNext){

}else{
	if (($y != date ( 'Y' )) and ($jour_cour > $jour_max)) {

		include ("erreur.php");
		die ();
	}
}

if (isset ( $_POST ['mm'] )) {
	$mm = $_POST ['mm'];
} elseif (isset ( $_GET ['mm'] )) {
	$mm = $_GET ['mm'];
} elseif (! isset ( $mm )) {
	$mm = date ( 'n' );
}
if (($mm < 1) || ($mm > 12)) {
	include ("erreur.php");
	die ();
}

if($mm != 1 && $y == $iYearNext){
	include ("erreur.php");
	die ();
}

if($iMonth ==12 && $y == $iYearNext){

}else{
	if (($mois_cour != '01') or ($jour_cour > $jour_max)) {
		if (($mm != date ( 'm' )) and ($mm != (date ( 'm' ) - 1))) {
			//echo 1;die;
			include ("erreur.php");
			die ();
		}
	}
}
if (($week < 1) || ($week > 54)) {
	include ("erreur.php");
	die ();
}

$listOfMonth=array(0=>01,1=>02,2=>03,3=>04,4=>05,5=>06,6=>07,7=>08,8=>09,9=>10,10=>11,11=>12);
$login = $_SESSION ['login']; //recuperation de la variable de session
if($week>50 && $mm=='01') {
	$years=$y-1;
	$mois_deb = $mois_fin = 12;
	$wknber = weeknumber($y, 12, 31);
	$annee = $years;
}
else {
	$years=$y;
	$wknber = $week;
}
$weekDay = getDaysInWeek ($wknber, $years);
//print_r($weekDay);

$reel_month = date('m');


$exe = explode("-", $weekDay[0]);
$d1 = $weekDay[0];
$date1 = $weekDay[0];
$d1_affich = $exe [2] . "-" . $exe [1];
$readOnly1='false';
$imput_month1 = $exe[1];
if($reel_month==$imput_month1){
	$readOnly1 = "false";
}else{
	if($imput_month1==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly1 = "true";
	}elseif($imput_month1==11 && $reel_month==01){
		$readOnly1 = "true";
	}elseif($imput_month1==12 && $reel_month==02){
		$readOnly1 = "true";
	}elseif($reel_month - $imput_month1 > 1){
		$readOnly1 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly1 = "true";
	}
}

/*if($exe[1]==12 && date('m')>01 && date('d') > $jour_max && date('m')!=12)  { $readOnly1 = "true"; echo "<br>1 readOnly1"; }
elseif($exe[1]==12 && date('m')>01 && date('m')!=12) { $readOnly1 = "true"; echo "<br>2 readOnly1"; }
elseif(($exe[1] - date('m')) > 1 && $exe[1]!=12) { $readOnly1 = "true"; echo "<br>3 readOnly1"; }
elseif(date('m') > $exe[1] && date('d') > $jour_max) { $readOnly1 = "true"; echo "<br>4 readOnly1"; }*/

$exe1 = explode("-", $weekDay[1]);
$d2 = $weekDay[1];
$date2 = $weekDay[1];
$d2_affich = $exe1 [2] . "-" . $exe1 [1];
$readOnly2="false";

/*if($exe1[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly2 = "true"; echo "<br>1 readOnly2"; }
elseif($exe1[1]==12 && date('m')>01 && date('m')!=12) { $readOnly2 = "true"; echo "<br>2 readOnly2"; }
elseif(($exe1[1] - date('m')) > 1 && $exe1[1]!=12) { $readOnly2 = "true"; echo "<br>3 readOnly2"; }
elseif(date('m') > $exe1[1] && date('d') > $jour_max) { $readOnly2 = "true"; echo "<br>4 readOnly2"; }*/

$imput_month2 = $exe1[1];
if($reel_month==$imput_month2){
	$readOnly2 = "false";
}else{
	if($imput_month2==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly2 = "true";
	}elseif($imput_month2==11 && $reel_month==01){
		$readOnly2 = "true";
	}elseif($imput_month2==12 && $reel_month==02){
		$readOnly2 = "true";
	}elseif($reel_month - $imput_month2 > 1){
		$readOnly2 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly2 = "true";
	}
}

$exe2 = explode("-", $weekDay[2]);
$d3 = $weekDay[2];
$date3 = $weekDay[2];
$d3_affich = $exe2 [2] . "-" . $exe2 [1];
$readOnly3="false";
/*if($exe2[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly3 = "true"; echo "<br>1 readOnly3"; }
elseif($exe2[1]==12 && date('m')>01 && date('m')!=12) { $readOnly3 = "true"; echo "<br>2 readOnly3"; }
elseif(($exe2[1] - date('m')) > 1 && $exe2[1]!=12) { $readOnly3 = "true"; echo "<br>3 readOnly3"; }
elseif(date('m') > $exe2[1] && date('d') > $jour_max) { $readOnly3 = "true"; echo "<br>4 readOnly3"; }*/

$imput_month3 = $exe2[1];
if($reel_month==$imput_month3){
	$readOnly3 = "false";
}else{
	if($imput_month3==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly3 = "true";
	}elseif($imput_month3==11 && $reel_month==01){
		$readOnly3 = "true";
	}elseif($imput_month3==12 && $reel_month==02){
		$readOnly3 = "true";
	}elseif($reel_month - $imput_month3 > 1){
		$readOnly3 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly3 = "true";
	}
}

$exe3 = explode("-", $weekDay[3]);
$d4 = $weekDay[3];
$date4 = $weekDay[3];
$d4_affich = $exe3 [2] . "-" . $exe3 [1];
$readOnly4="false";
/*if($exe3[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly4 = "true"; echo "<br>1 readOnly4"; }
elseif($exe3[1]==12 && date('m')>01 && date('m')!=12) { $readOnly4 = "true"; echo "<br>2 readOnly4"; }
elseif(($exe3[1] - date('m')) > 1 && $exe3[1]!=12) { $readOnly4 = "true"; echo "<br>3 readOnly4"; }
elseif(date('m') > $exe3[1] && date('d') > $jour_max) { $readOnly4 = "true"; echo "<br>4 readOnly4"; }*/

$imput_month4 = $exe3[1];
if($reel_month==$imput_month4){
	$readOnly4 = "false";
}else{
	if($imput_month4==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly4 = "true";
	}elseif($imput_month4==11 && $reel_month==01){
		$readOnly4 = "true";
	}elseif($imput_month4==12 && $reel_month==02){
		$readOnly4 = "true";
	}elseif($reel_month - $imput_month4 > 1){
		$readOnly4 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly4 = "true";
	}
}

$exe4 = explode("-", $weekDay[4]);
$d5 = $weekDay[4];
$date5 = $weekDay[4];
$d5_affich = $exe4 [2] . "-" . $exe4 [1];
$readOnly5="false";
/*if($exe4[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly5 = "true"; echo "<br>1 readOnly5"; }
elseif($exe4[1]==12 && date('m')>01 && date('m')!=12) { $readOnly5 = "true"; echo "<br>2 readOnly5"; }
elseif(($exe4[1] - date('m')) > 1 && $exe4[1]!=12) { $readOnly5 = "true"; echo "<br>3 readOnly5"; }
elseif(date('m') > $exe4[1] && date('d') > $jour_max) { $readOnly5 = "true"; echo "<br>4 readOnly5"; }*/
$imput_month5 = $exe4[1];
if($reel_month==$imput_month5){
	$readOnly5 = "false";
}else{
	if($imput_month5==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly5 = "true";
	}elseif($imput_month5==11 && $reel_month==01){
		$readOnly5 = "true";
	}elseif($imput_month5==12 && $reel_month==02){
		$readOnly5 = "true";
	}elseif($reel_month - $imput_month5 > 1){
		$readOnly5 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly5 = "true";
	}
}

$exe5 = explode("-", $weekDay[5]);
$d6 = $weekDay[5];
$date6 = $weekDay[5];
$d6_affich = $exe5 [2] . "-" . $exe5 [1];
$readOnly6="false";
/*if($exe5[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly6 = "true"; echo "<br>1 readOnly6"; }
elseif($exe5[1]==12 && date('m')>01 && date('m')!=12) { $readOnly6 = "true"; echo "<br>2 readOnly6"; }
elseif(($exe5[1] - date('m')) > 1 && $exe5[1]!=12) { $readOnly6 = "true"; echo "<br>3 readOnly6"; }
elseif(date('m') > $exe5[1] && date('d') > $jour_max) { $readOnly6 = "true"; echo "<br>4 readOnly6"; }*/

$imput_month6 = $exe5[1];
if($reel_month==$imput_month6){
	$readOnly6 = "false";
}else{
	if($imput_month6==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly6 = "true";
	}elseif($imput_month6==11 && $reel_month==01){
		$readOnly6 = "true";
	}elseif($imput_month6==12 && $reel_month==02){
		$readOnly6 = "true";
	}elseif($reel_month - $imput_month6 > 1){
		$readOnly6 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly6 = "true";
	}
}

$exe6 = explode("-", $weekDay[6]);
$d7 = $weekDay[6];
$date7 = $weekDay[6];
$d7_affich = $exe6 [2] . "-" . $exe6 [1];
$readOnly7="false";
/*if($exe6[1]==12 && date('m')>=01 && date('d') > $jour_max && date('m')!=12) { $readOnly7 = "true"; echo "<br>1 readOnly7"; }
elseif($exe6[1]==12 && date('m')>01 && date('m')!=12) { $readOnly7 = "true"; echo "<br>2 readOnly7"; }
elseif(($exe6[1] - date('m')) > 1 && $exe6[1]!=12) { $readOnly7 = "true"; echo "<br>3 readOnly7"; }
elseif(date('m') > $exe6[1] && date('d') > $jour_max) { $readOnly7 = "true"; echo "<br>4 readOnly7"; }*/

$imput_month7 = $exe6[1];
if($reel_month==$imput_month7){
	$readOnly7 = "false";
}else{
	if($imput_month7==12 && $reel_month==01){
		if(date('d') > $jour_max) $readOnly7 = "true";
	}elseif($imput_month7==11 && $reel_month==01){
		$readOnly7 = "true";
	}elseif($imput_month7==12 && $reel_month==02){
		$readOnly7 = "true";
	}elseif($reel_month - $imput_month7 > 1){
		$readOnly7 = "true";
	}else{
		if(date('d') > $jour_max) $readOnly7 = "true";
	}
}

if(isset($mm) && $mm !='' && isset($week) && $week !='' && isset($y) && $y !=''){
	$where = '?mm='.$mm.'&week='.$week.'&y='.$y;
}else{
	$where = '';
}


/*if($readOnly7=='true') {
	$readOnly1='true';
	$readOnly2='true';
	$readOnly3='true';
	$readOnly4='true';
	$readOnly5='true';
	$readOnly6='true';
}
if($readOnly6=='true') {
	$readOnly1='true';
	$readOnly2='true';
	$readOnly3='true';
	$readOnly4='true';
	$readOnly5='true';
}
if($readOnly5=='true') {
	$readOnly1='true';
	$readOnly2='true';
	$readOnly3='true';
	$readOnly4='true';
}
if($readOnly4=='true') {
	$readOnly1='true';
	$readOnly2='true';
	$readOnly3='true';
}
if($readOnly3=='true') {
	$readOnly1='true';
	$readOnly2='true';
}
if($readOnly2=='true') {
	$readOnly1='true';
}*/

?>
<!------------------------ MEDINI Mounira Le 06/03/2007 ------------------------------------->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php
echo $tab_parametres ['Imputation'];
?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<LINK media=print href="../style/global_printable.css" type=text/css
	rel=stylesheet>
<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>
<LINK href="../style/global.css" type=text/css rel=StyleSheet>

<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style>
</head>
<script type="text/javascript"
	src="../js/jquery.js">
	</script>

<SCRIPT language=JavaScript>//javascript pour l'action sur la liste deroulante

			function remplirFormDel(user, project, issue, mm, week, y, limps ){

				$('form#formdel #formdel_limps').val(limps);
				$('form#formdel #formdel_id').val(user);
				$('form#formdel #formdel_project').val(project);
				$('form#formdel #formdel_issue').val(issue);
				$('form#formdel #formdel_mm').val(mm);
				$('form#formdel #formdel_semaine').val(week);
				$('form#formdel #formdel_y').val(y);
				$('form#formdel').submit();
			}

			function changePage1(x)
			{
			if(x == "nothing")
			    {
			      document.forms[0].reset();
			      document.forms[0].elements[0].blur();
			      return;
			    }
			else
				{
				 location.href = x;
				}
			}
			function changePage2(x)
			{
			if(x == "nothing")
			    {
			      document.forms[0].reset();
			      document.forms[0].elements[0].blur();
			      return;
			    }
			else
				{
				 location.href = x;
				}
			}
function verif_champ(champ)
{
if (champ == "")
{ alert("La Saisie du champ RAF est obligatoire.");
return false;
}
document.form.submit();
return true;
}

function popup(page) {
	// ouvre une fenetre sans barre d'etat, ni d'ascenceur
	window.open(page,'popup','width=550,height=600,toolbar=no,scrollbars=No,resizable=yes,');
}
</script>
<body vLink=#003366 aLink=#cc0000 link=#003366 leftMargin=0 topMargin=0
	marginheight="0" marginwidth="0">
	<form action="supression.php?page=0" method="post"
	name="formdel" id='formdel'>
		<input type="hidden" name='limps' id='formdel_limps' value=''/>
		<input type="hidden" name='id' value='' id='formdel_id'/>
		<input type="hidden" name='project' value='' id='formdel_project'/>
		<input type="hidden" name='issue' value='' id='formdel_issue'/>
		<input type="hidden" name='mm' value='' id='formdel_mm'/>
		<input type="hidden" name='semaine' value='' id='formdel_semaine'/>
		<input type="hidden" name='y' value='' id='formdel_y'/>
	</form>
<form action="suivi_imputation.php<?php echo $where;?>" method="post"
	onSubmit="return loginCheck(this);" name="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	height="149">
	<tr valign="top">
		<td height="76"><?php
		include ("entete.php");
		?></td>
	</tr>

	<tr>
		<td> <?php
		echo tab_vide ( 9 );
		?></td>
	</tr>
	<tr>
		<td valign="top">
		<TABLE cellSpacing="0" cellPadding="1" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD vAlign="top" width="100%" colSpan="2">
					<TABLE cellSpacing="0" cellPadding="4" width="100%"
						bgColor="#ffffff" border="0">
						<TBODY>
							<TR>
								<TD vAlign="top" width="180%" bgColor="#EFEFEF">
								<TABLE cellSpacing="0" cellPadding="0" width="100%">
									<TBODY>
										<TR>
											<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left">
											<H3 class=formtitle><?php
											echo $tab_parametres ['fiche_suivi_hebdo'];
											?></H3>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								</TD>

						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE><?php
		echo tab_vide ( 9 );
		?>
		  <?php
				//des variables initialise pour etre utilise apres ds l'affichage de l'alerte
				$jourvide = 1;
				$num = 1;
				$imp = 1;
				$vide = 1;
				$somme = "vrai";
				$tache = 1;
				$som_lun = "vrai";
				$som_mar = "vrai";
				$som_mer = "vrai";
				$som_jeu = "vrai";
				$som_ven = "vrai";
				$som_sam = "vrai";
				$som_dim = "vrai";
				$check_conge_lun = "vrai";
				$check_conge_mar = "vrai";
				$check_conge_mer = "vrai";
				$check_conge_jeu = "vrai";
				$check_conge_ven = "vrai";
				$check_conge_sam = "vrai";
				$check_conge_dim = "vrai";

				$insert_raf = "vrai";
				$negatif = "faux";
				$date_jour_fer = "vrai";
				if (isset ( $_POST ['update'] )) {
					@$date1 = $_POST ['date1'];
					@$date7 = $_POST ['date7'];
					$date_lun = $_POST ['date_lun'];
					$date_mar = $_POST ['date_mar'];
					$date_mer = $_POST ['date_mer'];
					$date_jeu = $_POST ['date_jeu'];
					$date_ven = $_POST ['date_ven'];
					$date_sam = $_POST ['date_sam'];
					$date_dim = $_POST ['date_dim'];
					$lundi = $_POST ['lundi'];
					$mardi = $_POST ['mardi'];
					$mercredi = $_POST ['mercredi'];
					$jeudi = $_POST ['jeudi'];
					$vendredi = $_POST ['vendredi'];
					$samedi = $_POST ['samedi'];
					$dimanche = $_POST ['dimanche'];
					$comment = $_POST ['comment2'];
					//$comment = stripslashes($comment);
					$comment = str_replace ( "'", "\'", $comment );
					@$ancien_lundi = $_POST ['ancien_lundi'];
					@$ancien_mardi = $_POST ['ancien_mardi'];
					@$ancien_mercredi = $_POST ['ancien_mercredi'];
					@$ancien_jeudi = $_POST ['ancien_jeudi'];
					@$ancien_vendredi = $_POST ['ancien_vendredi'];
					@$ancien_samedi = $_POST ['ancien_samedi'];
					@$ancien_dimanche = $_POST ['ancien_dimanche'];
					$udate_comment = "faux";
					$raf = $_POST ['raf'];
					if ((strlen ( $raf ) != 0) and (is_numeric ( $raf ))) {
						$raf = format_number ( $raf );
					} else {
						$insert_raf = "faux";
						$input_raf = "vrai";
					}
					$newissue = $_POST ['newissue'];
					$sous_taches = $_POST ['sous_taches'];
					0^p-r$ù <

					$proj = $_POST ['proj'];
					//Si une des variables n'est pas defini alor elle sera initialise a 0 pour pouvoir la traiter ds le bloc is_numeric
					if (empty ( $lundi )) {
						$lundi = 0;
					}
					if (empty ( $mardi )) {
						$mardi = 0;
					}
					if (empty ( $mercredi )) {
						$mercredi = 0;
					}
					if (empty ( $jeudi )) {
						$jeudi = 0;
					}
					if (empty ( $vendredi )) {
						$vendredi = 0;
					}
					if (empty ( $samedi )) {
						$samedi = 0;
					}
					if (empty ( $dimanche )) {
						$dimanche = 0;
					}
					//voir si au moin une imputation est vide.
					$jour = "vrai";
					$numerique = "vrai";
					$imputation = "vrai";
					//voir si ttes ttes les imputations sont vides
					if ((empty ( $lundi )) && (empty ( $mardi )) && (empty ( $mercredi )) && (empty ( $jeudi )) && (empty ( $vendredi )) && (empty ( $samedi )) && (empty ( $dimanche ))) {
						$jour = "faux";
						$vide = 0; //ca sert pour afficher le message d'erreur en bas de page si tte les imputations sont vides
					}

					//voir si au moin une imputation n'est pas numerique
					if ((! is_numeric ( $lundi )) || (! is_numeric ( $mardi )) || (! is_numeric ( $mercredi )) || (! is_numeric ( $jeudi )) || (! is_numeric ( $vendredi )) || (! is_numeric ( $samedi )) || (! is_numeric ( $dimanche ))) {
						if (! is_numeric ( $lundi )) {
							$input_lun = "vrai";
						}
						if (! is_numeric ( $mardi )) {
							$input_mar = "vrai";
						}
						if (! is_numeric ( $mercredi )) {
							$input_mer = "vrai";
						}
						if (! is_numeric ( $jeudi )) {
							$input_jeu = "vrai";
						}
						if (! is_numeric ( $vendredi )) {
							$input_ven = "vrai";
						}
						if (! is_numeric ( $samedi )) {
							$input_sam = "vrai";
						}
						if (! is_numeric ( $dimanche )) {
							$input_dim = "vrai";
						}
						if (! is_numeric ( $raf )) {
							$input_raf = "vrai";
						}

						$numerique = "faux";
						$num = 0; //ca sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur non numerique
					}

					//voir si au moin une imputation a une valeur superieur e 1
					if (($lundi > 1) || ($mardi > 1) || ($mercredi > 1) || ($jeudi > 1) || ($vendredi > 1) || ($samedi > 1) || ($dimanche > 1)) {
						if ($lundi > 1) {
							$input_lun = "vrai";
						}
						if ($mardi > 1) {
							$input_mar = "vrai";
						}
						if ($mercredi > 1) {
							$input_mer = "vrai";
						}
						if ($jeudi > 1) {
							$input_jeu = "vrai";
						}
						if ($vendredi > 1) {
							$input_ven = "vrai";
						}
						if ($samedi > 1) {
							$input_sam = "vrai";
						}
						if ($dimanche > 1) {
							$input_dim = "vrai";
						}
						$imputation = "faux";
						$imp = 0; //ea sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur > e 1
					}
					if (($raf < 0) || ($lundi < 0) || ($mardi < 0) || ($mercredi < 0) || ($jeudi < 0) || ($vendredi < 0) || ($samedi < 0) || ($dimanche < 0)) {
						if ($lundi < 0) {
							$input_lun = "vrai";
						}
						if ($mardi < 0) {
							$input_mar = "vrai";
						}
						if ($mercredi < 0) {
							$input_mer = "vrai";
						}
						if ($jeudi < 0) {
							$input_jeu = "vrai";
						}
						if ($vendredi < 0) {
							$input_ven = "vrai";
						}
						if ($samedi < 0) {
							$input_sam = "vrai";
						}
						if ($dimanche < 0) {
							$input_dim = "vrai";
						}
						if ($raf < 0) {
							$input_raf = "vrai";
						}
						$imputation = "faux";
						$negatif = "vrai"; //ea sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur < e 0
					}

					if (($sous_taches == "faux") || (empty ( $proj ))) {
						$tache = 0;
					}
					if (($newissue == "faux") || (empty ( $proj ))) {
						$tache = 0;
					}

					if ($proj == 10064) {
						$issuetype = check_type_issue ( $newissue );
						$check_conge_lun = check_date_conge ( $date_lun, $lundi, $newissue );
						$check_conge_mar = check_date_conge ( $date_mar, $mardi, $newissue );
						$check_conge_mer = check_date_conge ( $date_mer, $mercredi, $newissue );
						$check_conge_jeu = check_date_conge ( $date_jeu, $jeudi, $newissue );
						$check_conge_ven = check_date_conge ( $date_ven, $vendredi, $newissue );
						$check_conge_sam = check_date_conge ( $date_sam, $samedi, $newissue );
						$check_conge_dim = check_date_conge ( $date_dim, $dimanche, $newissue );
					}
					//	Si tt est bon alors il y'aura insertion dans la base
					if ($tache != 0) {
						if (($jour != "faux") && (is_numeric ( $lundi )) && ($lundi <= 1) && ($tache != 0) && ($lundi >= 0) && ($insert_raf == "vrai") && ($check_conge_lun == "vrai")) {
							//appel de la fonction update
							// Recupere l'heure
							$lundi = format_number ( $lundi );
							$duree_conge = duree_conge ( $sous_taches );
							$som_imp = som_imp ( $sous_taches );
							$nlle_som = $som_imp + ($lundi - $ancien_lundi);
							$duree_conge = format_number ( $duree_conge );
							$som_imp = format_number ( $som_imp );
							$nlle_som = format_number ( $nlle_som );
							if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
								$depasser_conge = "vrai";
								$input_lun = "vrai";
							} else {
								$var1 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_lun, $lundi, $ancien_lundi, $raf, $comment );
								$som_lun = $var1 ['somme'];
								$input_lun = $var1 ['input'];
								$error = $var1 ['error'];
							}
						}
						if (($jour != "faux") && (is_numeric ( $mardi )) && ($mardi <= 1) && ($tache != 0) && ($mardi >= 0) && ($insert_raf == "vrai") && ($check_conge_mar == "vrai")) {
							$mardi = format_number ( $mardi );
							$duree_conge = duree_conge ( $sous_taches );
							$som_imp = som_imp ( $sous_taches );
							$nlle_som = $som_imp + ($mardi - $ancien_mardi);
							$duree_conge = format_number ( $duree_conge );
							$som_imp = format_number ( $som_imp );
							$nlle_som = format_number ( $nlle_som );
							if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
								$depasser_conge = "vrai";
								$input_mar = "vrai";
							} else {
								$var1 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_mar, $mardi, $ancien_mardi, $raf, $comment );
								$som_mar = $var1 ['somme'];
								$input_mar = $var1 ['input'];
								$error = $var1 ['error'];
							}
						}
						if (($jour != "faux") && (is_numeric ( $mercredi )) && ($mercredi <= 1) && ($tache != 0) && ($mercredi >= 0) && ($insert_raf == "vrai") && ($check_conge_mer == "vrai")) {
							$mercredi = format_number ( $mercredi );
							$duree_conge = duree_conge ( $sous_taches );
							$som_imp = som_imp ( $sous_taches );
							$nlle_som = $som_imp + ($mercredi - $ancien_mercredi);
							$duree_conge = format_number ( $duree_conge );
							$som_imp = format_number ( $som_imp );
							$nlle_som = format_number ( $nlle_som );
							if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
								$depasser_conge = "vrai";
								$input_mer = "vrai";
							} else {
								$var2 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_mer, $mercredi, $ancien_mercredi, $raf, $comment );
								$som_mer = $var2 ['somme'];
								$input_mer = $var2 ['input'];
								$error = $var2 ['error'];
							}
						}
						if (($jour != "faux") && (is_numeric ( $jeudi )) && ($jeudi <= 1) && ($tache != 0) && ($jeudi >= 0) && ($insert_raf == "vrai") && ($check_conge_jeu == "vrai")) {
							$jeudi = format_number ( $jeudi );
							$duree_conge = duree_conge ( $sous_taches );
							$som_imp = som_imp ( $sous_taches );
							$nlle_som = $som_imp + ($jeudi - $ancien_jeudi);
							$duree_conge = format_number ( $duree_conge );
							$som_imp = format_number ( $som_imp );
							$nlle_som = format_number ( $nlle_som );
							if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
								$depasser_conge = "vrai";
								$input_jeu = "vrai";
							} else {
								$var3 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_jeu, $jeudi, $ancien_jeudi, $raf, $comment );
								$som_jeu = $var3 ['somme'];
								$input_jeu = $var3 ['input'];
								$error = $var3 ['error'];
							}
						}
						if (($jour != "faux") && (is_numeric ( $vendredi )) && ($vendredi <= 1) && ($tache != 0) && ($vendredi >= 0) && ($insert_raf == "vrai") && ($check_conge_ven)) {
							$vendredi = format_number ( $vendredi );
							$duree_conge = duree_conge ( $sous_taches );
							$som_imp = som_imp ( $sous_taches );
							$nlle_som = $som_imp + ($vendredi - $ancien_vendredi);
							$duree_conge = format_number ( $duree_conge );
							$som_imp = format_number ( $som_imp );
							$nlle_som = format_number ( $nlle_som );
							if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
								$depasser_conge = "vrai";
								$input_ven = "vrai";
							} else {
								$var4 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_ven, $vendredi, $ancien_vendredi, $raf, $comment );
								$som_ven = $var4 ['somme'];
								$input_ven = $var4 ['input'];
								$error = $var4 ['error'];
							}
						}

						if ((($samedi != 0) and ($ancien_samedi == 0)) || (($dimanche != 0) and ($ancien_dimanche == 0))) {

							$update_week = "vrai";
							if (($samedi != 0) and ($ancien_samedi == 0)) {
								$input_sam = "vrai";
							}
							if (($dimanche != 0) and ($ancien_dimanche == 0)) {
								$input_dim = "vrai";
							}
						} elseif ($raf >= 0) {
							if (($jour != "faux") && (is_numeric ( $samedi )) && ($samedi <= 1) && ($tache != 0) && ($samedi >= 0) && ($insert_raf == "vrai") && ($check_conge_sam)) {
								$samedi = format_number ( $samedi );
								$duree_conge = duree_conge ( $sous_taches );
								$som_imp = som_imp ( $sous_taches );
								$nlle_som = $som_imp + ($samedi - $ancien_samedi);
								$duree_conge = format_number ( $duree_conge );
								$som_imp = format_number ( $som_imp );
								$nlle_som = format_number ( $nlle_som );
								if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
									$depasser_conge = "vrai";
									$input_sam = "vrai";
								} else {
									$var5 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_sam, $samedi, $ancien_samedi, $raf, $comment );
									$som_sam = $var5 ['somme'];
									$input_sam = $var5 ['input'];
									$error = $var5 ['error'];
								}
							}
							if (($jour != "faux") && (is_numeric ( $dimanche )) && ($dimanche <= 1) && ($tache != 0) && ($dimanche >= 0) && ($insert_raf == "vrai") && ($check_conge_dim)) {
								$dimanche = format_number ( $dimanche );
								$duree_conge = duree_conge ( $sous_taches );
								$som_imp = som_imp ( $sous_taches );
								$nlle_som = $som_imp + ($dimanche - $ancien_dimanche);
								$duree_conge = format_number ( $duree_conge );
								$som_imp = format_number ( $som_imp );
								$nlle_som = format_number ( $nlle_som );
								if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
									$depasser_conge = "vrai";
									$input_dim = "vrai";
								} else {
									$var6 = update_imputation ( $proj, $sous_taches, $newissue, $user, $date_dim, $dimanche, $ancien_dimanche, $raf, $comment );
									$som_dim = $var6 ['somme'];
									$input_dim = $var6 ['input'];
									$error = $var6 ['error'];
								}
							}
						}

					}
					$update = $_POST ['update'];
					//unset($update);
					if ($sous_taches != "faux") {
						update_variable ( $sous_taches, $raf );
					}
					if (($sous_taches != "faux") and ($newissue != "faux")) {
						if ($sous_taches != $newissue) {
							update_variable ( $newissue, $raf );
						}
					}

				}

				// l'utilisateur a clique sur le bouton valider


				if (isset ( $_POST ['insert'] )) {
					//Recuperer les valeurs de post
					$insert = $_POST ['insert'];
					$date_lun = $_POST ['date_lun'];
					$date_mar = $_POST ['date_mar'];
					$date_mer = $_POST ['date_mer'];
					$date_jeu = $_POST ['date_jeu'];
					$date_ven = $_POST ['date_ven'];
					$date_sam = $_POST ['date_sam'];
					$date_dim = $_POST ['date_dim'];
					$lundi = $_POST ['lundi'];
					$mardi = $_POST ['mardi'];
					$mercredi = $_POST ['mercredi'];
					$jeudi = $_POST ['jeudi'];
					$vendredi = $_POST ['vendredi'];
					$samedi = $_POST ['samedi'];
					$dimanche = $_POST ['dimanche'];
					$raf = $_POST ['raf'];
					$comment = $_POST ['comment'];
					$comment = str_replace ( "'", "\'", $comment );
					if ((strlen ( $raf ) != 0) and (is_numeric ( $raf ))) {
						$raf = format_number ( $raf );
					} else {
						$insert_raf = "faux";
						$input_raf = "vrai";
					}
					$sous_taches = $_POST ['sous_taches'];
					/*****************************************/

					$proj = $_POST ['proj'];
					//Si une des variables n'est pas defini alor elle sera initialise e 0 pour pouvoir la traiter ds le bloc is_numeric
					if (empty ( $lundi )) {
						$lundi = 0;
					}
					if (empty ( $mardi )) {
						$mardi = 0;
					}
					if (empty ( $mercredi )) {
						$mercredi = 0;
					}
					if (empty ( $jeudi )) {
						$jeudi = 0;
					}
					if (empty ( $vendredi )) {
						$vendredi = 0;
					}
					if (empty ( $samedi )) {
						$samedi = 0;
					}
					if (empty ( $dimanche )) {
						$dimanche = 0;
					}
					//voir si au moin une imputation est vide.
					$jour = "vrai";
					$numerique = "vrai";
					$imputation = "vrai";
					//voir si ttes ttes les imputations sont vides
					if ((empty ( $lundi )) && (empty ( $mardi )) && (empty ( $mercredi )) && (empty ( $jeudi )) && (empty ( $vendredi )) && (empty ( $samedi )) && (empty ( $dimanche )) && (empty ( $raf ))) {
						$jour = "faux";
						$vide = 0; //ea sert pour afficher le message d'erreur en bas de page si tte les imputations sont vides
					}
					if ($sous_taches == "faux") {
						$tache = 0;
					}
					//voir si au moin une imputation n'est pas numerique
					if ((! is_numeric ( $lundi )) || (! is_numeric ( $mardi )) || (! is_numeric ( $mercredi )) || (! is_numeric ( $jeudi )) || (! is_numeric ( $vendredi )) || (! is_numeric ( $samedi )) || (! is_numeric ( $dimanche )) || (! is_numeric ( $raf ))) {
						if (! is_numeric ( $lundi )) {
							$input_lun = "vrai";
						}
						if (! is_numeric ( $mardi )) {
							$input_mar = "vrai";
						}
						if (! is_numeric ( $mercredi )) {
							$input_mer = "vrai";
						}
						if (! is_numeric ( $jeudi )) {
							$input_jeu = "vrai";
						}
						if (! is_numeric ( $vendredi )) {
							$input_ven = "vrai";
						}
						if (! is_numeric ( $samedi )) {
							$input_sam = "vrai";
						}
						if (! is_numeric ( $dimanche )) {
							$input_dim = "vrai";
						}
						if (! is_numeric ( $raf )) {
							$input_raf = "vrai";
						}

						$numerique = "faux";
						$num = 0; //ea sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur non numerique
					}

					//voir si au moin une imputation a une valeur superieur e 1
					if (($lundi > 1) || ($mardi > 1) || ($mercredi > 1) || ($jeudi > 1) || ($vendredi > 1) || ($samedi > 1) || ($dimanche > 1)) {
						if ($lundi > 1) {
							$input_lun = "vrai";
						}
						if ($mardi > 1) {
							$input_mar = "vrai";
						}
						if ($mercredi > 1) {
							$input_mer = "vrai";
						}
						if ($jeudi > 1) {
							$input_jeu = "vrai";
						}
						if ($vendredi > 1) {
							$input_ven = "vrai";
						}
						if ($samedi > 1) {
							$input_sam = "vrai";
						}
						if ($dimanche > 1) {
							$input_dim = "vrai";
						}
						$imputation = "faux";
						$imp = 0; //ea sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur > e 1
					}
					# ea sert pour re-afficher les valeurs des imputations si le champ RAF est vide
					if (empty ( $raf )) {
						if (! empty ( $lundi )) {
							$input_lun = "vrai";
						}
						if (! empty ( $mardi )) {
							$input_mar = "vrai";
						}
						if (! empty ( $mercredi )) {
							$input_mer = "vrai";
						}
						if (! empty ( $jeudi )) {
							$input_jeu = "vrai";
						}
						if (! empty ( $vendredi )) {
							$input_ven = "vrai";
						}
						if (! empty ( $samedi )) {
							$input_sam = "vrai";
						}
						if (! empty ( $dimanche )) {
							$input_dim = "vrai";
						}
						$input_raf = "vrai";
					}
					if (($raf < 0) || ($lundi < 0) || ($mardi < 0) || ($mercredi < 0) || ($jeudi < 0) || ($vendredi < 0) || ($samedi < 0) || ($dimanche < 0)) {
						if ($lundi < 0) {
							$input_lun = "vrai";
						}
						if ($mardi < 0) {
							$input_mar = "vrai";
						}
						if ($mercredi < 0) {
							$input_mer = "vrai";
						}
						if ($jeudi < 0) {
							$input_jeu = "vrai";
						}
						if ($vendredi < 0) {
							$input_ven = "vrai";
						}
						if ($samedi < 0) {
							$input_sam = "vrai";
						}
						if ($dimanche < 0) {
							$input_dim = "vrai";
						}
						if ($raf < 0) {
							$input_raf = "vrai";
						}
						$imputation = "faux";
						$negatif = "vrai"; //ea sert pour afficher le message d'erreur en bas de page si une des imputations a une valeur > e 1
					}

					if ($proj == 10064) {
						$issuetype = check_type_issue ( $sous_taches );
						$check_conge_lun = check_date_conge ( $date_lun, $lundi, $sous_taches );
						$check_conge_mar = check_date_conge ( $date_mar, $mardi, $sous_taches );
						$check_conge_mer = check_date_conge ( $date_mer, $mercredi, $sous_taches );
						$check_conge_jeu = check_date_conge ( $date_jeu, $jeudi, $sous_taches );
						$check_conge_ven = check_date_conge ( $date_ven, $vendredi, $sous_taches );
						$check_conge_sam = check_date_conge ( $date_sam, $samedi, $sous_taches );
						$check_conge_dim = check_date_conge ( $date_dim, $dimanche, $sous_taches );
					}
					//	Si tt est bon alors il y'aura insertion dans la base
					if (($jour != "faux") && (is_numeric ( $lundi )) && ($lundi <= 1) && ($tache != 0) && ($lundi > 0) && ($insert_raf == "vrai") && ($check_conge_lun == "vrai")) {
						$lundi = format_number ( $lundi );
						$duree_conge = duree_conge ( $sous_taches );
						$nlle_som = som_imp ( $sous_taches ) + $lundi;

						if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
							$depasser_conge = "vrai";
							$input_lun = "vrai";
						} else {
							$wk = $_GET['week'];
							$mmO = $_GET['mm'];
							$ann= $_GET['y'];
							$var = insert_imputation ( $proj, $sous_taches, $user, $date_lun, $lundi, $raf, $comment );
							$som_lun = $var ['somme'];
							$input_lun = $var ['input'];
							$error = $var ['error'];
						}
					}
					if (($jour != "faux") && (is_numeric ( $mardi )) && ($mardi <= 1) && ($tache != 0) && ($mardi > 0) && ($insert_raf == "vrai") && ($check_conge_mar == "vrai")) {

						$mardi = format_number ( $mardi );
						$duree_conge = duree_conge ( $sous_taches );
						$nlle_som = som_imp ( $sous_taches ) + $mardi;
						if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
							$depasser_conge = "vrai";
							$input_mard = "vrai";
						} else {
							$var1 = insert_imputation ( $proj, $sous_taches, $user, $date_mar, $mardi, $raf, $comment );
							$som_mar = $var1 ['somme'];
							$input_mar = $var1 ['input'];
							$error = $var1 ['error'];
						}
					}
					if (($jour != "faux") && (is_numeric ( $mercredi )) && ($mercredi <= 1) && ($tache != 0) && ($mercredi > 0) && ($insert_raf == "vrai") && ($check_conge_mer == "vrai")) {
						$mercredi = format_number ( $mercredi );
						$duree_conge = duree_conge ( $sous_taches );
						$nlle_som = som_imp ( $sous_taches ) + $mercredi;
						if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
							$depasser_conge = "vrai";
							$input_mer = "vrai";
						} else {
							$var2 = insert_imputation ( $proj, $sous_taches, $user, $date_mer, $mercredi, $raf, $comment );
							$som_mer = $var2 ['somme'];
							$input_mer = $var2 ['input'];
							$error = $var2 ['error'];
						}
					}
					if (($jour != "faux") && (is_numeric ( $jeudi )) && ($jeudi <= 1) && ($tache != 0) && ($jeudi > 0) && ($insert_raf == "vrai") && ($check_conge_jeu == "vrai")) {
						$jeudi = format_number ( $jeudi );
						$duree_conge = duree_conge ( $sous_taches );
						$nlle_som = som_imp ( $sous_taches ) + $jeudi;
						if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
							$depasser_conge = "vrai";
							$input_jeu = "vrai";
						} else {
							$var3 = insert_imputation ( $proj, $sous_taches, $user, $date_jeu, $jeudi, $raf, $comment );
							$som_jeu = $var3 ['somme'];
							$input_jeu = $var3 ['input'];
							$error = $var3 ['error'];
						}
					}
					if (($jour != "faux") && (is_numeric ( $vendredi )) && ($vendredi <= 1) && ($tache != 0) && ($vendredi > 0) && ($insert_raf == "vrai") && ($check_conge_ven == "vrai")) {
						$duree_conge = duree_conge ( $sous_taches );
						$nlle_som = som_imp ( $sous_taches ) + $vendredi;
						if (($proj == 10064) and ($nlle_som > $duree_conge) and ($issuetype != 9)) {
							$depasser_conge = "vrai";
							$input_ven = "vrai";
						} else {
							$vendredi = format_number ( $vendredi );
							$var4 = insert_imputation ( $proj, $sous_taches, $user, $date_ven, $vendredi, $raf, $comment );
							$som_ven = $var4 ['somme'];
							$input_ven = $var4 ['input'];
							$error = $var4 ['error'];
						}
					}
					if (($samedi != 0) || ($dimanche != 0)) {
						$test_week = "vrai";
						if ($samedi != 0) {
							$input_sam = "vrai";
						}
						if ($dimanche != 0) {
							$input_dim = "vrai";
						}
					}
					$date_raf = date ( 'Y-m-d' );

					if ($sous_taches != "faux") {
						update_variable ( $sous_taches, $raf );
					}

				}

				?><?php
if($annee_deb!=$annee_fin && ($week>50)) $annee = $years;
else $annee = $y;
				?>
<TABLE cellSpacing="0" cellPadding="0" width="100%" bgColor="#bbbbbb"
			border="0">
			<TBODY>
				<TR>
					<TD>
					<TABLE align="right" cellSpacing="1" cellPadding="4" width="100%"
						bgColor="#bbbbbb" border="0">
						<TBODY bgColor="#EFEFEF">
							<tr height="20" valign="middle" class="txte-bleu10b"
								bgcolor="#EFEFEF">
								<td height="10" colspan="13" align="left">
		  <?php
				/********** mettre les mois de l'annee dans un tableau ***********/
				$mois = array ("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aeut", "Septembre", "Octobre", "Novembre", "Decembre" );
				?>
		  <?php
				echo $tab_parametres ['annee'];
				?>&nbsp;:		            &nbsp;&nbsp;
		            <select
									onChange="changePage1(this.options[this.options.selectedIndex].value) "
									name="annee" id="annee" class="input-annee">
		  <?php
				for($i = $annee_deb; $i <= $annee_fin; $i ++) {

					$aYears[] = $i;

					if ($i == $annee_fin) {
						$_mois = date ( 'm' );
					} else {
						$_mois = 12;
					}
					?>
            <option
										<?php
					if ($i == $annee) {
						echo "selected=\"selected\"";
					}
					?>
										value="suivi_imputation.php?y=<?php
					echo $i;
					?>&mm=<?php
					echo $_mois;
					?>"><?php
					echo $i;
					?> </option><?php
				}

				if($iMonth  == 12 && !in_array(12, $aYears)){ ?>
					 <option
										<?php
					if ($iYearNext == $y) {
						echo "selected=\"selected\"";
					}
					?>
										value="suivi_imputation.php?y=<?php
					echo $iYearNext;
					?>&mm=<?php
					echo '01';
					?>"><?php
					echo $iYearNext;
					?> </option>

			<?php	}
				?>
          </select>&nbsp;
          <?php
										echo $tab_parametres ['mois'];
										?>&nbsp;:
           <select
									onChange="changePage1(this.options[this.options.selectedIndex].value)"
									name="mois" id="mois" class="input-annee" style="width: 80px;">
  <?php $newyear = $y;

  		if($iMonth == 12){
  			$newyear = $iYear;
  		}

		for($i = ($mois_deb - 1); $i < $mois_fin; $i ++) {
			$aMonths[] = $i;?>

 				 <option value="suivi_imputation.php?mm=<?php echo ($i + 1);?>&y=<?php echo $newyear;	?>"	<?php if (($i + 1) == $mm) {echo "selected";}	?>><?php echo nom_mois ( $i );?></option>
 <?php }

		 if($iMonth  == 12 && !in_array(12, $aMonths)){?>
  	 		<option value="suivi_imputation.php?mm=01&y=<?php echo $iYearNext;	?>"	<?php if (1 == intval($mm)) {echo "selected";}?>><?php echo nom_mois ( 0 );	?></option>
  	 	<?php	}?>

</select><input name="mm" id="mm" type="hidden"
									value="<?php
									echo $mm;
									?>" />
        &nbsp; &nbsp;<?php
								echo $tab_parametres ['semaine'];
								?>&nbsp;:
          <select
									onChange="changePage1(this.form.semaine.options[this.form.semaine.options.selectedIndex].value)"
									name="semaine" id="semaine" class="input-annee"><?php
									$d = 01;
									$fin_mois = fin_mois ( $mm, $y );
									//$prem_sem = weeknumber ( $y, $mm, $d ); //Le numero de semaine du debut du mois
									//$fin_sem = weeknumber ( $y, $mm, $fin_mois );
									$semaines = getListOfWeek($mm, $y);
									for($i = 0; $i < count($semaines); $i ++) {

										?>
 <option
										value="<?php
										echo "suivi_imputation.php?mm=$mm&week=$semaines[$i]&y=$y";
										?>"
										<?php
										if ($semaines[$i] == $week) {
											echo "selected=\"selected\"";
										}
										?>><?php
										echo $semaines[$i];
										?> </option><?php

									}
									?>
  </select> &nbsp;&nbsp;<input name="week" id="week" type="hidden"
									value="<?php
									echo $week;
									?>" /> <span class="txte-bleu10b"> <?php
									$prem_sem = $semaines[0];
									$fin_sem = $semaines[count($semaines)-1];
									if (!in_array($week, $semaines)) {
										$week = $prem_sem;
									}


									$dat1 = explode("-", $weekDay[0]);
									$dat2 = explode("-", $weekDay[6]);
									echo $tab_parametres ['du'] . " " . ": " . $dat1_aff = $dat1 [2] . "-" . $dat1 [1] . "-" . $dat1 [0];
									echo "  " . $tab_parametres ['au'] . " " . ": " . $dat2_aff = $dat2 [2] . "-" . $dat2 [1] . "-" . $dat2 [0];
									?>
</span></td>
							</tr>
						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
      <?php
						/*******************************Update des imputation***********************************/
						/****************************MEDINI Mounira .:. Le 14/03/2007*********************************/

						if (isset ( $_GET ['etap'] )) {
							@$proj = $_GET ['proj'];
							@$issue = $_GET ['issue'];

							?>
      <?php //requete de selection des imputations


						$g = "SELECT DISTINCT (imputation.issue), project.ID, project.pname,jiraissue.pkey, jiraissue.summary,imputation.ID as id_imp
	 FROM imputation, jiraissue, userbase, project
	 WHERE imputation.Project = project.ID
	 AND imputation.issue = jiraissue.ID
     AND imputation.user =" . $_SESSION ['id'] . "
	 AND imputation.user = userbase.id
	 AND imputation.DATE BETWEEN '" . $date1 . "' AND '" . $date7 . "' ";
		$g .= "ORDER BY (project.pname)";
		$gq = mysql_query ( $g );
	$liste_imps ='';
	$nb_enregG = mysql_num_rows ( $gq );
    $verifF = false;
	$verifImpV =false;
	if($nb_enregG > 0){
		while ( $tab = mysql_fetch_array ( $gq) ) {
		    if($lien_adm !='actif'){
				$verifImpV = verifImpIsValide($tab['id_imp']);
				$verifF = $verifImpV;
			}
			if(!$verifImpV){
				if($liste_imps ==''){
					$liste_imps = $tab['id_imp'];
				}else{
					$liste_imps .= ','.$tab['id_imp'];
				}
			}
		}
	}
							$r = "SELECT DISTINCT (imputation.issue), project.ID, project.pname, jiraissue.summary
	FROM imputation, jiraissue, userbase, project
	WHERE imputation.Project = project.ID
	AND imputation.Project =$proj
	AND imputation.issue = jiraissue.ID
	AND imputation.issue =$issue
	AND imputation.user =" . $_SESSION ['id'] . "
	AND imputation.user = userbase.id
	AND imputation.DATE BETWEEN '" . $date1 . "' AND '" . $date7 . "' ";
							$q = mysql_query ( $r );
							@$nb_enreg = mysql_num_rows ( $q );

							//$tab=mysql_fetch_array($q);
							if ($nb_enreg != 0) {
								?>
      <input type="hidden" name="mm" id="mm"
			value="<?php
								echo @$mm = $mm;
								?>" />
<?php
								echo tab_vide ( 9 );
								?>
      <TABLE cellSpacing="0" cellPadding="1" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD vAlign="top" width="100%" colSpan="2">
					<TABLE cellSpacing="0" cellPadding="4" width="100%"
						bgColor="#ffffff" border="0">
						<TBODY>
							<TR>
								<TD vAlign="top" width="180%" bgColor="#EFEFEF">
								<TABLE cellSpacing="0" cellPadding="0" width="100%">
									<TBODY>
										<TR>
											<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font
												color="#CC0000"><b> <?php
								echo $tab_parametres ['update_imputation'];
								?> </b></font>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								</TD>

						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
    <?php
								echo tab_vide ( 9 );
								?>
      <TABLE cellSpacing="0" cellPadding="0" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD>
					<TABLE cellSpacing="1" cellPadding="4" width="100%"
						bgColor="#bbbbbb" border="0">
						<TBODY>
							<tr valign="middle" align="center" bgColor="#EFEFEF">
								<td vAlign="middle">
								<div id="titre-projets"><?php
								echo $tab_parametres ['projet'];
								?></div>
								</td>
								<td>
								<div id="titre-taches"><?php
								echo $tab_parametres ['taches'];
								?></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['lundi'];
								?></b><br />
								<?php echo $d1_affich;
								?>
          <input name="date_lun" id="date_lun" type="hidden"
									value="<?php
								echo $d1;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['mardi'];
								?></b><br />
<?php echo $d2_affich;
								?>
          <input name="date_mar" id="date_mar" type="hidden"
									value="<?php
								echo $d2;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['mercredi'];
								?></b><br />
<?php echo $d3_affich;
								?>
          <input name="date_mer" id="date_mer" type="hidden"
									value="<?php
								echo $d3;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['jeudi'];
								?></b><br />
<?php echo $d4_affich;
								?>
          <input name="date_jeu" id="date_jeu" type="hidden"
									value="<?php
								echo $d4;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['vendredi'];
								?></b><br />
<?php echo $d5_affich;
								?>
          <input name="date_ven" id="date_ven" type="hidden"
									value="<?php
								echo $d5;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['samedi'];
								?></b><br />
<?php echo $d6_affich;
								?>
          <input name="date_sam" id="date_sam" type="hidden"
									value="<?php
								echo $d6;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['dimanche'];
								?></b><br />
<?php  echo $d7_affich;
								?>
          <input name="date_dim" id="date_dim" type="hidden"
									value="<?php
								echo $d7;
								?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
								echo $tab_parametres ['RAF'];
								?></b></div>
								</td>
								<td>
								<div id="titre-comment"><b><?php
								echo $tab_parametres ['comment'];
								?></b></div>
								</td>
								<td colspan="2">
								<div id="titre-action"><b><?php
								echo $tab_parametres ['operation'];
								?></b></div>
								</td>
							</tr>

	  <?php
								$verifImpV1 = false;$verifImpV2 = false;$verifImpV3 = false;$verifImpV4 = false;$verifImpV5 = false;$verifImpV6 = false;$verifImpV7 = false;
								while ( $tab = mysql_fetch_array ( $q ) ) {
									$id_tache = $tab ['issue'];
									$Timp = requeteselection2 ( $d1, $d7, $user );
									if (isset ( $Timp [$id_tache] [$user] [$d1] )) {
										$imp1 = $Timp [$id_tache] [$user] [$d1];
										if($lien_adm !='actif'){
											$verifImpV1 = verifImpIsValideDate($d1, $user);
										}

									} else {
										$imp1 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d2] )) {
										$imp2 = $Timp [$id_tache] [$user] [$d2];
										if($lien_adm !='actif'){
											$verifImpV2 = verifImpIsValideDate($d2, $user);
										}
									} else {
										$imp2 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d3] )) {
										$imp3 = $Timp [$id_tache] [$user] [$d3];
										if($lien_adm !='actif'){
											$verifImpV3 = verifImpIsValideDate($d3, $user);
										}
									} else {
										$imp3 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d4] )) {
										$imp4 = $Timp [$id_tache] [$user] [$d4];
										if($lien_adm !='actif'){
											$verifImpV4 = verifImpIsValideDate($d4, $user);
										}
									} else {
										$imp4 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d5] )) {
										$imp5 = $Timp [$id_tache] [$user] [$d5];
										if($lien_adm !='actif'){
											$verifImpV5 = verifImpIsValideDate($d5, $user);
										}
									} else {
										$imp5 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d6] )) {
										$imp6 = $Timp [$id_tache] [$user] [$d6];
										if($lien_adm !='actif'){
											$verifImpV6 = verifImpIsValideDate($d6, $user);
										}
									} else {
										$imp6 = 0;
									}
									if (isset ( $Timp [$id_tache] [$user] [$d7] )) {
										$imp7 = $Timp [$id_tache] [$user] [$d7];
										if($lien_adm !='actif'){
											$verifImpV7 = verifImpIsValideDate($d7, $user);
										}
									} else {
										$imp7 = 0;
									}
									/*
$requete1 = requeteselection($d1,$d1,$id_tache,$user);
$query1= mysql_query($requete1);
$tab1=mysql_fetch_array($query1);

$requete2 = requeteselection($d2,$d2,$id_tache,$user);
$query2= mysql_query($requete2);
$tab2=mysql_fetch_array($query2);

$requete3 = requeteselection($d3,$d3,$id_tache,$user);
$query3= mysql_query($requete3);
$tab3=mysql_fetch_array($query3);

$requete4 = requeteselection($d4,$d4,$id_tache,$user);
$query4= mysql_query($requete4);
$tab4=mysql_fetch_array($query4);

$requete5 = requeteselection($d5,$d5,$id_tache,$user);
$query5= mysql_query($requete5);
$tab5=mysql_fetch_array($query5);

$requete6 = requeteselection($d6,$d6,$id_tache,$user);
$query6= mysql_query($requete6);
$tab6=mysql_fetch_array($query6);

$requete7 = requeteselection($d7,$d7,$id_tache,$user);
$query7= mysql_query($requete7);
$tab7=mysql_fetch_array($query7);
*/
$readOnlyTask="false";
if (isset ( $imp1 ) && ($imp1 != 0) && $readOnly1=="true") $readOnlyTask = "true";
if (isset ( $imp2 ) && ($imp2 != 0) && $readOnly2=="true") $readOnlyTask = "true";
if (isset ( $imp3 ) && ($imp3 != 0) && $readOnly3=="true") $readOnlyTask = "true";
if (isset ( $imp4 ) && ($imp4 != 0) && $readOnly4=="true") $readOnlyTask = "true";
if (isset ( $imp5 ) && ($imp5 != 0) && $readOnly5=="true") $readOnlyTask = "true";
if (isset ( $imp6 ) && ($imp6 != 0) && $readOnly6=="true") $readOnlyTask = "true";
if (isset ( $imp7 ) && ($imp7 != 0) && $readOnly7=="true") $readOnlyTask = "true";



$raf = requeteselection ( $d1, $d7, $id_tache, $user );

$query_raf = mysql_query ( $raf );
$tab_raf = mysql_fetch_array ( $query_raf );

$comments = "SELECT DISTINCT(commentaire)
			 FROM imputation
			 WHERE commentaire != '' AND Date BETWEEN '" . $d1 . "' AND  '" . $d7 . "'
			 AND user = '" . $user . "' AND issue = '" . $issue . "'";
$query_comment = mysql_query ( $comments );
$tab_comment = mysql_fetch_array ( $query_comment );

									?>

	  <?php
									$issue = $tab [0];
									?>

	  <tr bgcolor="#FFFFFF" valign="top" align="left">
								<td vAlign="top" id="titre-projets"><?php
									echo $tab ['pname'];
									$proj = $tab [1];
									$bg = $tab [0];
									?></td>
								<td vAlign="top"><select name="newissue" id="newissue"
									class="input-tache" <?php  if($readOnlyTask=="true") echo 'disabled';?> >
									<OPTION value="faux"><?php
									echo $tab_parametres ['liste_tache'];
									?></OPTION>
<?php
									if (isset ( $_GET ['proj'] )) {
										$tab_security = security ( $login, $proj );
										$proj = $_REQUEST ['proj'];
										$tab_security = security ( $login, $proj );
										$id_da = id_proj ( $proj );
										$date_deb = $dat1 [0] . "-" . $dat1 [1] . "-" . $dat1 [2];
										$date_fin = $dat2 [0] . "-" . $dat2 [1] . "-" . $dat2 [2];
										$gest_task = issue_abscence ( $date_deb, $date_fin, $login );
										$tab = explode ( "||", $gest_task );
										$nb_task = $tab [0];
										$req_task = $tab [1];
										$tab_proj = gestion_role ( $user, $login );
										if ($proj == $id_da) {
											$query1 = $req_task;

										} else {
											$query1 = "SELECT jiraissue.ID, jiraissue.pkey, jiraissue.SUMMARY
		 		  FROM project, jiraissue, issuestatus
				  WHERE project.ID ='$proj'
				  AND (project.ID = jiraissue.project)
				  AND (issuestatus.ID = jiraissue.issuestatus)
				  AND (jiraissue.ASSIGNEE ='$login' OR jiraissue.ASSIGNEE is NULL)
				  AND (jiraissue.security IN $tab_security OR jiraissue.security is NULL)
				  AND (issuestatus.pname NOT IN (select libelle FROM status_ferme))";
										}
										$query1 .= " ORDER BY jiraissue.pkey";
										$result1 = mysql_query ( $query1 );

										?>
		  <?php
										while ( $row1 = mysql_fetch_array ( $result1 ) ) {
											?>
<OPTION value="<?php
											echo $row1 [0];
											?>"
										<?php
											if ($issue == $row1 [0]) {
												echo "selected";
											}
											?>><?php
											echo $row1 [1] . "&nbsp;: &nbsp;" . $row1 [2];
											?></OPTION>
<?php
										}
									}
									?></select><?php  if($readOnlyTask=="true") { ?><input type="hidden" value="<?php echo $issue; ?>" id="newissue" name="newissue" /><?php } ?><input name="sous_taches" id="sous_taches" type="hidden"
									value="<?php
									if (isset ( $sous_taches )) {
										echo $sous_taches;
									}
									?>" /></td>
								<td id="titre-Jour" align="center" valign="top" <?php if ($verifImpV1 === true  ){?>style="background-color:red"<?php }?>><input
									type="hidden" name="ancien_lundi" id="ancien_lundi"
									class="input-jour"
									value="<?php
									if (isset ( $imp1 ) and ($imp1 != 0)) {
										echo $imp1;
									}
									?>" /><input
									type="text" name="lundi" id="lundi" class="input-jour"
									value="<?php
									if (isset ( $imp1 ) and ($imp1 != 0)) {
										echo $imp1;
									}
									?>" <?php if ($verifImpV1 === true ){echo 'readonly="readonly"';  }?><?php  if($readOnly1=="true") echo 'readonly="readonly"';?>/></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV2 === true  ){?>style="background-color:red"<?php }?>><input type="hidden"
									name="ancien_mardi" id="ancien_mardi" class="input-jour"
									value="<?php
									if (isset ( $imp2 ) and ($imp2 != 0)) {
										echo $imp2;
									}
									?>" /><input
									type="text" name="mardi" class="input-jour"
									value="<?php
									if (isset ( $imp2 ) and ($imp2 != 0)) {
										echo $imp2;
									}
									?>" <?php if ($verifImpV2 === true ){echo 'readonly="readonly"';  }?> <?php  if($readOnly2=="true") echo 'readonly="readonly"'; ?>  /></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV3 === true ){?>style="background-color:red"<?php }?> ><input type="hidden"
									name="ancien_mercredi" id="ancien_mercredi" class="input-jour"
									value="<?php
									if (isset ( $imp3 ) and ($imp3 != 0)) {
										echo $imp3;
									}
									?>" /><input
									type="text" name="mercredi" id="mercredi" class="input-jour"
									value="<?php
									if (isset ( $imp3 ) and ($imp3 != 0)) {
										echo $imp3;
									}
									?>" <?php if ($verifImpV3 === true  ){ echo 'readonly="readonly"'; }?> <?php  if($readOnly3=="true") echo 'readonly="readonly"';?> /></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV4 === true  ){?>style="background-color:red"<?php }?>><input type="hidden"
									name="ancien_jeudi" id="ancien_jeudi" class="input-jour"
									value="<?php
									if (isset ( $imp4 ) and ($imp4 != 0)) {
										echo $imp4;
									}
									?>" /><input
									type="text" name="jeudi" id="jeudi" class="input-jour"
									value="<?php
									if (isset ( $imp4 ) and ($imp4 != 0)) {
										echo $imp4;
									}
									?>" <?php if ($verifImpV4 === true ){echo 'readonly="readonly"';  }?> <?php  if($readOnly4=="true") echo 'readonly="readonly"';?> /></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV5 === true  ){?>style="background-color:red"<?php }?>><input type="hidden"
									name="ancien_vendredi" id="ancien_vendredi" class="input-jour"
									value="<?php
									if (isset ( $imp5 ) and ($imp5 != 0)) {
										echo $imp5;
									}
									?>" /><input
									type="text" name="vendredi" id="vendredi" class="input-jour"
									value="<?php
									if (isset ( $imp5 ) and ($imp5 != 0)) {
										echo $imp5;
									} ?>" <?php if ($verifImpV5 === true ){echo 'readonly="readonly"';  }?> <?php  if($readOnly5=="true") echo 'readonly="readonly"';?> /></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV6 === true ){?>style="background-color:red"<?php }?>><input type="hidden"
									name="ancien_samedi" id="ancien_samedi" class="input-jour"
									value="<?php
									if (isset ( $imp6 ) and ($imp6 != 0)) {
										echo $imp6;
									}
									?>" /><input
									type="text" name="samedi" id="samedi" class="input-jour"
									value="<?php
									if (isset ( $imp6 ) and ($imp6 != 0)) {
										echo $imp6;
									}
									?>" <?php if ($verifImpV6 === true ){echo 'readonly="readonly"';  }?> <?php  if($readOnly6=="true") echo 'readonly="readonly"';?>/></td>
								<td id="titre-Jour" align="center" <?php if ($verifImpV7 === true  ){?>style="background-color:red"<?php }?>><input type="hidden"
									name="ancien_dimanche" id="ancien_dimanche" class="input-jour"
									value="<?php
									if (isset ( $imp7 ) and ($imp7 != 0)) {
										echo $imp7;
									}
									?>" /> <input
									type="text" name="dimanche" id="dimanche" class="input-jour"
									value="<?php
									if (isset ( $imp7 ) and ($imp7 != 0)) {
										echo $imp7;
									}
									?>" <?php if ($verifImpV7 === true  ){ echo 'readonly="readonly"';  }?> <?php  if($readOnly7=="true") echo 'readonly="readonly"';?> /></td>
								<td align="center"><input type="text" name="raf" id="raf"
									class="input-jour" value="<?php
									echo $tab_raf ['RAF'];
									?>" /></td>
								<td align="center" id="titre-Jour"><input name="comment2"
									maxlength="50" class="input-comment"
									value="<?php
									if ($tab_comment ['commentaire'] != '') {
										echo $tab_comment ['commentaire'];
									}
									?>" /></td>
								<td align="center" width="21"><input name="etape" id="etape"
									type="hidden" value="modif" /><a
									href="<?php
									echo "suivi_imputation.php?id=$user&amp;proj=$proj&amp;issue=$issue&amp;mm=$mm&amp;semaine=$week&amp;y=$y";
									?>"><img
									src="../images/annuler.gif" border="0"
									title="<?php
									echo $tab_parametres ['annuler'];
									?>" /></a></td>
								<td align="center" width="21"><input name="update" id="update"
									type="hidden" value="update" /> <input name="proj" id="proj"
									type="hidden" value="<?php
									if (isset ( $proj ))
										echo $proj;
									?>" /><input
									name="sous_taches" id="sous_taches" type="hidden"
									value="<?php
									if (isset ( $issue ))
										echo $issue;
									?>" /><input
									name="week" id="week" type="hidden"
									value="<?php
									echo $week;
									?>" /><input name="mm" id="mm"
									type="hidden" value="<?php
									echo $mm;
									?>" /><input name="y"
									id="y" type="hidden" value="<?php
									echo $y;
									?>" /> <input
									type="button" title="<?php
									echo $tab_parametres ['valider'];
									?>"
									name="Submit2" value="" class="bouton"
									onClick="verif_champ(document.form.raf.value)" /></td>
							</tr> <?php
								}
								?> </TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
      <?php
							}
							?>
      <?php
						} else

						{
							?>
     <input name="date1" id="date1" type="hidden"
			value="<?php
							if (isset ( $date1 )) {
								echo $date1;
							}
							?>" /><input
			name="date7" id="date7" type="hidden"
			value="<?php
							if (isset ( $date7 )) {
								echo $date7;
							}
							?>" /><?php
							echo tab_vide ( 9 );
							?>
	 <TABLE cellSpacing="0" cellPadding="1" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD vAlign="top" width="100%" colSpan="2">
					<TABLE cellSpacing="0" cellPadding="4" width="100%"
						bgColor="#ffffff" border="0">
						<TBODY>
							<TR>
								<TD vAlign="top" width="180%" bgColor="#EFEFEF">
								<TABLE cellSpacing="0" cellPadding="0" width="100%">
									<TBODY>
										<TR>
											<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font
												color="#CC0000"><b><?php
							echo $tab_parametres ['nouvelle_imputation'];
							?> </b></font>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								</TD>

						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
      <?php
							echo tab_vide ( 9 );
							?>
      <TABLE cellSpacing="0" cellPadding="0" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD>
					<TABLE cellSpacing="1" cellPadding="4" width="100%"
						bgColor="#bbbbbb" border="0">
						<TBODY>
							<tr valign="middle" align="center" bgColor="#EFEFEF">
								<td >
								<div id="titre-projets"><?php
							echo $tab_parametres ['projet'];
							?></div>
								</td>
								<td>
								<div id="titre-taches"><?php
							echo $tab_parametres ['taches'];
							?></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['lundi'];
							?></b><br />
          <?php

							echo $d1_affich;
							?>
          <input name="date_lun" id="date_lun" type="hidden"
									value="<?php
							echo $d1;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['mardi'];
							?></b><br />
         <?php

							echo $d2_affich;
							?>
          <input name="date_mar" id="date_mar" type="hidden"
									value="<?php
							echo $d2;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['mercredi'];
							?></b><br />
          <?php echo $d3_affich;
							?>
          <input name="date_mer" id="date_mer" type="hidden"
									value="<?php
							echo $d3;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['jeudi'];
							?></b><br />
          <?php echo $d4_affich;
							?>
          <input name="date_jeu" id="date_jeu" type="hidden"
									value="<?php
							echo $d4;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['vendredi'];
							?></b><br />
          <?php echo $d5_affich;
							?>
          <input name="date_ven" id="date_ven" type="hidden"
									value="<?php
							echo $d5;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['samedi'];
							?></b><br />
          <?php echo $d6_affich
							?>
          <input name="date_sam" id="date_sam" type="hidden"
									value="<?php
							echo $d6;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['dimanche'];
							?></b><br />
          <?php echo $d7_affich;
							?>
          <input name="date_dim" id="date_dim" type="hidden"
									value="<?php
							echo $d7;
							?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
							echo $tab_parametres ['RAF'];
							?></b><br />
								</div>
								</td>
								<td>
								<div id="titre-comment"><b><?php
							echo $tab_parametres ['comment'];
							?></b></div>
								</td>
								<td colspan="2">
								<div id="titre-action"><b><?php
							echo $tab_parametres ['operation'];
							?></b></div>
								</td>
							</tr>

							<tr bgcolor="#FFFFFF" valign="top" align="left">
								<td vAlign="top" id="titre-projets">



<?php

							$date_deb = $dat1 [0] . "-" . $dat1 [1] . "-" . $dat1 [2];
							$date_fin = $dat2 [0] . "-" . $dat2 [1] . "-" . $dat2 [2];
							$gest_task = issue_abscence ( $date_deb, $date_fin, $login );
							$tab = explode ( "||", $gest_task );
							$nb_task = $tab [0];
							$req_task = $tab [1];
							$tab_proj = gestion_role ( $user, $login );

							if ($nb_task == 0) {
								$tab_proj = str_replace ( '10064', '', $tab_proj );
							}

							$query = "Select
				distinct(project.id), project.pname
			  FROM
				project, jiraissue, issuestatus
			  WHERE
				project.ID IN " . $tab_proj . "
				AND jiraissue.project = project.ID
				AND (jiraissue.ASSIGNEE ='" . $_SESSION ['login'] . "' OR jiraissue.ASSIGNEE is NULL)
				AND (issuestatus.ID = jiraissue.issuestatus)
				AND (issuestatus.pname NOT IN (select libelle FROM status_ferme))
				ORDER BY project.pname
				";
						//echo $query;die;
							//		echo $query;
							$result = mysql_query ( $query );
							//echo mysql_num_rows($result);
							$sel = "not";
							$p = "faux";
							$y = $annee;

							?>
<select
									onChange="changePage2(this.form.projets.options[this.form.projets.options.selectedIndex].value)"
									name="projets" id="projets" class="input-projects">
									<option value=nothing><?php
							echo $tab_parametres ['liste_projet'];
							?></option>
  <?php
							while ( $row = mysql_fetch_array ( $result ) ) {
								$pro = $row [0];
								if (isset ( $_GET ['proj'] )) {
									$proj = $_GET ['proj'];
									$p = "true";
								}
								$lien = "suivi_imputation.php?proj=$row[0]&mm=$mm&week=$week&y=$annee";
								?>

  <option value="<?php
								echo $lien;
								?>"
										<?php
								if (($p == "true") and ($pro == $proj)) {
									echo "selected";
								} elseif ((isset ( $_POST ['insert'] )) and ($proj == $row [0])) {
									echo "selected";
								}
								?>><?php
								echo $row [1];
								?>
  <?php
							}
							?></option>
								</select></td>
								<td><select name="sous_taches" id="sous_taches"
									class="input-tache">
									<OPTION value="faux"><?php
							echo $tab_parametres ['liste_tache'];
							?></OPTION>
<?php

							if (isset ( $_REQUEST ['proj'] )) {
								$proj = $_REQUEST ['proj'];
								$tab_security = security ( $login, $proj );

								$id_da = id_proj ( $proj );

								if ($proj == $id_da) {
									$query1 = $req_task;
								} else {
									$query1 = "SELECT jiraissue.ID, jiraissue.pkey, jiraissue.SUMMARY
		 		  FROM project, jiraissue, issuestatus
				  WHERE project.ID ='$proj'
				  AND (project.ID = jiraissue.project)
				  AND (issuestatus.ID = jiraissue.issuestatus)
				  AND (jiraissue.ASSIGNEE ='$login' OR jiraissue.ASSIGNEE is NULL)
				  AND (jiraissue.security IN $tab_security OR jiraissue.security is NULL)
				  AND (issuestatus.pname NOT IN (select libelle FROM status_ferme))";

								}
								$query1 .= " ORDER BY jiraissue.pkey";
								$result1 = mysql_query ( $query1 );

								?>
		  <?php
								while ( $row1 = mysql_fetch_array ( $result1 ) ) {
									?>
<OPTION value="<?php
									echo $row1 [0];
									?>"
										<?php
									if ((isset ( $_GET ['insert'] )) and ($sous_taches == $row1 [0])) {
										echo "selected";
									}
									?>><?php
									echo $row1 [1] . "&nbsp;:&nbsp;" . $row1 [2];
									?></OPTION>
<?php
								}
								?></select><br />
<?php
							}

							?></td>
								<td align="center"> <input type="text" name="lundi" id="lundi"
									class="input-jour"
									<?php
							if ((isset ( $input_lun )) && ($input_lun == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $lundi;
								?>" <?php
							}  if($readOnly1=="true") echo 'readonly="readonly"';?>	 /></td>
								<td align="center"> <input type="text" name="mardi" id="mardi"
									class="input-jour"
									<?php
							if ((isset ( $input_mar )) && ($input_mar == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $mardi;
								?>" <?php
							} if($readOnly2=="true") echo 'readonly="readonly"';?>
							 /></td>
								<td align="center"> <input type="text" name="mercredi"
									id="mercredi" class="input-jour"
									<?php
							if ((isset ( $input_mer )) && ($input_mer == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $mercredi;
								?>" <?php }
								if($readOnly3=="true") echo 'readonly="readonly"';?> /></td>
								<td align="center"> <input type="text" name="jeudi" id="jeudi"
									class="input-jour"
									<?php
							if ((isset ( $input_jeu )) && ($input_jeu == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $jeudi;
								?>" <?php
							} if($readOnly4=="true") echo 'readonly="readonly"';?> /></td>
								<td align="center"> <input type="text" name="vendredi"
									id="vendredi" class="input-jour"
									<?php
							if ((isset ( $input_ven )) && ($input_ven == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $vendredi;
								?>" <?php
							} if($readOnly5=="true") echo 'readonly="readonly"'; ?> /></td>
								<td align="center"> <input type="text" name="samedi" id="samedi"
									class="input-jour"
									<?php
							if ((isset ( $input_sam )) && ($input_sam == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $samedi;
								?>" <?php
							} if($readOnly6=="true") echo 'readonly="readonly"';?> /></td>
								<td align="center"> <input type="text" name="dimanche"
									id="dimanche" class="input-jour"
									<?php
							if ((isset ( $input_dim )) && ($input_dim == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $dimanche;
								?>" <?php
							} if($readOnly7=="true") echo 'readonly="readonly"';?> /></td>
								<td align="center"><input type="text" name="raf" id="raf"
									class="input-jour"
									<?php
							if ((isset ( $input_raf )) && ($input_raf == "vrai") && ($tache != 0)) {
								?>
									style="background-color: #F5CFCF; border-color: #990000"
									value="<?php
								echo $raf;
								?>" <?php
							}
							?> /></td>
								<td align="center"><input name="comment" maxlength="50"
									class="input-comment" /></td>
								<td align="center" width="21"><input type="reset"
									title="<?php
							echo $tab_parametres ['annuler'];
							?>"
									name="Submit3" value="" class="bouton_annuler" /></td>
								<td align="center" width="21"><input name="insert" id="insert"
									type="hidden" value="insert" /><input name="proj" id="proj"
									type="hidden" value="<?php
							if (isset ( $proj )) {
								echo $proj;
							}
							?>" /><input
									type="submit" title="<?php
							echo $tab_parametres ['valider'];
							?>"
									name="Submit" value="" class="bouton"></td>
							</tr>
						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE><?php
						}
						?>

	  <?php
			echo tab_vide ( 12 );
			?><?php

			if (((($ins_trav_fer == "vrai") || ($up_trav_fer == "vrai")) && ($test_week != "vrai") && ($update_week != "vrai")) and ($tache != 0)) {
				$chaine = "Vous avez travailler le jour de " . $lib_fer;
				?>
<TABLE cellSpacing="0" cellPadding="1" width="60%" align="center"
			bgColor="#bbbbbb" border="0">
			<TR>
				<TD vAlign="top" width="100%" colSpan="2">
				<TABLE cellSpacing="0" cellPadding="4" width="100%"
					bgColor="#ffffff" border="0">
					<TBODY>
						<TR>
							<TD vAlign="top" width="180%" bgColor="#EFEFEF">
							<TABLE cellSpacing="0" cellPadding="0" width="100%">
								<TBODY>
									<TR>
										<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="center"><font
											color="#CC0000"><b><?php
				echo $chaine;
				?></b></font><br />
                              <?php
				if ($raf == '0.00') {
					$raf = 0;
				}
				if ((($ins_trav_fer == "vrai") && ($test_week != "vrai")) and ($tache != 0) && ($check_conge_sam == "vrai") && ($check_conge_dim == "vrai")) {
					?>
<a
											href="<?php
					echo "week.php?page=0&amp;etapee=insert_fer&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;user=$user&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['oui'];
					?></a>&nbsp;&nbsp;<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['non'];
					?></a><?php
				}
				if (($up_trav_fer == "vrai") && ($update_week != "vrai") && ($check_conge_sam == "vrai") && ($check_conge_dim == "vrai")) {
					?><a
											href="<?php
					echo "week.php?page=0&amp;etapee=update_fer&amp;proj=$proj&amp;val_fer=$val_fer&amp;ancien_val_fer=$ancien_val_fer&amp;sous_taches=$sous_taches&amp;newissue=$newissue&amp;user=$user&amp;date_fer=$Date_fer&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['oui'];
					?></a>&nbsp;&nbsp;<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['non'];
					?></a>
<?php
				}
				?>


</TD>
									</TR>
								</TBODY>
							</TABLE>
							</TD>

					</TBODY>
				</TABLE>
				</TD>
			</TR>
			</TBODY>
		</TABLE>
				  <?php
				echo tab_vide ( 9 );
			}
			if ($tache == 0) {
				$chaine = $tab_parametres ['projet_tache_vide'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if ($depasser_conge == "vrai") {
				$chaine = $tab_parametres ['duree_conge'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if ((isset ( $raf )) and empty ( $raf )) {
				$chaine = $tab_parametres ['RAF_oblig'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}

			if (($vide == 0) and ($tache != 0)) {
				$chaine = $tab_parametres ['imputation_null'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($date_jour_fer == "faux") and ($tache != 0)) {
				$chaine = $tab_parametres ['pa_fer'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($negatif == "vrai") and ($tache != 0)) {
				$chaine = $tab_parametres ['val_negative'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($insert_raf == "faux") and ($tache != 0) and ($raf > 0)) {
				$chaine = $tab_parametres ['au_moins_champ_pour_raf'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($num == 0) and ($tache != 0)) {
				$chaine = $tab_parametres ['imputation_nonnumerique'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($imp == 0) and ($tache != 0)) {
				$chaine = $tab_parametres ['imputation_superieurun'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}
			if (($som_lun == "faux") || ($som_mar == "faux") || ($som_mer == "faux") || ($som_jeu == "faux") || ($som_ven == "faux") || ($som_sam == "faux") || ($som_dim == "faux")) {
				$chaine = $tab_parametres ['somme_superieurun'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}

			if (isset ( $error )) {
				tab_alerte ( $error );
			}
			if ((($check_conge_lun == "faux") and ($lundi > 0)) or (($check_conge_mar == "faux") and ($mardi > 0)) or (($check_conge_mer == "faux") and ($mercredi > 0)) or (($check_conge_jeu == "faux") and ($jeudi > 0)) or (($check_conge_ven == "faux") and ($vendredi > 0)) or (($check_conge_sam == "faux") and ($samedi > 0)) or (($check_conge_dim == "faux") and ($dimanche > 0))) {
				$chaine = $tab_parametres ['periode_conge'];
				tab_alerte ( $chaine );
				echo tab_vide ( 9 );
			}

			if ((isset ( $samedi )) && (is_numeric ( $samedi )) && ($samedi <= 1) && ($samedi > 0)) {
				$msg_sam = "tru";
			}
			if ((isset ( $dimanche )) && (is_numeric ( $dimanche )) && ($dimanche <= 1) && ($dimanche > 0)) {
				$msg_dim = "tru";
			}
			if (((($test_week == "vrai") || ($update_week == "vrai")) && ($ins_trav_fer != "vrai") && ($up_trav_fer != "vrai") && (($msg_sam == "tru") || ($msg_dim == "tru"))) && ($jour != "faux") && ($tache != 0) && ($check_conge_sam == "vrai") && ($check_conge_dim == "vrai")) {
				$chaine = $tab_parametres ['imputation_weekend'];
				?>
<TABLE cellSpacing="0" cellPadding="1" width="60%" align="center"
			bgColor="#bbbbbb" border="0">
			<TR>
				<TD vAlign="top" width="100%" colSpan="2">
				<TABLE cellSpacing="0" cellPadding="4" width="100%"
					bgColor="#ffffff" border="0">
					<TBODY>
						<TR>
							<TD vAlign="top" width="180%" bgColor="#EFEFEF">
							<TABLE cellSpacing="0" cellPadding="0" width="100%">
								<TBODY>
									<TR>
										<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="center"><font
											color="#CC0000"><b><?php
				echo $chaine;
				?></b></font><br />
                              <?php
				if ($test_week == "vrai") {
					?>
<a
											href="<?php
					echo "week.php?page=0&amp;etapee=insert&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;user=$user&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['oui'];
					?></a>&nbsp;&nbsp;<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['non'];
					?></a><?php
				}
				if (($update_week == "vrai") && ($up_trav_fer != "vrai")) {
					?> <a
											href="<?php
					echo "week.php?page=0&amp;etapee=update&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;newissue=$newissue&amp;user=$user&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;ancien_samedi=$ancien_samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;ancien_dimanche=$ancien_dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['oui'];
					?></a>&nbsp;&nbsp;<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['non'];
					?></a>
<?php
				}
				?>


</TD>
									</TR>
								</TBODY>
							</TABLE>
							</TD>

					</TBODY>
				</TABLE>
				</TD>
			</TR>
			</TBODY>
		</TABLE>
<?php
				echo tab_vide ( 9 );
			}
			//echo $tache;
			if (((($test_week == "vrai") || ($update_week == "vrai")) && (($ins_trav_fer == "vrai") || ($up_trav_fer == "vrai")) && (($msg_sam == "tru") || ($msg_dim == "tru"))) && ($jour != "faux") && ($tache != 0)) {
				$chaine = "Vous avez imputez du temps les jours du week-end et un jour ferie. Souhaitez vous confirmer ?
";
				?>
<TABLE cellSpacing="0" cellPadding="1" width="60%" align="center"
			bgColor="#bbbbbb" border="0">
			<TR>
				<TD vAlign="top" width="100%" colSpan="2">
				<TABLE cellSpacing="0" cellPadding="4" width="100%"
					bgColor="#ffffff" border="0">
					<TBODY>
						<TR>
							<TD vAlign="top" width="180%" bgColor="#EFEFEF">
							<TABLE cellSpacing="0" cellPadding="0" width="100%">
								<TBODY>
									<TR>
										<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="center"><font
											color="#CC0000"><b><?php
				echo $chaine;
				if (($date_jour_fer == "faux") and ($tache != 0)) {
					echo "Cejour n'est pas un jour ferie";
				}
				?></b></font><br />
                              <?php
				if ((($test_week == "vrai") && ($ins_trav_fer == "vrai")) and ($tache != 0)) {
					?>
<a
											href="<?php
					echo "week.php?page=0&amp;etapee=insert&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;user=$user&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_week'];
					?><br />
										&nbsp;&nbsp;<a
											href="<?php
					echo "week.php?page=0&amp;etapee=insert_fer&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;user=$user&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_fer'];
					?></a><br />
										&nbsp;&nbsp;<a
											href="<?php
					echo "week.php?page=0&amp;etapee=insert_week_fer&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;user=$user&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_deux'];
					?></a>&nbsp;<br />
										&nbsp;<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['annuler'];
					?></a><?php
				}
				if (($update_week == "vrai") && ($up_trav_fer == "vrai")) {
					?> <a
											href="<?php
					echo "week.php?page=0&amp;etapee=update_fer_week&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;newissue=$newissue&amp;user=$user&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;ancien_val_fer=$ancien_val_fer&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;ancien_samedi=$ancien_samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;ancien_dimanche=$ancien_dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_deux'];
					?>&nbsp;&nbsp;<br />
										<a
											href="<?php
					echo "week.php?page=0&amp;etapee=update&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;newissue=$newissue&amp;user=$user&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;ancien_val_fer=$ancien_val_fer&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;ancien_samedi=$ancien_samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;ancien_dimanche=$ancien_dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_week'];
					?></a>&nbsp;&nbsp;<br />
										<a
											href="<?php
					echo "week.php?page=0&amp;etapee=update_fer&amp;proj=$proj&amp;sous_taches=$sous_taches&amp;newissue=$newissue&amp;user=$user&amp;date_fer=$Date_fer&amp;val_fer=$val_fer&amp;ancien_val_fer=$ancien_val_fer&amp;date_sam=$date_sam&amp;samedi=$samedi&amp;ancien_samedi=$ancien_samedi&amp;date_dim=$date_dim&amp;dimanche=$dimanche&amp;ancien_dimanche=$ancien_dimanche&amp;y=$y&amp;week=$week&amp;id=$user&amp;mm=$mm&raf=$raf&comment=$comment";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['valid_fer'];
					?>&nbsp;&nbsp;<br />
										<a
											href="<?php
					echo "suivi_imputation.php?id=$user&amp;mm=$mm&amp;week=$week&amp;y=$y";
					?>"
											class="liens-bleu10n" /><?php
					echo $tab_parametres ['annuler'];
					?></a>
<?php
				}
				?></TD>
									</TR>
								</TBODY>
							</TABLE>
							</TD>

					</TBODY>
				</TABLE>
				</TD>
			</TR>
			</TBODY>
		</TABLE>
		<span class="<?php
				echo $class;
				?>"> <input type="hidden" name="mer1"
			id="mer1" class="input-jour"
			value="<?php
				echo $tab3 ['imputation'];
				?>" /> </span><?php
				echo tab_vide ( 9 );
			}

			?>
<?php

			echo tab_vide ( 12 );
			?>

  <?php
		/**********************************Ben smida Rihab le 07/03/2007*************************************/
		/****************************************************************************************************/
		//requete de selection des imputations

  	$g = "SELECT DISTINCT (imputation.issue), project.ID, project.pname,jiraissue.pkey, jiraissue.summary,imputation.ID as id_imp
	 FROM imputation, jiraissue, userbase, project
	 WHERE imputation.Project = project.ID
	 AND imputation.issue = jiraissue.ID
     AND imputation.user =" . $_SESSION ['id'] . "
	 AND imputation.user = userbase.id
	 AND imputation.DATE BETWEEN '" . $date1 . "' AND '" . $date7 . "' ";
		$g .= "ORDER BY (project.pname)";
		$gq = mysql_query ( $g );
	$liste_imps ='';
	$nb_enregG = mysql_num_rows ( $gq );
	$verifF = false;
	if($nb_enregG > 0){
		while ( $tab = mysql_fetch_array ( $gq) ) {
			$verifImpV = verifImpIsValide($tab['id_imp']);
			$verifF = $verifImpV;
			if(!$verifImpV  || $lien_adm =="actif"){
				if($liste_imps ==''){
					$liste_imps = $tab['id_imp'];
				}else{
					$liste_imps .= '-'.$tab['id_imp'];
				}
			}
		}
	}

		$r = "SELECT DISTINCT (imputation.issue), project.ID, project.pname,jiraissue.pkey, jiraissue.summary
	 FROM imputation, jiraissue, userbase, project
	 WHERE imputation.Project = project.ID
	 AND imputation.issue = jiraissue.ID
     AND imputation.user =" . $_SESSION ['id'] . "
	 AND imputation.user = userbase.id
	 AND imputation.DATE BETWEEN '" . $date1 . "' AND '" . $date7 . "' ";
		$r .= "ORDER BY (project.pname)";
		$q = mysql_query ( $r );
		$nb_enreg = mysql_num_rows ( $q );
		///$tab=mysql_fetch_array($q);
		//var_dump($tab);

		if ($nb_enreg != 0) {
			?>
			  <TABLE cellSpacing="0" cellPadding="1" width="100%" align="center"
			bgColor="#bbbbbb" border="0">
			<TBODY>
				<TR>
					<TD vAlign="top" width="100%" colSpan="2">
					<TABLE cellSpacing="0" cellPadding="4" width="100%"
						bgColor="#ffffff" border="0">
						<TBODY>
							<TR>
								<TD vAlign="top" width="180%" bgColor="#EFEFEF">
								<TABLE cellSpacing="0" cellPadding="0" width="100%">
									<TBODY>
										<TR>
											<TD vAlign="top" width="80%" bgColor="#EFEFEF" align="left"><font
												color="#CC0000"><b><?php
			echo $tab_parametres ['recapitulation_imputation'];
			?></b></font>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								</TD>

						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE>
		<TABLE cellSpacing="0" cellPadding="0" width="100%" align="center"
			border="0">
			<TR>
				<TD vAlign="top" width="100%" colSpan="2"><img
					src="../images/spacer.gif" width="1" height="9" /></TD>
			</TR>
		</TABLE>


		<TABLE cellSpacing="0" cellPadding="0" width="100%" align="center"
			bgColor="#bbbbbb" border="0">

			<TBODY>
				<TR>
					<TD>
					<TABLE cellSpacing="1" cellPadding="4" width="100%"
						bgColor="#bbbbbb" border="0">
						<TBODY>
							<tr valign="middle" align="center" bgColor="#EFEFEF">
								<td>
								<div id="titre-projets"><?php
			echo $tab_parametres ['projet'];
			?></div>
								</td>
								<td>
								<div id="titre-taches"><?php
			echo $tab_parametres ['taches'];
			?></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['lundi'];
			?></b><br />
          <?php

			echo $d1_affich;
			?>
          <input name="date_lun" id="date_lun" type="hidden"
									value="<?php
			echo $d1;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['mardi'];
			?></b><br />
          <?php

			echo $d2_affich ;
			?>
          <input name="date_mar" id="date_mar" type="hidden"
									value="<?php
			echo $d2;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['mercredi'];
			?></b><br />
          <?php

			echo $d3_affich;
			?>
          <input name="date_mer" id="date_mer" type="hidden"
									value="<?php
			echo $d3;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['jeudi'];
			?></b><br />
          <?php

			echo $d4_affich;
			?>
          <input name="date_jeu" id="date_jeu" type="hidden"
									value="<?php
			echo $d4;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['vendredi'];
			?></b><br />

          <?php

			echo $d5_affich;
			?>
          <input name="date_ven" id="date_ven" type="hidden"
									value="<?php
			echo $d5;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['samedi'];
			?></b><br />
          <?php

			echo $d6_affich;
			?>
          <input name="date_sam" id="date_sam" type="hidden"
									value="<?php
			echo $d6;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['dimanche'];
			?></b><br />
          <?php

			echo $d7_affich;
			?>
          <input name="date_dim" id="date_dim" type="hidden"
									value="<?php
			echo $d7;
			?>" /></div>
								</td>
								<td>
								<div id="titre-Jour"><b><?php
			echo $tab_parametres ['RAF'];
			?></b><br />
								</div>
								</td>
								<td>
								<div id="titre-comment"><b><?php
			echo $tab_parametres ['comment'];
			?></b></div>
								</td>
								<td colspan="2">
								<div id="titre-action"><b><?php
			echo $tab_parametres ['operation'];
			?></b></div>
								</td>
							</tr>

	  <?php

			/*$tab1=array(); $tab2=array(); $tab3=array(); $tab4=array(); $tab5=array(); $tab6=array(); $tab7=array();*/
			$jj=0;
			$verifImpV1 = false;$verifImpV2 = false;$verifImpV3 = false;$verifImpV4 = false;$verifImpV5 = false;$verifImpV6 = false;$verifImpV7 = false;
			while ( $tab = mysql_fetch_array ( $q ) ) {
				$id_tache = $tab ['issue'];

				$Timp = requeteselection2 ( $d1, $d7, $user );
				if (isset ( $Timp [$id_tache] [$user] [$d1] )) {
					$imp1 = $Timp [$id_tache] [$user] [$d1];
					if($lien_adm !='actif'){
						$verifImpV1 = verifImpIsValideDate($d1, $user);
					}
				} else {
					$imp1 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d2] )) {
					$imp2 = $Timp [$id_tache] [$user] [$d2];
					if($lien_adm !='actif'){
						$verifImpV2 = verifImpIsValideDate($d2, $user);
					}
				} else {
					$imp2 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d3] )) {
					$imp3 = $Timp [$id_tache] [$user] [$d3];
					if($lien_adm !='actif'){
						$verifImpV3 = verifImpIsValideDate($d3, $user);
					}
				} else {
					$imp3 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d4] )) {
					$imp4 = $Timp [$id_tache] [$user] [$d4];
					if($lien_adm !='actif'){
						$verifImpV4 = verifImpIsValideDate($d4, $user);
					}
				} else {
					$imp4 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d5] )) {
					$imp5 = $Timp [$id_tache] [$user] [$d5];
					if($lien_adm !='actif'){
						$verifImpV5 = verifImpIsValideDate($d5, $user);
					}
				} else {
					$imp5 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d6] )) {
					$imp6 = $Timp [$id_tache] [$user] [$d6];
					if($lien_adm !='actif'){
						$verifImpV6 = verifImpIsValideDate($d6, $user);
					}
				} else {
					$imp6 = 0;
				}
				if (isset ( $Timp [$id_tache] [$user] [$d7] )) {
					$imp7 = $Timp [$id_tache] [$user] [$d7];
					if($lien_adm !='actif'){
						$verifImpV7 = verifImpIsValideDate($d7, $user);
					}
				} else {
					$imp7 = 0;
				}
				/*$requete1 = requeteselection($d1,$d1,$id_tache,$user);
$query1= mysql_query($requete1);
$tab1=mysql_fetch_array($query1);
	$bench->add_flag('ligne 1405:');
$requete2 = requeteselection($d2,$d2,$id_tache,$user);
$query2= mysql_query($requete2);
$tab2=mysql_fetch_array($query2);

	$bench->add_flag('ligne 1410:');
$requete3 = requeteselection($d3,$d3,$id_tache,$user);
$query3= mysql_query($requete3);
$tab3=mysql_fetch_array($query3);
	$bench->add_flag('ligne 1414:');
$requete4 = requeteselection($d4,$d4,$id_tache,$user);
$query4= mysql_query($requete4);
$tab4=mysql_fetch_array($query4);
	$bench->add_flag('ligne 1418:');
$requete5 = requeteselection($d5,$d5,$id_tache,$user);
$query5= mysql_query($requete5);
$tab5=mysql_fetch_array($query5);
	$bench->add_flag('ligne 1422:');
$requete6 = requeteselection($d6,$d6,$id_tache,$user);
$query6= mysql_query($requete6);
$tab6=mysql_fetch_array($query6);
	$bench->add_flag('ligne 1426:');
$requete7 = requeteselection($d7,$d7,$id_tache,$user);
$query7= mysql_query($requete7);
$tab7=mysql_fetch_array($query7);	*/

				$raf = requeteselection ( $d1, $d7, $id_tache, $user );
				$query_raf = mysql_query ( $raf );
				$tab_raf = mysql_fetch_array ( $query_raf );
				$issu_id = $tab [0];
				if ((isset ( $bg )) and ($bg == $issu_id)) {
					$class = "x1";
					$class1 = "titre1";
				} else {
					$class = "x2";
					$class1 = "titre2";
				}

				?>



	  <tr bgcolor="#FFFFFF" valign="middle" align="center">
								<td vAlign="top" class="<?php
				echo $class1;
				?>" align="left"><?php
				echo $tab ['pname'];
				$project = $tab [1];

				?></td>
								<td align="left" vAlign="top" class="<?php
				echo $class1;
				?>"><?php
				echo $tab ['pkey'] . "&nbsp;:&nbsp;" . $tab ['summary'];
				$issue = $tab [0];
				$verifV = verifValideImp($d1, $d7, $user, $issue);

				?></td>
								<td class="<?php
				echo $class;
				?>" align="center"><input
									type="hidden" name="lund1" id="lund1" class="input-jour"
									value="<?php
				if (isset ( $imp1 ) and ($imp1 != 0)) {
					echo $imp1;
				}
				?>" /><?php
				if (isset ( $imp1 ) and ($imp1 != 0)) {
					echo $imp1;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="mar1" id="mar1" class="input-jour"
									value="<?php
				if (isset ( $imp2 ) and ($imp2 != 0)) {
					echo $imp2;
				}
				?>" /><?php
				if (isset ( $imp2 ) and ($imp2 != 0)) {
					echo $imp2;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="mer1" id="mer1" class="input-jour"
									value="<?php
				if (isset ( $imp3 ) and ($imp3 != 0)) {
					echo $imp3;
				}
				?>" /><?php
				if (isset ( $imp3 ) and ($imp3 != 0)) {
					echo $imp3;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="jeu1" id="jeu1" class="input-jour"
									value="<?php
				if (isset ( $imp4 ) and ($imp4 != 0)) {
					echo $imp4;
				}
				?>" /><?php
				if (isset ( $imp4 ) and ($imp4 != 0)) {
					echo $imp4;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="ven1" id="ven1" class="input-jour"
									value="<?php
				if (isset ( $imp5 ) and ($imp5 != 0)) {
					echo $imp5;
				}
				?>" /><?php
				if (isset ( $imp5 ) and ($imp5 != 0)) {
					echo $imp5;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="sam1" id="sam1" class="input-jour"
									value="<?php
				if (isset ( $imp6 ) and ($imp6 != 0)) {
					echo $imp6;
				}
				?>" /><?php
				if (isset ( $imp6 ) and ($imp6 != 0)) {
					echo $imp6;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><input type="hidden"
									name="dim1" id="dim1" class="input-jour"
									value="<?php
				if (isset ( $imp7 ) and ($imp7 != 0)) {
					echo $imp7;
				}
				?>" /><?php
				if (isset ( $imp7 ) and ($imp7 != 0)) {
					echo $imp7;
				}
				?>
          &nbsp;</td>
								<td class="<?php
				echo $class;
				?>"><?php
				echo $tab_raf ['RAF'];
				?><input
									type="hidden" name="raf1" id="raf1" class="input-jour" />
								&nbsp;</td>
								<td align="left" class="<?php
				echo $class1;
				?>"><?php
				echo affich_comment ( $issue, $user, $d1, $d7 );
				?></td>
								<td width="21" align="center">
								<?php if(!$verifV || $lien_adm =="actif"){?>

								<input name="date1" id="date1"
									type="hidden" value="<?php
				echo $date1;
				?>" /><input
									name="project" id="project" type="hidden"
									value="<?php
				echo $project;
				?>" /><input name="date7" id="date7"
									type="hidden" value="<?php
				echo $date7;
				?>" /><input
									name="issue" id="issue" type="hidden"
									value="<?php
				echo $issue;
				?>" /> <a
									href="#" onclick="remplirFormDel('<?php echo $user;?>','<?php echo $project;?>','<?php echo $issue;?>','<?php echo $mm;?>','<?php echo $week;?>','<?php echo $y;?>','<?php echo $liste_imps;?>')"><img
									src="../images/supprimer.gif" border="0"
									title="<?php
				echo $tab_parametres ['supprimer'];
				?>" /></a>
				<?php }else{?>
						<img
									src="../images/verrouillage.png" border="0" alt="Modifier"
									title="impossible de midifier les imputations" />

				<?php }?>
				</td>
								<td width="21" align="center">
								<?php if(!$verifV || $lien_adm =="actif"){?>
								<input name="dd1" id="dd1"
									type="hidden" value="<?php
				echo $date1;
				?>" /><input
									name="etape2" id="etape2" type="hidden" value="modif" /> <a
									href="<?php
				echo "suivi_imputation.php?etap=mod&amp;proj=$project&amp;issue=$issue&amp;mm=$mm&amp;week=$week&amp;y=$y";
				?>"><img
									src="../images/modifier.gif" border="0" alt="Modifier"
									title="<?php
				echo $tab_parametres ['edit'];
				?>" /></a>
				<?php }else{?>
					<img
									src="../images/verrouillage.png" border="0" alt="Modifier"
									title="impossible de supprimer les imputations" />
				<?php }?>
				</td>
							</tr> <?php
							$jj ++;
			}
			?> <tr bgcolor="#FFFFFF" valign="middle"
								align="left">
								<td colspan="2" vAlign="top" align="right" class="style2"><?php
			echo $tab_parametres ['total']?> : </td>
								<td id="titre-Jour" align="right"><b><?php
			$exe1 = total_imputation ( $d1, $user );
			if (($exe1 [0] != '0.00') and (! empty ( $exe1 [0] ))) {
				$number = format_number ( $exe1 [0] );
				echo $number;
			}
			?></b></td>
								<td id="titre-Jour" align="right"><b><?php
			$exe1 = total_imputation ( $d2, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d3, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d4, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d5, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d6, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-Jour" align="right"><b><?php $exe1= total_imputation($d7, $user) ;
		if(($exe1[0]!='0.00') and (!empty($exe1[0]))) { $number = format_number($exe1[0]); echo $number; }?></b></td>
								<td id="titre-RAF" align="right"><input type="hidden"
									name="raf1" id="raf1" class="input-jour" readonly="true" /></td>
								<td colspan="3" align="right">&nbsp;</td>
							</tr>
						</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
		</TABLE><?php }?>
    </td>
	</tr>
</table>
</form><?php include("bottom.php"); ?>

</body>
</html>

