// retourne un objet xmlHttpRequest.
// m�thode compatible entre tous les navigateurs (IE/Firefox/Opera)
function getXMLHTTP()
{
    var xhr = null;
    if(window.XMLHttpRequest)
    { // Firefox et autres
        xhr = new XMLHttpRequest();
    }
    else if(window.ActiveXObject)
    { // Internet Explorer
        try
        {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e)
        {
            try
            {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e1)
            {
                xhr = null;
            }
        }
    }
    else
    { // XMLHttpRequest non support� par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
    }

    return xhr;
}


//Fonction renvoyant le code de la touche appuy�e lors d'un �v�nement clavier
function getKeyCode(evenement)
{
    for (prop in evenement)
    {
        if(prop == 'which')
        {
            return evenement.which;
        }
    }

    return event.keyCode;
}


//Suppression des espaces/sauts de ligne inutiles (http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256C0C0062AC78)
function trim(value) {
   var temp = value;
   var obj = /^(\s*)([\W\w]*)(\b\s*$)/;
   if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }
   var obj = /  /g;
   while (temp.match(obj)) { temp = temp.replace(obj, " "); }
   return temp;
}

//Fonction donnant la largeur en pixels du texte donn� (merci SpaceFrog !)
function getTextWidth(texte)
{
	//Valeur par d�faut : 150 pixels
	var largeur = 150;

	if(trim(texte) == "")
	{
		return largeur;
	}

	//Cr�ation d'un span cach� que l'on "mesurera"
	var span = document.createElement("span");
	span.style.visibility = "hidden";
	span.style.position = "absolute";

	//Ajout du texte dans le span puis du span dans le corps de la page
	span.appendChild(document.createTextNode(texte));
	document.getElementsByTagName("body")[0].appendChild(span);

	//Largeur du texte
	largeur = span.offsetWidth;

	//Suppression du span
	document.getElementsByTagName("body")[0].removeChild(span);
	span = null;

	return largeur;
}


//Fonction renvoyant une valeur "al�atoire" pour forcer le navigateur (ie...)
//� envoyer la requ�te de mise � jour
function ieTrick(sep)
{
	d = new Date();
	trick = d.getYear() + "ie" + d.getMonth() + "t" + d.getDate() + "r" + d.getHours() + "i" + d.getMinutes() + "c" + d.getSeconds() + "k" + d.getMilliseconds();

	if (sep != "?")
	{
		sep = "&";
	}

	return sep + "ietrick=" + trick;
}

function heriter(classeEnfant, classeParent)
{
	function heritage() {}
	heritage.prototype = classeParent.prototype;
	
	classeEnfant.prototype 				= new heritage();
	classeEnfant.prototype.constructor 	= classeEnfant;
	classeEnfant.constructeurParent 		= classeParent;
	classeEnfant.classeParent			= classeParent.prototype;
}