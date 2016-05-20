<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
    function saveBet(score, matchID, team) {
        var data = {
            'act': 'save_HTTP_bet',
            'userID': {CURRENT_USER_ID},
            'matchID': matchID,
            'team': team,
            'score': score
        };
        $.ajax({
            'type': 'POST',
            'url': '/',
            'data': arrayToDataQuery(data),
            'success': handleHttpResponse
        });
    }

    function handleHttpResponse(data) {
        var results = data.split("|");
        var matchID = results[0];
        var pool = results[1];
        var scoreA = document.getElementById(matchID + '_score_team_A').value;
        var scoreB = document.getElementById(matchID + '_score_team_B').value;
        var teamA = document.getElementById(matchID + '_team_A');
        var teamB = document.getElementById(matchID + '_team_B');

        teamA.style.backgroundColor = 'transparent';
        teamB.style.backgroundColor = 'transparent';

        if (scoreA > scoreB) {
            teamA.style.backgroundColor = '#99FF99';
        }
        else if (scoreA < scoreB) {
            teamB.style.backgroundColor = '#99FF99';
        }

        var HTML_ranking = '<table class="ranking-pool">';
        HTML_ranking += "<tr>";
        HTML_ranking += "<td width=\"80%\"><b>Nations</b></td><td width=\"10%\"><b>Pts</b></td><td width=\"10%\"><b>Diff</b></td>";
        HTML_ranking += "</tr>";

        for (var i = 2; i < results.length - 1; i++) {
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

    $(document).ready(headlineButtonsInit);
</script>

<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Pronostics de {CURRENT_USER}</h1>
            </div>
			<div class="headline-menu">
                <button class="headline-button phase" data-value="finals_bets">Phase finale</button>
                <button class="headline-button order" data-value="date"><i class="icon-sort-number-up"></i> Trier par date</button>
            </div>
        </div>

        <div class="tag_cloud user-infos">
            <!-- BEGIN stats -->
            <div class="user-stats">
                <h4 class="user-stats-title">{stats.TYPE}</h4>
                <div class="user-stats-chart" id="stats_{stats.ID}"></div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    if ($('.user-infos').css('display') !== 'none') {
                        $.plot("#stats_{stats.ID}", {stats.DATA}, {
                            colors: ["{stats.COLOR}"],
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

                        var previousPoint = null;
                        $("#stats_{stats.ID}").bind("plothover", function (event, pos, item) {
                            if (item) {
                                if (previousPoint != item.dataIndex) {
                                    previousPoint = item.dataIndex;

                                    $("#tooltip").remove();
                                    var x = item.datapoint[0].toFixed(2);
                                    var y = item.datapoint[1].toFixed(2);

                                    showTooltip(item.pageX, item.pageY, parseInt(y));
                                }
                            } else {
                                $("#tooltip").remove();
                                previousPoint = null;
                            }
                        });
                    }
                });
            </script>
            <!-- END stats -->
        </div>

        <form name="save_bets" action="/?act=save_bets" method="post">
            <!-- BEGIN pools -->
            <div class="tag_cloud">
                <span style="font-size: 150%">Groupe {pools.POOL}</span>

                <table width="100%">
                    <!-- BEGIN bets -->
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- BEGIN view -->
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            <i>{pools.bets.view.DATE}</i></td>
                    </tr>
                    <tr>
                        <td id="{pools.bets.view.ID}_team_A" width="35%" rowspan="2"
                            style="text-align:right;background-color:{pools.bets.view.TEAM_COLOR_A};">
                            {pools.bets.view.TEAM_NAME_A}
                            <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_A_URL}.png"/>
                        </td>
                        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                            {pools.bets.view.SCORE_A}
                        </td>
                        <td width="10%"
                            style="text-align:center;font-weight:300;font-size:9px;color:{pools.bets.view.COLOR};"
                            rowspan="2" colspan="2">
                            {pools.bets.view.POINTS}<br/>
                            <span style="color:black;">{pools.bets.view.DIFF}</span>
                        </td>
                        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                            {pools.bets.view.SCORE_B}
                        </td>
                        <td id="{pools.bets.view.ID}_team_B" width="35%" rowspan="2"
                            style="text-align:left;background-color:{pools.bets.view.TEAM_COLOR_B};">
                            <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_B_URL}.png"/>
                            {pools.bets.view.TEAM_NAME_B}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                            {pools.bets.view.RESULT_A}
                        </td>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                            {pools.bets.view.RESULT_B}
                        </td>
                    </tr>
                    <!-- END view -->
                    <!-- BEGIN edit -->
                    <tr>
                        <td colspan="6" class="game-date">{pools.bets.edit.DATE}</td>
                    </tr>
                    <tr>
                        <td id="{pools.bets.edit.ID}_team_A" width="35%"
                            style="text-align:right;background-color:{pools.bets.edit.TEAM_COLOR_A};">
                            <div class="team">
                                {pools.bets.edit.TEAM_NAME_A}
                                <img src="{TPL_WEB_PATH}images/flag/{pools.bets.edit.TEAM_NAME_A_URL}.png"/>
                                <span class="fifaRankTip">Classement FIFA : {pools.bets.edit.TEAM_RANK_A}</span>
                            </div>
                        </td>
                        <td style="text-align:right;padding-right:10px;" colspan="2">
                            <input type="number" min="0" max="99" size="2"
                                   id="{pools.bets.edit.ID}_score_team_A"
                                   name="{pools.bets.edit.ID}_score_team_A"
                                   value="{pools.bets.edit.SCORE_A}"
                                   onChange="saveBet(this.value,{pools.bets.edit.ID},'A')"
                                   onKeyUp="saveBet(this.value,{pools.bets.edit.ID},'A')" {pools.bets.edit.DISABLED}/>
                        </td>
                        <td style="text-align:left;padding-left:10px;" colspan="2">
                            <input type="number" min="0" max="99"
                                   size="2"
                                   id="{pools.bets.edit.ID}_score_team_B"
                                   name="{pools.bets.edit.ID}_score_team_B"
                                   value="{pools.bets.edit.SCORE_B}"
                                   onChange="saveBet(this.value,{pools.bets.edit.ID},'B')"
                                   onKeyUp="saveBet(this.value,{pools.bets.edit.ID},'B')" {pools.bets.edit.DISABLED}/>
                        </td>
                        <td id="{pools.bets.edit.ID}_team_B" width="35%"
                            style="text-align:left;background-color:{pools.bets.edit.TEAM_COLOR_B};">
                            <div class="team">
                                <img src="{TPL_WEB_PATH}images/flag/{pools.bets.edit.TEAM_NAME_B_URL}.png"/>
                                {pools.bets.edit.TEAM_NAME_B}
                                <span class="fifaRankTip">Classement FIFA : {pools.bets.edit.TEAM_RANK_B}</span>
                            </div>
                        </td>
                    </tr>
                    <!-- END edit -->
                    <!-- END bets -->
                </table>

            </div>
            <!-- END pools -->

            <div class="form-submit-zone">
                <input type="submit" value="Valider" />
            </div>
        </form>
    </div>

    <aside>
        <div class="headline">
            <div class="headline-title">
                <h2>Classements virtuels</h2>
            </div>
        </div>
        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <div><h3>Groupe {pools.POOL}</h3></div>
            <div id="pool_{pools.POOL}_ranking">
                <table class="ranking-pool">
                    <tr>
                        <td width="80%"><b>Nations</b></td>
                        <td width="10%"><b>Pts</b></td>
                        <td width="10%"><b>Diff</b></td>
                    </tr>
                    <!-- BEGIN teams -->
                    <tr>
                        <td id="{pools.teams.ID}_team">
                            <img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png"/>
                            {pools.teams.NAME}
                        </td>
                        <td>{pools.teams.POINTS}</td>
                        <td>{pools.teams.DIFF}</td>
                    </tr>
                    <!-- END teams -->
                </table>
            </div>
        </div>
        <!-- END pools -->

    </aside>
</section>
