 <SCRIPT language=JavaScript>
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
			function RemoveAccents(Texte){
				var Accents = '��������������';
				var NoAccen = 'aaaeeeeiioouuu';
				 
				Accents = Accents.split('');
				NoAccen = NoAccen.split('');
				var i=0;
				while(Accents[i]){
									var Reg=new RegExp(Accents[i],'gi');
				                 Texte=Texte.replace(Reg,NoAccen[i]);
				                 i++
				                 }
				return Texte;
				}
			//--> 
			</SCRIPT>
						<script language="JavaScript" type="text/JavaScript">

function popup(page) {
	// ouvre une fenetre sans barre d'etat, ni d'ascenceur
	window.open(page,'popup','width=550,height=600,toolbar=no,scrollbars=No,resizable=yes,');	
}
</script>
<?php
//print_r($_SESSION['groupetab']);
$val_fer = 0;
$ancien_val_fer = 0;
$i = 0;
$j=0; 
$lien_bord = "inactif";
$lien_adm = "inactif";
$user_rapp = "inactif";
$valid_imputation = "inactif";
$update_imputation = "inactif";
$rapport_imputation = "inactif";
$rapport_projet = "inactif";
$rapport_facturation = "inactif";
$liste_group = membershipbase($user);
unset($_SESSION['exp_liste']);
$nb_group = count($liste_group);
$tab_group=array();
for($i==0;$i<$nb_group;$i++)
{
$tab_group[$i] = $liste_group[$i];
}
for($j==0;$j<$nb_group;$j++)
{
$list_group = $tab_group[$j];
if(strtolower($list_group)=="bd-users"){  $rapport_imputation = "actif"; }
if(strtolower($list_group)=="td-tdbusers"){ $lien_bord = "actif"; }
if(strtolower($list_group)=="td-administrators"){ $lien_adm = "actif"; }
if(strtolower($list_group)=="clients-users"){ $user_rapp = "actif"; }
if(strtolower($list_group)=="td-administrators"){ $valid_imputation = "actif"; $update_imputation = "actif"; $rapport_projet = "actif";  $rapport_facturation = "actif"; }
if(strtolower($list_group)=="bd-cp"){ $valid_imputation = "actif"; }
if(strtolower($list_group)=="bd-cra"){ $user_cras = "actif"; $rapport_imputation = "actif"; $rapport_projet = "actif";  $rapport_facturation = "actif";}
}
//echo $update_imputation;
?>
<link href="../style/global.css" rel="stylesheet" type="text/css">
<link href="../style/global-static.css" rel="stylesheet" type="text/css">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<link href="../style/theme.css" rel="stylesheet" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
        <TBODY>
        <TR valign="bottom" bgcolor="#161648">
          <TD vAlign="top" nowrap="nowrap" width="5%"><A 
            href="suivi_imputation.php"><IMG class=logo 
            height=60 alt="Business&amp;Decision Tunisie" 
            src="../images/logo-new.jpg"
            width=auto border="0"></A></TD>
          <TD vAlign="bottom" nowrap="nowrap" width="65%">&nbsp;</TD>
          <TD vAlign="bottom" nowrap="nowrap" align="right" width="30%" class="texte-bleu10n"><?php echo $tab_parametres['utilisateur'];?>: &nbsp;&nbsp;<?php echo nom_prenom_user($user); ?>&nbsp;&nbsp;&nbsp;<A 
            title="Se d�connecter et annuler l�ventuelle authentification automatique." 
            href="suivi_imputation.php?logout=logout" class="liens-bleu10n" style="padding-bottom:4px;"><?php echo $tab_parametres['deconnecter'];?></A>  &nbsp; &nbsp; <A 
            href="javascript:window.print()">
			<IMG 
            title="<?php echo $tab_parametres['imprimer'];?>" height=16 
            src="../images/print.gif" 
            width=16 align="absMiddle" border="0"></A> <a href="#" onClick="popup('aide.html')"><IMG title="<?php echo $tab_parametres['aide'];?>"  height=16 src="../images/help_blue.gif" width=16 align="absMiddle" border="0"></A>&nbsp;  </TD>
        </TR></TBODY></TABLE></td>
  </tr>
  <tr><td><TABLE class="menu" cellSpacing="0" cellPadding="0" width="100%" border="0">
  <TBODY>
  <TR>
    <TD bgColor="#161648">
      <TABLE cellSpacing="0" cellPadding="0" border="0" height="21">
        <TBODY>
        <TR>
          <TD width="5"><IMG height="1" 
            src="../images/spacer.gif" 
            width="1" border="0"></TD>
			<?php if($user_rapp=="inactif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onclick="window.document.location='suivi_imputation.php'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['accuei']);?>" accessKey=a 
            onclick="return false" 
            href="suivi_imputation.php"><?php echo strtoupper($tab_parametres['accuei']);?></A> 
            &nbsp;&nbsp;</TD><?php } ?>
			<TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onclick="window.document.location='rapport_activite.php'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['rapp_activi']);?>" accessKey=a 
            onclick="return false" 
            href="rapport_activite.php"><?php echo strtoupper($tab_parametres['rapp_activi']);?></A> 
            &nbsp;&nbsp;</TD>
			<?php if($user_rapp=="inactif") { ?>
			<TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onclick="window.document.location='date_cras.php'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['generer_cras']);?>" accessKey=a 
            onclick="return false" 
            href="date_cras.php"><?php echo strtoupper($tab_parametres['generer_cras']);?></A> 
            &nbsp;&nbsp;</TD><?php } ?>
			<?php if(($lien_adm=="actif") and ($user_rapp=="inactif")) { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onclick="window.document.location='admin.php'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['admin']);?>" href="admin.php"><?php echo strtoupper($tab_parametres['admin']);?></A> &nbsp;&nbsp;</TD><?php }  if($valid_imputation == "actif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
           onclick="window.document.location='valid_imputation.php'" 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['valid_imputation']);?>" href="javascript://"><?php echo strtoupper($tab_parametres['valid_imputation']);?></A> &nbsp;&nbsp;</TD><?php }  if($update_imputation == "actif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
           onclick="window.document.location='update_imputation.php'" 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['update_imp']);?>" href="javascript://"><?php echo strtoupper($tab_parametres['update_imp']);?></A> &nbsp;&nbsp;</TD><?php }  if($rapport_imputation == "actif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
        	  onclick="window.document.location='rapport_imputation.php?y=<?php echo date('Y')?>'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          align="middle">&nbsp;&nbsp; <A id="home_link"
            title="<?php echo strtoupper($tab_parametres['rapport_imputation']);?>" href="javascript://"><?php echo strtoupper($tab_parametres['rapport_imputation']);?></A> &nbsp;&nbsp;</TD><?php } if($rapport_projet == "actif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
          onclick="window.document.location='rapport_projet.php?y=<?php echo date('Y')?>'" 
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['rapport_projet']);?>" href="javascript://"><?php echo strtoupper($tab_parametres['rapport_projet']);?></A> &nbsp;&nbsp;</TD><?php } if($rapport_facturation == "actif") { ?>
          <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
        	 onclick="window.document.location='rapport_facturation.php?y=<?php echo date('Y')?>'"      
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="<?php echo strtoupper($tab_parametres['rapport_facturation']);?>" href="javascript://"><?php echo strtoupper($tab_parametres['rapport_facturation']);?></A> &nbsp;&nbsp;</TD><?php } ?>
         <?php  if($valid_imputation == "actif") { ?>  
            <TD class="navItem" onMouseOver="this.className='navItemOver'" 
          onmouseout="this.className='navItem'" vAlign=center noWrap 
        	 onclick="window.document.location='histo_project.php'"      
          align="middle">&nbsp;&nbsp; <A id=home_link 
            title="HISTORIQUES DES PROJETS" href="javascript://">HISTORIQUES DES PROJETS</A> &nbsp;&nbsp;</TD><?php }?>
        
          <SCRIPT type=text/javascript>
            var calledReallyShow = false;
            var calledReallyHide = false;
            var mouseOnText = false;
            var mouseInTip = false;

            function showToolTip()
            {
                mouseOnText = true;
                mouseInTip = false;
                if(!calledReallyShow)
                {
                    calledReallyShow = true;
                    self.setTimeout("reallyShowTip()", 800);
                }
            }

            function recordInTip()
            {
                mouseInTip = true;
                mouseOnText = false;
            }

            function recordOutTip()
            {
                mouseInTip = false;
                fireReallyHide();
            }

            function reallyShowTip()
            {
                calledReallyShow = false;
                if(mouseOnText)
                {
                    document.getElementById('quicksearchhelp').style.display='';
                    document.getElementById('quicksearchhelp').style.top= findPosY(document.getElementById('quickSearchInput')) + 25;
                }
            }

            function hideToolTip()
            {
                mouseOnText = false;
                fireReallyHide();
            }

            function fireReallyHide()
            {
                if(!calledReallyHide)
                {
                    calledReallyHide = true;
                    self.setTimeout("reallyHideTip()", 800);
                }
            }

            function reallyHideTip()
            {
                calledReallyHide = false;
                if(!mouseInTip)
                {
                    document.getElementById('quicksearchhelp').style.display='none';
                }
            }

            function findPosY(obj)
            {
                var curtop = 0;
                if (obj.offsetParent)
                {
                    while (obj.offsetParent)
                    {
                        curtop += obj.offsetTop
                        obj = obj.offsetParent;
                    }
                }
                else if (obj.y)
                    curtop += obj.y;
                return curtop;
            }
        </SCRIPT>

          <TD class="navItem" vAlign=center align=right width="100%" 


          bgColor="#161648">&nbsp;</TD>
          <TD width="5"><IMG height="1" 
            src="../images/spacer.gif" 
            width="1" border="0"></TD>
        </TR></TBODY></TABLE></TD></TR></TBODY></TABLE></td></tr>
</table>
