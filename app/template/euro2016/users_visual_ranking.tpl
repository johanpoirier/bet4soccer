<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Classement en relief après {NB_MATCHES}</h1>
                <span>{NB_ACTIVE_USERS} parieurs</span>
            </div>
            <div class="headline-menu">
                <a href="/?act=view_users_ranking">Général</a>
                <a href="/?act=view_users_visual_ranking"><strong>Relief</strong></a>
                <a href="/?act=view_groups_ranking">{LABEL_TEAMS_RANKING}</a>
            </div>
        </div>

        <table class="ranking">
            <tr>
                <th width="10%">Rang</b></th>
                <th width="70%" class="aligned">Parieurs</th>
                <th width="30%">Points</th>
            </tr>

            <!-- BEGIN users -->
            <tr class="{users.CLASS} list_element">
                <td><strong>{users.RANK}</strong></td>
                <td class="user_visual aligned">{users.NB}{users.NAME}</td>
                <td><strong>{users.POINTS}</strong></td>
            </tr>
            <!-- END users -->
        </table>
    </div>

    <aside>
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h2>TagBoard</h2></div>
            <div id="tag_0" style="text-align:center;">
                <form onsubmit="return saveTag('');">
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
