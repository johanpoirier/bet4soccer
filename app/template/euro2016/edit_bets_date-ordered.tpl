<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
    var xmlhttp = getHTTPObject();
    var k = 0;
    var queue = new Array();
    var inProgress = false;

    function debug(str) {
        document.getElementById('debug').innerHTML += str + "<br/>";
    }

    function saveBet(score, matchID, team) {
        if (!inProgress) {
            inProgress = true;
            xmlhttp.open("GET", "/?act=save_HTTP_bet&userID={CURRENT_USER_ID}&matchID=" + matchID + "&team=" + team + "&score=" + score + "&j=" + k, true);
            xmlhttp.onreadystatechange = handleHttpResponse;
            xmlhttp.send(null);
        } else {
            queue.push(score + '|' + matchID + '|' + team + '|' + k);
        }
        k++;
    }

    function handleHttpResponse() {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                var results = xmlhttp.responseText.split("|");
                var matchID = results[0];
                var pool = results[1];
                var num = results[2];
                var scoreA = document.getElementById(matchID + '_score_team_A').value;
                var scoreB = document.getElementById(matchID + '_score_team_B').value;
                var teamA = document.getElementById(matchID + '_team_A');
                var teamB = document.getElementById(matchID + '_team_B');

                teamA.style.backgroundColor = 'transparent';
                teamB.style.backgroundColor = 'transparent';

                if (scoreA > scoreB) teamA.style.backgroundColor = '#99FF99';
                if (scoreA < scoreB) teamB.style.backgroundColor = '#99FF99';

                var HTML_ranking = "<table style=\"font-size:9px;\">";
                HTML_ranking += "<tr>";
                HTML_ranking += "<td width=\"80%\"><b>Nations</b></td><td width=\"10%\"><b>Pts</b></td><td width=\"10%\"><b>Diff</b></td>";
                HTML_ranking += "</tr>";

                for (i = 3; i < results.length - 1; i++) {
                    var result = results[i].split(";");

                    HTML_ranking += "<tr>";
                    HTML_ranking += "<td id=\"" + result[0] + "_team\"><img width=\"15px\" src=\"{TPL_WEB_PATH}/images/flag/" + result[2] + ".png\" /> " + result[1] + "</td>";
                    HTML_ranking += "<td>" + result[3] + "</td>";
                    HTML_ranking += "<td>" + result[4] + "</td>";
                    HTML_ranking += "</tr>";
                }

                var team_ranking = document.getElementById("pool_" + pool + "_ranking");
                team_ranking.innerHTML = HTML_ranking;
            }

            if (queue.length > 0) {
                request = queue.shift().split("|");
                xmlhttp.open("GET", "/?act=save_HTTP_bet&userID={USERID}&matchID=" + request[1] + "&team=" + request[2] + "&score=" + request[0] + "&j=" + request[3], true);
                xmlhttp.onreadystatechange = handleHttpResponse;
                xmlhttp.send(null);
            } else inProgress = false;
        }
    }
    function changePhase(action) {
        window.location.href = "?act=" + action + "";
    }

</script>
<div id="mainarea">
    <div class="maincontent">
        <div id="headline" style="height:20px"><h1 style="float: left;">Pronostics de {CURRENT_USER}</h1><select
                    class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
                <option selected="selected" value="bets">Poules</option>
                <option value="finals_bets">Phase finale</option>
            </select></div>
        <div id="update_ranking" class="headline" style="color:red;text-align:right;"><a
                    href="/?act=bets&match_display=pool">Classer par poule</a></div>
    </div>
	
	<div class="maincontent">
		<!-- BEGIN stats -->
		<div style="text-align:center;width:50%;float:left;"><br/><strong>{stats.TYPE}</strong></div>
		<!-- END stats -->
		<!-- BEGIN stats -->
		<div class="stats" id="stats_{stats.ID}" style="height: 120px;width:50%;float:left;"></div>
		<script type="text/javascript">
			var data = {stats.DATA};
			$.plot("#stats_{stats.ID}", data,
			{
				colors: [ "{stats.COLOR}" ],
				xaxis: {
					ticks: {stats.XSERIE}
				},
				yaxis: {
					min: {stats.YMIN},
					max: {stats.YMAX},
					{stats.INVERSE}
					ticks: {stats.YTICKS},
					tickDecimals: 0
				},
				grid: {
					backgroundColor: "#ffffff",
					hoverable: true
				}
			});

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

			var previousPoint = null;
			$("#stats_{stats.ID}").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.dataIndex) {

						previousPoint = item.dataIndex;

						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
								y = item.datapoint[1].toFixed(2);

						showTooltip(item.pageX, item.pageY, parseInt(y));
					}
				} else {
					$("#tooltip").remove();
					previousPoint = null;
				}
			});
		</script>
		<!-- END stats -->
	</div>
	
    <form name="save_bets" action="/?act=save_bets" method="post">

        <div class="maincontent">

            <div class="tag_cloud">

                <table width="100%">
                    <!-- BEGIN bets -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- BEGIN view -->
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            <i>{bets.view.DATE}</i></td>
                    </tr>
                    <tr>
                        <td><i>({bets.view.POOL})</i></td>
                        <td id="{bets.view.ID}_team_A" width="35%" rowspan="2"
                            style="text-align:right;background-color:{bets.view.TEAM_COLOR_A};">
                            {bets.view.TEAM_NAME_A}
                            <img src="{TPL_WEB_PATH}images/flag/{bets.view.TEAM_NAME_A_URL}.png"/>
                        </td>
                        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                            {bets.view.SCORE_A}</td>
                        <td width="10%" style="text-align:center;font-weight:300;font-size:9px;color:{bets.view.COLOR};"
                            rowspan="2" colspan="2">
                            {bets.view.POINTS}<br/>
                            <span style="color:black;">{bets.view.DIFF}</span>
                        </td>
                        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                            {bets.view.SCORE_B}</td>
                        <td id="{bets.view.ID}_team_B" width="35%" rowspan="2"
                            style="text-align:left;background-color:{bets.view.TEAM_COLOR_B};">
                            <img src="{TPL_WEB_PATH}images/flag/{bets.view.TEAM_NAME_B_URL}.png"/>
                            {bets.view.TEAM_NAME_B}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                            {bets.view.RESULT_A}</td>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                            {bets.view.RESULT_B}</td>
                        <td></td>
                    </tr>
                    <!-- END view -->
                    <!-- BEGIN edit -->
                    <tr>
                        <td colspan="7" style="text-align:center;"><i>{bets.edit.DATE}</i></td>
                    </tr>
                    <tr>
                        <td><i>({bets.edit.POOL})</i></td>
                        <td id="{bets.edit.ID}_team_A" width="35%" style="text-align:right;background-color:{bets.edit.TEAM_COLOR_A};">
							<div class="team">
								{bets.edit.TEAM_NAME_A}
								<img src="{TPL_WEB_PATH}images/flag/{bets.edit.TEAM_NAME_A_URL}.png"/>
								<span class="fifaRankTip">Classement FIFA : {bets.edit.TEAM_RANK_A}</span>
							</div>
                        </td>
                        <td style="text-align:right;padding-right:10px;" colspan="2">
                            <input type="number" min="0"
                                                                                            max="99" size="2"
                                                                                            id="{bets.edit.ID}_score_team_A"
                                                                                            name="{bets.edit.ID}_score_team_A"
                                                                                            value="{bets.edit.SCORE_A}"
                                                                                            onChange="saveBet(this.value,{bets.edit.ID},'A')"
                                                                                            onKeyUp="saveBet(this.value,{bets.edit.ID},'A')" {bets.edit.DISABLED}/>
                        </td>
                        <td style="text-align:left;padding-left:10px;" colspan="2">
                            <input type="number" min="0" max="99"
                                                                                          size="2"
                                                                                          id="{bets.edit.ID}_score_team_B"
                                                                                          name="{bets.edit.ID}_score_team_B"
                                                                                          value="{bets.edit.SCORE_B}"
                                                                                          onChange="saveBet(this.value,{bets.edit.ID},'B')"
                                                                                          onKeyUp="saveBet(this.value,{bets.edit.ID},'B')" {bets.edit.DISABLED}/>
                        </td>
                        <td id="{bets.edit.ID}_team_B" width="35%" style="text-align:left;background-color:{bets.edit.TEAM_COLOR_B};">
							<div class="team">
								<img src="{TPL_WEB_PATH}images/flag/{bets.edit.TEAM_NAME_B_URL}.png"/>
								{bets.edit.TEAM_NAME_B}
								<span class="fifaRankTip">Classement FIFA : {bets.edit.TEAM_RANK_B}</span>
							</div>
                        </td>
                    </tr>
                    <!-- END edit -->
                    <!-- END bets -->
                </table>

            </div>
        </div>

        <!-- BEGIN pools -->
        <div id="rightcolumn">

            <div class="tag_cloud">

                <div class="rightcolumn_headline"><h1>Groupe {pools.POOL}</h1></div>

                <div id="pool_{pools.POOL}_ranking">
                    <table style="font-size:9px;">
                        <tr>
                            <td width="80%"><b>Nations</b></td>
                            <td width="10%"><b>Pts</b></td>
                            <td width="10%"><b>Diff</b></td>
                        </tr>
                        <!-- BEGIN teams -->
                        <tr>
                            <td id="{pools.teams.ID}_team"><img width="15px"
                                                                src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png"/> {pools.teams.NAME}
                            </td>
                            <td>{pools.teams.POINTS}</td>
                            <td>{pools.teams.DIFF}</td>
                        </tr>
                        <!-- END teams -->
                    </table>
                </div>

            </div>

        </div>
        <!-- END pools -->

        <div class="maincontent" style="text-align:center;">
            <p><input type="image" name="submit" {SUBMIT_STATE} src="{TPL_WEB_PATH}/images/submit.gif"/></p>
        </div>
    </form>
</div>
