function arrayToDataQuery(data) {
	return Object.keys(data).map(function(key) {
		return key + "=" + encodeURIComponent(data[key]);
	}).join('&');
}

function saveTag(groupID) {
	var data = {
		'act': 'save_HTTP_tag',
		'text': document.getElementById('tag').value,
		'groupID': groupID || ''
	};
	$.ajax({
		type: 'POST',
		url: '/',
		data: arrayToDataQuery(data),
		success: function() {
			getTags(groupID);
			$('#tag').val('');
		}
	});

	return false;
}

function delTag(tagID, groupID) {
	if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
		var data = {
			'act': 'del_HTTP_tag',
			'tagID': tagID,
			'groupID': groupID || ''
		};
		$.ajax({
			type: 'POST',
			url: '/',
			data: arrayToDataQuery(data),
			success: function() {
				getTags(groupID);
			}
		});
	}
}

function getTags(groupID, startTag) {
	if (typeof groupID === 'function') {
		groupID = '';
	}
	var data = {
		'act': 'get_HTTP_tags',
		'startTag': startTag || 0,
		'groupID': groupID || ''
	};
	$.ajax({
		type: 'GET',
		url: '/',
		data: arrayToDataQuery(data),
		success: function(data) {
			$('#tags').html(data);
		}
	});
}

function selectListValue(id_liste, value) {
	$('#' + id_liste).val(value);
}

function updateRanking(forced) {
	$('#update_ranking').html('En cours...');
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
