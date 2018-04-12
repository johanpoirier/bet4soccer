<script src="{TPL_WEB_PATH}js/jquery.selectboxes.pack.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    document.onload = loadTeams();

    function loadTeams(idTeamA, idTeamB) {
        var pool = $("#pool").val();
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_teams&pool=" + pool,
            success: function (datas) {
                handleHttpResponse(datas, idTeamA, idTeamB);
            }
        });

        var round = $("#round");
        var rank = $("#rank");

        if ((round != null) && (rank != null)) {
            if (pool == "PF") {
                round.css("visibility", "visible");
                rank.css("visibility", "visible");
            } else {
                round.css("visibility", "hidden");
                rank.css("visibility", "hidden");
            }
        }
    }

    function handleHttpResponse(datas, idTeamA, idTeamB) {
        if (datas.length > 0) {
            var results = datas.split("|");
            var teamA = $("#teamA");
            var teamB = $("#teamB");

            teamA.find('option').remove().end().addOption("", "");
            teamB.find('option').remove().end().addOption("", "");

            for (var i = 0; i < results.length - 1; i++) {
                var result = results[i].split(",");
                teamA.addOption(result[0], result[1]);
                teamB.addOption(result[0], result[1]);
            }
            teamA.sortOptions();
            teamB.sortOptions();

            // select teams
            if (idTeamA) {
                selectListValue('teamA', idTeamA);
            }
            else {
                teamA.selectOptions("", true);
            }
            if (idTeamB) {
                selectListValue('teamB', idTeamB);
            }
            else {
                teamB.selectOptions("", true);
            }
        }
    }

    function loadMatchPool(matchID) {
        loadMatch(matchID, 0);
    }

    function loadMatchRounds(matchID) {
        loadMatch(matchID, 1);
    }

    function loadMatch(matchID, isRounds) {
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_match&matchID=" + matchID + "&isRounds=" + isRounds,
            success: function (data) {
                handleMatchHttpResponse(data);
            }
        });
    }

    var handleMatchHttpResponse = function (obj) {
        var matchData = obj.    split("|");

        // group & round
        selectListValue('pool', matchData[0]);
        selectListValue('round', matchData[1]);
        selectListValue('rank', matchData[2]);

        // date & hour
        selectListValue('day', matchData[3]);
        selectListValue('month', matchData[4]);
        $("#hour").val(matchData[6]);
        $("#minute").val(matchData[7]);

        // teams
        loadTeams(matchData[8], matchData[9]);

        // id match for update
        $("#matchID").val(matchData[10]);
    }

    $(document).ready(function () {
        var lastPool = "{LAST_POOL}";
        if (lastPool.length > 0) {
            var round = $("#round");
            var rank = $("#rank");
            if ((round != null) && (rank != null)) {
                if (lastPool == "PF") {
                    round.css("visibility", "visible");
                    rank.css("visibility", "visible");
                } else {
                    round.css("visibility", "hidden");
                    rank.css("visibility", "hidden");
                }
            }

            selectListValue('pool', lastPool);
            loadTeams();
        }
    });
</script>

<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Matchs</h1>
            </div>
        </div>


        <div class="tag_cloud">
            <form name="add_team" action="/?act=add_match" method="post">
                <input type="hidden" id="matchID" name="matchID" value=""/>
                <table>
                    <tr>
                        <td>Groupe/Phase finale :</td>
                    </tr>

                    <tr>
                        <td><select name="pool" id="pool" onchange="loadTeams()">
                                <option name="0" value="0"></option>
                                <!-- BEGIN pools -->
                                <option name="{pools.VALUE}"
                                        value="{pools.VALUE}" {pools.SELECTED}>{pools.NAME}</option>
                                <!-- END pools -->
                                <option name="PF" value="PF">PF</option>
                            </select>&nbsp;<select name="round" id="round">
                                <option name="0" value="0"></option>
                                <!-- BEGIN rounds -->
                                <option name="{rounds.VALUE}" value="{rounds.VALUE}">{rounds.NAME}</option>
                                <!-- END rounds -->
                            </select>&nbsp;<select name="rank" id="rank">
                                <option name="0" value="0"></option>
                                <!-- BEGIN rank -->
                                <option name="{rank.RANK}" value="{rank.RANK}">{rank.RANK}</option>
                                <!-- END rank -->
                            </select></td>
                    </tr>
                    <tr>
                        <td>Date :</td>
                    </tr>
                    <tr>
                        <td><select name="day" id="day">
                                <!-- BEGIN days -->
                                <option name="{days.DAY}" value="{days.DAY}" {days.SELECTED}>{days.DAY}</option>
                                <!-- END days -->
                            </select>&nbsp;<select name="month" id="month">
                                <!-- BEGIN months -->
                                <option name="{months.VALUE}"
                                        value="{months.VALUE}" {months.SELECTED}>{months.NAME}</option>
                                <!-- END months -->
                            </select> {YEAR}
                            <input type="text" size="2" name="hour" id="hour" value="21"/>h<input type="hidden" size="2"
                                                                                                  name="minute"
                                                                                                  id="minute"
                                                                                                  value="00"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Equipe A :</td>
                        <td>Equipe B :</td>
                    </tr>
                    <tr>
                        <td><select name="teamA" id="teamA"/></td>
                        <td><select name="teamB" id="teamB"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" name="add_match" value="Ajouter / Modifier" size="50"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <span style="font-size: 150%">Groupe {pools.NAME}</span>
            <!-- BEGIN matches -->
            <div id="match_{pools.matches.ID}" onclick="loadMatchPool({pools.matches.ID})">
                {pools.matches.DATE} - {pools.matches.TEAM_NAME_A} - {pools.matches.TEAM_NAME_B}
            </div>
            <!-- END matches -->
        </div>
        <!-- END pools -->

        <!-- BEGIN rounds -->
        <div class="tag_cloud">
            <span style="font-size: 150%">{rounds.NAME}</span>
            <!-- BEGIN matches -->
            <div id="match_{rounds.matches.ID}" onclick="loadMatchRounds({rounds.matches.ID})">
                {rounds.matches.DATE} - {rounds.matches.TEAM_NAME_A} - {rounds.matches.TEAM_NAME_B}
            </div>
            <!-- END matches -->
        </div>
        <!-- END rounds -->
    </div>
</section>