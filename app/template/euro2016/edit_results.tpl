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

    function handleHttpResponse(response) {
        var matchID = response.matchID;
        var pool = response.pool;
        var scoreA = $('#' + matchID + '_score_team_A').val();
        var scoreB = $('#' + matchID + '_score_team_B').val();
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

        var rankingTableBody = $('#pool_' + pool + '_ranking tbody');
        rankingTableBody.html('');

        for (var i = 0; i < response.teams.length; i++) {
            var team = response.teams[i];

            var line = $('<tr>');
            line.append($('<td>').attr('id', team.teamID).append('<img width="15px" src="{TPL_WEB_PATH}/images/flag/' + team.name + '.png" alt="' + team.name + '" />' + team.name));
            line.append($('<td>').html(team.points));
            line.append($('<td>').html((team.diff > 0 ? '+' : '') + team.diff));

            rankingTableBody.append(line);
        }

        $('.update-ranking').html('<button onclick="updateRanking(0)">Classement obsolète.</button>');
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
                <span class="update-ranking">{UPDATE_RANK_LINK}</span>
                <span class="update-stats"><button onclick="updateStats()">Générer les stats</button></span>
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
                        <img src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_A}.png"/></td>
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
                                src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_B}.png"/> {pools.matches.TEAM_NAME_B}
                    </td>
                </tr>
                <!-- END matches -->
            </table>
        </div>
        <!-- END pools -->
    </div>

    <aside>
        <div class="headline">
            <div class="headline-title">
                <h2>Classements</h2>
            </div>
        </div>
        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h3>Groupe {pools.POOL}</h3></div>
            <div id="pool_{pools.POOL}_ranking">
                <table class="ranking-pool">
                    <thead>
                        <tr>
                            <th width="80%"><b>Nations</b></th>
                            <th width="10%"><b>Pts</b></th>
                            <th width="10%"><b>Diff</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- BEGIN teams -->
                        <tr class="ranking-pool-team">
                            <td id="{pools.teams.ID}_team">
                                <img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME}.png" alt="{pools.teams.NAME}" /> {pools.teams.NAME}
                            </td>
                            <td>{pools.teams.POINTS}</td>
                            <td>{pools.teams.DIFF}</td>
                        </tr>
                        <!-- END teams -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END pools -->
    </aside>

</section>
