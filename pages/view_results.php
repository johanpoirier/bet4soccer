<?php
if (isset($_GET['phase'])) {
    $phaseID = $_GET['phase'];
} else {
    $phaseID = PHASE_ID_ACTIVE;
}
$phase = $engine->getPhase($phaseID);
if ($phase['phasePrecedente'] != NULL) {
    $phases = $engine->getPhaseByPhaseRoot($phase['phasePrecedente']);
} else {
    $phases = array($phase);
}
?>
<script type="text/javascript">
    <!--
    function changePhase(phaseID) {
        window.location.href = "?op=view_results&phase=" + phaseID;
    }

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
    //-->
</script>
<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Resultats et Cotes</h1>
        </div>
        <div class="headline-menu">
            <select style="float:right;" name="sltPhase"
                    onchange="changePhase(this.value)"><?php foreach ($engine->getPhasesPlayed() as $phase) {
                    echo "<option value=\"" . $phase['phaseID'] . "\"" . (($phaseID == $phase['phaseID']) ? "selected=\"selected\"" : "") . ">" . $phase['name'] . "</option>";
                } ?>
            </select>
        </div>
    </div>

    <?php foreach ($phases as $phase) { ?>
        <div class="tag_cloud uniq">
            <span style="font-size: 150%"><?php echo $phase['name']; ?></span>
            <table width="100%" class="games-results">
                <?php
                $results = $engine->getResultsByPhase($phase['phaseID'], false);
                $lastDate = "";
                foreach ($results as $result) {
                    if ($lastDate != $result['dateStr']) {
                        ?>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="3" class="game-result-date"><?php echo $result['dateStr']; ?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    $lastDate = $result['dateStr'];
                    $odds = $engine->getOddsByMatch($result['matchID']);

                    // Stats paris
                    $exact_bets = null;
                    $nb_exact_bets = 0;
                    $str_exact_bets = "";
                    if (isset($result['scoreMatchA']) && isset($result['scoreMatchB'])) {
                        $exact_bets = $engine->getBestPronosByMatch($result['matchID'], $result['scoreMatchA'], $result['scoreMatchB'], EXACT_SCORE);
                        $nb_exact_bets = count($exact_bets);
                        if ($nb_exact_bets == 0) {
                            $str_exact_bets = "aucun super score";
                        }
                        if ($nb_exact_bets == 1) {
                            $str_exact_bets = "1 super score";
                        }
                        if ($nb_exact_bets > 1) {
                            $str_exact_bets = $nb_exact_bets . " supers scores";
                        }
                    }
                    ?>
                    <tr>
                        <td width="5%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="4"></td>
                        <td id="m_<?php echo $result['matchID']; ?>_team_A" width="32%" class="result-teamA" rowspan="3" style="background-color: <?php echo $result['COLOR_A']; ?>;">
                            <span class="tLogo <?php echo $result['teamAname']; ?>"></span>
                            <span class="result-teamA-name"><?php echo $result['teamAname']; ?></span>
                        </td>
                        <td width="12%"
                            style="text-align:center;font-weight:600;font-size:15px;"><?php echo $result['scoreMatchA']; ?></td>
                        <td width="7%" style="text-align:center; font-weight:300; font-size:9px;" rowspan="2"></td>
                        <td width="12%"
                            style="text-align:center;font-weight:600;font-size:15px;"><?php echo $result['scoreMatchB']; ?></td>
                        <td id="m_<?php echo $result['matchID']; ?>_team_B" width="32%" class="result-teamB" rowspan="3" style="background-color: <?php echo $result['COLOR_B']; ?>;">
                            <span class="tLogo <?php echo $result['teamBname']; ?>"></span>
                            <span class="result-teamB-name"><?php echo $result['teamBname']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $odds['A_AVG']; ?></td>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $odds['B_AVG']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><?php echo $odds['A_WINS']; ?>
                            /1
                        </td>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><?php echo $odds['NUL']; ?>
                            /1
                        </td>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><?php echo $odds['B_WINS']; ?>
                            /1
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
                            <a onclick="toggle_exact_bets(<?php echo $result['matchID']; ?>);"><span
                                    style="color:red;"><?php echo $str_exact_bets; ?></span></a>

                            <div id="exact_bets_<?php echo $result['matchID']; ?>" style="display:none;">
                                <?php foreach ($exact_bets as $exact_bet) { ?>
                                    <br/><a
                                        href="?op=view_pronos&user=<?php echo $exact_bet['userID']; ?>"><b><?php echo $exact_bet['username']; ?></b></a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
</div>
<aside>
    <div class="headline">
        <h2>Classements</h2>
    </div>
    <?php
    $pools = $engine->getPoolsByPhase($phase['phaseID']);
    foreach ($pools as $pool) {
        ?>
        <div class="tag_cloud">
            <div><h1 style="color:black;">Groupe <?php echo $pool['name']; ?></h1></div>
            <div id="pool_<?php echo $pool['name']; ?>_ranking">
                <table style="font-size:9px;">
                    <tr>
                        <td width="80%"><b>Nations</b></td>
                        <td width="10%"><b>Pts</b></td>
                        <td width="10%"><b>Diff</b></td>
                    </tr>
                    <?php
                    $matchs = $engine->getMatchsByPool($pool['poolID']);
                    $teams = $engine->getTeamsByPool($pool['poolID']);
                    $ranked_teams = $engine->getRanking($teams, $matchs, 'scoreMatch', $_SESSION['userID']);
                    //$ranked_teams = array();
                    foreach ($ranked_teams as $team) {
                        ?>
                        <tr<?php if (isset($team['style']))
                            echo " style=\"" . $team['style'] . "\""; ?>>
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
