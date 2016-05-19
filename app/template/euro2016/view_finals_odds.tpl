<script type="text/javascript">
    function toggle_exact_bets(id) {
        var exact_bets = document.getElementById('exact_bets_' + id);
        if (exact_bets.style.display == 'inline') exact_bets.style.display = 'none';
        else exact_bets.style.display = 'inline';
    }

    function toggle_good_bets(id) {
        var good_bets = document.getElementById('good_bets_' + id);
        if (good_bets.style.display == 'inline') good_bets.style.display = 'none';
        else good_bets.style.display = 'inline';
    }

    $(document).ready(headlineButtonsInit);
</script>
<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Résultats & Cotes</h1>
            </div>
            <div class="headline-menu">
                <button class="headline-button phase" data-value="finals_bets">Phase finale</button>
            </div>
        </div>

        <div class="tag_cloud" style="text-align:center;">
            <strong>Les scores de la phase finale sont ceux à l'issue des éventuelles prolongations.</strong>
        </div>

        <div class="tag_cloud">
            <!-- BEGIN finals -->
            <table border="0" cellpadding="0" cellspacing="0" style="font-size: 90%; margin-left: 20px; margin-right: 20px; width: 100%;">
                <tr>
                    <!-- BEGIN rounds -->
                    <td>
                        <!-- BEGIN merge_top -->
                        <table border="0" cellpadding="0" cellspacing="0" style="font-size: 90%; margin:0; width: 100%;">
                            <!-- END merge_top -->
                            <tr height="25px">
                                <td align="center" colspan="2" style="border:1px solid #999999;"
                                    bgcolor="#CFCFCF">{finals.rounds.NAME}</td>
                                <td colspan="3"></td>
                            </tr>

                            <tr>
                                <td align="center" colspan="2" style="border:1px solid #999999;"></td>
                                <td colspan="3"></td>
                            </tr>

                            <tr>
                                <td width="150">&#160;</td>
                                <td width="30">&#160;</td>
                                <td width="10">&#160;</td>
                                <td width="15">&#160;</td>
                            </tr>
                            <!-- BEGIN ranks -->
                            <tr>
                                <td colspan="2" height="{finals.rounds.ranks.HEIGHT_TOP}">&#160;</td>
                                <!-- BEGIN bottom_line -->
                                <td rowspan="3"
                                    style="border-width:0 3px 2px 0; border-style: solid;border-color:black;">
                                    &#160;</td>
                                <td rowspan="3" style="border-width:2px 0 0 0; border-style: solid;border-color:black;">
                                    &#160;</td>
                                <!-- END bottom_line -->
                            </tr>
                            <tr>
                                <td colspan="2">{finals.rounds.ranks.DATE}</td>
                            </tr>
                            <input type="hidden" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_W"
                                   value="{finals.finals.rounds.ranks.TEAM_W}"/>
                            <!-- BEGIN teams -->
                            <input type="hidden"
                                   id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_ID"
                                   value="{finals.rounds.ranks.teams.ID}"/>
                            <tr height="25px">
                                <td style="border:1px solid #999999;" bgcolor="{finals.rounds.ranks.teams.COLOR}"
                                    id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_NAME"
                                    onClick="javascript:setWinner({finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});">{finals.rounds.ranks.teams.IMG}
                                    &nbsp;{finals.rounds.ranks.teams.NAME}</td>
                                <td style="border:1px solid #999999; text-align:center;font-weight:600;font-size:15px;"
                                    bgcolor="{finals.rounds.ranks.teams.COLOR}">
                                    <b>{finals.rounds.ranks.teams.SCORE}</b><br/><span
                                            style="text-align:center;color:blue;font-weight:300;font-size:9px;">{finals.rounds.ranks.teams.AVG}</span><br/><span
                                            style="text-align:center;color:green;font-weight:300;font-size:9px;">{finals.rounds.ranks.teams.ODD}</span>
                                </td>
                                <!-- BEGIN points -->
                                <td style="text-align:center;font-weight:300;font-size:9px;color:{finals.rounds.ranks.COLOR};">{finals.rounds.ranks.POINTS}
                                    <br/><span style="color:black;">{finals.rounds.ranks.DIFF}</span></td>
                                <!-- END points -->
                                <!-- BEGIN top_line -->
                                <td rowspan="2"
                                    style="border-width:2px 3px 0 0; border-style: solid;border-color:black;">
                                    &#160;</td>
                                <!-- END top_line -->
                            </tr>
                            <!-- END teams -->
                            <tr>
                                <td colspan="2" height="{finals.rounds.ranks.HEIGHT_BOTTOM}"
                                    style="valign=top;text-align:center;">

                                    <a href="javascript:toggle_exact_bets({finals.rounds.ranks.MATCH_ID});"><span
                                                style="color:red;">{finals.rounds.ranks.EXACT_BETS}</span></a>
                                    <div id="exact_bets_{finals.rounds.ranks.MATCH_ID}" style="display:none;">
                                        <!-- BEGIN exact_bets -->
                                        <br/><a href="?act=view_finals_bets&user={finals.rounds.ranks.exact_bets.USERID}"><b>{finals.rounds.ranks.exact_bets.NAME}</b></a>
                                        <!-- END exact_bets -->
                                    </div>
                                    <br/>
                                    <a href="javascript:toggle_good_bets({finals.rounds.ranks.MATCH_ID});"><span
                                                style="color:red;">{finals.rounds.ranks.GOOD_BETS}</span></a>
                                    <div id="good_bets_{finals.rounds.ranks.MATCH_ID}" style="display:none;">
                                        <!-- BEGIN good_bets -->
                                        <br/><a href="?act=view_finals_bets&user={finals.rounds.ranks.good_bets.USERID}">{finals.rounds.ranks.good_bets.NAME}</a>
                                        <!-- END good_bets -->
                                    </div>

                                </td>
                            </tr>
                            <!-- END ranks -->
                            <!-- BEGIN merge_bottom -->
                        </table>
                        <!-- END merge_bottom -->
                    </td>
                    <!-- END rounds -->
                </tr>
            </table>
            <!-- END finals -->
        </div>
    </div>
</section>
