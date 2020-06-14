/********************************************************
* 	CLASSE DE CHAMP DE SAISIE : Texte	     	*
* 							*
* Permet la saisie de texte sur une ligne dans un champ	*
* input.						*
*********************************************************/

var input = null;

//Constructeur de l'objet
function Texte()
{
	this.id = -1;
	this.valeur = "";
	this.nomChamp = "";
	this.parent = null;
	this.texteErreur = "";
}

//Fonction de remplacement du texte de parent par le champ
Texte.prototype.remplacerTexte = function (parent, sauvegarde)
{
	if(!parent || !sauvegarde)
	{
		return false;
	}
	else
	{
		this.parent = parent;
	}

	input = document.createElement("input");
	
	input.value = this.valeur;
	input.style.width = getTextWidth(this.valeur) + 10 + "px";
	
	//Assignation des �v�nements qui d�clencheront la sauvegarde de la valeur
	
	//Sortie de l'input
	input.onblur = function ()
	{
		sauvegarde();
	};

	//Appui sur la touche Entr�e
	input.onkeydown = function keyDown(event)
	{
		if((window.event && (getKeyCode(window.event) == 13)) || (getKeyCode(event) == 13))
		{
			sauvegarde.call();
		}		
	};
	
	parent.replaceChild(input, parent.firstChild);
}

//Fonction permettant de r�cup�rer la valeur du champ
Texte.prototype.getValeur = function ()
{
	return input.value;
}

//Fonction d'activation du champ
Texte.prototype.activerChamp = function ()
{
	input.focus();
	input.select();
}

//Fonction de sortie du mode d'�dition
Texte.prototype.terminerEdition = function ()
{
	this.parent.replaceChild(document.createTextNode(input.value), this.parent.firstChild);
	delete input;
}

//Fonction d�terminant si la valeur pass�e au script PHP doit �tre format�e par celui-ci ou pas (avec mysql_real_escape_string($str);)
//Ici oui, car il s'agit de texte
Texte.prototype.echaperValeur = function ()
{
	return "true";
}

//Erreur si champ vide
Texte.prototype.erreur = function ()
{
	if(this.getValeur() == "")
	{
		this.texteErreur = "Aucune saisie effectu�e !";
		return true;
	}
	else
		return false;
}