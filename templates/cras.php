<?php
session_start();// On démarre la session
set_time_limit(10);

require_once "class_excel/class.writeexcel_workbook.inc.php";
require_once "class_excel/class.writeexcel_worksheet.inc.php";
include "connexion.php";
include "fonctions.php";
 
//initialisation des variables de session et des variables envoyés par $_POST
if(isset($_POST['collab']))
{
$id = $_POST['collab'];
$user = $_POST['collab'];
}
else
{
$id = $_SESSION['id'];
$user = $_SESSION['id'];
}
@$an = $_POST['annee'];
@$mois = $_POST['mois'];
//
$len_mois = strlen ($mois);
   if($len_mois==1) 
   {
   $mois = "0".$mois;
   }
//on séléctionne la date début et la date fin du mois choisie
	$date1 = $an."-".$mois."-01";
	$Y= $an;
	$n= $mois;
	$finmois = fin_mois ($n,$Y);
	$date2= $an."-".$mois."-".$finmois;

//le nom d'un user   
$nom= nom_prenom_user($id); 
//le mail d'un user 
$mail = email($id); 
$m= nom_mois($n-1);	

	
//$fname = tempnam("/tmp", "CRA.xls");
$path="../files/CRAS_".$m."_".$Y;//création d'un dossier pour le souvgardes des cras
if (!file_exists($path))  
{mkdir ($path, 0777);}
/*else {
    print "Le dossier". $path." existe";
}
*/
$fname = $path."/CRA_".$nom."_".$n."_".$Y.".xls";

     $workbook = &new writeexcel_workbook($fname); // on lui passe en paramètre le chemin de notre 	fichier   
	$worksheet =& $workbook->addworksheet(); //le paramètre ici est le nom du classeur   
  
 
 
/*****************************définitions des formats utilisés ********************/ 
   
	
	
	
	
	
	$heading  =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras   
                                        'color'  => 'black', // de couleur noire   
                                        'size'   => 12,    // de taille 12   
                                        //'merge'  => 1,    // avec une marge 
										'align'  => 'center' , //alignement   
                                        'fg_color' => 'silver', // coloration du fond des cellules                            
										     
                                      )); 
									  
	$heading1  =& $workbook->addformat(array(   
                                        'bold' => 2,    // on met le texte en gras   
                                        'color'  => 'black', // de couleur noire   
                                        'size'   => 12,    // de taille 12   
                                        'merge'  => 1,    // avec une marge 
										'align'  => 'center' , //alignement 
										 'border'=> 2, 
                                        'fg_color' => 'silver', // coloration du fond des cellules                            
										'top_color'=>'silver' ,
										'bottom_color'=>'silver',
										'left_color'=>'silver',
										'right_color'=>'silver',
										 
										 
	 
                                      )); 
	$format =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'right' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'white',
										'left_color'=>'white',
										'right_color'=>'white',												
																				 
                                      )); 
	$format_weekend =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'fg_color' => 'yellow',
										'border'=> 1,
										'top_color'=>'black' ,




										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',
										
										
																				 
                                      )); 
									  								  
									
	$format1 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'red', // de couleur rouge  
                                        'size'    => 8,    // de taille 8  
                                        //'merge'   => 1,    // avec une marge 
										'align' => 'right' , //alignement   
                             			 'font'  => 'Tahoma',
										 //'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'white',
										'left_color'=>'white',
										'right_color'=>'white',
										 
                                      ));   
    
	$format_url =& $workbook->addformat(array(   
                                           
                                        'color'   => 'blue', // de couleur bleu   
                                        'size'    => 10,    // de taille 6   
                                        //'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'underline' => 1,//souligné
										//'border'=> 0.5,
										//'top_color'=>'white' ,
										//'bottom_color'=>'white',
										//'left_color'=>'white',
										//'right_color'=>'white',
																				 
                                      ));   
	$format21 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'right' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'white',
										'left_color'=>'black',
										'right_color'=>'white',
										
	                                      )); 
	$format22 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'right' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'white',
										'left_color'=>'black',
										'right_color'=>'white',
										 )); 
	$format23 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'right' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'white',
										 )); 

	$format31 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'white',

										'left_color'=>'white',
										'right_color'=>'white',
										 )); 
	$format32 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'white',
										'left_color'=>'white',
										'right_color'=>'white',
										 )); 
									  
	$format33 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'white',
										'left_color'=>'white',
										'right_color'=>'black',
										 )); 
	$format34 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'white',
										'left_color'=>'white',
										'right_color'=>'black',
										 ));
	$format35 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'black',
										'left_color'=>'white',
										'right_color'=>'black',
										 )); 
	$format36 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'white' ,
										'bottom_color'=>'black',
										'left_color'=>'white',
										'right_color'=>'white',
										 )); 
	
	$format4 =& $workbook->addformat(array(   
                                        'bold' => 2,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'center' , //alignement   
                                        'font'  => 'Tahoma',
										'fg_color'=>'silver',
										'border'=> 2,
										'top_color'=>'silver' ,
										'bottom_color'=>'silver',
										'left_color'=>'silver',
										'right_color'=>'silver',
										)); 
										
	$format6 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'fg_color'=>'silver',
										
										)); 
	$format5 =& $workbook->addformat(array(   
                                        'bold' => 1,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'fg_color'=>'silver',
										 )); 
	$format41 =& $workbook->addformat(array(   
                                        //'bold' => 2,    // on met le texte en gras    
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'left' , //alignement   
                                        'font'  => 'Tahoma',
										'fg_color'=>'silver',
										 )); 
	$entete  =& $workbook->addformat(array(   
                                        
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8   
                                        'merge'   => 1,    // avec une marge 
										'align' => 'top' , //alignement   
                                        'fg_color'  => 'silver', // coloration du fond des cellules   
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',
										 
                                      ));   
	$entete11  =& $workbook->addformat(array(   
                                         
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8  
                                        'align' => 'center' , //alignement  
										'merge'   => 1, 
                                        'fg_color'    => 'silver' ,// coloration du fond des cellules   
										'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'silver',
										'left_color'=>'black',
										'right_color'=>'black',
                                      )); 
   $entete12  =& $workbook->addformat(array(   
                                         
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8  
                                        'align' => 'center' , //alignement  
										'merge'   => 1, 
                                        'fg_color'    => 'silver' ,// coloration du fond des cellules   
										'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'silver' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',
                                      )); 
   $entete13  =& $workbook->addformat(array(   
                                         
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8  
                                        'align' => 'left' , //alignement  
										'fg_color'    => 'silver' ,// coloration du fond des cellules   
										'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'silver',
                                      )); 
	$entete14  =& $workbook->addformat(array(   
                                         
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 8  
                                        'align' => 'left' , //alignement  
										//'merge'   => 1, 
                                        'fg_color'    => 'silver' ,// coloration du fond des cellules   
										'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'silver',
										'right_color'=>'silver',
                                      )); 
	$entete15  =& $workbook->addformat(array(   
                                         
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 10,    // de taille 10  
                                        'align' => 'left' , //alignement  
										//'merge'   => 1, 
                                        'fg_color'    => 'silver' ,// coloration du fond des cellules   
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'silver',
										'right_color'=>'black',
                                      )); 
									  
	 $entete16  =& $workbook->addformat(array(   
                                         
                                        //'color'   => 'black', // de couleur noire   
                                        //'size'    => 8,    // de taille 8  
                                        'align' => 'left' , //alignement  
										//'merge'   => 1, 
                                        'fg_color'    => 'silver' ,// coloration du fond des cellules   
										//'font'  => 'Tahoma',
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'silver',
										'left_color'=>'black',
										'right_color'=>'black',
                                      )); 
                                     
	$entete21 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 7,    // de taille 7  
										'merge'   => 1, 
                                        'align' => 'right' , //alignement 
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',  
                                          
                                      )); 
	$entete22 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 7,    // de taille 7  
										'merge'   => 1, 
                                        'align' => 'center' , //alignement 
										'border'=> 1,
										'fg_color'    => 'silver' ,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',  
                                          
                                      ));
   $entete23 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'blue', // de couleur noire   
                                        'size'    => 7,    // de taille 7  
										'merge'   => 1, 
                                        'align' => 'left' , //alignement 
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black', 
	
	                                    ));
										
										
										
	$entete24 =& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 7,    // de taille 7  
										'merge'   => 1, 
                                        'align' => 'left' , //alignement 
										'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black',  
                                          
                                      )); 
	
	$entete4=& $workbook->addformat(array(   
                                           
                                        'color'   => 'black', // de couleur noire   
                                        'size'    => 8,    // de taille 6  
										'merge'   => 1, 
                                       	'align' => 'center' , //alignement  
										 'fg_color'    => 'silver' ,
                                         'border'=> 1,
										'top_color'=>'black' ,
										'bottom_color'=>'black',
										'left_color'=>'black',
										'right_color'=>'black', 
                                      )); 
   
  $format10=& $workbook->addformat(array(   
                                          
                                         'fg_color' => 'white',
										 'border_color'=> 'white',
										 
                                      )); 
	  
	  
	 
	 
	$worksheet->set_column('A:A',0.5,$format10);
	$worksheet->set_column('B:B', 18,$format10); 
	$worksheet->set_column('C:C', 22,$format10);
	$worksheet->set_column('D:D',32,$format10); 
	$worksheet->set_column('E:E', 3,$format10);
	
	
	
	
	$worksheet->set_column('G:G', 6,$format10);
	$worksheet->set_column('F:F', 0.3,$format10);
	$worksheet->set_column('H:H', 0.3,$format10);
	$worksheet->set_column('I:AM', 4,$format10); //le 4 représente la largeur de chaque colonne > AM  
	 
	 
	$worksheet->set_row(0,23,$format10);
	$worksheet->set_row(1,15,$format10);
	$worksheet->set_row(2,15,$format10);
	$worksheet->set_row(3,3,$format10);   
	$worksheet->set_row(4,19,$format10);
	$worksheet->set_row(11,17,$format10);
	$worksheet->set_row(15,3,$format10);
	
	//for ($j=1;$j<4;$j++){
	  //$worksheet->set_row($j,13,$format10);
	//}
		
	for ($j=5;$j<11;$j++){
	  $worksheet->set_row($j,13,$format10);
	}
	
	for ($j=12;$j<15;$j++){
	  $worksheet->set_row($j,11,$format10);
	}
	
	for ($j=16;$j<6000;$j++){
	  $worksheet->set_row($j,11,$format10);
	}
	
	
/*************************la sélection des enregistrements****************************/

/*****requete jour ferier**********/
$sql_jf= "SELECT * FROM `jour_ferie` where date between '$date1' and '$date2' ";
$req_jf= mysql_query($sql_jf);

//print_r ($data_jf);
$data_jf = mysql_fetch_array($req_jf);
$nb_f=mysql_num_rows($req_jf);			




//la requête
   $sql = "SELECT project.ID
, imputation.issue,imputation.user as user ,project.pname,sum(imputation.imputation) AS imput ,jiraissue.summary ,jiraissue.issuetype
	FROM imputation, jiraissue, userbase, project,issuetype
	WHERE imputation.Project = project.ID
	AND imputation.issue = jiraissue.ID
	AND jiraissue.issuetype=issuetype.ID 
	AND imputation.user = $id
	AND imputation.user = userbase.id
	AND imputation.DATE
	BETWEEN '$date1'
	AND '$date2' group by project.ID"; 
		$req = mysql_query($sql); 
		$data = mysql_fetch_array($req);   
	
	//initialisation du repère
		$col=2;
		$row=17;
		$j=7;
		$fin=$finmois+$j;	
//les samedi les dimanches et les jours friées
$tabsam=sam_mois($n,$Y);//samedis
$nb_sam =count($tabsam); 
$tabdim=dim_mois($n,$Y);//dimanches
$nb_dim =count($tabdim); 
//print_r ($tabdim);
//nombre de jours travaillées

$nb_jour_trav = $finmois-($nb_sam+$nb_dim+$nb_f);


// 

 $sql_r = "SELECT project.ID,imputation.DATE as date 
, imputation.issue,imputation.user as user ,project.pname,sum(imputation.imputation) AS imput  ,nodeassociation.SINK_NODE_ID
	FROM imputation, jiraissue, userbase, project ,nodeassociation ,projectcategory
	WHERE imputation.Project = project.ID
	AND imputation.issue = jiraissue.ID
	AND nodeassociation.SOURCE_NODE_ID = project.ID
	AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
	AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
	AND imputation.user = $id
	AND imputation.user = userbase.id
	AND imputation.DATE
	BETWEEN '$date1'
	AND '$date2' 
	AND nodeassociation.SINK_NODE_ID != '10043'
	AND nodeassociation.SINK_NODE_ID != '10040'
	AND nodeassociation.SINK_NODE_ID != '10041'
	AND nodeassociation.SINK_NODE_ID != '10042'
	AND nodeassociation.SINK_NODE_ID != '10044'
	AND nodeassociation.SINK_NODE_ID != '10045'
	AND nodeassociation.SINK_NODE_ID != '10031'
	AND nodeassociation.SINK_NODE_ID != '10003'
	AND project.ID != '10090'
	AND project.ID != '10103'
	AND project.ID != '10083'
    GROUP BY project.ID ";
        $req_r = mysql_query($sql_r);
		//$data_r = mysql_fetch_array($req_r);
		$nb1=mysql_num_rows($req_r);
	    
		
		
		//print_r ($data_r);
			


		 	
//  on recupere les champs
				$champ1=$data[0];//project
				$champ2=$data[1];//issue
				$champ3=$data[2];//user
				$champ4=$data[3];//pname
				$champ5=$data[4];//imputation
				$champ6=$data[5];//Ssummary
				$champ7=$data[6];//SINK_NODE_ID
				$champ8=$data[7];//SINK_NODE_ID
				//
 	
		 
		//echo $champ6;
		
		//*******************************************projets facturés	
		  while ($data_r = mysql_fetch_array($req_r)){
		  
		$champs1=$data_r[0];//id project
	    $champs2=$data_r[6]; //sink node id
		$champs3=$data_r[4];//pname
		   if( ($champs2!='10031')&&($champs2!='10040')&&($champs2!='10003')&&($champs2!='10041')&&($champs2!='10042')&&($champs2!='10045')&&($champs2!='10044')&&($champs2!='10043')&&($champs1!='10090')&&($champs1!='10103')&&($champs1!='10083'))
			 
				{ 
				
			  $sql1="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput,jiraissue.summary ,projectcategory.cname FROM imputation,project,jiraissue,nodeassociation ,projectcategory
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				AND project.pname = '$champs3' GROUP BY date ";
				 $req1= mysql_query($sql1); 
				
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row,$p,                 $entete21);
					}
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row,$j+$f1,                 $format_weekend);							
						}
					} 
				}		
					 //samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				     $worksheet->write_blank($row,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					while ($data1=mysql_fetch_array($req1))
					{ 
					$worksheet->write($row,2,$data1[1],$entete24); 
					$worksheet->write($row,1,$data1[4],$entete24);
					$worksheet->write_blank($row,3,                 $entete24);
					$worksheet->write_blank($row,4,                 $entete24);
								
						for ($i=1;$i<=$finmois;$i++){ 
						 	if(substr($data1[0],-2,2)==$i){
							


								$worksheet->write ($row,($j+$i),$data1[2],$entete21);
							}
							
												
						 } 
	
					$requete="SELECT imputation.DATE as date ,
					project.pname ,SUM(imputation.imputation) AS total 
				FROM imputation,project,jiraissue,nodeassociation ,projectcategory
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND imputation.DATE BETWEEN '$date1'AND '$date2'
				AND nodeassociation.SINK_NODE_ID != '10043'
     			AND nodeassociation.SINK_NODE_ID != '10040'
				AND nodeassociation.SINK_NODE_ID != '10041'
				AND nodeassociation.SINK_NODE_ID != '10042'
				AND nodeassociation.SINK_NODE_ID != '10044'
				AND nodeassociation.SINK_NODE_ID != '10045'
				AND project.ID != '10090'
				AND project.ID != '10103'
				AND project.ID != '10083'
				AND project.ID = '$champs1' GROUP BY project.ID ";
					$exe = mysql_query($requete);
					$tab= mysql_fetch_array($exe); 
					
					
			    	}
				$worksheet->write ($row,6,$tab[2],$entete22);
				$row++;
					
				
				  
				 } }
				  //ligne vide
				  for ($compt=8;$compt<$fin+1;$compt++){
 				 $worksheet->write_blank(17+$nb1,$compt,    $entete21);
    			 }
				 for ($compt1=8;$compt1<$fin+1;$compt1++){
 				 $worksheet->write_blank(18+$nb1,$compt1,    $entete21);
    			 }
				 for ($compt2=8;$compt2<$fin+1;$compt2++){
 				 $worksheet->write_blank(16,$compt2,    $entete22);
    			 }
				  //samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(17+$nb1,$j+$c,     $format_weekend);
				       $worksheet->write_blank(18+$nb1,$j+$c,     $format_weekend);
								}
						  } 
						  					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				     $worksheet->write_blank(17+$nb1,$j+$c,     $format_weekend);
				      $worksheet->write_blank(18+$nb1,$j+$c,     $format_weekend);
								}
						  } 
					}
				//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(17+$nb1,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(18+$nb1,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					 
				$worksheet->write_blank (16,6,          $entete22);
				$worksheet->write_blank (17+$nb1,6,          $entete22);
				$worksheet->write_blank (18+$nb1,6,          $entete22);	
		
			
			//
				$champ1=$data[0];//project
				$champ2=$data[1];//issue
				$champ3=$data[2];//user
				$champ4=$data[3];//pname
				$champ5=$data[4];//imputation
			
				/******************************projets non facturé*****************/
				
				if(($champ7='10043')&&($champ8='10'))
				{
			
			  $sql2="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput,jiraissue.summary  FROM imputation,project,jiraissue,nodeassociation ,projectcategory,issuetype
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID ='10043'
				AND jiraissue.issuetype=issuetype.ID
				AND issuetype.ID='10'
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				GROUP BY date ";
					$req2 = mysql_query($sql2); 
				    				
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(20+$nb1,$p,                 $entete21);
					}
						
						
					
							
						
										
			while ($data2= mysql_fetch_array($req2))
			 {
					
					$worksheet->write (20+$nb1,2,'Intercontrat',$entete23) ;
					$worksheet->write_blank(20+$nb1,1,                 $entete24);
					$worksheet->write_blank(20+$nb1,3,                 $entete24);
					$worksheet->write_blank(20+$nb1,4,                 $entete24);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data2[0],-2,2)==$i)
							{
							$worksheet->write (20+$nb1,$j+$i,$data2[2],$entete21);
							
							}
						} 
					}
						$requete1="SELECT imputation.DATE as date ,project.pname ,jiraissue.summary ,SUM(imputation.imputation) AS total FROM imputation,project,jiraissue,nodeassociation ,projectcategory,issuetype
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID = '10043'
				AND jiraissue.issuetype=issuetype.ID
				AND issuetype.ID='10'
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				GROUP BY jiraissue.ID ";
					$exe1 = mysql_query($requete1);
					$tab1= mysql_fetch_array($exe1); 
					//print_r($tab);
					$worksheet->write (20+$nb1,6,$tab1[3],$entete22);
					
			   		$sql2.=" and project.pname";
					$req2 = mysql_query($sql2); 
					$nb2=mysql_num_rows($req2);
					
					
					
					
					//samedis
                     for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				     $worksheet->write_blank(20+$nb1,$j+$c,    
					 $format_weekend);
				     
								}
						  } 
					}					
					//dimanches
                     for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				    $worksheet->write_blank(20+$nb1,$j+$c,     $format_weekend); 
				   
								}
						  } 
					}	
				//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(20+$nb1,$j+$f1,              $format_weekend);
														
						}
					} 
				}	
		       }
			  if(($champ7='10043')&&($champ8='20'))
				{
			
			 $sql21="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput,jiraissue.summary  FROM imputation,project,jiraissue,nodeassociation ,projectcategory,issuetype
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID ='10043'
				AND jiraissue.issuetype=issuetype.ID
				AND issuetype.ID='20'
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				GROUP BY date ";
					$req21 = mysql_query($sql21); 
				    				
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(20+$nb1+$nb2,$p,                 $entete21);
					}
						
					
										
			while ($data21= mysql_fetch_array($req21))
			 {
					
					$worksheet->write (20+$nb1+$nb2,2,'Jour férié',$entete23) ;
					$worksheet->write_blank(20+$nb1+$nb2,1,                 $entete24);
					$worksheet->write_blank(20+$nb1+$nb2,3,                 $entete24);
					$worksheet->write_blank(20+$nb1+$nb2,4,                 $entete24);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data21[0],-2,2)==$i)
							{
							$worksheet->write (20+$nb1+$nb2,$j+$i,$data21[2],$entete21);
							
							}
						} 
					}
						$requete11="SELECT imputation.DATE as date ,project.pname ,jiraissue.summary ,SUM(imputation.imputation) AS total FROM imputation,project,jiraissue,nodeassociation ,projectcategory,issuetype
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND imputation.user = $id
				AND nodeassociation.SOURCE_NODE_ID = project.ID
				AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
				AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID = '10043'
				AND jiraissue.issuetype=issuetype.ID
				AND issuetype.ID='20'
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				GROUP BY jiraissue.ID ";
					$exe11 = mysql_query($requete11);
					$tab11= mysql_fetch_array($exe11); 
					//print_r($tab);
					$worksheet->write (20+$nb1+$nb2,6,$tab11[3],$entete22);
					
			   		$sql21.=" and project.pname";
					$req21 = mysql_query($sql21); 
					$nb21=mysql_num_rows($req21);
					
					
					
					for ($compt=8;$compt<$fin+1;$compt++){
                    $worksheet->write_blank(20+$nb1+$nb2+$nb21,$compt,    $entete21);
                    }
					for ($compt1=8;$compt1<$fin+1;$compt1++){
                    $worksheet->write_blank(21+$nb1+$nb2+$nb21,$compt1,    $entete21);
                    }
					for ($compt2=8;$compt2<$fin+1;$compt2++){
                    $worksheet->write_blank(19+$nb1,$compt2,    $entete22);
                    }
					//samedis
                     for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				     $worksheet->write_blank(20+$nb1+$nb2,$j+$c,     $format_weekend);
					 $worksheet->write_blank(20+$nb1+$nb2+$nb21,$j+$c,     $format_weekend);
				     $worksheet->write_blank(21+$nb1+$nb2+$nb21,$j+$c,     $format_weekend);
								}
						  } 
						  
					}					
					//dimanches
                     for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				    $worksheet->write_blank(20+$nb1+$nb2,$j+$c,     $format_weekend);
					$worksheet->write_blank(20+$nb1+$nb2+$nb21,$j+$c,     $format_weekend); 
				    $worksheet->write_blank(21+$nb1+$nb2+$nb21,$j+$c,     $format_weekend);
								}
						  } 
					}	
				//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(20+$nb1+$nb2,$j+$f1,              $format_weekend);
						$worksheet->write_blank(20+$nb1+$nb2+$nb21,$j+$f1,              $format_weekend);
						$worksheet->write_blank(21+$nb1+$nb2+$nb21,$j+$f1,              $format_weekend);								
						}
					} 
				}	
		       }
			   $worksheet->write_blank (19+$nb1,6,          $entete22);		
				$worksheet->write_blank (20+$nb1+$nb2+$nb21,6,$entete22);
				$worksheet->write_blank(21+$nb1+$nb2+$nb21,6,$entete22);
/**************************projets internes*******************************************************/
		if($champ7='10045'){
		
		
		$row1=23+$nb1+$nb2+$nb21;			
								
		    $sql3="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue ,nodeassociation ,projectcategory
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND nodeassociation.SOURCE_NODE_ID = project.ID
	            AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
	            AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID ='10045'
				AND imputation.user = $id
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				GROUP BY project.ID ";
					$req3 = mysql_query($sql3); 
					//$data3= mysql_fetch_array($req3);
		while ($data3= mysql_fetch_array($req3))
			{	
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row1,$p,                 $entete21);
					
					}
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row1,$j+$f1,               $format_weekend);							
						}
					} 
				}					
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row1,$j+$c,                 $format_weekend);
				      				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank($row1,$j+$c,                 $format_weekend);
				      				
								}
						  } 
					}
	
					
				$sql30="SELECT imputation.DATE as date ,project.pname ,SUM(imputation.imputation) AS imput ,jiraissue.SUMMARY FROM imputation,project,jiraissue ,nodeassociation ,projectcategory
				WHERE imputation.Project =project.ID
				AND imputation.issue = jiraissue.ID
				AND nodeassociation.SOURCE_NODE_ID = project.ID
	            AND nodeassociation.SINK_NODE_ID  = projectcategory.ID
	            AND nodeassociation.ASSOCIATION_TYPE='ProjectCategory'
				AND nodeassociation.SINK_NODE_ID ='10045'
				AND imputation.user = $id
				AND imputation.DATE BETWEEN '$date1'AND '$date2' 
				AND project.pname= '$data3[1]' GROUP BY date ";
					$req30= mysql_query($sql30); 
					
					$worksheet->write ($row1,2,$data3[1],$entete24) ; 
					$worksheet->write_blank($row1,1,                 $entete24);
					$worksheet->write_blank($row1,3,                 $entete24);
					$worksheet->write_blank($row1,4,                 $entete24);
					
					
					while ($data30= mysql_fetch_array($req30))
					{
					//echo $data30[0];
															
						for ($i=1;$i<=$finmois;$i++){ 
						   	if(substr($data30[0],-2,2)==$i)
							{ 						
							$worksheet->write ($row1,($j+$i),$data30[2],$entete21);
							//break ;
							}					
							
						} 
						
			    	}
					
			 	
					//print_r($tab);
					$worksheet->write ($row1,6,$data3[2],$entete22);
					//$sql3.=" and project.pname";
					//$req3 = mysql_query($requete3); 
					
				   $nb3=mysql_num_rows($req3);
					$row1++; 
					}
					
					for ($compt=8;$compt<$fin+1;$compt++){
  					$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,$compt,    $entete21);
    				}
				for ($compt1=8;$compt1<$fin+1;$compt1++){
  					$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,$compt1,    $entete21);
    				}
					for ($compt2=8;$compt2<$fin+1;$compt2++){
  					$worksheet->write_blank(22+$nb1+$nb2+$nb21,$compt2,    $entete22);
    				}
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,$j+$c,     $format_weekend);
				$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,$j+$c,     $format_weekend);
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				     $worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,$j+$c,     $format_weekend);
					 $worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,$j+$c,     $format_weekend);
				
								}
						  } 
					}
				
				//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,$j+$f1,                 $format_weekend);		
						$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
			
			
			    $worksheet->write_blank (22+$nb1+$nb2+$nb21,6,          $entete22);		
				$worksheet->write_blank (23+$nb1+$nb2+$nb21+$nb3,6,$entete22);
				$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,6,$entete22);	
				
	}	   
	/**************************************************************/
				
			if(($champ1='10064')&&($champ8='8'))//en cas de C.payé
				{
					$sql4 = requeteconge('8',$id,$date1,$date2); 
					$req4 = mysql_query($sql4);
				
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,$p,                 $entete21);
					
					}
					
					
					
					
					while ($data4= mysql_fetch_array($req4))
					{
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3,$col,'Congé payé',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,1,                 $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,3,                 $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,4,                 $entete21);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data4[0],-2,2)==$i)
							{
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3,$j+$i,$data4[2],$entete21);
							}
						} 
				    $requete3=$sql4."and project.ID and jiraissue.ID";
						
					$exe3 = mysql_query($requete3);
					$tab3= mysql_fetch_array($exe3); 
					//print_r($tab);
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3,6,$tab3[2],$entete22);
			   		} 
					$sql4.=" and project.pname";
					$req4 = mysql_query($sql4); 
					$nb4=mysql_num_rows($req4);
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					
					
					
				}
				if(($champ1='10064')&&($champ8='17'))//en cas de C.excep
				{
					$sql5 = requeteconge('17',$id,$date1,$date2); 
					$req5 = mysql_query($sql5);
					//$nb5=mysql_num_rows($req5);
					
				  for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,$p,                 $entete21);
					
					}
					
					
					
					{
					
					while ($data5= mysql_fetch_array($req5))
					
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4,$col,'Congé exceptionnel',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,1,                $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,3,                $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,4,                $entete21); 
					
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data5[0],-2,2)==$i)
							{
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4,$j+$i,$data5[2],$entete21);
							
							}
						}
					$requete4=$sql5."and project.ID and jiraissue.ID";
					$exe4 = mysql_query($requete4);
					$tab4= mysql_fetch_array($exe4); 
					//print_r($tab);
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4,6,$tab4[2],$entete22); 
			   		}
					$sql5.=" and project.pname";
					$req5 = mysql_query($sql5); 
					$nb5=mysql_num_rows($req5);
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,$j+$c,                 $format_weekend);
				      
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4,$j+$c,                 $format_weekend);
				      
				
								}
						  } 
					}

					
					
				}
				

				if(($champ1='10064')&&($champ8='16'))//en cas de C.de maladie
				{   
					$sql6 = requeteconge('16',$id,$date1,$date2); 
					$req6 = mysql_query($sql6);
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$p,                 $entete21);
					
					}
					
					
					while ($data6= mysql_fetch_array($req6))
					{
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$col,'Congé de maladie',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,1,            $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,3,            $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,4,            $entete21);  
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data6[0],-2,2)==$i)
							{
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$j+$i,$data6[2],$entete21);
							}
						} 
					$requete5=$sql6."and project.ID and jiraissue.ID";
					$exe5 = mysql_query($requete5);
					$tab5= mysql_fetch_array($exe5); 
					//print_r($tab);
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,6,$tab5[2],$entete22);
			   		}
					$sql6.=" and project.pname";
					$req6 = mysql_query($sql6); 
					$nb6=mysql_num_rows($req6);
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$j+$c,                 $format_weekend);
				     
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5,$j+$c,                 $format_weekend);
				     
								}
						  } 
					}
					
				}
				
				if(($champ1='10064')&&($champ8='18'))//en cas de C.sans solde
				{
					$sql7 = requeteconge('18',$id,$date1,$date2); 
					$req7 = mysql_query($sql7); 
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,$p,                 $entete21);

					
					}
					
					
					
					while ($data7= mysql_fetch_array($req7))
					{$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,$col,'Congé sans solde',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,1,        $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,3,        $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,4,        $entete21);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data7[0],-2,2)==$i)
							
							
							{ 
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,($j+$i),$data7[2],$entete21);
						
							}
						}
					$requete6=$sql7."and project.ID and jiraissue.ID";
					$exe6 = mysql_query($requete6);
					$tab6= mysql_fetch_array($exe6); 
					//print_r($tab);
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,6,$tab6[2],$entete22); 
			   		}
					$sql7.=" and project.pname";
					$req7 = mysql_query($sql7); 
					$nb7=mysql_num_rows($req7);
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,$j+$c,                 $format_weekend);
				    
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6,$j+$c,                 $format_weekend);
				    
				
								}
						  } 
					}
				}
				if(($champ1='10064')&&($champ8='19'))//en cas de récupération
				{
					$sql8 = requeteconge('19',$id,$date1,$date2); 
					$req8 = mysql_query($sql8); 
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$p,                 $entete21);
					
					}
					
					
					
					while ($data1= mysql_fetch_array($req8))
					{$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$col,'Récupération',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,1,    $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,3,    $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,4,    $entete21);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data8[0],-2,2)==$i)
							{
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$j+$i,$data8[2],$entete21);
							
							}
						}
					$requete7=$sql8."and project.ID and jiraissue.ID"; 
					$exe7 = mysql_query($requete7);
					$tab7= mysql_fetch_array($exe7); 
					//print_r($tab);
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,6,$tab7[2],$entete22);
			   		}
					$sql8.=" and project.pname";
					$req8 = mysql_query($sql8); 
					$nb8=mysql_num_rows($req8);
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$j+$c,                 $format_weekend);
				      
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7,$j+$c,                 $format_weekend);
				      
				
								}
						  } 
					}
					
					
				}
				if(($champ1='10064')&&($champ8='9'))//en cas de autorisation de sortie
				{
					$sql9 = requeteconge('9',$id,$date1,$date2); 
					$req9 = mysql_query($sql9);
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$p,                 $entete21);
					
					}
					
					
					
					
					
					while ($data9= mysql_fetch_array($req9))
					{$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$col,'Autorisation de sortie',$entete23) ;
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,1, $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nbp5+$nb6+$nb7+$nb8,3, $entete21);
					$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nbp6+$nb7+$nb8,4, $entete21); 
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data9[0],-2,2)==$i)
							{
							$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$j+$i,$data9[2],$entete21);
				     
							}
							
						} 
					$requete8=$sql9."and project.ID and jiraissue.ID";
					$exe8 = mysql_query($requete8);
					$tab8= mysql_fetch_array($exe8); 
					
					$worksheet->write (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,6,$tab8[2],$entete22);
			   		}
					$sql9.=" and project.pname";
					$req9 = mysql_query($sql9); 
					$nb9=mysql_num_rows($req9);
					
					
					
					for ($compt=8;$compt<$fin+1;$compt++){
 
  				$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$compt,    $entete21); 
                  }
				  for ($compt1=8;$compt1<$fin+1;$compt1++){
 
  				$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$compt1,    $entete21); 
                  }
				  for ($compt2=8;$compt2<$fin+1;$compt2++){
 
  				$worksheet->write_blank(26+$nb1+$nb2+$nb21+$nb3,$compt2,    $entete22); 
                  }
				  //jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$j+$f1,                 $format_weekend);	
						$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$f1,                 $format_weekend);	
						$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,     $format_weekend);
				$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,     $format_weekend);
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,     $format_weekend);
					  $worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,     $format_weekend);
				
								}
						  } 
					}
					
					
					
				}
				$worksheet->write_blank (26+$nb1+$nb2+$nb21+$nb3,6,          $entete22);		
				$worksheet->write_blank (27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,6,$entete22);
				$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,6,$entete22);
				/**********************formations*****************************/
				
						
				
				if (($champ1='10083')&&($champ8='12'))//en cas de formation interne
				{
					$sql10=requeteformation('12',$id,$date1,$date2);
					$req10 = mysql_query($sql10);
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$p,                 $entete21);
					
					}
					 
					
					
					while ($data10= mysql_fetch_array($req10))
					{$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$col,'Formation interne',$entete23) ;
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,1, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,3, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,4, $entete21); 
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data10[0],-2,2)==$i)
							{
							$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$i,$data10[2],$entete21);
							
							}
						} 
				    $requete9=$sql10."and project.ID and  jiraissue.ID";
					$exe9 = mysql_query($requete9);
					$tab9= mysql_fetch_array($exe9); 
					
					$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,6,$tab9[2],$entete22);
			   		}
					$sql10.=" and project.pname";
					$req10 = mysql_query($sql10); 
					$nb10=mysql_num_rows($req10);
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){

						$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					
					
					
				}
				if (($champ1='10083')&&($champ8='13'))//en cas de formation externe
				{
					$sql11=requeteformation('13',$id,$date1,$date2);
					$req11 = mysql_query($sql11); 
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$p,                 $entete21);
					
					}
					
					
					 
					while ($data11= mysql_fetch_array($req11))
					{$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$col,'Formation externe',$entete23) ;
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,1, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,3, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,4, $entete21);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data11[0],-2,2)==$i)
							{
							$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$j+$i,$data11[2],$entete21);
						
							}
						}
					$requete10=$sql11."and project.ID and  jiraissue.ID"; 
					$exe10 = mysql_query($requete10);
					$tab10= mysql_fetch_array($exe10); 
				
					$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,6,$tab10[2],$entete22);
			   		}
					$sql11.=" and project.pname";
					$req11= mysql_query($sql11); 
					$nb11=mysql_num_rows($req11);
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//samedis
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}

					
					
					
				}
				if (($champ1='10083')&&($champ8='11'))//en cas d'auto-formation
				{
					$sql12=requeteformation('11',$id,$date1,$date2);
					$req12 = mysql_query($sql12);
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$p,                 $entete21);
					
					}
					
					
					
					  
					while ($data12= mysql_fetch_array($req12))
					{$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$col,'Auto-formation',$entete23) ;
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,1, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,3, $entete21);
					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,4, $entete21);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data12[0],-2,2)==$i)
							{
							$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$j+$i,$data12[2],$entete21);
							
							}
						} 
					$requete11=$sql12."and project.ID and  jiraissue.ID"; 
					$exe11 = mysql_query($requete11);
					$tab11= mysql_fetch_array($exe11); 
					
					$worksheet->write (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,6,$tab11[2],$entete22);
			   		}
					$sql12.=" and project.pname";
					$req12 = mysql_query($sql12); 
					$nb12=mysql_num_rows($req12);
					
					
					
					for ($compt=8;$compt<$fin+1;$compt++){
   					$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+		$nb11+$nb12,$compt,    $entete21);
  					}
 					 for ($compt1=8;$compt1<$fin+1;$compt1++){
  					 $worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+	$nb11+$nb12,$compt1,    $entete21);
 					 }
 					 for ($compt2=8;$compt2<$fin+1;$compt2++){
 					  $worksheet->write_blank(30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,$compt2,    $entete22);
 					 }
					 //jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$c,     $format_weekend);
					  $worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$c,     $format_weekend);
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$c,     $format_weekend);
					  $worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$j+$c,     $format_weekend);
				
								}
						  } 
					}
					
					
					
				}
				$worksheet->write_blank (30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,6,          $entete22);		
				$worksheet->write_blank (31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+		$nb11+$nb12,6,$entete22);
				$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+	$nb11+$nb12,6,$entete22);
				
				
				
				/********************Commercial*******************************/
				if (($champ1='10100')&&($champ8='15'))//en cas d'action commerciale
				{
			    	$sql13=requetecommercial('15',$id,$date1,$date2);
					$req13 = mysql_query($sql13);
					$nb13=mysql_num_rows($req13);
					$row4=35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12;
					
			while ($data13= mysql_fetch_array($req13)){
				    $var4 =str_replace("'","\'","$data13[3]");
					$var41=($data13[4]);
					$sql131=requetecommercial1('15',$id,$var41,$date1,$date2);
					$req131 = mysql_query($sql131);	
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row4,$p,                 $entete21);
					
					}
					
					while ($data131= mysql_fetch_array($req131))
					{
					$worksheet->write ($row4,1,'Action commerciale',$entete23) ;
					$worksheet->write ($row4,2,$data13[3],$entete23) ;
					$worksheet->write_blank($row4,3, $entete23);
					$worksheet->write_blank($row4,4, $entete23);   
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data131[0],-2,2)==$i)
							{
							$worksheet->write ($row4,$j+$i,$data131[2],$entete21);
						
							}
						} 
					$requete12=$sql131." and jiraissue.ID";
					$exe12 = mysql_query($requete12);
					$tab12= mysql_fetch_array($exe12); 
					
					$worksheet->write ($row4,6,$tab12[2],$entete22);
			   		}
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row4,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row4,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank($row4,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					$row4++;
					}
				}
				if(($champ1='10100')&&($champ8='14'))//en cas de proposition commerciale
				{
				    $sql14=requetecommercial('14',$id,$date1,$date2);
					$req14 = mysql_query($sql14);
					$nb14=mysql_num_rows($req14);
					$row5=35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13;
					
					
					while ($data14= mysql_fetch_array($req14)){
					
							
					$var5 =str_replace("'","\'","$data14[3]");
					$var51=($data14[4]);
					$sql141=requetecommercial1('14',$id,$var51,$date1,$date2);
					$req141 = mysql_query($sql141);	
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row5,$p,                 $entete21);
					
					}
					
					
					
					while ($data141= mysql_fetch_array($req141))
					{$worksheet->write ($row5,1,'Proposition commerciale',$entete23) ;
					$worksheet->write ($row5,2,$data14[3],$entete23) ;
					$worksheet->write_blank($row5,3, $entete23);
					$worksheet->write_blank($row5,4, $entete23);
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data141[0],-2,2)==$i)
							{
							$worksheet->write ($row5,$j+$i,$data141[2],$entete21);
						
							}
						}
					$requete13=$sql141." and jiraissue.ID";
					$exe13 = mysql_query($requete13);
					$tab13= mysql_fetch_array($exe13); 
				
					$worksheet->write ($row5,6,$tab13[2],$entete22); 
			 
					
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row5,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row5,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank($row5,$j+$c,                 $format_weekend);
				     
				
								}
						  } 

					}
					
					
										
				}$row5++;}
					for ($compt=8;$compt<$fin+1;$compt++){
   $worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$compt,    $entete21);
 					}
 					for ($compt1=8;$compt1<$fin+1;$compt1++){
   $worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$compt1,    $entete21);
  					}
  					for ($compt2=8;$compt2<$fin+1;$compt2++){
   $worksheet->write_blank(34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,$compt2,    $entete22);
  					}
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13,$j+$f1,                 $format_weekend);	
						$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$f1,                 $format_weekend);						
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,     $format_weekend);
					  $worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,     $format_weekend);
				
								}
						  } 
					}
				//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,     $format_weekend);
					  $worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,     $format_weekend);
				
								}
						  } 
					}	
					
				}
				$worksheet->write_blank (34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,6,          $entete22);		
				$worksheet->write_blank (35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,6,$entete22);
				$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,6,$entete22);
				/*********************************marketing*******************************/
				if ($champ1='10090')
				{
				
				    $sql151=requetemarketingmanagement1('10090',$id,$date1,$date2);
					$req151 = mysql_query($sql151);
				    $nb15=mysql_num_rows($req151);
				 $row2= 38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14;
			while ($data151= mysql_fetch_array($req151)){
			
				
				  $var =str_replace("'","\'","$data151[3]");
					$sql15=requetemarketingmanagement('10090',$id,$var,$date1,$date2);
					$req15 = mysql_query($sql15);
					
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row2,$p,                 $entete21);
					
					}
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row2,$j+$f1,                 $format_weekend);							
						}
					} 
				}		
					 //samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row2,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				     $worksheet->write_blank($row2,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					
					while ($data15= mysql_fetch_array($req15))
					{
					
					    $worksheet->write ($row2,$col,$var,$entete23) ;
					$worksheet->write_blank($row2,1, $entete23);
					$worksheet->write_blank($row2,3, $entete23);
					$worksheet->write_blank($row2,4, $entete23); 
					
					
					
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data15[0],-2,2)==$i)
							{
							$worksheet->write ($row2,$j+$i,$data15[2],$entete21);
							
							}
						} 
					$requete14=$sql15."and jiraissue.ID";
					$exe14 = mysql_query($requete14);
					$tab14= mysql_fetch_array($exe14); 
					
					$worksheet->write ($row2,6,$tab14[2],$entete22);
					
					
					
			   		}
					 
				$row2++;	
					
					
				}	
					
					for ($compt=8;$compt<$fin+1;$compt++){
   $worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$compt,    $entete21);
  					}
              		for ($compt1=8;$compt1<$fin+1;$compt1++){
   $worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$compt1,    $entete21);
  					}
 					for ($compt2=8;$compt2<$fin+1;$compt2++){
   $worksheet->write_blank(37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$compt2,    $entete22);
  					}
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						
						$worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$f1,                 $format_weekend);							
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$c,     $format_weekend);
					   $worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$c,     $format_weekend);
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$c,     $format_weekend);
					  $worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$c,     $format_weekend);

				
								}
						  } 
					}
					
					
				}
				
				$worksheet->write_blank (37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,6,          $entete22);		
				$worksheet->write_blank (38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,6,$entete22);
				$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,6,$entete22);
				/******************************management administratif *********************/
				if ($champ1='10103')
				{
				
				
				$sql161=requetemarketingmanagement1('10103',$id,$date1,$date2); 
				$req161 = mysql_query($sql161); 
				$nb16=mysql_num_rows($req161);
			
				   $row3=41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15;
				   
				
			while ($data161= mysql_fetch_array($req161)){	
			
			
			
			//print_r($data161);
			
			 $var1 =str_replace("'","\'","$data161[3]");
				    $sql16=requetemarketingmanagement('10103',$id,$var1,$date1,$date2);
					$req16 = mysql_query($sql16);
					
					for($p=8;$p<=$fin;$p++)
					{
					$worksheet->write_blank($row3,$p,                 $entete21);
					
					}
					
					
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank($row3,$j+$f1,                 $format_weekend);							
						}
					} 
				}		
					 //samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank($row3,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				     $worksheet->write_blank($row3,$j+$c,                 $format_weekend);
				     
				
								}
						  } 
					}
								
					
					
					while ($data16= mysql_fetch_array($req16))
					{
					$worksheet->write ($row3,$col,$var1,$entete23) ;
					$worksheet->write_blank($row3,1, $entete23);
					$worksheet->write_blank($row3,3, $entete23);
					$worksheet->write_blank($row3,4, $entete23);  
						for ($i=1;$i<=$finmois;$i++){
							if(substr($data16[0],-2,2)==$i)
							{
							$worksheet->write ($row3,$j+$i,$data16[2],$entete21);
							}
						} 
					$requete15=$sql16."and jiraissue.ID";
					$exe15 = mysql_query($requete15);
					$tab15= mysql_fetch_array($exe15); 
					
					$worksheet->write ($row3,6,$tab15[2],$entete22);
					
				}
				$row3++;
			}	
					
					
					
					for ($compt=8;$compt<$fin+1;$compt++){
   $worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$compt,    $entete21);
  					}
  					for ($compt1=8;$compt1<$fin+1;$compt1++){
   $worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$compt1,    $entete21);
  					}
					for ($compt2=8;$compt2<$fin+1;$compt2++){
   $worksheet->write_blank(40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$compt2,    $entete22);
  					}
					
					
				//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
							
						$worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$f1,                 $format_weekend);
						$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$f1,                 $format_weekend);						
						}
					} 
				}	
					//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      
				      $worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$c,     $format_weekend);
					  $worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$c,     $format_weekend);
				
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 

				      $worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,$j+$c,                 $format_weekend);
				      $worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$c,     $format_weekend);
					  $worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$j+$c,     $format_weekend);
					  
				
								}
						  } 
					}	
				
				
			}	
				$worksheet->write_blank (40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,6,          $entete22);		
				$worksheet->write_blank (41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,6,$entete22);
				$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,6,$entete22);
				
				/***********************TOTAL*******************************/

				
	$requete16 = "SELECT project.ID
, imputation.issue,imputation.user as user ,project.pname,sum(imputation.imputation) AS imput
	FROM imputation, jiraissue, userbase, project
	WHERE imputation.Project = project.ID
	AND imputation.issue = jiraissue.ID
	AND imputation.user = $id
	AND imputation.user = userbase.id
	AND imputation.DATE
	BETWEEN '$date1'
	AND '$date2' group by userbase.id"; 
		$exe16 = mysql_query($requete16);
		$tab16= mysql_fetch_array($exe16); 
		
		$worksheet->write (44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,6,$tab16[4],$entete22);
	
	
	
	for ($compt=8;$compt<$fin+1;$compt++){
   $worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,$compt,    $entete21);
     }
	 				//samedis
					for ($k=0;$k<$nb_sam;$k++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabsam[$k]==$c){
					 
				      $worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,($j+$c),                 $format_weekend);
				      
								}
						  } 
					}
					//dimanches
					for ($l=0;$l<$nb_dim;$l++){
	                    for ($c=1;$c<=$finmois;$c++){
			                 if($tabdim[$l]==$c){
					 
				      $worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,($j+$c),                 $format_weekend);
				      
								}
						  } 
					}	
					//jour férié
				for ($f=0;$f<$nb_f;$f++){
					for ($f1=1;$f1<=$finmois;$f1++){
						if(substr($data_jf[1],-2,2)==$f1){
						$worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,($j+$f1),                 $format_weekend);							
						}
					} 
				}		
				
				
  $s = "SELECT project.ID
, imputation.issue,imputation.user as user ,project.pname,sum(imputation.imputation) AS imput ,jiraissue.summary ,imputation.date as date  
	FROM imputation, jiraissue, userbase, project
	WHERE imputation.Project = project.ID
	AND imputation.issue = jiraissue.ID
	AND imputation.user = $id
	AND imputation.user = userbase.id
	AND imputation.DATE
	BETWEEN '$date1'
	AND '$date2' group by date"; 			
	$r = mysql_query($s);			
				
				//echo $db[7];
				while ($db = mysql_fetch_array($r))
					{
						for ($i=1;$i<=$finmois;$i++){ 
						 	if(substr($db[6],-2,2)==$i){
							
								$worksheet->write (44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,($j+$i),$db[4],$entete21);
							}
							
												
						 }
				    }
				
				
$requetefonction="SELECT ps.propertyvalue
FROM propertyentry pe, propertystring ps
WHERE pe.ID = ps.ID
AND pe.ENTITY_ID = $id
AND pe.`PROPERTY_KEY` = 'jira.meta.Fonction'";
$reqfonction = mysql_query($requetefonction);
$tab_fonction= mysql_fetch_array($reqfonction);	
$function=$tab_fonction[0];				
						
			
	/********************************début entête du fichier */
	$worksheet->insert_bitmap('B1', '../images/logo.bmp', 5, 3);//logo BD
	$worksheet->write('D2', "A renvoyer au plus tard le 2 du chaque nouveau mois à  ", $format);
	
	$worksheet->write_url('E2', 'mailto:mjrad@businessdecision.com', 'mjrad@businessdecision.com', $format_url);
	
	$worksheet->write('D3', "Copie obligatoire pour validation à", $format1);
	$worksheet->write_url('E3',  'mailto:ihbaieb@businessdecision.com', 'ihbaieb@businessdecision.com', $format_url);
		
	$worksheet->write('M5', "COMPTE RENDU D'ACTIVITE", $heading); 
	
	
	for ($g=1;$g<12;$g++)
	{$worksheet->write_blank(4,$g,                 $heading);}
	
	
	for ($g1=13;$g1<$fin+1;$g1++)
	{$worksheet->write_blank(4,$g1,                 $heading);}
	
	$worksheet->write('B7', 'Nom :',   $format21);
	$worksheet->write('C7',        $nom,   $format32);
	$worksheet->write_blank('D7',                 $format33);
	$worksheet->write('B8', 'E-mail B&D :',   $format22);
	$worksheet->write('C8',        $mail,   $format_url);
	$worksheet->write_blank('D8',                 $format34);
	$worksheet->write('B9', 'fonction :',   $format22);
	$worksheet->write('C9',        $function,   $format32);
	//$worksheet->write_blank('C9',                 $format31);
	$worksheet->write_blank('D9',                 $format34);
	$worksheet->write_blank('B10',                $format23);
	$worksheet->write_blank('C10',                $format36);
	$worksheet->write_blank('D10',                $format35);
	
	$worksheet->write('G8', 'CRA du :',   $format5);
	$worksheet->write('I8', $m,   $format4);
	$worksheet->write('J8', $Y,   $format4);
	$worksheet->write_blank('F7',                 $format41);
	$worksheet->write_blank('F8',                 $format41);
	$worksheet->write_blank('F9',                 $format41);
	$worksheet->write_blank('F10',                 $format41);
	$worksheet->write_blank('G7',                 $format41);
	$worksheet->write_blank('G9',                 $format41);
	$worksheet->write('G10','Nombre de jours travaillés :' ,$format41);
	
	
	$worksheet->write_blank('H7',                 $format41);
	$worksheet->write_blank('H8',                 $format41);
	$worksheet->write_blank('H9',                 $format41);
	$worksheet->write_blank('H10',                 $format41);
	$worksheet->write_blank('I7',                 $format41);
	$worksheet->write_blank('I9',                 $format41);
	$worksheet->write_blank('I10',                 $format41);
	$worksheet->write_blank('J7',                 $format41);
	$worksheet->write_blank('J9',                 $format41);
	$worksheet->write_blank('J10',                 $format41);
	$worksheet->write_blank('K7',                 $format41);
	$worksheet->write_blank('K8',                 $format41);
	$worksheet->write_blank('K9',                 $format41);
	$worksheet->write_blank('K10',                 $format41);
	
	$worksheet->write_blank('L7',                 $format41);
	$worksheet->write_blank('L8',                 $format41);
	$worksheet->write_blank('L9',                 $format41);
	
	$worksheet->write('L10',$nb_jour_trav ,$format41);
	
	$worksheet->write_blank('N7',                 $format21);
	$worksheet->write_blank('N8',                 $format22);
	$worksheet->write_blank('N9',                 $format22);
	$worksheet->write_blank('N10',                $format23);
	
	
	
	for ($d=14;$d<$fin;$d++)
	{$worksheet->write_blank(6,$d,                 $format32);}
	for ($d1=14;$d1<$fin;$d1++)
	{$worksheet->write_blank(9,$d1,                $format36);}
	for ($d2=$fin;$d2<=$fin;$d2++)
	{$worksheet->write_blank(6,$d2,                $format33);}
	for ($d3=$fin;$d3<=$fin;$d3++)
	{$worksheet->write_blank(7,$d3,                $format34);}
	for ($d4=$fin;$d4<=$fin;$d4++)
	{$worksheet->write_blank(8,$d4,                $format34);}
	for ($d5=$fin;$d5<=$fin;$d5++)
	{$worksheet->write_blank(9,$d5,                $format35);}
	
	
	
	$worksheet->write('M12', 'Réalisé',   $heading);
	
	$worksheet->write('O12', $m,   $format6);
	$worksheet->write('P12',$Y ,   $format6);
	
	
	$worksheet->write_blank('B12',                 $heading);
	$worksheet->write_blank('C12',                 $heading);
	$worksheet->write_blank('D12',                 $heading);
	$worksheet->write_blank('E12',                 $heading);
	$worksheet->write_blank('F12',                 $heading);
	$worksheet->write_blank('G12',                 $heading);
	$worksheet->write_blank('H12',                 $heading);
	$worksheet->write_blank('I12',                 $heading);
	$worksheet->write_blank('J12',                 $heading);
	$worksheet->write_blank('K12',                 $heading);
	$worksheet->write_blank('L12',                 $heading);
	$worksheet->write_blank('N12',                 $heading);
	for ($g2=16;$g2<$fin+1;$g2++)
	{$worksheet->write_blank(11,$g2,                 $heading);}
	
	
		
	
	$worksheet->write_blank('B11',                 $format10);
	
	
	
	
	$worksheet->write('B14', 'Client',   $entete11);
	$worksheet->write_blank('B15',                 $entete12);
	$worksheet->write('C14', 'Projet',   $entete11);
	$worksheet->write_blank('C15',                 $entete12);
	$worksheet->write('D14', 'Adresse mail du responsable projet',   $entete11);
	$worksheet->write_blank('D15',                 $entete12);
	$worksheet->write_blank('E14',                 $entete16);
	$worksheet->write_blank('E15',                 $entete12);
	$worksheet->write('G14', 'TOTAL',   $entete11);
	$worksheet->write_blank('G15',                 $entete12);
	$worksheet->write('B17', 'Projets facturés',   $entete13);
	$worksheet->write_blank('C17',                 $entete14);
	$worksheet->write_blank('D17',                 $entete14);
	$worksheet->write_blank('E17',                 $entete15);
	$worksheet->write_blank(17+$nb1,1,    $entete21); //ligne vide
	$worksheet->write_blank(17+$nb1,2,                 $entete21);
	$worksheet->write_blank(17+$nb1,3,                 $entete21);
	$worksheet->write_blank(17+$nb1,4,                 $entete21);
	$worksheet->write_blank(18+$nb1,1,    $entete21); //ligne vide
	$worksheet->write_blank(18+$nb1,2,                 $entete21);
	$worksheet->write_blank(18+$nb1,3,                 $entete21);
	$worksheet->write_blank(18+$nb1,4,                 $entete21);
	$worksheet->write(19+$nb1,1, 'Projets non facturés',   $entete13);
	$worksheet->write_blank(19+$nb1,2,                 $entete14);
	$worksheet->write_blank(19+$nb1,3,                 $entete14);
	$worksheet->write_blank(19+$nb1, 4,                $entete15);
	$worksheet->write_blank(20+$nb1+$nb2+$nb21,1,    $entete21);//ligne vide
	$worksheet->write_blank(20+$nb1+$nb2+$nb21,2,                 $entete21);
	$worksheet->write_blank(20+$nb1+$nb2+$nb21,3,                 $entete21);
	$worksheet->write_blank(20+$nb1+$nb2+$nb21,4,                 $entete21);
	$worksheet->write_blank(21+$nb1+$nb2+$nb21,1,    $entete21);//ligne vide
	$worksheet->write_blank(21+$nb1+$nb2+$nb21,2,                 $entete21);
	$worksheet->write_blank(21+$nb1+$nb2+$nb21,3,                 $entete21);
	$worksheet->write_blank(21+$nb1+$nb2+$nb21,4,                 $entete21);
	$worksheet->write(22+$nb1+$nb2+$nb21,1, 'Projets internes',   $entete13);
	$worksheet->write_blank(22+$nb1+$nb2+$nb21,2,                 $entete14);
	$worksheet->write_blank(22+$nb1+$nb2+$nb21,3,                 $entete14);
	$worksheet->write_blank(22+$nb1+$nb2+$nb21,4,                 $entete15);
	$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,1,    $entete21);//ligne vide
	$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,2,                 $entete21);
	$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,3,                 $entete21);
	$worksheet->write_blank(23+$nb1+$nb2+$nb21+$nb3,4,                 $entete21);
	$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,1,    $entete21);//ligne vide
	$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,2,                 $entete21);
	$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,3,                 $entete21);
	$worksheet->write_blank(24+$nb1+$nb2+$nb21+$nb3,4,                 $entete21);
	$worksheet->write(26+$nb1+$nb2+$nb21+$nb3,1, 'Congés',   $entete13);
	$worksheet->write_blank(26+$nb1+$nb2+$nb21+$nb3,2,                 $entete14);
	$worksheet->write_blank(26+$nb1+$nb2+$nb21+$nb3,3,                 $entete14);
	$worksheet->write_blank(26+$nb1+$nb2+$nb21+$nb3,4,                 $entete15);
	$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,1,    $entete21);//ligne vide
	$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,2,                 $entete21);
	$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,3,                 $entete21);
	$worksheet->write_blank(27+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,4,                 $entete21);
	$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,1,    $entete21);//ligne vide
	$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,2,                 $entete21);
	$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,3,                 $entete21);
	$worksheet->write_blank(28+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,4,                 $entete21);
	$worksheet->write(30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,1, 'Formations',   $entete13);
	$worksheet->write_blank(30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,2,                 $entete14);
	$worksheet->write_blank(30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,3,                 $entete14);
	$worksheet->write_blank(30+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9,4,                 $entete15);
	$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,1,    $entete21);
	$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,2,                 $entete21);
	$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,3,                 $entete21);
	$worksheet->write_blank(31+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,4,                 $entete21);
	$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,1,    $entete21);
	$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,2,                 $entete21);
	$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,3,                 $entete21);
	$worksheet->write_blank(32+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,4,                 $entete21);
	$worksheet->write(34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,1, 'Activités commerciales',$entete13);
	$worksheet->write_blank(34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,2,                 $entete14);
	$worksheet->write_blank(34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,3,                 $entete14);
	$worksheet->write_blank(34+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12,4,                 $entete15);
	$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,1,    $entete21);//ligne vide
	$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,2,                 $entete21);
	$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,3,                 $entete21);
	$worksheet->write_blank(35+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,4,                 $entete21);
	$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,1,    $entete21);//ligne vide
	$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,2,                 $entete21);
	$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,3,                 $entete21);
	$worksheet->write_blank(36+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,4,                 $entete21);
	$worksheet->write(37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,1,"Marketing ",   $entete13);
	$worksheet->write_blank(37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,2,                 $entete14);
	$worksheet->write_blank(37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,3,                 $entete14);
	$worksheet->write_blank(37+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14,4,                 $entete15);
	
	$worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,1,    $entete21);
	$worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,2,                 $entete21);
	$worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,3,                 $entete21);
	$worksheet->write_blank(38+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,4,                 $entete21);
	
	$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,1,    $entete21);
	$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,2,                 $entete21);
	$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,3,                 $entete21);
	$worksheet->write_blank(39+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,4,                 $entete21);
	
	$worksheet->write(40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,1, 'Management / Administratif',   $entete13);
	$worksheet->write_blank(40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,2,                 $entete14);
	$worksheet->write_blank(40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,3,                 $entete14);
	$worksheet->write_blank(40+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15,4,                 $entete15);
	$worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,1,    $entete21);
	$worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,2,                 $entete21);
	$worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,3,                 $entete21);
	$worksheet->write_blank(41+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,4,                 $entete21);
	$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,1,    $entete21);
	$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,2,                 $entete21);
	$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,3,                 $entete21);
	$worksheet->write_blank(42+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,4,                 $entete21);
	$worksheet->write(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,1, 'TOTAL :',   $entete13);
	$worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,2,                 $entete14);
	$worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,3,                 $entete14);
	$worksheet->write_blank(44+$nb1+$nb2+$nb21+$nb3+$nb4+$nb5+$nb6+$nb7+$nb8+$nb9+$nb10+$nb11+$nb12+$nb13+$nb14+$nb15+$nb16,4,                 $entete15);
	

	
	//les jours du mois
	
	for ($z=1;$z<=$finmois;$z++){
	
	$worksheet->write(13,$j+$z, $z,$entete4);
	
	}
	
	/********************************fin entête du fichier */
	
	
  
		

  	    header("Content-Disposition: attachment;filename=CRA_".$nom."_".$n."_".$Y.".xls");
		header("Content-Type: application/x-msexcel");
		header('Pragma: no-cache');
		header('Expires: 0');
       
	$workbook->close(); // on ferme le fichier Excel créer  


   

$fh=fopen($fname,"ab+");
fpassthru($fh);//Affiche le reste du fichier
//unlink($fname);	//efface un fichier 



?>