<?php
$userId = $_SESSION['userID'];
if (isset($_REQUEST['user'])) $userId = $_REQUEST['user'];
$user = $engine->getUser($userId);
$mode = 0;
if ($userId != $_SESSION['userID']) $mode = 1;
if (($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) $mode = 2;

$phase = $engine->getPhase(PHASE_ID_ACTIVE);
?>
<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Pronostics de <?php echo $user['name']; ?></h1>
        </div>
    </div>

    <div class="tag_cloud uniq">
        <form action="?op=save_pronos" method="post" name="formPronos">
            <input type="hidden" name="userId" value="<?php echo $user['userID']; ?>"/>
            <!-- BEGIN pools -->
                <table width="100%">
                    <!-- BEGIN bets -->
    <?php
                    $pronos = $engine->getPronosByUserAndPool($userId, false, 1, $mode);
                    $lastDate = "";
                    foreach ($pronos as $prono) {
                        // Bonus ?
                        if ($prono['scorePronoA'] >= 20) $bonusDisplayA = 'block';
                        else $bonusDisplayA = 'none';
                        if ($prono['scorePronoB'] >= 20) $bonusDisplayB = 'block';
                        else $bonusDisplayB = 'none';
                        $bonusA = '';
                        $bonusB = '';
                        if ($prono['pnyPronoA'] != NULL) {
                            if ($prono['pnyPronoA'] == 1) $bonusA = " checked=\"checked\"";
                        }
                        if ($prono['pnyPronoB'] != NULL) {
                            $bonusDisplayB = 'block';
                            if ($prono['pnyPronoB'] == 1) $bonusB = " checked=\"checked\"";
                        }

                        if ($lastDate != $prono['dateStr']) {
    ?>
                            <tr>
                                <td></td>
                                <td colspan="5" style="text-align:center;"><br/><i><?php echo $prono['dateStr']; ?></i></td>
                            </tr>
    <?php
                        }
                        $lastDate = $prono['dateStr'];
    ?>
                        <tr>
                            <td width="4%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="2">
                                (<?php echo $prono['teamPool']; ?>)
                            </td>
                            <td id="m_<?php echo $prono['matchID']; ?>_team_A" width="38%" rowspan="2" class="result-teamA" style="background-color: <?php echo $prono['COLOR_A']; ?>;">
                                <span class="tLogo <?php echo $prono['teamAname']; ?>"></span>
                                <span class="result-teamA-name"><?php echo $prono['teamAname']; ?></span>
                            </td>
                            <td width="8%" style="text-align:right;">
                                <input type="number" min="0" max="500" size="2"
                                       name="iptScoreTeam_A_<?php echo $prono['matchID']; ?>"
                                       id="iptScoreTeam_A_<?php echo $prono['matchID']; ?>"
                                       value="<?php echo $prono['scorePronoA']; ?>"
                                       <?php echo $prono['DISABLED']; ?> />
                            </td>
                            <td width="4%"
                                style="text-align:center; font-weight:300; font-size:9px; color:<?php echo $prono['COLOR']; ?>;"
                                rowspan="2">
                                <?php echo $prono['POINTS']; ?><br/>
            <span style="color:grey;">
              <?php echo $prono['DIFF']; ?>
            </span>
                            </td>
                            <td width="8%" style="text-align:left;">
                                <input type="number" min="0" max="500" size="2"
                                       name="iptScoreTeam_B_<?php echo $prono['matchID']; ?>"
                                       id="iptScoreTeam_B_<?php echo $prono['matchID']; ?>"
                                       value="<?php echo $prono['scorePronoB']; ?>"
                                       <?php echo $prono['DISABLED']; ?> />
                            </td>
                            <td id="m_<?php echo $prono['matchID']; ?>_team_B" width="38%" rowspan="2" class="result-teamB" style="background-color: <?php echo $prono['COLOR_B']; ?>;">
                                <span class="tLogo <?php echo $prono['teamBname']; ?>"></span>
                                <span class="result-teamB-name"><?php echo $prono['teamBname']; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $prono['scoreMatchA']; ?></td>
                            <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $prono['scoreMatchB']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <!-- END bets -->
                    <?php } ?>
                    <?php if ($mode != 1) { ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                <input type="submit" value="Valider" name="iptSubmit"/>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
        </form>
    </div>
</div>

<aside>
    <div class="headline">
        <h2>Classements virtuels</h2>
    </div>
<?php
    $pools = $engine->getPoolsByPhase();
    foreach ($pools as $pool) {
        ?>
        <div class="tag_cloud">
            <div><h3 style="color:black;">Groupe <?php echo $pool['name']; ?></h3></div>
            <div id="pool_<?php echo $pool['name']; ?>_ranking">
                <table class="ranking-pool">
                    <tr>
                        <td width="80%"><b>Nations</b></td>
                        <td width="10%"><b>Pts</b></td>
                        <td width="10%"><b>Diff</b></td>
                    </tr>
                    <?php
                    $pronos = $engine->getPronosByUserAndPool($_SESSION['userID'], $pool['poolID']);
                    $teams = $engine->getTeamsByPool($pool['poolID']);
                    $ranked_teams = $engine->getRanking($teams, $pronos, 'scoreProno', $_SESSION['userID']);
                    //$ranked_teams = array();
                    foreach ($ranked_teams as $team) {
                        ?>
                        <tr<?php if (isset($team['style'])) echo " style=\"" . $team['style'] . "\""; ?>>
                            <td id="team_<?php echo $team['teamID']; ?>" class="ranking-pool-team"><span class="tLogoSmall <?php echo $team['name']; ?>"></span><?php echo $team['name']; ?></td>
                            <td class="ranking-pool-team-points"><?php echo $team['points']; ?></td>
                            <td class="ranking-pool-team-points"><?php echo $team['diff']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
<?php } ?>
</aside>
<script type="text/javascript">
    $('input[type="number"]').on('change', checkScore);
</script>
