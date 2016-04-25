	document.onload = PlaceDiv(0,50,15,15);
	document.onresize = PlaceDiv(0,50,15,15);
	
	function PlaceDiv(deb,fin,mv,mh)
/*
	Fonction placant automatiquement sur une page une gallerie de divs de différentes tailles.
	Le placement évite d'afficher la scrollbar verticale et s'adapte à la taille de la fenetre cliente.
	Les élements sont centrés horizontalement et verticalement sur chaque ligne.
	
	Script réalisé par Kitof <kitof@kitof.com> le 15/03/2003.
	
	Ce script peut être librement utilisé, mais merci de m'envoyer un petit mail pour m'en informer. =)
*/
{

	
	/* Valeurs de base determinant le point initial superieur de la gallerie */
	var initTop=250;
	var initLeft=202;
	
	/* Marges minimales séparant chaque div */
	var margeVerticale=mv;
	var margeHorizontale=mh;
	
	/* Variables locales */
	var divTop=initTop;			// Valeur de l'attribut Top du prochain Div
	var divLeft=initLeft;		// Valeur de l'attribut Left du prochain Div
	var maxHautDiv=0;			// Hauteur du Div le plus haut de la ligne courante
	var largDiv=0;				// Largeur du Div courant
	var hautDiv=0;				// Hauteur du Div courant

	var largMax = parseInt(document.getElementById('content').offsetWidth);	// Largeur Max Disponible (via la div parent)
	var reste = largMax;		// Largeur en pixel encore disponible sur la ligne courante
	var nbImgLig = 0;			// Nb de div sur la ligne courante
	
	var i,j;

	document.getElementById('navigBarBottom').style.visibility = 'hidden';	// On cache la barre de navigation inferieure

	// Pour tous les div ...
	for(i=deb;i<fin;i++)
	{
		idDiv = "img"+i;	// Reconstitution de l'ID de la div courante
		
		// Recupération des dimensions de la div
		largDiv = parseInt(document.getElementById(idDiv).offsetWidth);
		hautDiv = parseInt(document.getElementById(idDiv).offsetHeight);
		
		// S'il ne reste plus de place sur la ligne courante ...
		if(reste < largDiv)
		{
			// On calcule la marge suplémentaire disponible pour chaque élément de la ligne courante
			addMarge = reste / (nbImgLig + 1);
			
			// Et pour tous les élements de la ligne déja placés ...
			for(j=(i-nbImgLig),indLig=1; j < i; j++,indLig++)
			{
				idDiv2 = "img" + j;
				// ... on recentre horizontalement
				document.getElementById(idDiv2).style.left = parseInt(document.getElementById(idDiv2).style.left) + (addMarge*indLig);
				
				// ... et verticalement relativement à l'élement le plus haut
				hautDiv2 = parseInt(document.getElementById(idDiv2).offsetHeight);
				document.getElementById(idDiv2).style.top = parseInt(document.getElementById(idDiv2).style.top) + ((maxHautDiv-hautDiv2)/2);
				
				// ... on rend visible la div ainsi placée définitivement
				document.getElementById(idDiv2).style.visibility = 'visible';
			}
			
			// On initialise les valeurs de la ligne courante
			reste = largMax;
			divLeft = initLeft;
			// On décale vers le bas la nouvelle ligne d'un nombre de pixels correspondant à la div la plus grande de la ligne supérieure			
			divTop = divTop + maxHautDiv + margeVerticale;	
			maxHautDiv = 0;
			nbImgLig = 0;
		}
		
		// Recherche du Maximum de la ligne courante
		if(hautDiv>maxHautDiv) maxHautDiv=hautDiv;
		
		// On cache la div
		document.getElementById(idDiv).style.visibility = 'hidden';
		// Et on lui affecte des valeurs provisoires (avant recentrage en fin de ligne)	
		document.getElementById(idDiv).style.top = divTop;
		document.getElementById(idDiv).style.left = divLeft;
		
		// On incrémente le pointeur horizontale
		divLeft = divLeft + largDiv + margeHorizontale;
		// On calcule le nouveau reste
		reste = reste - largDiv - margeHorizontale;
		// Et on incrémente le nb de div de la ligne courante
		nbImgLig++;
	}
	// En fin de boucle on recentre les div de la derniere ligne avec l'espace restant
	addMarge = reste / (nbImgLig + 1);
	
	for(j=(i-(nbImgLig)),indLig=1; j < i; j++,indLig++)
	{
		idDiv2 = "img" + j;
		document.getElementById(idDiv2).style.left = parseInt(document.getElementById(idDiv2).style.left) + (addMarge*indLig);
		hautDiv2 = parseInt(document.getElementById(idDiv2).offsetHeight);
		document.getElementById(idDiv2).style.top = parseInt(document.getElementById(idDiv2).style.top) + ((maxHautDiv-hautDiv2)/2);
		document.getElementById(idDiv2).style.visibility = 'visible';
	}
	
	// On affiche la barre de navigation inférieure
	largDiv = parseInt(document.getElementById('navigBarBottom').offsetWidth);
	hautDiv = parseInt(document.getElementById('navigBarBottom').offsetHeight);
	addMarge = (largMax - largDiv) / 2;
	divTop = divTop + maxHautDiv + margeVerticale;
	document.getElementById('navigBarBottom').style.left = initLeft + addMarge;
	document.getElementById('navigBarBottom').style.top = divTop;
	document.getElementById('navigBarBottom').style.visibility = 'visible';
	
	
	hautDiv = (divTop - initTop) + hautDiv;
	hautMenu = parseInt(document.getElementById('menu').offsetHeight);
	if (hautDiv < hautMenu) hautDiv = hautMenu+20;
	document.getElementById('content').style.height = hautDiv;
}
