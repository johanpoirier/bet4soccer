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

function showTooltip(x, y, contents) {
	$("<div id='tooltip'>" + contents + "</div>").css({
		position: "absolute",
		display: "none",
		top: y - 30,
		left: x - 10,
		border: "1px solid #fdd",
		padding: "2px",
		"background-color": "#fee",
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}

function globalInit() {
	$('.logo').click(function () {
		window.location.assign('/');
	});

	$('.nextGame').on('mouseover', function () {
		var el = $(this);
		$.ajax({
			type: 'GET',
			url: '/',
			data: 'act=view_match_stats&matchID=' + el.data('game-id'),
			success: function(data) {
				$('.nextGameCard').html(data);
			}
		});
	});
}

function headlineButtonsInit() {
	$('button.headline-button.phase').click(function (el) {
		window.location.assign('/?act=' + $(el.target).data('value'));
	});
	$('button.headline-button.order').click(function (el) {
		var btn = $(el.target);
		window.location.assign('/?act=bets&match_display=' + btn.data('value') + '&user=' + btn.data('user'));
	});
}

