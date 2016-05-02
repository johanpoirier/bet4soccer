<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>{GROUP_NAME} : classement après {NB_MATCHES}</h1>
                <span>
                    {NB_ACTIVE_USERS}/{NB_USERS} parieurs
                    <!-- BEGIN g1 -->
                    <a href="/?act=view_users_ranking_by_group&groupID={g1.GROUP_ID}">{g1.GROUP_NAME}</a><br/>
                    <!-- END g1 -->
                    <!-- BEGIN g2 -->
                    <a href="/?act=view_users_ranking_by_group&groupID={g2.GROUP_ID2}">{g2.GROUP_NAME2}</a><br/>
                    <!-- END g2 -->
                    <!-- BEGIN g3 -->
                    <a href="/?act=view_users_ranking_by_group&groupID={g3.GROUP_ID3}">{g3.GROUP_NAME3}</a><br/>
                    <!-- END g3 -->
                </span>
            </div>
            <div class="headline-menu">
                <a href="/?act=view_users_ranking">Général</a>
                <a href="/?act=view_users_visual_ranking">Relief</a>
                <a href="/?act=view_groups_ranking">{LABEL_TEAMS_RANKING}</a>
            </div>
        </div>

        <!-- BEGIN money -->
        <div style="float:right;width:160px;">
            <i>Montant de la cagnotte</i> :<br/>
            <center><span style="font-size:20px;"><a href="/?act=money"><b>{money.AMOUNT} €</b></a></span></center>
        </div>
        <!-- END money -->

        <table class="ranking">
            <tr>
                <th width="10%">Rang</th>
                <th width="30%" class="aligned">Parieur</th>
                <th width="15%">Points</th>
                <th width="15%">Scores Exacts</th>
                <th width="15%">Résultats Justes</th>
                <th width="15%">Ecart de scores</th>
            </tr>

            <!-- BEGIN users -->
            <tr class="list_element" style="background-color:{users.COLOR};">
                <td><strong>{users.RANK}</strong></td>
                <td class="aligned"><strong>{users.VIEW_BETS}{users.NAME}</a></strong> {users.NB_MISS_BETS}</td>
                <td><strong>{users.POINTS}</strong></td>
                <td>{users.NBSCORES}</td>
                <td>{users.NBRESULTS}</td>
                <td>{users.DIFF}</td>
            </tr>
            <!-- END users -->
        </table>
    </div>

    <aside>
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h2>Team TagBoard</h2></div>
            <div id="tag_0" style="text-align:center;">
                <br/>
                <form onsubmit="return saveTag({GROUP_ID});">
                    <input type="text" id="tag" value="" size="18"/>
                    <span style="font-size:8px;">(Entrée pour envoyer)</span>
                </form>
            </div>
            <div id="tags">
                <!-- BEGIN tags -->
                <div id="tag_{tags.ID}">
                    {tags.DEL_IMG}
                    <u>{tags.DATE}{TAG_SEPARATOR}<b>{tags.USER}</b></u>
                    {tags.TEXT}
                </div>
                <!-- END tags -->
                <div id="navig" style="font-size:10px;text-align:center;">
                    {NAVIG}
                </div>
            </div>
        </div>
    </aside>
</section>
<script type="text/javascript">
    getTags({GROUP_ID});
</script>
