//fonction pour le cas d'appui sur la touche entree
function testsubmit() {
	if (document.formulaire.action=="") return false;
	return true ;
}

//fonction pour choisir l'action
function gopage(page)
{

	document.form.action = page; 
	if(page=="export_validation_imp.php"){
		var chaine =document.getElementById('div_form').innerHTML;
		chaine = RemoveAccents(chaine);
		document.getElementById('rapport_activite').value=chaine;
		document.getElementById('annee').value=document.getElementById('annee').innerHTML;
		document.getElementById('mois').value=document.getElementById('mois').innerHTML;
		document.getElementById('valid_imputation').value=document.getElementById('valid_imputation').innerHTML;
	}else {
		document.form.submit();
	}
}

//fonction pour choisir l'action
function gopagef(page){
	document.getElementById('annees').value=jQuery('select#annee').val();
	document.getElementById('moiss').value=jQuery('select#mois').val();
	document.getElementById('projss').value=jQuery('select#projets').val();
	document.getElementById('collabs').value=jQuery('select#collab').val();
	document.getElementById('gbu').value=jQuery('select#group-bu').val();
	document.getElementById('semaine').value=jQuery('select#semaines').val();
	jQuery('input#pageS').val('1');
	 document.form.action = page;
	 document.form.submit();
}
//fonction pour choisir la page
function changePage3(proj){
	var BU = jQuery('select#group-bu').val();
	jQuery.post('ajax/list_user_par_proj.php',{"proj":proj,"group":BU}, function(data){
		jQuery('select#collab').html(data);
	});
}
//change liste bu (poles)
function changeListeUserBu(bu){
	var proj = jQuery('select#projets').val();
	jQuery.post('ajax/list_user_par_bu.php',{"group":bu, "projet":proj}, function(data){
		jQuery('select#collab').html(data);
		
	});
}
//pagination
function changePagePaginate(page){
	//jQuery('#pg-paginate').val(page);
	var mm =document.getElementById('moiss').value;
	var an =document.getElementById('annees').value;
	var proj = document.getElementById('projss').value;
	var collab = document.getElementById('collabs').value;
	var bu = document.getElementById('gbu').value;
	var semaine=	document.getElementById('semaine').value;
	window.location = 'valid_imputation.php?y='+an+'&mm='+mm+'&pg='+page+'&projss='+proj+'&collab='+collab+'&gb='+bu+'&semaine='+semaine;
}


// valider imputation
function update_imp_check(idIMP,login,value,user,date, elm, kd, proj, isValider){
	
	if(jQuery(elm).is(':checked')){
		var valueF = 1;
		var fact = 'facturable';
	}else{
		var valueF = 2;
		var fact = 'non facturable';
	}
	
	
	
	if(parseInt(isValider) > 0 || jQuery(elm).hasClass('valideBy')){
	
				jQuery("<div>Vous &ecirc;tes entrain de modifier l'&eacute;tat de facturation de cette imputation, vous voulez continuer ?</div>").dialog({
					resizable: false,
					height:150,
					title: 'Confirmation',
					modal: true,
					buttons: {
						"Ok": function() {
							jQuery( this ).dialog( "close" );
							jQuery.post('ajax/valide_par_imp.php',{"id":idIMP,"date":date, "login":login, "value":valueF, "user":user}, function(data){
								jQuery(elm).parent().parent().css({'background-color':'green'});
								
								if(!jQuery(elm).parent().parent().hasClass('isvalide')){ 
									jQuery(elm).parent().parent().removeClass('isnovalide');
									jQuery(elm).parent().parent().removeClass('isvalide');
									jQuery(elm).parent().parent().addClass('isvalide');
									
									jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
									jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
									jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
											
									if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) <= 0){
										jQuery("td.issueT-"+idIMP+"-"+user+" .njr").html('0');
										jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
										jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
										jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
										jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
										jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').hide();
										jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').hide();
										jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
									}
									
									if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
										jQuery("td.projTTTT-"+proj+"-"+user+" .njr").html('0');
										jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
										jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
										jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
										jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
										jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
										jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
										jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
									}
									
									if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
										jQuery("td.user-nov-"+user).html('0');
										jQuery("td.user-nov-"+user).removeClass('isnovalide');
										jQuery("td.user-nov-"+user).removeClass('isvalide');
										jQuery("td.user-nov-"+user).addClass('isvalide');
										jQuery("td.user-nov-"+user).css({'background-color':'green'});
										jQuery("td.user-nov-"+user).find('img.valideIs').hide();
										jQuery("td.user-nov-"+user).find('input:checkbox').hide();
										jQuery("td.user-nov-"+user).find('img.nvalideIs').show();
									}
						
									
									var verifimp = false;
									jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
										if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
											if(!jQuery(this).hasClass('isvalide')){
												verifimp = true;
											}
										}
									});
									
									if(!verifimp){
										jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
										jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
										jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isvalide');
										jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'green'});
										var verifproj = false;
										jQuery("td.proj-"+user+"-"+kd).each(function(){
											if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
												if(!jQuery(this).hasClass('isvalide')){
													verifproj = true;
												}
											}
										});
										if(!verifproj){
											jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
											jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
											jQuery("td.userT-"+user+"-"+kd).addClass('isvalide');
											jQuery("td.userT-"+user+"-"+kd).css({'background-color':'green'});
											jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
											jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
											jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
										}
										
										
										
									}
									
									var verifissue = false;
									jQuery("td.issue-"+idIMP+"-"+user).each(function(){
										if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
											if(!jQuery(this).hasClass('isvalide')){
												verifissue = true;
											}
										}
									});
									if(!verifissue){
										jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
										jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
										jQuery("td.issueT-"+user+"-"+kd).addClass('isvalide');
										jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) + parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
										jQuery("td.issueT-"+user+"-"+kd).text('0');
										jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
										jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
										jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
									}
									
								}
								jQuery(elm).parent().parent().find('img.valideIs').hide();
								jQuery(elm).parent().parent().find('img.nvalideIs').show();
								
							});
						},
						"Annuler": function() {
							if(jQuery(elm).is(':checked')){
								jQuery(elm).removeAttr('checked');
							}else{
								jQuery(elm).attr('checked','checked');
							}
							jQuery( this ).dialog( "close" );
						}
				}
			});
	}else{
		
		jQuery(elm).removeClass('valideBy');
		jQuery(elm).addClass('valideBy');
		
		jQuery.post('ajax/valide_par_imp.php',{"id":idIMP,"date":date, "login":login, "value":valueF, "user":user}, function(data){
					
					jQuery(elm).parent().parent().css({'background-color':'green'});
					
					if(!jQuery(elm).parent().parent().hasClass('isvalide')){ 
						jQuery(elm).parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().addClass('isvalide');
						
						jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
								
						if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) <= 0){
							jQuery("td.issueT-"+idIMP+"-"+user+" .njr").html('0');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').hide();
							jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').hide();
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
						}
						
						if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
							jQuery("td.projTTTT-"+proj+"-"+user+" .njr").html('0');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
						}
						
						if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
							jQuery("td.user-nov-"+user).html('0');
							jQuery("td.user-nov-"+user).removeClass('isnovalide');
							jQuery("td.user-nov-"+user).removeClass('isvalide');
							jQuery("td.user-nov-"+user).addClass('isvalide');
							jQuery("td.user-nov-"+user).css({'background-color':'green'});
							jQuery("td.user-nov-"+user).find('img.valideIs').hide();
							jQuery("td.user-nov-"+user).find('input:checkbox').hide();
							jQuery("td.user-nov-"+user).find('img.nvalideIs').show();
						}
			
						
						var verifimp = false;
						jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
							if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								if(!jQuery(this).hasClass('isvalide')){
									verifimp = true;
								}
							}
						});
						
						if(!verifimp){
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isvalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'green'});
							var verifproj = false;
							jQuery("td.proj-"+user+"-"+kd).each(function(){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									if(!jQuery(this).hasClass('isvalide')){
										verifproj = true;
									}
								}
							});
							if(!verifproj){
								jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
								jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
								jQuery("td.userT-"+user+"-"+kd).addClass('isvalide');
								jQuery("td.userT-"+user+"-"+kd).css({'background-color':'green'});
								jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
								jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
								jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
							}
							
							
							
						}
						
						var verifissue = false;
						jQuery("td.issue-"+idIMP+"-"+user).each(function(){
							if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								if(!jQuery(this).hasClass('isvalide')){
									verifissue = true;
								}
							}
						});
						if(!verifissue){
							jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
							jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
							jQuery("td.issueT-"+user+"-"+kd).addClass('isvalide');
							jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) + parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
							jQuery("td.issueT-"+user+"-"+kd).text('0');
							jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
							jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
							jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
						}
						
					}
					
					jQuery(elm).parent().parent().find('img.valideIs').hide();
					jQuery(elm).parent().parent().find('img.nvalideIs').show();
					
				});
	}
	
}


   
// valider imputation
function update_imp(idIMP,login,value,user,date, elm, kd, proj, isValider){
	if(jQuery(elm).parent().parent().find('input:checkbox:checked').length == 1){
		var valueF = 1;
		var fact = 'facturable';
	}else{
		var valueF = 2;
		var fact = 'non facturable';
	}
	
	

		
		jQuery(elm).removeClass('valideBy');
		jQuery(elm).addClass('valideBy');
		
		jQuery.post('ajax/valide_par_imp.php',{"id":idIMP,"date":date, "login":login, "value":valueF, "user":user}, function(data){
					
					jQuery(elm).parent().parent().css({'background-color':'green'});
					
					if(!jQuery(elm).parent().parent().hasClass('isvalide')){ 
						jQuery(elm).parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().addClass('isvalide');
						
						jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
								
						if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) <= 0){
							jQuery("td.issueT-"+idIMP+"-"+user+" .njr").html('0');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').hide();
							jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').hide();
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
							
						}
						
						if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
							jQuery("td.projTTTT-"+proj+"-"+user+" .njr").html('0');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
						}
						
						if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
							jQuery("td.user-nov-"+user).html('0');
							jQuery("td.user-nov-"+user).removeClass('isnovalide');
							jQuery("td.user-nov-"+user).removeClass('isvalide');
							jQuery("td.user-nov-"+user).addClass('isvalide');
							jQuery("td.user-nov-"+user).css({'background-color':'green'});
							jQuery("td.user-nov-"+user).find('img.valideIs').hide();
							jQuery("td.user-nov-"+user).find('input:checkbox').hide();
							jQuery("td.user-nov-"+user).find('img.nvalideIs').show();
						}
			
						
						var verifimp = false;
						jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
							if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								if(!jQuery(this).hasClass('isvalide')){
									verifimp = true;
								}
							}
						});
						
						if(!verifimp){
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isvalide');
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'green'});
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).find('img.valideIs').hide();
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).find('input:checkbox').hide();
							jQuery("td.proj-"+proj+"-"+user+"-"+kd).find('img.nvalideIs').show();
							var verifproj = false;
							jQuery("td.proj-"+user+"-"+kd).each(function(){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									if(!jQuery(this).hasClass('isvalide')){
										verifproj = true;
									}
								}
							});
							if(!verifproj){
								jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
								jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
								jQuery("td.userT-"+user+"-"+kd).addClass('isvalide');
								jQuery("td.userT-"+user+"-"+kd).css({'background-color':'green'});
								jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
								jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
								jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
							}
							
							
							
						}
						
						var verifissue = false;
						var verifissueissuenv = false;
						jQuery("td.issue-"+idIMP+"-"+user).each(function(){
							if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								if(!jQuery(this).hasClass('isvalide')){
									verifissue = true;
								}
								if(jQuery(this).hasClass('isnovalide')){
									verifissueissuenv = true;
								}
							}
						});
						if(!verifissue){
							jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
							jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
							jQuery("td.issueT-"+user+"-"+kd).addClass('isvalide');
							jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) + parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
							jQuery("td.issueT-"+user+"-"+kd).text('0');
							jQuery("td.issueT-"+user+"-"+kd).find('img.valideIs').hide();
							jQuery("td.issueT-"+user+"-"+kd).find('input:checkbox').hide();
							jQuery("td.issueT-"+user+"-"+kd).find('img.nvalideIs').show();
						}
						
						
						if( verifissue=== true && verifissueissuenv === true){						
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
							jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').show();
							jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
						}
						
						if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
							jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
						}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
						}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
							jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
							jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
						}
						/*if(parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()) > 0  &&  parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) > 0){
							jQuery("td.issueT-"+user+"-"+kd).find('img.valideIs').show();
							jQuery("td.issueT-"+user+"-"+kd).find('input:checkbox').show();
							jQuery("td.issueT-"+user+"-"+kd).find('img.nvalideIs').show();
						}*/
						
						
					}
					
					jQuery(elm).hide();
					//jQuery(elm).parent().parent().find('input:checkbox').hide();
					jQuery(elm).prev().show();
					
					
				});
	
	
}


// devalider imputation
function update_impD(idIMP,login,value,user,date, elm, kd, proj, isValider){
	
	if(jQuery(elm).parent().parent().hasClass('isvalide')){
	
			jQuery("<div>Cette imputation est déjà validée, êtes vous sur de vouloir enlever cette validation?</div>").dialog({
				resizable: false,
				height:190,
				title: 'Confirmation',
				modal: true,
				buttons: {
					"Ok": function() {
						jQuery( this ).dialog( "close" );
						jQuery(elm).removeClass('valideBy');
				
						jQuery.post('ajax/devalide_par_imp.php',{"id":idIMP,"date":date, "login":login, "user":user}, function(data){
									
									jQuery(elm).parent().parent().css({'background-color':'#FEB75A'});
									
									if(jQuery(elm).parent().parent().hasClass('isvalide')){ 
										jQuery(elm).parent().parent().removeClass('isnovalide');
										jQuery(elm).parent().parent().removeClass('isvalide');
										jQuery(elm).parent().parent().addClass('isnovalide');
										
										jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
										jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
										jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
												
										if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) > 0){
											
											jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
											jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
											jQuery("td.issueT-"+idIMP+"-"+user).addClass('isnovalide');
											jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'#FEB75A'});
											jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
											jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').show();
											jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').hide();
										}
										
										if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0){
											
											jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
											jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
											jQuery("td.projTTTT-"+proj+"-"+user).addClass('isnovalide');
											jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'#FEB75A'});
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
										}
										
										if(parseFloat(jQuery("td.user-nov-"+user).text()) > 0){
											
											jQuery("td.user-nov-"+user).removeClass('isnovalide');
											jQuery("td.user-nov-"+user).removeClass('isvalide');
											jQuery("td.user-nov-"+user).addClass('isnovalide');
											jQuery("td.user-nov-"+user).css({'background-color':'#FEB75A'});
											jQuery("td.user-nov-"+user).find('img.valideIs').show();
											jQuery("td.user-nov-"+user).find('input:checkbox').show();
											jQuery("td.user-nov-"+user).find('img.nvalideIs').hide();
										}
										
										
										
										
							
										
										var verifimp = false;
										jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
											if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
												if(!jQuery(this).hasClass('isnovalide')){
													verifimp = true;
												}
											}
										});
										
										if(!verifimp){
											jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
											jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
											jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isnovalide');
											jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'#FEB75A'});
											var verifproj = false;
											jQuery("td.proj-"+user+"-"+kd).each(function(){
												if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
													if(!jQuery(this).hasClass('isnovalide')){
														verifproj = true;
													}
												}
											});
											if(!verifproj){
												jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
												jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
												jQuery("td.userT-"+user+"-"+kd).addClass('isnovalide');
												jQuery("td.userT-"+user+"-"+kd).css({'background-color':'#FEB75A'});
												jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').show();
												jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').show();
												jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').hide();
											}
											
											
											
										}
										
										var verifissue = false;
										var verifissueissuenv = false;
										jQuery("td.issue-"+idIMP+"-"+user).each(function(){
											if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
												if(!jQuery(this).hasClass('isnovalide')){
													verifissue = true;
												}
												if(jQuery(this).hasClass('isnovalide')){
													verifissueissuenv = true;
												}
											}
										});
										
										if(!verifissue){
											jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
											jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
											jQuery("td.issueT-"+user+"-"+kd).addClass('isnovalide');
											jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) - parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
											jQuery("td.issueT-"+user+"-"+kd).text('0');
											jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').show();
											jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').show();
											jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').hide();
											
										}
										
										if( verifissue=== true && verifissueissuenv === true){						
											jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
											jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').show();
											jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
										}
										
										if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
										}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
											jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
										}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
											jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
										}
										
									}
									
									
									
									
									
									jQuery(elm).hide();
									jQuery(elm).parent().parent().find('input:checkbox').removeAttr('checked');
									jQuery(elm).parent().parent().find('input:checkbox').show();
									jQuery(elm).next().show();
									
								});
							},
							"Annuler": function() {
								jQuery( this ).dialog( "close" );
							}
						}
					});
	}else{
		jQuery("<div>Cette imputation n'est pas encore validée</div>").dialog({
					resizable: false,
					height:150,
					title: 'Information',
					modal: true,
					buttons: {
						"Ok": function() {
							jQuery( this ).dialog( "close" );
						}
					}
		});
	}
	
}


function changeMois(elm){
	jQuery.post('ajax/semaines_mois.php',{"mois":jQuery(elm).val(),"annee":jQuery('select#annee').val()}, function(data){	
			if(data.length >0){
				jQuery('select#semaines').html(data);
			}
	});
}


function changeSemaine(elm){
	jQuery.post('ajax/selected_mois.php',{"semaine":jQuery(elm).val(),"annee":jQuery('select#annee').val()}, function(data){
			if(data.length >0){
				jQuery('select#mois').val(data);
			}
	});
}

// valider imputation non facturable
function update_imp_nofact(idIMP,login,value,user,date, elm, kd, proj){
	
	jQuery.post('ajax/valide_par_imp.php',{"id":idIMP,"date":date, "login":login, "value":2, "user":user}, function(data){
		jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'green'});
		
		if(!jQuery(elm).parent().parent().parent().parent().parent().hasClass('isvalide')){ 
			jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
			jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
			jQuery(elm).parent().parent().parent().parent().parent().addClass('isvalide');
		
			jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
			jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
			jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
			if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) <= 0){
				jQuery("td.issueT-"+idIMP+"-"+user+" .njr").html('0');
				jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
				jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
				jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
				jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
				jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').hide();
				jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').hide();
				jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
			}
			
			if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
				jQuery("td.projTTTT-"+proj+"-"+user+" .njr").html('0');
				jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
				jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
				jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
				jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
				jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').hide();
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
			}
			
			if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
				jQuery("td.user-nov-"+user).html('0');
				jQuery("td.user-nov-"+user).removeClass('isnovalide');
				jQuery("td.user-nov-"+user).removeClass('isvalide');
				jQuery("td.user-nov-"+user).addClass('isvalide');
				jQuery("td.user-nov-"+user).css({'background-color':'green'});
				jQuery("td.user-nov-"+user).find('img.valideIs').hide();
				jQuery("td.user-nov-"+user).find('input:checkbox').hide();
				jQuery("td.user-nov-"+user).find('img.nvalideIs').show();
			}

			var verifimp = false;
			jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
				if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
					if(!jQuery(this).hasClass('isvalide')){
						verifimp = true;
					}
				}
			});
			
			if(!verifimp){
				jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
				jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
				jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isvalide');
				jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'green'});
				
				var verifproj = false;
				jQuery("td.proj-"+user+"-"+kd).each(function(){
					if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
						if(!jQuery(this).hasClass('isvalide')){
							verifproj = true;
						}
					}
				});
				if(!verifproj){
					jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
					jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
					jQuery("td.userT-"+user+"-"+kd).addClass('isvalide');
					jQuery("td.userT-"+user+"-"+kd).css({'background-color':'green'});
					jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
					jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
					jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
				}
				
				
				
			}
			
			var verifissue = false;
			var verifissueissuenv = false;
			jQuery("td.issue-"+idIMP+"-"+user).each(function(){
				if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
					if(!jQuery(this).hasClass('isvalide')){
						verifissue = true;
					}
					if(jQuery(this).hasClass('isnovalide')){
						verifissueissuenv = true;
					}
				}
			});
			if(!verifissue){
				jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
				jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
				jQuery("td.issueT-"+user+"-"+kd).addClass('isvalide');
				jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) + parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
				jQuery("td.issueT-"+user+"-"+kd).text('0');
				jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').hide();
				jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').hide();
				jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').show();
			}
			
			if( verifissue=== true && verifissueissuenv === true){						
				jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
				jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
			}
			
			if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
			}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
			}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
				jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
			}
								
			jQuery(elm).hide();
			jQuery(elm).prev().show();
		}
	});
	
}


// devalider imputation non facturable
function update_imp_nofactD(idIMP,login,value,user,date, elm, kd, proj){
	
	
	if(jQuery(elm).parent().parent().parent().parent().parent().hasClass('isvalide')){
	
			jQuery("<div>Cette imputation est déjà validée, êtes vous sur de vouloir enlever cette validation?</div>").dialog({
				resizable: false,
				height:190,
				title: 'Confirmation',
				modal: true,
				buttons: {
					"Ok": function() {
						jQuery( this ).dialog( "close" );
						jQuery(elm).removeClass('valideBy');
						
						jQuery.post('ajax/devalide_par_imp.php',{"id":idIMP,"date":date, "login":login, "user":user}, function(data){
							jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'#FEB75A'});
							
							if(jQuery(elm).parent().parent().parent().parent().parent().hasClass('isvalide')){ 
								jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
								jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
								jQuery(elm).parent().parent().parent().parent().parent().addClass('isnovalide');
							
								jQuery("td.issueTT-"+idIMP+"-"+user).text(parseFloat(jQuery("td.issueTT-"+idIMP+"-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
								jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
								jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
										
								if(parseFloat(jQuery("td.issueT-"+idIMP+"-"+user+" .njr").text()) > 0){
									
									jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
									jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
									jQuery("td.issueT-"+idIMP+"-"+user).addClass('isnovalide');
									jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'#FEB75A'});
									jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
									jQuery("td.issueT-"+idIMP+"-"+user).find('input:checkbox').show();
									jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').hide();
								}
								
								if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0){
								
									jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
									jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
									jQuery("td.projTTTT-"+proj+"-"+user).addClass('isnovalide');
									jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'#FEB75A'});
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
									jQuery("td.projTTTT-"+proj+"-"+user).find('input:checkbox').show();
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
								}
								
								if(parseFloat(jQuery("td.user-nov-"+user).text()) > 0){
									
									jQuery("td.user-nov-"+user).removeClass('isnovalide');
									jQuery("td.user-nov-"+user).removeClass('isvalide');
									jQuery("td.user-nov-"+user).addClass('isnovalide');
									jQuery("td.user-nov-"+user).css({'background-color':'#FEB75A'});
									jQuery("td.user-nov-"+user).find('img.valideIs').show();
									jQuery("td.user-nov-"+user).find('input:checkbox').show();
									jQuery("td.user-nov-"+user).find('img.nvalideIs').hide();
								}
					
								var verifimp = false;
								jQuery("td.issue-"+proj+"-"+user+"-"+kd).each(function(){
									if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
										if(!jQuery(this).hasClass('isnovalide')){
											verifimp = true;
										}
									}
								});
								
								if(!verifimp){
									jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isnovalide');
									jQuery("td.proj-"+proj+"-"+user+"-"+kd).removeClass('isvalide');
									jQuery("td.proj-"+proj+"-"+user+"-"+kd).addClass('isnovalide');
									jQuery("td.proj-"+proj+"-"+user+"-"+kd).css({'background-color':'#FEB75A'});
									
									var verifproj = false;
									jQuery("td.proj-"+user+"-"+kd).each(function(){
										if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
											if(!jQuery(this).hasClass('isnovalide')){
												verifproj = true;
											}
										}
									});
									if(!verifproj){
										jQuery("td.userT-"+user+"-"+kd).removeClass('isnovalide');
										jQuery("td.userT-"+user+"-"+kd).removeClass('isvalide');
										jQuery("td.userT-"+user+"-"+kd).addClass('isnovalide');
										jQuery("td.userT-"+user+"-"+kd).css({'background-color':'#FEB75A'});
										jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').show();
										jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').show();
										jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').hide();
									}
									
									
									
								}
								
								var verifissue = false;
								var verifissueissuenv = false;
								jQuery("td.issue-"+idIMP+"-"+user).each(function(){
									if(!jQuery(this).hasClass('videImp') && jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
										if(!jQuery(this).hasClass('isnovalide')){
											verifissue = true;
										}
										if(jQuery(this).hasClass('isnovalide')){
											verifissueissuenv = true;
										}
									}
								});
								if(!verifissue){
									jQuery("td.issueT-"+user+"-"+kd).removeClass('isnovalide');
									jQuery("td.issueT-"+user+"-"+kd).removeClass('isvalide');
									jQuery("td.issueT-"+user+"-"+kd).addClass('isnovalide');
									jQuery("td.issueTT-"+user+"-"+kd).text(parseFloat(jQuery("td.issueTT-"+user+"-"+kd).text()) - parseFloat(jQuery("td.issueT-"+user+"-"+kd).text()));
									jQuery("td.issueT-"+user+"-"+kd).text('0');
									jQuery("td.userT-"+user+"-"+kd).find('img.valideIs').show();
									jQuery("td.userT-"+user+"-"+kd).find('input:checkbox').show();
									jQuery("td.userT-"+user+"-"+kd).find('img.nvalideIs').hide();
								}
								
								if( verifissue=== true && verifissueissuenv === true){						
									jQuery("td.issueT-"+idIMP+"-"+user).find('img.valideIs').show();
									jQuery("td.issueT-"+idIMP+"-"+user).find('img.nvalideIs').show();
								}
								
								if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
								}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
								}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
									jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
								}
							}
							
							jQuery(elm).hide();
							jQuery(elm).next().show();
						});
					},
							"Annuler": function() {
								jQuery( this ).dialog( "close" );
							}
						}
					});
					
	}else{
		jQuery("<div>Cette imputation n'est pas encore validée</div>").dialog({
					resizable: false,
					height:150,
					title: 'Information',
					modal: true,
					buttons: {
						"Ok": function() {
							jQuery( this ).dialog( "close" );
						}
					}
		});
	}
	
}
// valider projet
function update_all_proj(project,login,value,user, date1, date2,elm){

	
	if(jQuery(elm).parent().parent().find('input:checkbox:checked').length == 1){
		var valueF = 1;
		var fact = 'facturable';
	}else{
		var valueF = 2;
		var fact = 'non facturable';
	}
	
	jQuery("<div>Vous avez choisi de valider tous les imputations de ce projet comme "+fact+" entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/valide_par_proj.php',{"id":project, "login":login, "date1":date1, "date2":date2, "value":valueF, "user":user}, function(data){
					
					jQuery(elm).parent().parent().css({'background-color':'green'});
					jQuery(elm).parent().parent().removeClass('isnovalide');
					jQuery(elm).parent().parent().removeClass('isvalide');
					jQuery(elm).parent().parent().addClass('isvalide');
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().prev().text()));
					
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
					jQuery("td.proj-"+project).each(function(){
							jQuery(this).find('img').removeClass('valideBy');
							jQuery(this).find('img').addClass('valideBy');
							jQuery(this).find('input:checkbox').removeClass('valideBy');
							jQuery(this).find('input:checkbox').addClass('valideBy');
							
							jQuery(this).find('input:checkbox').removeAttr('checked');
							if(valueF == 1){
								jQuery(this).find('input:checkbox').attr('checked','checked');
							}
							if(jQuery(this).hasClass('isnovalide')){
								jQuery(this).css({'background-color':'green'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isvalide');
								jQuery(this).find('img.valideIs').hide();
								//jQuery(this).find('input:checkbox').hide();
								jQuery(this).find('img.nvalideIs').show();
							}
					});
					
					var verif = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).css({'background-color':'green'});
							jQuery(this).find('img.valideIs').hide();
							//jQuery(this).find('input:checkbox').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
					}
					
					jQuery("td.issue-proj-"+project).each(function(){
						jQuery(this).find('input:checkbox').hide();
						jQuery(this).find('img.valideIs').hide();
						jQuery(this).find('br').hide();
						jQuery(this).find('img.nvalideIs').show();
						jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
						jQuery(this).find('.njr').text('0');
					});
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).find('img.valideIs').hide();
							//jQuery(this).find('input:checkbox').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						
					}
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().find('input:checkbox').hide();
					jQuery(elm).parent().parent().find('br').hide();
					jQuery(elm).prev().show();
					jQuery(elm).parent().parent().prev('td').text(parseFloat(jQuery(elm).parent().parent().prev('td').text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
				});
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
	
	
}


// devalider projet
function update_all_projD(project,login,value,user, date1, date2,elm){

	
	jQuery("<div>Vous avez choisi d'annuler la validation de tous les imputations de ce projet entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/devalide_par_proj.php',{"id":project, "login":login, "date1":date1, "date2":date2, "user":user}, function(data){
					
					jQuery(elm).parent().parent().css({'background-color':'#FEB75A'});
					jQuery(elm).parent().parent().removeClass('isnovalide');
					jQuery(elm).parent().parent().removeClass('isvalide');
					jQuery(elm).parent().parent().addClass('isnovalide');
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().prev().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().prev().text()));
					
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
					jQuery("td.proj-"+project).each(function(){
							jQuery(this).find('img').removeClass('valideBy');
							jQuery(this).find('input:checkbox').removeClass('valideBy');
							
							jQuery(this).find('input:checkbox').removeAttr('checked');
							
							if(jQuery(this).hasClass('isvalide') ){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									jQuery(this).css({'background-color':'#FEB75A'});
									jQuery(this).removeClass('isnovalide');
									jQuery(this).removeClass('isvalide');
									jQuery(this).addClass('isnovalide');
									jQuery(this).find('img.nvalideIs').hide();
									jQuery(this).find('img.valideIs').show();
									jQuery(this).find('input:checkbox').show();
								}
							}
					});
					
					var verif = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
					}
					
					jQuery("td.issue-proj-"+project).each(function(){
						jQuery(this).find('img.nvalideIs').hide();
						jQuery(this).find('input:checkbox').show();
						jQuery(this).find('img.valideIs').show();
						jQuery(this).find('br').show();
						jQuery(this).find('.njr').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).find('.njr').text()));
						jQuery(this).prev('td').text('0');
						
					});
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
						
					}
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().find('input:checkbox').show();
					jQuery(elm).next().show();
					jQuery(elm).parent().parent().find('br').show();
					jQuery(elm).parent().prev().text(parseFloat(jQuery(elm).parent().prev().text()) + parseFloat(jQuery(elm).parent().parent().prev('td').text()));
					jQuery(elm).parent().parent().prev('td').text('0');
					
					
				});
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
	
	
}
// valider projet non facturable
function update_all_projNfact(project,login,value,user, date1, date2,elm){
	
	jQuery("<div>Vous avez choisi de valider tous les imputations de ce projet comme non facturable entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/valide_par_proj.php',{"id":project, "login":login, "date1":date1, "date2":date2, "value":2, "user":user}, function(data){
					
					jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'green'});
					jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
					jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
					jQuery(elm).parent().parent().parent().parent().parent().addClass('isvalide');
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().prev().text()));
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
					jQuery("td.proj-"+project).each(function(){
							jQuery(this).find('input:checkbox').removeAttr('checked');
							if(jQuery(this).hasClass('isnovalide')){
								jQuery(this).css({'background-color':'green'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isvalide');
								jQuery(this).find('img.valideIs').hide();
								jQuery(this).find('img.nvalideIs').show();
							}
					});
					
					var verif = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
								
						});
					}
					
					jQuery("td.issue-proj-"+project).each(function(){
					
						jQuery(this).find('img.valideIs').hide();
						
						jQuery(this).find('img.nvalideIs').show();
						
						jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).find('.njr').text()));
						jQuery(this).find('.njr').text('0');
					});
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif2 = true;
								
							}
						}
					});
					
					
				
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						
					}
					
					
					
					jQuery(elm).hide();
					jQuery(elm).prev().show();
					jQuery(elm).parent().parent().parent().parent().parent().find('input:checkbox').hide();
					jQuery(elm).parent().parent().parent().parent().parent().find('br').hide();
					jQuery(elm).parent().parent().parent().parent().parent().prev('td').text(parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev('td').text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
				});
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}


// devalider projet non facturable
function update_all_projNfactD(project,login,value,user, date1, date2,elm){
	
	jQuery("<div>Vous avez choisi d'annuler la validation de tous les imputations de ce projet entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/devalide_par_proj.php',{"id":project, "login":login, "date1":date1, "date2":date2, "user":user}, function(data){
					
					jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'#FEB75A'});
					jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
					jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
					jQuery(elm).parent().parent().parent().parent().parent().addClass('isnovalide');
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
					jQuery("td.proj-"+project).each(function(){
							jQuery(this).find('input:checkbox').removeAttr('checked');
							if(jQuery(this).hasClass('isvalide')){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									jQuery(this).css({'background-color':'#FEB75A'});
									jQuery(this).removeClass('isnovalide');
									jQuery(this).removeClass('isvalide');
									jQuery(this).addClass('isnovalide');
									jQuery(this).find('img.nvalideIs').hide();
									jQuery(this).find('input:checkbox').show();
								}
							}
					});
					
					var verif = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
					}
					
					jQuery("td.issue-proj-"+project).each(function(){
						jQuery(this).find('img.nvalideIs').hide();
						
						jQuery(this).find('img.valideIs').show();
						jQuery(this).find('br').show();
						jQuery(this).find('.njr').text(parseFloat(jQuery(this).find('.njr').text()) + parseFloat(jQuery(this).prev('td').text()));
						jQuery(this).prev('td').text('0');
						
					});
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif2 = true;
								
							}
						}
					});
				
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
						
					}
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().parent().parent().parent().find('input:checkbox').show();
					jQuery(elm).parent().parent().parent().parent().parent().find('br').show();
					jQuery(elm).next().show();
					jQuery(elm).parent().prev().text(parseFloat(jQuery(elm).parent().prev().text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev('td').text()));
					jQuery(elm).parent().parent().parent().parent().parent().prev('td').text('0');
					
				});
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}

// valider issue (tache)
function updateIssue(issue,login,value,user,date1, date2, elm, proj){
	if(jQuery(elm).parent().parent().find('input:checkbox:checked').length == 1){
		var valueF = 1;
		var fact = 'facturable';
	}else{
		var valueF = 2;
		var fact = 'non facturable';
	}
	
	 jQuery("<div>Vous avez choisi de valider tous les imputations de de cette tâche(issue) comme "+fact+" entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/valide_par_issue.php',{"id":issue, "login":login, "date1":date1, "date2":date2, "value":valueF, "user":user}, function(data){
						jQuery(elm).parent().parent().css({'background-color':'green'});
						jQuery(elm).parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().addClass('isvalide');
						
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().prev().text()));
						
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().prev().text()));
			
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
						jQuery("td.issue-"+issue).each(function(){
							jQuery(this).find('img').removeClass('valideBy');
							jQuery(this).find('img').addClass('valideBy');
							jQuery(this).find('input:checkbox').removeClass('valideBy');
							jQuery(this).find('input:checkbox').addClass('valideBy');
							
							jQuery(this).find('input:checkbox').removeAttr('checked');
							if(valueF == 1){
								jQuery(this).find('input:checkbox').attr('checked','checked');
							}
							if(jQuery(this).hasClass('isnovalide')){
								jQuery(this).css({'background-color':'green'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isvalide');
							}
							
							//jQuery(this).find('input:checkbox').hide();
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						
					var verif = false;
					jQuery("td.issue-proj-"+proj+"-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.proj-"+proj+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).find('.njr').text('0');
							jQuery(this).find('img.nvalideIs').show();
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('input:checkbox').hide();
						});
						
					}
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
						});
						
					}
					
					if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
					}
					
					jQuery(elm).prev().show();
					jQuery(elm).hide();
					
					jQuery(elm).parent().parent().find('input:checkbox').hide();
					jQuery(elm).parent().parent().find('br').hide();
					jQuery(elm).parent().parent().prev('td').text(parseFloat(jQuery(elm).parent().parent().prev('td').text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
				});
				
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}


// devalider issue (tache)
function updateIssueD(issue,login,value,user,date1, date2, elm, proj){
	
	
	 jQuery("<div>Vous avez choisi d'annuler la validation de tous les imputations de de cette tâche(issue)  entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/devalide_par_issue.php',{"id":issue, "login":login, "date1":date1, "date2":date2, "user":user}, function(data){
						jQuery(elm).parent().parent().css({'background-color':'#FEB75A'});
						jQuery(elm).parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().addClass('isnovalide');
					
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().prev('td').text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().prev('td').text()));
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().parent().prev('td').text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) - parseFloat(jQuery(elm).parent().parent().prev('td').text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
						jQuery("td.issue-"+issue).each(function(){
							jQuery(this).find('img').removeClass('valideBy');
							jQuery(this).find('input:checkbox').removeClass('valideBy');
							
							jQuery(this).find('input:checkbox').removeAttr('checked');
							
							if(!jQuery(this).hasClass('isnovalide')){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									jQuery(this).css({'background-color':'#FEB75A'});
									jQuery(this).removeClass('isnovalide');
									jQuery(this).removeClass('isvalide');
									jQuery(this).addClass('isnovalide');
								}
							}
							
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
						
					var verif = false;
					jQuery("td.issue-proj-"+proj+"-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.proj-"+proj+" td.isvalide").each(function(){
							if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								jQuery(this).css({'background-color':'#FEB75A'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isnovalide');
								jQuery(this).find('img.nvalideIs').hide();
								jQuery(this).find('img.valideIs').show();
								jQuery(this).find('input:checkbox').show();
							}
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).find('.njr').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).prev('td').text('0');
						});
						
					}
					
					
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
						
					}
					
					if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
					}
					
					jQuery(elm).hide();
					jQuery(elm).next().show();
					jQuery(elm).parent().parent().find('input:checkbox').show();
					jQuery(elm).parent().parent().find('br').show();
					jQuery(elm).parent().prev().text(parseFloat(jQuery(elm).parent().prev().text()) + parseFloat(jQuery(elm).parent().parent().prev('td').text()));
					jQuery(elm).parent().parent().prev('td').text('0');
					
				});
				
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}
// valider issue (tache) non facturable
function updateIssueNfact(issue,login,value,user,date1, date2, elm, proj){
	
	
	 jQuery("<div>Vous avez choisi de valider tous les imputations de de cette tâche(issue) comme non facturable entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/valide_par_issue.php',{"id":issue, "login":login, "date1":date1, "date2":date2, "value":2, "user":user}, function(data){
						jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'green'});
						jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().parent().parent().parent().addClass('isvalide');
						
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) + parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) - parseFloat(jQuery(elm).parent().prev().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
						
						jQuery("td.issue-"+issue).each(function(){
							jQuery(this).find('input:checkbox').removeAttr('checked');
							
							if(jQuery(this).hasClass('isnovalide')){
								jQuery(this).css({'background-color':'green'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isvalide');
								jQuery(this).find('input:checkbox').hide();
								jQuery(this).find('img.valideIs').hide();
								jQuery(this).find('img.nvalideIs').show();
							}
						});
						
					var verif = false;
					jQuery("td.issue-proj-"+proj+"-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.proj-"+proj+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).find('input:checkbox').hide();
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).find('.njr').text('0');
						});
						
					}
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isvalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isnovalide").each(function(){
							jQuery(this).css({'background-color':'green'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isvalide');
							jQuery(this).find('input:checkbox').hide();
							jQuery(this).find('img.valideIs').hide();
							jQuery(this).find('img.nvalideIs').show();
						});
						
					}
					
					if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
					}
					
					
					jQuery(elm).hide();
					jQuery(elm).prev().show();
					jQuery(elm).parent().parent().find('input:checkbox').hide();
					jQuery(elm).parent().parent().find('br').hide();
					jQuery(elm).parent().parent().parent().parent().parent().prev('td').text(parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev('td').text()) + parseFloat(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
				});
				
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}


// devalider issue (tache) non facturable
function updateIssueNfactD(issue,login,value,user,date1, date2, elm, proj){
	
	
	 jQuery("<div>Vous avez choisi d'annuler la validation de tous les imputations de de cette tâche(issue) entre "+date1+" et "+date2+", voulez vous continuer ?</div>").dialog({
		resizable: false,
		height:190,
		title: 'Confirmation',
		modal: true,
		buttons: {
			"Ok": function() {
				jQuery( this ).dialog( "close" );
				jQuery.post('ajax/devalide_par_issue.php',{"id":issue, "login":login, "date1":date1, "date2":date2, "user":user}, function(data){
						jQuery(elm).parent().parent().parent().parent().parent().css({'background-color':'#FEB75A'});
						jQuery(elm).parent().parent().parent().parent().parent().removeClass('isnovalide');
						jQuery(elm).parent().parent().parent().parent().parent().removeClass('isvalide');
						jQuery(elm).parent().parent().parent().parent().parent().addClass('isnovalide');
						
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
						jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
						jQuery("td.projTTT-"+proj+"-"+user).text(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
						
						jQuery("td.issue-"+issue).each(function(){
							jQuery(this).find('input:checkbox').removeAttr('checked');
							
							if(jQuery(this).hasClass('isvalide')){
								if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
									jQuery(this).css({'background-color':'#FEB75A'});
									jQuery(this).removeClass('isnovalide');
									jQuery(this).removeClass('isvalide');
									jQuery(this).addClass('isnovalide');
									jQuery(this).find('img.nvalideIs').hide();
									jQuery(this).find('img.valideIs').show();
									jQuery(this).find('input:checkbox').show();
								}
							}
						});
						
					var verif = false;
					jQuery("td.issue-proj-"+proj+"-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif = true;
							}
						}
					});
					
					if(!verif){
						jQuery("tr.proj-"+proj+" td.isvalide").each(function(){
							if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
								jQuery(this).css({'background-color':'#FEB75A'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isnovalide');
								jQuery(this).find('img.nvalideIs').hide();
								jQuery(this).find('img.valideIs').show();
								jQuery(this).find('input:checkbox').show();
							}
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).find('.njr').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).prev('td').text('0');
						});
						
					}
					
					
					var verif2 = false;
					jQuery("td.proj-user-"+user).each(function(){
						if(jQuery(this).text() !="" && jQuery(this).text() !=" " && !isNaN(parseInt(jQuery(this).text())) && jQuery(this).text() !="* * *" && jQuery(this).text() !="&nbsp;"){
							if(!jQuery(this).hasClass('isnovalide')){
								verif2 = true;
							}
						}
					});
					
					if(!verif2){
						jQuery("tr.tr-"+user+" td.isvalide").each(function(){
							jQuery(this).css({'background-color':'#FEB75A'});
							jQuery(this).removeClass('isnovalide');
							jQuery(this).removeClass('isvalide');
							jQuery(this).addClass('isnovalide');
							jQuery(this).find('img.nvalideIs').hide();
							jQuery(this).find('img.valideIs').show();
							jQuery(this).find('input:checkbox').show();
						});
						
					}
					
					if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) > 0 && parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text())> 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').hide();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').show();
					}else if(parseFloat(jQuery("td.projTTT-"+proj+"-"+user).text()) <= 0){
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.valideIs').show();
						jQuery("td.projTTTT-"+proj+"-"+user).find('img.nvalideIs').hide();
					}
					
					jQuery(elm).hide();
					jQuery(elm).next().show();
					jQuery(elm).parent().parent().find('input:checkbox').show();
					jQuery(elm).parent().parent().find('br').show();
					jQuery(elm).parent().prev().text(parseFloat(jQuery(elm).parent().parent().parent().parent().parent().prev('td').text()));
					jQuery(elm).parent().parent().parent().parent().parent().prev('td').text('0');
					
				});
				
			},
			"Annuler": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}

// update projet
function updateProj(idproj,login,value,date_deb,date_fin){	
	var tab = new Array(nb);
	for (i=0;i<nb;i++){
		champ = "valid"+identifiant+i;
		document.getElementById(champ).selectedIndex = value;
		Role = "imp"+identifiant+i;
		imputation = "hid"+identifiant+i;
		defaultRole = "defaultRole"+identifiant+i;
		id  = document.getElementById(Role).selectedIndex;
		tab[i]=  document.getElementById(imputation).value+"**"+document.getElementById(Role).options[id].value+"**"+document.getElementById(defaultRole).value;
	} 

	varAjax = idproj+"||"+login+"||"+value+"||"+date_deb+"||"+date_fin;
	sendReq1("live_process.php", "val,fct,FormName,FieldName",varAjax+" ,updateProj,formulaire,listhidden");
}