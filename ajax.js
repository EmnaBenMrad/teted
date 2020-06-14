var http = createRequestObject();
var objectId = '';

function createRequestObject(htmlObjectId){
    var obj;
    var browser = navigator.appName;
    objectId = htmlObjectId;
    if(browser == "Microsoft Internet Explorer"){
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else{
        obj = new XMLHttpRequest();
    }
    return obj;    
}


function sendReq(serverFileName, variableNames, variableValues) {
	
	var paramString = '';
			
	variableNames = variableNames.split(',');
	variableValues = variableValues.split(',');
	for(i=0; i<variableNames.length; i++) {
		paramString += variableNames[i]+'='+variableValues[i]+'&';
	}
	paramString = paramString.substring(0, (paramString.length-1));
	if (paramString.length == 0) {
	   	http.open('get', serverFileName);
	}
	else {
		http.open('get', serverFileName+'?'+paramString);
	}
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
	var errid ='';
	if(http.readyState == 4){
		responseText = http.responseText;
		res = responseText.split(':');
		//alert (res[0]);
		document.getElementById(res[0]).innerHTML = "&nbsp;&nbsp;"+res[1];
		document.getElementById("d"+res[0]).innerHTML = "&nbsp;&nbsp;"+res[2];		
    }
}

function sendReq1(serverFileName, variableNames, variableValues) {
	
	var paramString = '';
			
	variableNames = variableNames.split(',');
	variableValues = variableValues.split(',');
	for(i=0; i<variableNames.length; i++) {
		paramString += variableNames[i]+'='+variableValues[i]+'&';
	}
	paramString = paramString.substring(0, (paramString.length-1));
	if (paramString.length == 0) {
	   	http.open('get', serverFileName);
	}
	else {
		http.open('get', serverFileName+'?'+paramString);
	}
    http.onreadystatechange = handleResponse1;
    http.send(null);
}

function handleResponse1() {
	var errid ='';
	if(http.readyState == 4){
		responseText = http.responseText;
		res = responseText.split('|');
		nb = res.length;
		champ = nb-2;
		//alert (res);
		//alert(champ);
		for(i=0; i<champ; i=i+3)
		{
		//alert (res);
		document.getElementById(res[i]).innerHTML = "&nbsp;&nbsp;"+res[i+1];
		document.getElementById("d"+res[i]).innerHTML = "&nbsp;&nbsp;"+res[i+2];
		}
    }
}

