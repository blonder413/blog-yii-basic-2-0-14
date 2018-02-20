/* Desarrollado por www.cesarcancino.com */
function obtiene_http_request(){
	var req = false;
	try
	  {
	    req = new XMLHttpRequest(); /* p.e. Firefox */
	  }
	catch(err1)
	  {
	  try
	    {
	     req = new ActiveXObject("Msxml2.XMLHTTP");
	  /* algunas versiones IE */
	    }
	  catch(err2)
	    {
	    try
	      {
	       req = new ActiveXObject("Microsoft.XMLHTTP");
	  /* algunas versiones IE */
	      }
	      catch(err3)
	        {
	         req = false;
	        }
	    }
	  }
	return req;
}

var miPeticion = obtiene_http_request();

//*******************************************************************************
function ajax(ide,url){
	//ponemos true para que la petición sea asincrónica
	miPeticion.open("GET",url,true);
		
	//ahora procesamos la información enviada
	miPeticion.onreadystatechange=miPeticion.onreadystatechange = function(){
		//alert("ready_State="+miPeticion.readyState);
	    if (miPeticion.readyState==4){
	    	//alert(miPeticion.readyState);
	    	//alert("status ="+miPeticion.status);
	    	if (miPeticion.status==200){
	    		//alert(miPeticion.status);
	    		//var http=miPeticion.responseXML;
	    		//alert("http="+http);
	    		var http=miPeticion.responseText;
	    		document.getElementById(ide).innerHTML= http;
	    	}
		}
	}
	miPeticion.send(null);
}