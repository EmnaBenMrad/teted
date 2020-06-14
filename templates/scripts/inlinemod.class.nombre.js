/****************************************************************
*	CLASSE DE CHAMP DE SAISIE : Nombre			*
*								*
* Permet la saisie de nombre sur une ligne dans un champ 	*
* input.
* MEDINI Mounira <mounira.medini@businessdecision.com> 29 Avril 2008
*****************************************************************/

var input = null;

//Constructeur de l'objet
function Nombre()
{
	this.id = -1;
	this.valeur = "";
	this.nomChamp = "";
	this.parent = null;
	this.texteErreur = "";
}

//Fonction de remplacement du texte de parent par le champ
Nombre.prototype.remplacerTexte = function (parent, sauvegarde)
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
		sauvegarde.call(this);
	};

	//Appui sur la touche Entr�e
	input.onkeydown = function keyDown(event)
	{
		if((window.event && (getKeyCode(window.event) == 13)) || (getKeyCode(event) == 13))
		{
			sauvegarde.call(this);
		}		
	};
	
	parent.replaceChild(input, parent.firstChild);
}

//Fonction permettant de r�cup�rer la valeur du champ
Nombre.prototype.getValeur = function ()
{
	return input.value;
}

//Fonction d'activation du champ
Nombre.prototype.activerChamp = function ()
{
	input.focus();
	input.select();
}

//Fonction de sortie du mode d'�dition
Nombre.prototype.terminerEdition = function ()
{
	this.parent.replaceChild(document.createTextNode(input.value), this.parent.firstChild);
	delete input;
}

//Fonction d�terminant si la valeur pass�e au script PHP doit �tre format�e par celui-ci ou pas (avec mysql_real_escape_string($str);)
//Ici non, car il s'agit de valeur num�rique
Nombre.prototype.echaperValeur = function ()
{
	return "false";
}

//Si le texte entr� ne repr�sente pas un nombre, on renvoie true
Nombre.prototype.erreur = function ()
{
	if(isNaN(this.getValeur()))
	{
		this.texteErreur = "Vous devez entrer un chiffre !";
		return true;
	}
	if(((this.getValeur())>1) || ((this.getValeur())<0))
	{
	this.texteErreur = "Vous devez entrer un chiffre positif > 1 !";
	return true;	
	}
	else
		return false;
}