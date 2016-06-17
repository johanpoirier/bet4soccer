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

        if (scoreA > scoreB) teamA.style.backgroundColor = '#99FF99';
        if (scoreA < scoreB) teamB.style.backgroundColor = '#99FF99';

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
                <h1>{PAGE_TITLE}</h1>
            </div>
            <div class="headline-menu">
                <button class="headline-button phase" data-value="finals_bets"><i class="icon-final"></i>Phase finale</button>
                <button class="headline-button order" data-value="pool" data-user="{CURRENT_USER_ID}"><i class="icon-sort-name-up"></i> Trier par poule</button>
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
                        displayChart({stats.ID}, {stats.DATA}, '{stats.COLOR}', {stats.XSERIE}, {stats.YTICKS}, {stats.YMIN}, {stats.YMAX}, {stats.TRANSFORM}, {stats.INVERSE_TRANSFORM});
                    });
                });
            </script>
            <!-- END stats -->
        </div>

        <div class="tag_cloud uniq">
            <form name="save_bets" action="/?act=save_bets" method="post">
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
                        <td width="10%"
                            style="text-align:center;font-weight:300;font-size:9px;color:{bets.view.COLOR};"
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
                        <td></td>
                        <td colspan="6" class="game-date">{bets.edit.DATE}</td>
                    </tr>
                    <tr>
                        <td><i>({bets.edit.POOL})</i></td>
                        <td id="{bets.edit.ID}_team_A" width="35%"
                            style="text-align:right;background-color:{bets.edit.TEAM_COLOR_A};">
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
                        <td id="{bets.edit.ID}_team_B" width="35%"
                            style="text-align:left;background-color:{bets.edit.TEAM_COLOR_B};">
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

                <div class="form-submit-zone">
                    <input type="submit" value="Valider" />
                </div>
            </form>
        </div>
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
        <!-- END pools -->
    </aside>

</section>