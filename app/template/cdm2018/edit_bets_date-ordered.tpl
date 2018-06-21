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
        else if (scoreA < scoreB) {
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
                <button class="headline-button phase" data-value="finals_bets" data-user="{CURRENT_USER_ID}"><i class="icon-final"></i>Phase finale</button>
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
                        displayChart({stats.ID}, {stats.DATA}, '{stats.STYLE}', '{stats.COLOR}', {stats.XSERIE}, {stats.YTICKS}, {stats.YMIN}, {stats.YMAX}, {stats.TRANSFORM}, {stats.INVERSE_TRANSFORM});
                    }
                });
            </script>
            <!-- END stats -->
        </div>

        <div class="tag_cloud uniq">
            <form name="save_bets" action="/?act=save_bets" method="post">
                <div class="bets-list">
                    <!-- BEGIN bets -->
                    <!-- BEGIN view -->
                    <div class="bets-list-date">{bets.view.DATE}</div>
                    <div class="bets-list-game">
                        <div class="bets-list-game-pool">({bets.view.POOL})</div>
                        <div id="{bets.view.ID}_team_A" class="bets-list-game-team team-a" style="background-color:{bets.view.TEAM_COLOR_A};">
                            {bets.view.TEAM_NAME_A}&nbsp;
                            <img src="{TPL_WEB_PATH}images/flag/{bets.view.TEAM_NAME_A}.png"/>
                        </div>
                        <div class="bets-list-game-score team-a">
                            {bets.view.SCORE_A}
                            <div class="bets-list-game-result">{bets.view.RESULT_A}</div>
                        </div>
                        <div class="bets-list-game-points" style="color:{bets.view.COLOR};">
                            {bets.view.POINTS}<br/>
                            <span style="color:black;">{bets.view.DIFF}</span>
                        </div>
                        <div class="bets-list-game-score team-b">
                            {bets.view.SCORE_B}
                            <div class="bets-list-game-result">{bets.view.RESULT_B}</div>
                        </div>
                        <div id="{bets.view.ID}_team_B" width="35%" class="bets-list-game-team team-b" style="background-color:{bets.view.TEAM_COLOR_B};">
                            <img src="{TPL_WEB_PATH}images/flag/{bets.view.TEAM_NAME_B}.png"/>
                            &nbsp;{bets.view.TEAM_NAME_B}
                        </div>
                    </div>
                    <!-- END view -->
                    <!-- BEGIN edit -->
                    <div class="bets-list-date">{bets.edit.DATE}</div>
                    <div class="bets-list-game">
                        <div class="bets-list-game-pool">({bets.edit.POOL})</div>
                        <div id="{bets.edit.ID}_team_A" class="bets-list-game-team team-a" style="background-color:{bets.edit.TEAM_COLOR_A};">
                            {bets.edit.TEAM_NAME_A}&nbsp;
                            <img src="{TPL_WEB_PATH}images/flag/{bets.edit.TEAM_NAME_A}.png"/>
                            <span class="fifa-rank-tip">Classement FIFA : {bets.edit.TEAM_RANK_A}</span>
                        </div>
                        <div class="bets-list-game-bet team-a">
                            <input type="number" min="0"
                                   max="99" size="2"
                                   id="{bets.edit.ID}_score_team_A"
                                   name="{bets.edit.ID}_score_team_A"
                                   value="{bets.edit.SCORE_A}"
                                   onChange="saveBet(this.value,{bets.edit.ID},'A')"
                                   onKeyUp="saveBet(this.value,{bets.edit.ID},'A')" {bets.edit.DISABLED}/>
                        </div>
                        <div class="bets-list-game-bet team-b">
                            <input type="number" min="0" max="99"
                                   size="2"
                                   id="{bets.edit.ID}_score_team_B"
                                   name="{bets.edit.ID}_score_team_B"
                                   value="{bets.edit.SCORE_B}"
                                   onChange="saveBet(this.value,{bets.edit.ID},'B')"
                                   onKeyUp="saveBet(this.value,{bets.edit.ID},'B')" {bets.edit.DISABLED}/>
                        </div>
                        <div id="{bets.edit.ID}_team_B" class="bets-list-game-team team-b" style="background-color:{bets.edit.TEAM_COLOR_B};">
                            <img src="{TPL_WEB_PATH}images/flag/{bets.edit.TEAM_NAME_B}.png"/>
                            &nbsp;{bets.edit.TEAM_NAME_B}
                            <span class="fifa-rank-tip">Classement FIFA : {bets.edit.TEAM_RANK_B}</span>
                        </div>
                    </div>
                    <!-- END edit -->
                    <!-- END bets -->
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
                                                            src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME}.png"/> {pools.teams.NAME}
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
