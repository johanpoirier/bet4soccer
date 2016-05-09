<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Classement général après {NB_MATCHES}</h1>
                <span>{NB_ACTIVE_USERS} parieurs</span>
            </div>
            <div class="headline-menu">
                <a href="/?act=view_users_ranking"><strong>Général</strong></a>
                <a href="/?act=view_users_visual_ranking">Relief</a>
                <a href="/?act=view_groups_ranking">{LABEL_TEAMS_RANKING}</a>
                <span>
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
        </div>

        <div class="focus">
            <!-- BEGIN mine -->
            <div class="focus_mine">
                <h4>Votre classement</h4>
                <div>
                    <b>{mine.RANK}</b><sup>e</sup>
                    <span class="focus_points">{mine.POINTS} pts</span>
                </div>
                <span class="focus_name"><a href="#user{mine.ID}">{USERNAME}</a></span>
                <span class="focus_evol">{mine.EVOL}</span>
            </div>
            <!-- END mine -->
            <!-- BEGIN max -->
            <div class="focus_most">
                <h3>+ forte hausse</h3>
                <div><img src="{TPL_WEB_PATH}images/hausse.png" width="30px"/></div>
                <span class="focus_name"><a href="#user{max.ID}">{max.NAME}</a></span>
                <span class="focus_evol">{max.EVOL}</span>
            </div>
            <!-- END max -->
            <!-- BEGIN min -->
            <div class="focus_most">
                <h3>+ forte baisse</h3>
                <div><img src="{TPL_WEB_PATH}images/baisse.png" width="30px"/></div>
                <span class="focus_name"><a href="#user{min.ID}">{min.NAME}</a></span>
                <span class="focus_evol">{min.EVOL}</span>
            </div>
            <!-- END min -->
        </div>

        <table class="ranking">
            <tr>
                <th width="10%">Rang</th>
                <th width="25%" class="aligned">Parieur</th>
                <th width="17%">Equipe</th>
                <th width="12%">Points</th>
                <th width="12%">Scores Exacts</th>
                <th width="12%">Résultats Justes</th>
                <th width="12%">Ecart Scores</th>
            </tr>

            <!-- BEGIN users -->
            <tr class="list_element" style="background-color:{users.COLOR};" id="user{users.ID}">
                <td><b>{users.RANK}</b> {users.LAST_RANK}</td>
                <td class="aligned">
                    <b>{users.VIEW_BETS}{users.NAME}</a></b> {users.NB_MISS_BETS}
                </td>
                <td><i>{users.GROUP}</i></td>
                <td><b>{users.POINTS}</b></td>
                <td>{users.NBSCORES}</td>
                <td>{users.NBRESULTS}</td>
                <td>{users.DIFF}</td>
            </tr>
            <!-- END users -->
        </table>
    </div>

    <aside>
        <div class="tag_cloud tagboard">
            <div class="headline"><h2>TagBoard</h2></div>
            <div id="tag_0" class="tagboard-form">
                <form onsubmit="return saveTag('');">
                    <input type="text" id="tag" value="" size="18" />
                    <span>(Entrée pour envoyer)</span>
                </form>
            </div>
            <div id="tags"></div>
        </div>
    </aside>
</section>
<script type="text/javascript">
    $(document).ready(getTags);
</script>
