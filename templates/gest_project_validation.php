<?php
session_start();// On demarre la session
 if (isset($_GET['logout'])) {  // si l'utilisateur clike sur le lien se deconnecter 
 
session_destroy();  // On detruit la session
header("Location: login.php");
 }
//si le le login n'est pas fourni par session donc redirection vers la page login.php
if (!isset($_SESSION['login'])) 
{
  header("Location: login.php");
}

$id = $_SESSION['id'];
$user = $_SESSION['id'];
 include "connexion.php"; // la page de connexion a la base jira
 include "fonctions.php"; 

 
// $lg est le parametre de langue et c est egal 1 pour le francais
$lg=1;
$sql_parametres="select * from parametres where langue=".$lg;
$query_parametres=mysql_query($sql_parametres);
$tab_parametres=mysql_fetch_array($query_parametres);
$img_path = $tab_parametres['img_path'];
/**fin requete de parametrage***/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gestion de la validation des projets</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.quicksearch.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<LINK media=print href="../style/global_printable.css" type=text/css rel=stylesheet>
<LINK href="../style/global-static.css" type=text/css rel=StyleSheet>
<LINK href="../style/global.css" type=text/css rel=StyleSheet>
<LINK href="../style/theme.css" type=text/css rel=StyleSheet>


<style type="text/css">
<!--
body {
	background-color: #ffffff;
}
-->
</style></head>

 <SCRIPT language=JavaScript><!---------------- javascript pour l'action sur la liste deroulante -----------------//--><!--

 	$(function(){
		jQuery('input#id_search').quicksearch('table#myTableg tbody tr.project-tr');
		jQuery('input#envoiCollab').live('click',function(){
			if(jQuery('select.input-collabs').val() ==  "nothing"){
				alert('Veuillez choisir un collaborateur');
				return false;
			}
		});
		
		
	});

	function delCollabValidate(id, idproject){
		if (confirm("Voulez-vous supprimer cet utilisateur ?")) { // Clic sur OK
			jQuery.post('delete_collab.php',{"id":id, "idproject":idproject}, function(){
				jQuery('tr#tr-c-'+id).remove();
				if(jQuery('tr.tr-c-collab').length == 0){
					jQuery('table#listeCollab').append('<tr><td bgColor=#ffffff>Aucun</td><td bgColor=#ffffff width="50px"></td></tr>');
				}
			});
		}
		
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
			//--> 
			</SCRIPT>
			<STYLE type="text/css">
<!--
SELECT, INPUT, TABLE { font-family : Verdana; font-size : 11px; color : #000033; }
.TITRE { font-family : Verdana; font-size : 12px; color : #000033; }
-->
</STYLE>

<body vLink=#003366 aLink=#cc0000 link=#003366 bgColor=#f0f0f0 leftMargin=0 
topMargin=0 marginheight="0" marginwidth="0">
<?php
$msgError ="";
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
$liste_group = membershipbase($user);
$nb_group = count($liste_group);
$tab_group=array();
for($i=0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j=0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if($list_group=="TD-tdbusers"){ $lien_bord = "actif"; }
if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
}
if($lien_adm=="inactif") {
?>
<script language="javascript">document.location.href="../templates/login.php"</script>
<?php
}
?>

<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("entete.php"); ?></td>
  </tr>
  <TABLE cellSpacing=0 cellPadding=0 width="100%" bgColor=#ffffff border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="170" bgColor="#f0f0f0">
       <?php include("menu-admin.php"); ?>       <BR></TD>
    <TD width=1 bgColor=#bbbbbb><IMG height=1 
      src="/spacer.gif" 
    width=1></TD>
    <TD vAlign=top>
      <TABLE cellSpacing="0" cellPadding="10" width="100%" border="0">
        <TBODY>
        <TR>
          <TD>
             <?php if(isset($_POST) && !empty($_POST)){
             	if(isset($_POST['idcollab'])){
             		if($_POST['idcollab'] !="nothing" && isset($_POST['Editer'])){
             		   insertDataValidate($_POST);
             		}if($_POST['idcollab'] !="nothing" && isset($_POST['Supprimer'])){
             		   deleteDataValidate($_POST);
             		}
             	}
             }
             
            
              if(isset($_GET['idp'])){
             
	             	if(!isset($_GET['operation'])){?>
	             
					 <TABLE class="jiraform  maxWidth" >
	              <TBODY>
	              <TR>
	                <TD valign="top" class="jiraformheader"><H3 class=formtitle>Liste des validateurs</H3></TD></TR>
	              <TR>
	                <TD valign="top" class=jiraformbody><?php echo $tab_parametres['page_gest_proj'];?></TD>
	              </TR></TBODY></TABLE>
	              	<div align="right" style="margin-top:50px"><a class="liens-bleu10b" href="gest_project_validation.php">Retour</a>&nbsp;&nbsp;&nbsp;&nbsp;</div>
				<table width="60%"  cellspacing="1" id="listeCollab" cellpadding="4" border="0" bgcolor="#bbbbbb" align="center" style="margin-top:30px">
	              <tbody><tr class="jiraformheader">
	                <td valign="top" align="center" class="style2">Validateur</td>
	                <td valign="top" align="center" class="style2"></td>
	              </tr>
	                <?php 
	               
				
				
				    $aValidateurs = validate_project($_GET['idp']);
				    foreach($aValidateurs as $key=>$value){
				    ?>
				     <tr id="tr-c-<?php echo $value['id'];?>" class="tr-c-collab"><td bgColor=#ffffff><?php echo $value['name'];?></td><td align="center" bgColor=#ffffff width="50px"><a href="javascript://" onclick="delCollabValidate('<?php echo $value['id'];?>','<?php echo $_GET['idp'];?>')"><img border="0" src="../images/supprimer.gif"></a></td></tr>
				    	
				  <?php }
				  if(empty($aValidateurs)){?>
				  	<tr><td bgColor=#ffffff>Aucun</td><td bgColor=#ffffff width="50px"></td></tr>
				   <?php  }
				  ?>
	                </tbody></table><br/>
	              <table cellspacing="0" cellpadding="0" border="0" align="center" width="0%">
	                <tbody><tr>
	                  <td> <a class="liens-bleu10b" href="gest_project_validation.php?operation=ajouter&idp=<?php echo $_GET['idp'];?>"><img border="0" width="20" height="20" src="../images/valider.gif"></a></td>
	                </tr>
	              </tbody></table>      
				<?php }else{?>
				<div align="right" style="margin-top:50px"><a class="liens-bleu10b" href="gest_project_validation.php?idp=<?php echo $_GET['idp'];?>">Retour</a>&nbsp;&nbsp;&nbsp;&nbsp;</div>
				<form id="form1" name="form1" method="post" action="gest_project_validation.php?idp=<?php echo $_GET['idp'];?>">
					<table class="jiraform" width="60%" align="center">
					              <tr class='jiraformheader'>
					                <td colspan='2' align='center' valign='top' class="style2" ><?php echo $tab_parametres['ajout'];?></td>
					                </tr>
								               <tr>
					                <td valign='top'><?php echo $tab_parametres['collab'];?></td>
					                <td valign='top'><?php $query2="SELECT 
											DISTINCT(userbase.ID), propertystring . propertyvalue 
										FROM 
											propertystring, propertyentry, userbase, project, membershipbase 
										WHERE 
											propertystring.id = propertyentry.id 
											AND propertyentry.property_key = 'fullname' 
											AND propertyentry.entity_id = userbase.id
											AND membershipbase.USER_NAME = userbase.username
											AND membershipbase.GROUP_NAME = 'BD-users' 
											AND userbase.ID  not in (select user from userbasestatus where status =0) ";
											if(isset($_GET['idp'])){
												$query2 .=" AND  userbase.ID  not in (select user from tetd_project_validate where project ='".$_GET['idp']."') ";
											}
												
										$query2 .=" ORDER BY propertyvalue ASC "; // Ajout de Status
					$result2 = mysql_query($query2); ?>
					<select name="idcollab" class="input-collabs">
					      <option value="nothing">Collaborateur</option>
					      <?php while($row2 = mysql_fetch_array($result2)){ 
					       $nom = nom_prenom_user($row2[0]);
					        $id = $row2[0];
					      ?>
					<OPTION value="<?php echo $id; ?>" ><?php echo $nom; ?></OPTION>
					 <?php } ?></option>
					</select></td>
					              </tr>
					            </table>
					<span style="width:80%; float:right;margin-top:20px">
					<input name="idp" type="hidden" value="<?php echo $_GET['idp']; ?>" />
					<input name="Editer" type="submit" id="envoiCollab" value="<?php echo $tab_parametres['ajout'];?> >>" />
					</span>
			</form>
				
				
				<?php }
             	
             	
             	
             	}else{?>
			<TABLE class="jiraform  maxWidth">
              <TBODY>
              <TR>
                <TD valign="top" class="jiraformheader"><H3 class=formtitle>Gestion de la validation des projets</H3></TD></TR>
              <TR>
                <TD valign="top" class=jiraformbody><?php echo $tab_parametres['page_gest_proj'];?></TD>
              </TR></TBODY></TABLE>
			  <form action="#" style="margin-left:215px;margin-top:50px;;margin-bottom:15px">
				 <b>Recherche:</b>&nbsp;&nbsp;<input type="text" autofocus="" placeholder="Search" id="id_search" value="" name="search">
			 </form>
              <table width="60%" id="myTableg" cellspacing="1" cellpadding="4" border="0" bgcolor="#bbbbbb" align="center">
              <tbody><tr class="jiraformheader">
                <th valign="top" align="center" class="style2" style="text-align:center">Libellé</th>
                <th valign="top" align="center" class="style2" style="text-align:center">Validateurs</th>
                <th valign="top" align="center" class="style2" style="text-align:center"></th>
              </tr>
                    
          <?php $validateurs ="";
          
          $query="SELECT 
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
				) order by project.pname"; // Ajout de Status
			$result = mysql_query($query); 
			
			
			
			while($row = mysql_fetch_array($result)){ 
			  	$id = $row[0];
			    $pname = $row[1];
			    $liste = ""; 
			    $aValidateurs = validate_project($id);
			    foreach($aValidateurs as $key=>$value){
			    	if($liste == ""){
			    		$liste = $value['name'];
			    	}else{
			    		$liste .= ', '.$value['name'];
			    	}
			    }
			    
			    ?>
			    
			    <tr class="project-tr"><td bgColor=#ffffff><?php  echo $pname;?></td><td bgColor=#ffffff><?php  echo $liste;?></td><td bgColor=#ffffff><a href="gest_project_validation.php?idp=<?php  echo $id;?>"><img src='../images/config-configuration.png' style="cursor:pointer" width='25px'/></a></td></tr>
			<?php  }
			?>

		  </tbody></table>

	<?php } ?>
<?php  include("bottom.php"); ?>  
 
    
  </body>
</html>

