//Objet servant � l'�dition de la valeur dans la page
var champ = null;

//On ne pourra �diter qu'une valeur � la fois
var editionEnCours = false;

//variable �vitant une seconde sauvegarde lors de la suppression de l'input
var sauve = false;

//Fonction de modification inline de l'�l�ment double-cliqu�
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
	
	//Cr�ation de l'objet dont le nom de classe est pass� en param�tre
	champ = eval('new ' + classe + '();');
	
	//Assignation des diff�rentes propri�t�s
	champ.valeur = obj.innerText ? obj.innerText : obj.textContent;
	champ.valeur = trim(champ.valeur);
	
	champ.id = id;
	champ.nomChamp = nomChamp;

	//Remplacement du texte par notre objet input
	champ.remplacerTexte(obj, sauverMod);

	//"Activation" du champ (focus, s�lection ou autres...)
	champ.activerChamp();
}


//Objet XMLHTTPRequest
var XHR = null;

//Fonction de sauvegarde des modifications apport�es
function sauverMod()
{
	//Si on a d�j� sauv� la valeur en cours, on sort
	if(sauve)
	{
		return false;
	}
	else
	{
		sauve = true;
	}

	//V�rification d'erreur
	if(champ.erreur())
	{
		document.getElementById("erreur").innerHTML = champ.texteErreur;
		sauve = false;
		return false;
	}
		
	//Si l'objet existe d�j� on abandonne la requ�te et on le supprime
	if(XHR && XHR.readyState != 0)
	{
		XHR.abort();
		delete XHR;
	}

	//Cr�ation de l'objet XMLHTTPRequest
	XHR = getXMLHTTP();

	if(!XHR)
	{
		return false;
	}
	
	//URL du script de sauvegarde auquel on passe la requ�te � ex�cuter
	XHR.open("GET", "sauverMod.php?champ=" + escape(champ.nomChamp) + "&valeur=" + escape(champ.getValeur()) + "&echap=" + champ.echaperValeur() + "&id=" + champ.id + ieTrick(), true);

	//On se sert de l'�v�nement OnReadyStateChange pour supprimer l'input et le replacer par son contenu
	XHR.onreadystatechange = function()
	{
		//Si le chargement est termin�
		if (XHR.readyState == 4)
			if(!XHR.responseText)
			{
				//R�initialisation de la variable d'�tat d'�dition
				editionEnCours = false;
				//alert("erreur");

				//Sortie du mode d'�dition
				champ.terminerEdition();
				
				//R�initialisation de l'affichage d'erreur
				document.getElementById("erreur").innerHTML = "";
				
				return true;
			}
			else //S'il y a une r�ponse texte, c'est une erreur PHP
			{
				//Affichage de l'erreur
				document.getElementById("erreur").innerHTML = XHR.responseText;
				sauve = false;
				return false;
			}
	}

	//Envoi de la requ�te
	XHR.send(null);
}


