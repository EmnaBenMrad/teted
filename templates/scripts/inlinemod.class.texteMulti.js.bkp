/****************************************************************
*	CLASSE DE CHAMP DE SAISIE : TexteMulti		     	*
*								*
* Permet la saisie de texte sur plusieurs lignes dans un champ	*
* textarea.							*
*****************************************************************/

var textarea = null;

//Constructeur de l'objet
function TexteMulti()
{
	this.id = -1;
	this.valeur = "";
	this.nomChamp = "";
	this.parent = null;
	this.texteErreur = "";
}

//Fonction de remplacement du texte de parent par le champ
TexteMulti.prototype.remplacerTexte = function (parent, sauvegarde)
{
	if(!parent || !sauvegarde)
	{
		return false;
	}
	else
	{
		this.parent = parent;
	}

	textarea = document.createElement("textarea");
	
	textarea.value = this.valeur;
	textarea.style.width = getTextWidth(this.valeur) + 30 + "px";
	
	//Assignation des événements qui déclencheront la sauvegarde de la valeur
	
	//Sortie du textarea
	textarea.onblur = function ()
	{
		sauvegarde.call();
	};

	parent.replaceChild(textarea, parent.firstChild);
}

//Fonction permettant de récupérer la valeur du champ
TexteMulti.prototype.getValeur = function ()
{
	return textarea.value;
}

//Fonction d'activation du champ
TexteMulti.prototype.activerChamp = function ()
{
	textarea.focus();
	textarea.select();
}

//Fonction de sortie du mode d'édition
TexteMulti.prototype.terminerEdition = function ()
{
	this.parent.replaceChild(document.createTextNode(textarea.value), this.parent.firstChild);
	delete textarea;
}

//Fonction déterminant si la valeur passée au script PHP doit être formatée par celui-ci ou pas (avec mysql_real_escape_string($str);)
//Ici oui, car il s'agit de texte
TexteMulti.prototype.echaperValeur = function ()
{
	return "true";
}

//Erreur si champ vide
TexteMulti.prototype.erreur = function ()
{
	if(this.getValeur() == "")
	{
		this.texteErreur = "Aucune saisie effectuée !";
		return true;
	}
	else
		return false;
}