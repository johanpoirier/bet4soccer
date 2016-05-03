function saveTag(groupID) {
	var tag = document.getElementById('tag').value;
	var XHR = new XHRConnection();
	XHR.resetData();
	XHR.setRefreshArea("tags");
	XHR.appendData("act", "save_HTTP_tag");
	if (!groupID)
		groupID = '';
	XHR.appendData("groupID", groupID);
	XHR.appendData("text", tag);
	XHR.sendAndLoad("/", "POST");
	document.getElementById('tag').value = "";

	return false;
}

function delTag(tagID, groupID) {
	if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
		var XHR = new XHRConnection();
		XHR.resetData();
		XHR.setRefreshArea("tags");
		XHR.appendData("act", "del_HTTP_tag");
		XHR.appendData("tagID", tagID);
		if (!groupID)
			groupID = '';
		XHR.appendData("groupID", groupID);
		XHR.sendAndLoad("/", "POST");
	}
}

function getTags(groupID, startTag) {
	var XHR = new XHRConnection();
	XHR.resetData();
	XHR.setRefreshArea("tags");
	XHR.appendData("act", "get_HTTP_tags");
	if (!startTag) {
		startTag = 0;
	}
	XHR.appendData("start", startTag);
	if (!groupID) {
		groupID = '';
	}
	XHR.appendData("groupID", groupID);
	XHR.sendAndLoad("/", "POST");

	return false;
}

function selectListValue(id_liste, value) {
	$("#"+id_liste+" option[value='"+value+"']").attr('selected', 'selected');
}

function updateRanking(forced) {
	$('#update_ranking').html("En cours...");
	$.ajax({
		type: "GET",
		url: "/",
		data: "act=update_HTTP_ranking&forced="+forced,
		success: function(data) {
			handleUpdateRankingResponse(data);
		}
	});
}

function handleUpdateRankingResponse(results) {
	if(results == 'OKOK') {
		$('#update_ranking').html("<strong>Classement à jour.</strong>");
	}
	else if ((results == 'IN_PROGRESS') || (results == 'OKIN_PROGRESS')) {
		$('#update_ranking').html("<strong><a href=\"#\" onClick=\"updateRanking(1)\">Génération déjà en cours. Forcer ?</a></strong>");
	}
	else {
		$('#update_ranking').html("<strong>Erreur</strong>");
		alert(results);
	}
	
}

function updateStats() {
	$('#generate_stats').html("Génération en cours...");
	$.ajax({
		type: "GET",
		url: "/",
		data: "act=update_HTTP_stats",
		success: function(data) {
			handleUpdateStatsResponse(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus);
		}
	}); 
}

function handleUpdateStatsResponse() {
	$('#generate_stats').html("<strong>Statistiques à jour.</strong>");
}
