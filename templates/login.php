<?php  
session_start();
/*************************************Ben smida Rihab le 06/03/2007***************************/
/*********************************************************************************************/
$loginOK = false;
$user_rapp = "inactif";
include ("connexion.php");
include ("fonctions.php");
// $lg est le parametre de langue et c est egal 1 pour le fran�ais
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;

$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de param�trage***/

/******** On n'effectue les traitement qu'� la condition que les informations aient �t� effectivement post�es*/

if  (( @$_POST['login'] != "") || (@$_POST['password'] != "")) 
{
$var="";
if ( isset($_POST) && (!empty($_POST['login'])) && (!empty($_POST['password'])) ) {
  // extract($_POST);
     //echo "PnURAF0n/xlYSknE9AMzLSN5IS7S8+vsOg6WhbBmEwmvGEF/rzZv+9DWL9nbi2PsNbnTwW2NdGzj25eOHfpe2Q==";
  //echo "<br>".$data['PASSWORD_HASH'];
/******** On va chercher le mot de passe aff�rent � ce login*/
 $codage = base64_encode(hash('sha512',$_POST['password'],true));

 $sql = "SELECT * FROM userbase WHERE username = '".$_POST['login']."' and PASSWORD_HASH = '".$codage."'";
 $req = mysql_query($sql) or die('Erreur SQL !');

/********On v�rifie que l'utilisateur existe bien*/
  if (mysql_num_rows($req) > 0) {
     $data = mysql_fetch_array($req);
/********On v�rifie que son mot de passe est correct*/
       if ($codage == $data['PASSWORD_HASH']) {
          $loginOK = true;
		   
    }
	

  
/************* Si le login a �t� valid� on met les donn�es en sessions*/
if ($loginOK) {
  $_SESSION['login'] = $data['username'];
  $_SESSION['password'] = $data['PASSWORD_HASH'];
  $_SESSION['id'] = $data['ID'];
} 

$liste_group = membershipbase($_SESSION['id']);
$nb_group = count($liste_group);
$tab_group=array();
for($i=0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j=0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if(strtolower($list_group)=="clients-users"){ $user_rapp = "actif"; }
}
$_SESSION['user_rapp'] = $user_rapp;

if($user_rapp == "inactif") 
{

//die();
?>
<script language="javascript">document.location.href="../templates/suivi_imputation.php"</script>
<?php
}
else{ //die();
?>
<script language="javascript">document.location.href="../templates/rapport_activite.php"</script>

<?php } }}else {$_SESSION['id']="";}  
 if (( @$_SESSION['id'] == "")||(mysql_num_rows($req)==0)){
 $var='erreur';}
}
 
       
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $tab_parametres['Connexion'];?></title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<link href="../style/global.css" rel="stylesheet" type="text/css" />
<link href="../style/theme.css" rel="stylesheet" type="text/css" />
<link href="../style/global-static.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" align="center">
<tr ><td>
<table cellpadding="0" cellspacing="0" class="centred borderedBoxBlack" width=468>
    <tr cellpadding="0" cellspacing="0" border="0" width=400 bgcolor=#ffffff>
        <td><table>

               
    
                        </td>
        </table>
         <img src="../images/logo-new.jpg" width="468" height="60" border="0" alt="Business&Decision Tunisie"></td>
    </tr>
    <tr cellpadding="0" cellspacing="0" border="0" width="400" height="5" bgcolor="#cc0000">
      	<td><img src="/images/border/spacer.gif" width=5 height=5 border="0"></td>
    </tr>

    <tr><td>
       	<table cellpadding=6 cellspacing=0 border=0 width=421>
<tr>
    <td width="409" bgcolor="EFEFEF" class="style2" align="center"><?php echo $tab_parametres['bienvenu'];?></td>
</tr>
<tr>
    <td bgcolor=ffffff>
    


	<form method="POST" action="" name="loginform" >

		<table align=center cellpadding=4 cellspacing=0 border=0>
<?php if(@$var=='erreur')
{
?>
		<tr>

			<td valign=top colspan=2 bgcolor=ffcccc class="txte-rouge11b">
			<?php echo $tab_parametres['erreur_connexion'];?>
			</td>
		</tr>
<?php }?>	
		<tr>
			<td width=31% align=right valign=middle class="liens-bleu10b"><?php echo $tab_parametres['nom_utilisateur'];?></td>
			<td width="69%" valign=middle>
				<input style="width: 12em;" type="text" name="login" size="25" tabindex=1 accessKey="u">
			</td>
		</tr>

		<tr>
			<td valign=middle align=right width=31% class="liens-bleu10b"><?php echo $tab_parametres['password'];?></td>
			<td valign=middle>
				<input style="width: 12em;" type="password" name="password" size="25" tabindex=2 accessKey="p"> &nbsp;
            </td>
		</tr>
        
        
		<tr>
			<td valign=middle align=right width=31%></td>
			
		</tr>
        

		<tr>
			<td valign=middle align=center colspan=2>
				<input id="login" type="submit" value="<?php echo $tab_parametres['seconnecter'];?>" tabindex=4>
			</td>
		</tr>

		
		<tr>
			<td valign=middle align=right width=31%>&nbsp;</td>
			
		</tr>
		

		
		</table>

		<input type="hidden" name="os_destination" value="/secure/">
	</form>
    
    
	<script language="javascript">
	document.loginform.elements[0].focus();
	</script>
    
    </td>
</tr>
</table>
    </td></tr>
</table>


</td></tr>

</table>
</body>
</html>
