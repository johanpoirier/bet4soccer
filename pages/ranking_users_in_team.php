<?php
$userId = $_SESSION['userID'];
if (isset($_REQUEST['user'])) {
    $userId = $_REQUEST['user'];
}
$currentUser = $engine->getUser($userId);
$mode = 0;
if ($_SESSION['status'] == 1) {
    $mode = 2;
}

$users = $engine->loadRankingInTeams($currentUser['userTeamID']);

?><div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Classement : <?php echo $currentUser['team']; ?></h1>
        </div>
        <div class="headline-menu">
            <a href="/?op=view_ranking">Général</a>
            <a href="/?op=view_ranking_teams">Par équipes</a>
            <a href="/?op=view_ranking_users_in_team"><strong>Interne</strong></a>
        </div>
    </div>

    <table class="ranking">
        <tr>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Rang</i></th>
            <th width="40%" style="font-size:80%"><i>Parieur</i></th>
            <th width="20%" style="font-size:80%;text-align:center;"><i>Points</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>R&eacute;sultats Exacts</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Scores Exacts</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Différence</i></th>
        </tr>

    <!-- BEGIN users -->
<?php
    foreach ($users as $user) {
?>
        <tr class="list_element <?php echo $user['CLASS']; ?>">
            <td style="font-size:80%;text-align:center;"><strong><?php echo $user['RANK']; ?></strong> <?php echo $user['LAST_RANK']; ?></td>
            <td style="font-size:70%"><strong><?php if ($mode == 2)
echo $user['VIEW_BETS']; ?><?php echo $user['NAME']; ?><?php if ($mode == 2)
echo "</a>"; ?></strong> <?php echo $user['NB_BETS']; ?></td>
            <td style="font-size:70%;text-align:center;"><strong><?php echo $user['POINTS']; ?></strong></td>
            <td style="font-size:70%;text-align:center;"><?php echo $user['NBRESULTS']; ?></td>
            <td style="font-size:70%;text-align:center;"><?php echo $user['NBSCORES']; ?></td>
            <td style="font-size:70%;text-align:center;"><?php echo $user['DIFF']; ?></td>
        </tr>
<?php } ?>
    <!-- END users -->

    </table>
</div>

<aside>
    <div class="tag_cloud">
        <div class="rightcolumn_headline"><h1 style="color:black;">TeamBoard</h1></div>
        <div id="tag_0"  class="tag">
            <form onsubmit="return saveTag(<?php echo $currentUser['userTeamID']; ?>);">
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
    loadTags(<?php echo $currentUser['userTeamID']; ?>);
</script>
