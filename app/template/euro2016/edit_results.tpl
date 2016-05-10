<script type="text/javascript">
    function saveResult(score, matchID, team) {
        var data = {
            'act': 'save_HTTP_result',
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
        if (scoreA < scoreB) {
            teamB.style.backgroundColor = '#99FF99';
        }

        var HTML_ranking = '<table class="ranking-pool">';
        HTML_ranking += '<tr>';
        HTML_ranking += '<td width="80%"><strong>Nations</strong></td><td width="10%"><strong>Pts</strong></td><td width="10%"><strong>Diff</strong></td>';
        HTML_ranking += '</tr>';

        for (var i = 2; i < results.length - 1; i++) {
            var result = results[i].split(';');

            HTML_ranking += '<tr>';
            HTML_ranking += '<td id="' + result[0] + '_team"><img width="15px" src="{TPL_WEB_PATH}/images/flag/' + result[2] + '.png" /> ' + result[1] + '</td>';
            HTML_ranking += '<td>' + result[3] + '</td>';
            HTML_ranking += '<td>' + result[4] + '</td>';
            HTML_ranking += '</tr>';
        }

        var team_ranking = document.getElementById("pool_" + pool + "_ranking");
        team_ranking.innerHTML = HTML_ranking;
        document.getElementById('update_ranking').innerHTML = '<a href="#" onClick="updateRanking(0)"><strong>Classement obsoléte.</strong></a>';
    }

    function changePhase(action) {
        window.location.assign('/?act=' + action);
    }
</script>

<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Résultats</h1>
            </div>
            <div class="headline-menu">
                <select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
                    <option selected="selected" value="edit_results">Poules</option>
                    <option value="edit_finals_results">Phase finale</option>
                </select>
                <span id="update_ranking">{UPDATE_RANK_LINK}</span>
                <a href="#" onclick="updateStats()">Générer les stats.</a>
            </div>
        </div>

        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <span style="font-size: 150%">Groupe {pools.POOL}</span>
            <table width="100%">
                <!-- BEGIN matches -->
                <tr>
                    <td colspan="4" style="text-align:center;"><i>{pools.matches.DATE}</i></td>
                </tr>
                <tr>
                    <td id="{pools.matches.ID}_team_A" width="35%"
                        style="text-align:right;background-color:{pools.matches.TEAM_COLOR_A};">{pools.matches.TEAM_NAME_A}
                        <img src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_A_URL}.png"/></td>
                    <td width="15%" style="text-align:right;"><input type="number" min="0" max="99" size="2"
                                                                     id="{pools.matches.ID}_score_team_A"
                                                                     value="{pools.matches.SCORE_A}"
                                                                     onChange="saveResult(this.value,{pools.matches.ID},'A')"
                                                                     onKeyUp="saveResult(this.value,{pools.matches.ID},'A')"/>
                    </td>
                    <td width="15%" style="text-align:left;"><input type="number" min="0" max="99" size="2"
                                                                    id="{pools.matches.ID}_score_team_B"
                                                                    value="{pools.matches.SCORE_B}"
                                                                    onChange="saveResult(this.value,{pools.matches.ID},'B')"
                                                                    onKeyUp="saveResult(this.value,{pools.matches.ID},'B')">
                    </td>
                    <td id="{pools.matches.ID}_team_B" width="35%"
                        style="text-align:left;background-color:{pools.matches.TEAM_COLOR_B};"><img
                                src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_B_URL}.png"/> {pools.matches.TEAM_NAME_B}
                    </td>
                </tr>
                <!-- END matches -->
            </table>
        </div>
        <!-- END pools -->
    </div>

    <aside>
        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h3>Groupe {pools.POOL}</h3></div>
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
