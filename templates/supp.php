<?php /***********************************Ben Smida rihab le 14/03/2007***********************************/
include("connexion.php");
include("fonctions.php");
session_start();
$user = $_SESSION['id'];
$j=0;
$lien_adm = "inactif";
$group = membershipbase($user);
$nb_group = count($group);
for($j==0;$j<$nb_group;$j++)
{
	$list_group = $group[$j];
	if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
}
$lg = 1;
$sql_parametres = "SELECT * 
				 FROM parametres 
				 WHERE langue=" . $lg;
$query_parametres = mysql_query ( $sql_parametres );
$tab_parametres = mysql_fetch_array ( $query_parametres );
/**fin requete de paramètrage***/
$jour_max = $tab_parametres ['param_fermeture_imputation'];

(isset($_POST['limps']))? $lipms = $_POST['limps'] : $lipms ='' ;
$lipms = str_replace('-',',',$lipms);
$whereL ='';
if($lipms !=''){
	$whereL = ' And imputation.ID in ('.$lipms.')';
}

$id=$_POST['id'];
$page=$_POST['page'];
 if($page==0) { $lien_page = "suivi_imputation.php?id="; }
 if($page==1) { $lien_page = "update_imputation.php?collab="; }
$proj=$_POST['proj'];
$issue=$_POST['issue'];
$tot=$_POST['tot'];
$mm = $_POST['mm'];
$y = $_POST['y'];
$semaine = $_POST['semaine'];

$jour_max = $tab_parametres ['param_fermeture_imputation'];
if($semaine>50 && $mm=='01') {
	$years=$y-1;
	$wknber = weeknumber($y, 12, 31);
}
else {
	$years=$y;
	$wknber = $semaine;
}
$weekDay = getDaysInWeek ($wknber, $years);
$exe = explode("-", $weekDay[0]);
$exe1 = explode("-", $weekDay[1]);
$exe2 = explode("-", $weekDay[2]);
$exe3 = explode("-", $weekDay[3]);
$exe4 = explode("-", $weekDay[4]);
$exe5 = explode("-", $weekDay[5]);
$exe6 = explode("-", $weekDay[6]);


$datefin = $exe6[0]."-".$exe6[1]."-".$exe6[2];//$dat2['year']."-".$dat2['month']."-".$dat2['day'];

/*if($exe[1]==12 && date('m')==01 && date('d') > $jour_max) {
	//$datedebut = $exe1[0]."-".$exe1[1]."-01";
	$datedebut = date('Y')."-".date('m')."-01";
	echo 11;
}
elseif($exe[1]==12 && date('m')>01) {
	$datedebut = date('Y')."-01-01";echo 22;
}
elseif(($exe[1] - date('m')) > 1) {
	$datedebut = $exe1[0]."-".(date('m')-1)."-01";echo 33;
}
elseif(date('m') > $exe[1] && date('d') > $jour_max) {
	$datedebut = date('Y')."-".date('m')."-01"; echo 44;
}
elseif((date('m') - $exe[1]) > 1) {
	$datedebut =date('Y')."-".(date('m')-1)."-"."01";
	
}else{
	$datedebut = $exe[0]."-".$exe[1]."-".$exe[2]; echo 55;
}
*/

$reel_month = date('m');
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
$readOnly2="false";

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
$readOnly3="false";

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
$readOnly4="false";

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
$readOnly5="false";

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
$readOnly6="false";

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
$readOnly7="false";

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
if($lien_adm == 'actif'){
	$readOnly1 = "false";
	$readOnly2 = "false";
	$readOnly3 = "false";
	$readOnly4 = "false";
	$readOnly5 = "false";
	$readOnly6 = "false";
	$readOnly7 = "false";
}


if($readOnly1=="false") $datedebut=$exe[0]."-".$exe[1]."-".$exe[2];
elseif($readOnly2=="false") $datedebut=$exe1[0]."-".$exe1[1]."-".$exe1[2];
elseif($readOnly3=="false") $datedebut=$exe2[0]."-".$exe2[1]."-".$exe2[2];
elseif($readOnly4=="false") $datedebut=$exe3[0]."-".$exe3[1]."-".$exe3[2];
elseif($readOnly5=="false") $datedebut=$exe4[0]."-".$exe4[1]."-".$exe4[2];
elseif($readOnly6=="false") $datedebut=$exe5[0]."-".$exe5[1]."-".$exe5[2];
elseif($readOnly7=="false") $datedebut=$exe6[0]."-".$exe6[1]."-".$exe6[2];
else $datedebut="null";
if($datedebut=="null") $datefin = "null";


//$fin_sem = weeknumber ($y, $mm, $fin_mois); //Le numèro de semaine de la fin du mois

$sql="DELETE from imputation WHERE imputation.user=$id 
And imputation.Project = $proj AND imputation.DATE BETWEEN '".$datedebut."' AND '".$datefin."' And imputation.issue=$issue $whereL";


$exe=mysql_query($sql);

$login = login_user($id);
$sql_w = "DELETE FROM worklog WHERE issueid = ".$issue." and AUTHOR = '".$login."' and CREATED BETWEEN '".$datedebut."' AND '".$datefin."'";
mysql_query($sql_w);

//requète d'update de champs numbervalue dde table customfieldvalues c'est le champ consommée vue ds JIRA
$sql2="update `customfieldvalue`  
set `NUMBERVALUE`= `NUMBERVALUE` - '".$tot."' 
WHERE (issue = $issue) and (CUSTOMFIELD = 10020)";
$exe2=mysql_query($sql2);


$sql3="update `customfieldvalue` set `NUMBERVALUE`= 0 WHERE (issue = $issue) and (CUSTOMFIELD = 10021)";
$exe3=mysql_query($sql3);

$sql4="update `customfieldvalue` set `NUMBERVALUE`= 0 WHERE (issue = $issue) and (CUSTOMFIELD = 10082)";
$exe4=mysql_query($sql4);

$val_raf=0;
$sql4 = mysql_query("SELECT RAF FROM imputation WHERE issue = ".$issue." AND Date = (SELECT MAX(Date) FROM imputation WHERE issue = ".$issue.")");
while ($vraf = mysql_fetch_row($sql4))
{
$val_raf=$vraf[0];
}

update_variable($issue,$val_raf);
//die();
?>
<script language="javascript">

document.location.href='<?php echo $lien_page.$id."&proj=$proj&issue=$issue&mm=$mm&week=$semaine&y=$y"; ?>';
</script>
