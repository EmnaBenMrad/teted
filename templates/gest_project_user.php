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

$alpha = "all";
$groupe = "";

if(isset($_REQUEST['alpha']) && $_REQUEST['alpha'] !=''){
	$alpha = $_REQUEST['alpha'];
}

if(isset($_REQUEST['gp']) && $_REQUEST['gp'] !=''){
	$groupe = $_REQUEST['gp'];
}

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
		jQuery('input#id_search').quicksearch('table#myTableg tbody tr.jiraformheader-1');
		jQuery('input#envoiCollab').live('click',function(){
			if(jQuery('select.input-collabs').val() ==  "nothing"){
				alert('Veuillez choisir un collaborateur');
				return false;
			}
		});
		
		jQuery('select#groupebd').change(function(){
			var alpha = jQuery('.center-wrap-alpha a.active').attr('rel');
			var groupe = jQuery(this).val();
		
			window.location = 'gest_project_user.php?alpha='+alpha+'&gp='+groupe;
			
		});
		
		
		jQuery('#checkAllUser').live('click', function(){
			if(jQuery(this).is(':checked')){
				jQuery('.checkU').each(function(){
					jQuery(this).attr('checked','checked');
				});
			}else{
				jQuery('.checkU').each(function(){
					jQuery(this).removeAttr('checked');
				});
				
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

	
	jQuery('img#activeAllUser').live('click',function(){
	
		jQuery('.checkU:checked').each(function(){
				
				if(!jQuery(this).hasClass('active')){
					var idU = jQuery(this).attr('value');
					jQuery.post('active_desactive_collab.php',{"id":idU, "action":1}, function(){
						jQuery('tr.user-'+idU+' td').attr('bgColor','green');
						jQuery('tr.user-'+idU+' td a#desactiveUser').show();
						jQuery('tr.user-'+idU+' td a#activeUser').hide();
						
						jQuery('tr.user-'+idU+' td input:checkbox').removeClass('active');
						jQuery('tr.user-'+idU+' td input:checkbox').addClass('active');
						
					});
				}
			
		
		});
	});
	
	
	jQuery('img#deactiveAllUser').live('click',function(){
		jQuery('.checkU:checked').each(function(){
			if(jQuery(this).hasClass('active')){
				var idU = jQuery(this).attr('value');
				jQuery.post('active_desactive_collab.php',{"id":idU, "action":0}, function(){
					jQuery('tr.user-'+idU+' td').attr('bgColor','red');
					jQuery('tr.user-'+idU+' td a#desactiveUser').hide();
					jQuery('tr.user-'+idU+' td a#activeUser').show();
					jQuery('tr.user-'+idU+' td input:checkbox').removeClass('active');
					
				});
			}
		
		});
	});
	
	
	
	function desactiverUser(id){
		if (confirm("Voulez-vous désactiver cet utilisateur ?")) { // Clic sur OK
			jQuery.post('active_desactive_collab.php',{"id":id, "action":0}, function(data){
				jQuery('tr.user-'+id+' td').attr('bgColor','red');
				jQuery('tr.user-'+id+' td a#desactiveUser').hide();
				jQuery('tr.user-'+id+' td a#activeUser').show();
				jQuery('tr.user-'+id+' td input:checkbox').removeClass('active');
			});
		}
		
	}
	function activerUser(id){
		if (confirm("Voulez-vous activer cet utilisateur ?")) { // Clic sur OK
			jQuery.post('active_desactive_collab.php',{"id":id, "action":1}, function(){
				jQuery('tr.user-'+id+' td').attr('bgColor','green');
				
				jQuery('tr.user-'+id+' td a#desactiveUser').show();
				jQuery('tr.user-'+id+' td a#activeUser').hide();
				jQuery('tr.user-'+id+' td input:checkbox').removeClass('active');
				jQuery('tr.user-'+id+' td input:checkbox').addClass('active');
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
			
			function changePagePaginate(alpha){
			
			
			var groupe = jQuery('select#groupebd').val();
		
			window.location = 'gest_project_user.php?alpha='+alpha+'&gp='+groupe;
			
			
			}

			//--> 
			</SCRIPT>
			<STYLE type="text/css">
			div.center-wrap-alpha a {
				background: none repeat scroll 0 0 #CCCCCC;
				color: #FFFFFF;
				cursor: pointer;
				font-weight: bold;
				margin: 0 1px;
				padding: 2px 5px;
			}
			div.center-wrap-alpha a:hover {
				background: none repeat scroll 0 0 #C4C4C4;
				color: #CC0000;
				font-weight: bold;
				margin: 0 1px;
				padding: 2px 5px;
			}
			div.center-wrap-alpha a.active {
				color: #CC0000;
				font-weight: bold;
			}
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
            
           
			<TABLE class="jiraform  maxWidth">
              <TBODY>
              <TR>
                <TD valign="top" class="jiraformheader"><H3 class=formtitle>Gestion des utilisateurs</H3></TD></TR>
              <TR>
                <TD valign="top" class=jiraformbody></TD>
              </TR></TBODY></TABLE>
			  <form action="#" style="margin-top:50px;;margin-bottom:15px;text-align:center" >
				<div class="center-wrap-alpha"> 
					<b>Groupe : </b> <select name="groupe" id="groupebd" style="margin-right:80px"><option value="">Tous</option>
					<?php 
					 $query="SELECT DISTINCT(groupbase.ID), groupbase.groupname
														FROM groupbase
														ORDER BY groupbase.groupname ASC "; // Ajout de Status
					$result = mysql_query($query); 
							
							
							
							while($row = mysql_fetch_array($result)){ 
							  	$id = $row[0];
							    $pname = $row[1];
							  
							   ?>
					<option value="<?php echo $id; ?>" <?php if($groupe == $id){ echo "selected='selected'";} ?>> <?php echo $pname; ?></option>
					<?php } ?>
					</select>
					<?php   foreach(range('a', 'z') as $lettre){ ?><a role="button" class=" <?php if (strtolower($alpha) == $lettre){ echo "active ";}?>paginate-page" id="p-<?php echo $lettre;?>"  rel="<?php echo $lettre;?>" onclick="changePagePaginate('<?php echo $lettre;?>')"><span><?php echo strtoupper($lettre);?></span></a><?php }?><a <?php if ($alpha == 'all'){?>class="active" <?php }?> rel="<?php echo "all";?>" role="button" onclick='changePagePaginate("all")'><span>Tous</span></a>
					</p>
				</div>
			 </form>
			 
			 
              <table width="60%" id="myTableg" cellspacing="1" cellpadding="4" border="0" bgcolor="#bbbbbb" align="center">
              <tbody><tr class="jiraformheader">
                <th valign="top" align="center" class="style2" style="text-align:center">Libellé</th>
				<th style="text-align:center"><img src='../images/activer2.png' style="cursor:pointer" width='25px' title="Activer" id="activeAllUser"/>&nbsp;<img src='../images/desactiver.png' style="cursor:pointer" width='25px' title="D&eacute;sactiver" id="deactiveAllUser"/></th>
                <th style="width:10px;text-align:center" align="center"><input type="checkbox" name="selectAll" id="checkAllUser"/></th>
              </tr>
                    
          <?php 
          
         $query="SELECT DISTINCT(userbase.ID), propertystring . propertyvalue ,userbasestatus.status
										FROM propertystring
										
										left join  propertyentry on ( propertystring.id = propertyentry.id)
										left join  userbase on (  propertyentry.entity_id = userbase.id)
										left join  membershipbase on (membershipbase.USER_NAME = userbase.username)
										left join  userbasestatus on (   userbase.id = userbasestatus.user)
										left join  groupbase on ( groupbase.groupname=membershipbase.GROUP_NAME)
										
										WHERE
										 propertyentry.property_key = 'fullname'
									
										 ";
										 
										 if($alpha !="" && $alpha!="all"){
											$query .= " and propertyvalue like '$alpha%'";
										 }
										 
										 if($groupe !="" && $groupe!="all"){
											$query .=" and groupbase.ID ='".intval($groupe)."' ";
										 }
										
										$query .= " ORDER BY propertyvalue ASC"; // Ajout de Status
			$result = mysql_query($query); 
			
			
			
			while($row = mysql_fetch_array($result)){ 
			  	$id = $row[0];
			    $pname = $row[1];
			    $status = $row[2];
			   
			    ?>
			    
			    <tr class="jiraformheader-1 user-<?php echo $id;?>"><td style="width:350px" <?php if($status == 1){?>bgColor=green<?php }else{?> bgColor=red<?php }?>><?php  echo $pname;?></td><td style="width:50px"<?php if($status == 1){?>bgColor=green<?php }else{?> bgColor=red<?php }?> align="center"><a href="javascript://" onclick="desactiverUser('<?php echo $id;?>')" id="desactiveUser" <?php if($status != 1){?>style="display:none"<?php }?>><img src='../images/desactiver.png' style="cursor:pointer" width='25px' title="D&eacute;sactiver"/></a><a href="javascript://" onclick="activerUser('<?php echo $id;?>')"  id="activeUser" <?php if($status == 1){?>style="display:none"<?php }?>><img src='../images/activer2.png' style="cursor:pointer" width='25px' title="Activer"/></a></td><td style="width:10px;text-align:center" <?php if($status == 1){?>bgColor=green<?php }else{?> bgColor=red<?php }?>><input type="checkbox" name="selectU" id="checkUser" class="checkU<?php if($status == 1){?> active<?php } ?>" value="<?php echo $id;?>"/></td></tr>
			<?php  }
			?>

		  </tbody></table>

	
<?php  include("bottom.php"); ?>  
 
    
  </body>
</html>

