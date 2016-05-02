<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Classement par groupe après {NB_MATCHES}</h1>
                <span>
                    {NB_ACTIVE_GROUPS}/{NB_GROUPS} groupes
                    <!-- BEGIN g1 -->
                        <a href="/?act=view_users_ranking_by_group&groupID={g1.GROUP_ID}">{g1.GROUP_NAME}</a><br/>
                    <!-- END g1 -->
                    <!-- BEGIN g2 -->
                        <a href="/?act=view_users_ranking_by_group&groupID={g2.GROUP_ID2}">{g2.GROUP_NAME2}</a><br/>
                    <!-- END g2 -->
                    <!-- BEGIN g3 -->
                        <a href="/?act=view_users_ranking_by_group&groupID={g3.GROUP_ID3}">{g3.GROUP_NAME3}</a><br/>
                    <!-- END g3 -->
                    </td>
                </span>
            </div>
            <div class="headline-menu">
                <a href="/?act=view_users_ranking">Général</a>
                <a href="/?act=view_users_visual_ranking">Relief</a>
                <a href="/?act=view_groups_ranking"><strong>{LABEL_TEAMS_RANKING}</strong></a>
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
                <th width="30%" class="aligned">Equipe</th>
                <th width="15%">Joueurs</th>
                <th width="15%">Moyenne</th>
                <th width="15%">Max</th>
                <th width="15%">Total</th>
            </tr>

            <!-- BEGIN teams -->
            <tr class="list_element" style="background-color:{teams.COLOR};">
                <td><strong>{teams.RANK}</strong> {teams.LAST_RANK}</td>
                <td class="aligned"><strong><a href="/?act=view_users_ranking_by_group&groupID={teams.GROUP_ID}">{teams.NAME}</a></strong></td>
                <td>{teams.NB_ACTIFS} / {teams.NB_TOTAL}</td>
                <td><strong>{teams.AVG_POINTS}</strong></td>
                <td>{teams.MAX_POINTS}</td>
                <td>{teams.TOTAL_POINTS}</td>
            </tr>
        <!-- END teams -->
        </table>
    </div>

    <aside>
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h2>TagBoard</h2></div>
            <div id="tag_0" style="text-align:center;">
                <form onsubmit="return saveTag('');">
                    <input type="text" id="tag" value="" size="18"></textarea>
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
            </div>
            <div id="navig" style="font-size:10px;text-align:center;">
                {NAVIG}
            </div>
        </div>
    </aside>
</section>
<script type="text/javascript">
    getTags();
</script>
