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

function selectListValue(id_list, value) {
	$('#' + id_list).val(value);
}

function updateRanking(forced) {
	$('.update-ranking').html('En cours...');
	$.ajax({
		type: 'GET',
		url: '/',
		data: 'act=update_HTTP_ranking&forced=' + forced,
		success: function(data) {
			handleUpdateRankingResponse(data);
		}
	});
}

function handleUpdateRankingResponse(results) {
	if(results == 'OKOK') {
		$('.update-ranking').html('Classement à jour.');
	}
	else if ((results == 'IN_PROGRESS') || (results == 'OKIN_PROGRESS')) {
		$('.update-ranking button').attr('onclick', 'updateRanking(1)').html('Génération déjà en cours. Forcer ?');
	}
	else {
		$('.update-ranking').html('Erreur !');
		alert(results);
	}
	
}

function updateStats() {
	$('.update-stats button').html("Génération en cours...");
	$.ajax({
		type: 'GET',
		url: '/',
		data: 'act=update_HTTP_stats',
		success: function(data) {
			handleUpdateStatsResponse(data);
		},
		error: function(XMLHttpRequest, textStatus) {
			alert(textStatus);
		}
	});
}

function handleUpdateStatsResponse() {
	$('.update-stats').html('Stats à jour.');
}

function globalInit(isPublic) {
	$('.logo').click(function () {
		window.location.assign('/');
	});

	if (window.localStorage) {
		var uuid = getUuid();
		if (uuid === null) {
			window.localStorage.setItem('uuid', generateUuid());
		}
	}

	if (isPublic !== true) {
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
}

function headlineButtonsInit() {
	$('button.headline-button.phase').click(function (el) {
		var btn = $(el.target);
		var url = '/?act=' + btn.data('value');
		if (btn.data('user')) {
			url += '&user=' + btn.data('user');
		}
		window.location.assign(url);
	});
	$('button.headline-button.order').click(function (el) {
		var btn = $(el.target);
		var url = '/?act=bets&match_display=' + btn.data('value');
		if (btn.data('user')) {
			url += '&user=' + btn.data('user');
		}
		window.location.assign(url);
	});
}

function generateUuid() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	});
}

function getUuid() {
	if (window.localStorage) {
		return window.localStorage.getItem('uuid');
	}
	return null;
}

function getUrlArgValue(argName) {
	var regexp = new RegExp(argName + '=(\\w*)');
	var value = regexp.exec(window.location.search);
	return value && value.length > 1 ? value[1]: null;
}

function showTooltip(x, y, contents) {
	$('<div class="tooltip">' + contents + '</div>').css({
		top: y - 30,
		left: x - 10
	}).appendTo('body').fadeIn(200);
}

function displayChart(id, data, style, color, xSerie, yTicks, yMin, yMax, transformFunc, inverseTransformFunc) {
	var elId = '#stats_' + id;
	var options = {
		colors: [ color ],
		xaxis: {
			ticks: xSerie
		},
		yaxis: {
			min: yMin,
			max: yMax,
			ticks: yTicks,
			tickDecimals: 0
		},
		grid: {
			backgroundColor: "#ffffff",
			hoverable: true
		}
	};
	if (style === "bar") {
		options.series = {
			lines: {
				show: false
			},
			bars: {
				show: true,
				barWidth: 1,
				align: 'left'
			}
		}
	}
	if (transformFunc) {
		options.yaxis.transform = transformFunc;
	}
	if (inverseTransformFunc) {
		options.yaxis.inverseTransform = inverseTransformFunc;
	}
	$.plot(elId, data, options);

	var previousPoint = null;
	$(elId).bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$('.tooltip').remove();
				var y = item.datapoint[1].toFixed(2);

				showTooltip(parseInt(item.pageX, 10), parseInt(item.pageY, 10) - 3, parseInt(y, 10));
			}
		} else {
			$('.tooltip').remove();
			previousPoint = null;
		}
	});
}