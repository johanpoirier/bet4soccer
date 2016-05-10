<script type="text/javascript">
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
                    <option value="view_results">Poules</option>
                    <option selected="selected" value="view_finals_results">Phase finale</option>
                </select>
            </div>
        </div>

        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <span style="font-size: 150%">Groupe {pools.POOL}</span>
            <table width="100%">
                <!-- BEGIN next_matches -->
                <tr>
                    <td colspan="4" style="text-align:center;"><i>-> Prochain match <u>{pools.next_matches.DATE}</u>
                            (<i>dans {pools.next_matches.DELAY}</i>) :</i>
                        <!-- BEGIN list -->
                        <br/><b>{pools.next_matches.list.TEAM_NAME_A} - {pools.next_matches.list.TEAM_NAME_B}</b></td>
                </tr>
                <!-- END list -->
                <!-- END next_matches -->
                <!-- BEGIN matches -->
                <tr>
                    <td colspan="4" style="text-align:center;"><i>{pools.matches.DATE}</i></td>
                </tr>
                <tr>
                    <td id="{pools.matches.ID}_team_A" width="35%"
                        style="text-align:right;background-color:{pools.matches.TEAM_COLOR_A};">{pools.matches.TEAM_NAME_A}
                        <img src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_A_URL}.png"/></td>
                    <td width="15%"
                        style="text-align:center;font-weight:600;font-size:15px;">{pools.matches.SCORE_A}</td>
                    <td width="15%"
                        style="text-align:center;font-weight:600;font-size:15px;">{pools.matches.SCORE_B}</td>
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
            <div class="rightcolumn_headline"><h1>Groupe {pools.POOL}</h1></div>
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
