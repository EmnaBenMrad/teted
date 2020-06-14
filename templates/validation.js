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
	jQuery.post('ajax/list_user_par_proj.php',{"proj":proj}, function(data){
		jQuery('select#group-bu').val("");
		jQuery('#gbu').val("");
		jQuery('select#collab').html(data);
	});
}
//change liste bu (poles)
function changeListeUserBu(bu){
	jQuery.post('ajax/list_user_par_bu.php',{"group":bu}, function(data){
		jQuery('select#projets').val("");
		jQuery('#projss').val("");
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
function update_imp(idIMP,login,value,user,date, elm, kd, proj, isValider){
	if(jQuery(elm).parent().parent().find('input:checkbox:checked').length == 1){
		var valueF = 1;
		var fact = 'facturable';
	}else{
		var valueF = 2;
		var fact = 'non facturable';
	}
	
	if(parseInt(isValider) > 0){
	
				jQuery("<div>Cette imputation est d&eacute;j&agrave; valid&eacute;, voulez vous continuer ?</div>").dialog({
					resizable: false,
					height:190,
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
										jQuery("td.issueT-"+idIMP+"-"+user).html('0');
										jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
										jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
										jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
										jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
									}
									
									if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
										jQuery("td.projTTTT-"+proj+"-"+user).html('0');
										jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
										jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
										jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
										jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
									}
									
									if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
										jQuery("td.user-nov-"+user).html('0');
										jQuery("td.user-nov-"+user).removeClass('isnovalide');
										jQuery("td.user-nov-"+user).removeClass('isvalide');
										jQuery("td.user-nov-"+user).addClass('isvalide');
										jQuery("td.user-nov-"+user).css({'background-color':'green'});
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
									}
									
								}
								
							});
						},
						"Annuler": function() {
							jQuery( this ).dialog( "close" );
						}
				}
			});
	}else{
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
							jQuery("td.issueT-"+idIMP+"-"+user).html('0');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
							jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
							jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
						}
						
						if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
							jQuery("td.projTTTT-"+proj+"-"+user).html('0');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
							jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
							jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
						}
						
						if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
							jQuery("td.user-nov-"+user).html('0');
							jQuery("td.user-nov-"+user).removeClass('isnovalide');
							jQuery("td.user-nov-"+user).removeClass('isvalide');
							jQuery("td.user-nov-"+user).addClass('isvalide');
							jQuery("td.user-nov-"+user).css({'background-color':'green'});
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
				jQuery("td.issueT-"+idIMP+"-"+user).html('0');
				jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isnovalide');
				jQuery("td.issueT-"+idIMP+"-"+user).removeClass('isvalide');
				jQuery("td.issueT-"+idIMP+"-"+user).addClass('isvalide');
				jQuery("td.issueT-"+idIMP+"-"+user).css({'background-color':'green'});
			}
			
			if(parseFloat(jQuery("td.projTTTT-"+proj+"-"+user+" .njr").text()) <= 0){
				jQuery("td.projTTTT-"+proj+"-"+user).html('0');
				jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isnovalide');
				jQuery("td.projTTTT-"+proj+"-"+user).removeClass('isvalide');
				jQuery("td.projTTTT-"+proj+"-"+user).addClass('isvalide');
				jQuery("td.projTTTT-"+proj+"-"+user).css({'background-color':'green'});
			}
			
			if(parseFloat(jQuery("td.user-nov-"+user).text()) <= 0){
				jQuery("td.user-nov-"+user).html('0');
				jQuery("td.user-nov-"+user).removeClass('isnovalide');
				jQuery("td.user-nov-"+user).removeClass('isvalide');
				jQuery("td.user-nov-"+user).addClass('isvalide');
				jQuery("td.user-nov-"+user).css({'background-color':'green'});
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
			}
		}
	});
	
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
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
					
					jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
					jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
					jQuery("td.proj-"+project).each(function(){
					
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
						});
					}
					
					jQuery("td.issue-proj-"+project).each(function(){
						jQuery(this).find('input:checkbox').parent().hide();
						jQuery(this).find('img').parent().hide();
						jQuery(this).find('br').remove();
						jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
						jQuery(this).text('0');
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
						});
						
					}
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().find('input:checkbox').parent().hide();
					jQuery(elm).parent().parent().find('br').remove();
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
					
					jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
					jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
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
							jQuery(this).addclass('isnovalide');
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
					
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().parent().parent().parent().find('input:checkbox').parent().hide();
					jQuery(elm).parent().parent().parent().parent().parent().find('br').remove();
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
						
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
					
						jQuery("td.issue-"+issue).each(function(){
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
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).text('0');
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
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().find('input:checkbox').parent().hide();
					jQuery(elm).parent().parent().find('br').remove();
					jQuery(elm).parent().parent().prev('td').text(parseInt(jQuery(elm).parent().parent().prev('td').text()) + parseInt(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
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
						
						jQuery("td.userT-nov-"+user).text(parseFloat(jQuery("td.userT-nov-"+user).text()) + parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
						jQuery("td.user-nov-"+user).text(parseFloat(jQuery("td.user-nov-"+user).text()) - parseFloat(jQuery(elm).parent().parent().parent().parent().parent().text()));
						jQuery("td.userT-nov-"+user).text((parseFloat(jQuery("td.userT-nov-"+user).text())).toFixed(2));
						jQuery("td.user-nov-"+user).text((parseFloat(jQuery("td.user-nov-"+user).text())).toFixed(2));
						
						jQuery("td.issue-"+issue).each(function(){
							jQuery(this).find('input:checkbox').removeAttr('checked');
							
							if(jQuery(this).hasClass('isnovalide')){
								jQuery(this).css({'background-color':'green'});
								jQuery(this).removeClass('isnovalide');
								jQuery(this).removeClass('isvalide');
								jQuery(this).addClass('isvalide');
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
						});
						jQuery("td.proj-u-"+proj).each(function(){
							jQuery(this).prev('td').text(parseFloat(jQuery(this).prev('td').text()) + parseFloat(jQuery(this).text()));
							jQuery(this).text('0');
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
					
					
					jQuery(elm).hide();
					jQuery(elm).parent().parent().find('input:checkbox').parent().hide();
					jQuery(elm).parent().parent().find('br').remove();
					jQuery(elm).parent().parent().prev('td').text(parseInt(jQuery(elm).parent().parent().prev('td').text()) + parseInt(jQuery(elm).parent().prev().text()));
					jQuery(elm).parent().prev().text('0');
					
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
	//alert(identifiant);
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