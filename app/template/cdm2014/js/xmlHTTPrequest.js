function getHTTPObject()
{
  var xmlhttp = false;

  /* Compilation conditionnelle d'IE */
  /*@cc_on
  @if (@_jscript_version >= 5)
     try
     {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
     }
     catch (e)
     {
        try
        {
           xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (E)
        {
           xmlhttp = false;
        }
     }
  @else
     xmlhttp = false;
  @end @*/

  /* on essaie de créer l'objet si ce n'est pas déjà fait */
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
  {
    var browser = navigator.appName;
     try
     {
        if (browser == "Microsoft Internet Explorer") {
            xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            xmlhttp = new XMLHttpRequest();
        }
     }
     catch (e)
     {
        xmlhttp = false;
     }
  }

  if (xmlhttp)
  {
     /* on définit ce qui doit se passer quand la page répondra */
     xmlhttp.onreadystatechange=function()
     {
        if (xmlhttp.readyState == 4) /* 4 : état "complete" */
        {
           if (xmlhttp.status == 200) /* 200 : code HTTP pour OK */
           {
              /*
              Traitement de la réponse.
              Ici on affiche la réponse dans une boîte de dialogue.
              */
              alert(xmlhttp.responseText);
           }
        }
     }
  }
  return xmlhttp;
}
