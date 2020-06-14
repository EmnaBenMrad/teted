//Objet servant à l'édition de la valeur dans la page
var champ = null;

//On ne pourra éditer qu'une valeur à la fois
var editionEnCours = false;

//variable évitant une seconde sauvegarde lors de la suppression de l'input
var sauve = false;

//Fonction de modification inline de l'élément double-cliqué
function inlineMod(id, obj, nomChamp, classe)
{
	if(editionEnCours)
	{
		return false;
	}
	else
	{
		editionEnCours = true;
		sauve = false;
	}
	
	//Création de l'objet dont le nom de classe est passé en paramètre
	champ = eval('new ' + classe + '();');
	
	//Assignation des différentes propriétés
	champ.valeur = obj.innerText ? obj.innerText : obj.textContent;
	champ.valeur = trim(champ.valeur);
	
	champ.id = id;
	champ.nomChamp = nomChamp;

	//Remplacement du texte par notre objet input
	champ.remplacerTexte(obj, sauverMod);

	//"Activation" du champ (focus, sélection ou autres...)
	champ.activerChamp();
}


//Objet XMLHTTPRequest
var XHR = null;

//Fonction de sauvegarde des modifications apportées
function sauverMod()
{
	//Si on a déjà sauvé la valeur en cours, on sort
	if(sauve)
	{
		return false;
	}
	else
	{
		sauve = true;
	}

	//Vérification d'erreur
	if(champ.erreur())
	{
		document.getElementById("erreur").innerHTML = champ.texteErreur;
		sauve = false;
		return false;
	}
		
	//Si l'objet existe déjà on abandonne la requête et on le supprime
	if(XHR && XHR.readyState != 0)
	{
		XHR.abort();
		delete XHR;
	}

	//Création de l'objet XMLHTTPRequest
	XHR = getXMLHTTP();

	if(!XHR)
	{
		return false;
	}
	
	//URL du script de sauvegarde auquel on passe la requête à exécuter
	XHR.open("GET", "sauverMod.php?champ=" + escape(champ.nomChamp) + "&valeur=" + escape(champ.getValeur()) + "&echap=" + champ.echaperValeur() + "&id=" + champ.id + ieTrick(), true);

	//On se sert de l'événement OnReadyStateChange pour supprimer l'input et le replacer par son contenu
	XHR.onreadystatechange = function()
	{
		//Si le chargement est terminé
		if (XHR.readyState == 4)
			if(!XHR.responseText)
			{
				//Réinitialisation de la variable d'état d'édition
				editionEnCours = false;
				//alert("erreur");

				//Sortie du mode d'édition
				champ.terminerEdition();
				
				//Réinitialisation de l'affichage d'erreur
				document.getElementById("erreur").innerHTML = "";
				
				return true;
			}
			else //S'il y a une réponse texte, c'est une erreur PHP
			{
				//Affichage de l'erreur
				document.getElementById("erreur").innerHTML = XHR.responseText;
				sauve = false;
				return false;
			}
	}

	//Envoi de la requête
	XHR.send(null);
}


