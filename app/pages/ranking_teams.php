<?php
$teams = $engine->loadUserTeamRanking();
?><div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Classement par équipe</h1>
        </div>
        <div class="headline-menu">
            <a href="/?op=view_ranking">Général</a>
            <a href="/?op=view_ranking_teams"><strong>Par équipes</strong></a>
            <a href="/?op=view_ranking_users_in_team">Interne</a>
        </div>
    </div>

    <table class="ranking">
        <tr>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Rang</i></th>
            <th width="40%" style="font-size:80%"><i>Equipe</i></th>
            <th width="20%" style="font-size:80%;text-align:center;"><i>Joueurs</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Moyenne</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Max</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Total</i></th>
        </tr>

    <!-- BEGIN users -->
<?php
    foreach ($teams as $team) {
?>
        <tr class="list_element">
            <td style="font-size:80%;text-align:center;"><strong><?php echo $team['rank']; ?></strong> <?php echo $team['lastRank']; ?></td>
            <td style="font-size:70%"><strong><?php echo $team['name']; ?></a></strong></td>
            <td style="font-size:70%;text-align:center;"><?php echo $team['nbUsersActifs'] . "/" . $team['nbUsersTotal']; ?></td>
            <td style="font-size:70%;text-align:center;"><strong><?php echo $team['avgPoints']; ?></strong></td>
            <td style="font-size:70%;text-align:center;"><?php echo $team['maxPoints']; ?></td>
            <td style="font-size:70%;text-align:center;"><?php echo $team['totalPoints']; ?></td>
        </tr>
<?php } ?>
    <!-- END users -->
    </table>
</div>

<aside>
    <div class="tag_cloud">
        <div class="rightcolumn_headline"><h1 style="color:black;">ChatBoard</h1></div>
        <div id="tag_0" class="tag">
            <form onsubmit="return saveTag();">
                <input type="text" id="tag" value="" size="20" />
                <span style="font-size:8px;">(Entrée pour envoyer)</span>
            </form>
        </div>
        <div id="tags"></div>
        <div id="navig" style="font-size:10px;text-align:center;">
            <!--{NAVIG}-->
        </div>
    </div>
</aside>
<script type="text/javascript">
    loadTags();
</script>
