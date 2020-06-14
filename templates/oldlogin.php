<?php 
session_start();
/*************************************Ben smida Rihab le 06/03/2007***************************/
/*********************************************************************************************/
$loginOK = false;
$user_rapp = "inactif";
include ("connexion.php");
// $lg est le parametre de langue et c est egal 1 pour le français
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
/**fin requete de paramètrage***/

/******** On n'effectue les traitement qu'à la condition que les informations aient été effectivement postées*/

if  (( @$_POST['login'] != "") || (@$_POST['password'] != "")) 
{
$var="";
if ( isset($_POST) && (!empty($_POST['login'])) && (!empty($_POST['password'])) ) {
  // extract($_POST);
     //echo "PnURAF0n/xlYSknE9AMzLSN5IS7S8+vsOg6WhbBmEwmvGEF/rzZv+9DWL9nbi2PsNbnTwW2NdGzj25eOHfpe2Q==";
  //echo "<br>".$data['PASSWORD_HASH'];
/******** On va chercher le mot de passe afférent à ce login*/
 $codage = base64_encode(hash('sha512',$_POST['password'],true));

 $sql = "SELECT * FROM userbase WHERE username = '".$_POST['login']."' and PASSWORD_HASH = '".$codage."'";
 $req = mysql_query($sql) or die('Erreur SQL !');

/********On vérifie que l'utilisateur existe bien*/
  if (mysql_num_rows($req) > 0) {
     $data = mysql_fetch_array($req);
/********On vérifie que son mot de passe est correct*/
       if ($codage == $data['PASSWORD_HASH']) {
          $loginOK = true;
		   
    }
	
  
  
/************* Si le login a été validé on met les données en sessions*/
if ($loginOK) {
  $_SESSION['login'] = $data['username'];
  $_SESSION['password'] = $data['PASSWORD_HASH'];
  $_SESSION['id'] = $data['ID'];
  $sql1="select groupbase.groupname as groupe from groupbase,membershipbase,userbase 
  where groupbase.groupname=membershipbase.GROUP_NAME and membershipbase.USER_NAME=userbase.username
  and userbase.ID=".$data['ID'];
  $query=mysql_query($sql1);
  $i=0;
  while( $tab=mysql_fetch_array($query)){
  
  
  $tab1[]=$tab['groupe'];
  
  $_SESSION['groupetab']=$tab1;
  
  $i++;

  }
 
}
$i = 0;
$j=0; 
$nb_group = count($_SESSION['groupetab']);
$tab_group=array();
$tabbb = "(";
for($i==0;$i<$nb_group;$i++)
{
$tab_group[$i] = $_SESSION['groupetab'][$i];
$tabbb.="'".$_SESSION['groupetab'][$i]."'";
if($i!=($nb_group-1))
{ $tabbb.= ", "; }
}
$tabbb.= ")";
$_SESSION['tabbb'] = $tabbb;
for($j==0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if(strtolower($list_group)=="rac-users"){ $user_rapp = "actif"; }
}
/**************************        La liste des scheme de cet utilisateur      ******************************/
//echo $tabbb;
$tab2 = str_replace("Project-watcher", "PW", $tabbb);
$schema_sql="SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '".$_SESSION['login']."'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN".$tabbb;

$proj_sql = "SELECT 
				DISTINCT (schemepermissions.SCHEME)
			 FROM 
			 	schemepermissions, membershipbase
			 WHERE 
			 	`PERMISSION` =10
				AND membershipbase.user_name = '".$_SESSION['login']."'
				AND membershipbase.group_name = schemepermissions.perm_parameter
				AND schemepermissions.perm_parameter
				IN".$tab2;
$schema_query = mysql_query($schema_sql);
$proj_query = mysql_query($proj_sql);
$nb_proj = mysql_num_rows($proj_query);
$nb_scheme = mysql_num_rows($schema_query);
//$nb_schema = $nb_scheme + $nb_role;
if($nb_scheme>0)
{
$_SESSION['tab_schema'] = "("; $sc = 0; 
while( $tab_schema=mysql_fetch_array($schema_query)){
$_SESSION['tab_schema'].= "'".$tab_schema[0]."'";
if ($sc!=($nb_scheme-1)) { $_SESSION['tab_schema'].=", "; }
$sc++;
}

$_SESSION['tab_schema'].=")";
}

//echo $_SESSION['tab_schema'];
//die();

$sql_role = "SELECT 
					DISTINCT (p.PID) 
			FROM 
					schemepermissions sc, 



					projectroleactor p 
			WHERE 
					sc.perm_type = 'projectrole'
					AND sc.PERMISSION =10
					AND sc.perm_parameter = p.PROJECTROLEID
					AND (p.ROLETYPEPARAMETER = '".$_SESSION['login']."' 
					OR p.ROLETYPEPARAMETER IN ".$tab2.")";
$query_role = mysql_query($sql_role);
$nb_role = mysql_num_rows($query_role);

$nb_schema_proj = $nb_proj + $nb_role;
if(($nb_proj>0) or($nb_role>0))
{
$_SESSION['tab_imputation'] = "("; $sc = 0; 
while( $tab_proj=mysql_fetch_array($proj_query)){
$_SESSION['tab_imputation'].= "'".$tab_proj[0]."'";
if ($sc!=($nb_schema_proj-1)) { $_SESSION['tab_imputation'].=", "; }
$sc++;
}


while( $tab_role=mysql_fetch_array($query_role)){
//echo "<br><br><br>".$tab_role[0]."<br>";
$_SESSION['tab_imputation'].= "'".$tab_role[0]."'";
if ($sc!=($nb_schema_proj-1)) { $_SESSION['tab_imputation'].=", "; 
$sc++;
}
}
$_SESSION['tab_imputation'].=")";
}

$query="Select 
				distinct(project.id), project.pname
			  FROM 
				project, nodeassociation, permissionscheme
			  WHERE 
				project.ID = nodeassociation.SOURCE_NODE_ID
				AND SOURCE_NODE_ENTITY = 'Project'
				AND SINK_NODE_ENTITY = 'PermissionScheme'
				AND SINK_NODE_ID IN ".$_SESSION['tab_imputation']." 
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
					AND sc.perm_parameter = p.PROJECTROLEID
					AND (p.ROLETYPEPARAMETER = '".$_SESSION['login']."' 
					OR p.ROLETYPEPARAMETER IN ".$tab2.")";
$query_role = mysql_query($sql_role);
$nb_role = mysql_num_rows($query_role);

 $nb_project = $nbproj + $nb_role;	
if($nb_project>0)
{ 
$_SESSION['tab_proj'] = "("; $sc = 0; 

while( $tab_projet=mysql_fetch_array($result))
 {
 $_SESSION['tab_proj'].= "'".$tab_projet[0]."'";
if ($sc!=($nb_project-1)) { $_SESSION['tab_proj'].=", "; }
$sc++;
 }

while( $tab_proj=mysql_fetch_array($query_role)){
$_SESSION['tab_proj'].= "'".$tab_proj[0]."'";
if ($sc!=($nb_project-1)) { $_SESSION['tab_proj'].=", "; }
$sc++;
}

$_SESSION['tab_proj'].=")";
} else { $_SESSION['tab_proj'] = "('')"; }
 		
//echo $_SESSION['tab_proj'];				
//echo $_SESSION['tab_imputation'];
//die();
//$_SESSION['tab_imputation'];

//echo $_SESSION['tab_schema'];
/*********************************************     Fin de liste       ******************************************/
$_SESSION['user_rapp'] = $user_rapp;



if($user_rapp == "inactif") 
{
?>
<script language="javascript">document.location.href="../templates/suivi_imputation.php"</script>
<?php
}
else{
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
<table cellpadding="0" cellspacing="0" class="centred borderedBoxBlack" width=400>
    <tr cellpadding="0" cellspacing="0" border="0" width=400 bgcolor=#ffffff>
        <td><table>
            <td valign="bottom" width=200>&nbsp;</td>
            <td valign="bottom" width=200 align="right">&nbsp;
               
    
                        </td>
        </table>
         <img src="../images/logo_bd_tn.png" width="400" height="37" border="0" alt="Business&Decision Tunisie"></td>
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
