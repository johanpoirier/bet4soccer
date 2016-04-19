<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Résultats</h1>
        </div>
        <div class="headline-menu">
            <a href="/?op=update_ranking">Mettre à jour le classement</a>
        </div>
    </div>

    <div class="tag_cloud uniq">
        <form action="../?op=save_results" method="post" name="formPronos">
            <table width="100%">
                <!-- BEGIN matchs -->
<?php
                $matchs = $engine->getMatchs();
                $lastDate = "";
                foreach ($matchs as $match) {
                    // Pny ?
                    $pnyDisplay = 'none';
                    $pnyA = '';
                    $pnyB = '';
                    if ($match['pnyMatchA'] != NULL) {
                        $pnyDisplay = 'table-row';
                        if ($match['pnyMatchA'] == 1) $pnyA = " checked = 'checked'";
                    }
                    if ($match['pnyMatchB'] != NULL) {
                        if ($match['pnyMatchB'] == 1) $pnyB = " checked = 'checked'";
                    }

                    // Match passé ?
                    if ($lastDate != $match['dateStr']) {
?>
                        <tr>
                            <td></td>
                            <td colspan="4" style="text-align: center;"><br/><i><?php echo $match['dateStr']; ?></i></td>
                        </tr>
<?php
                    }
                    $lastDate = $match['dateStr'];
?>
                    <tr>
                        <td align="left" width="4%" style="white-space: nowrap; font-size: 7pt;">(<?php echo $match['teamPool']; ?>)</td>
                        <td id="m_<?php echo $match['matchID']; ?>_team_A" width="42%" class="result-teamA" style="background-color: <?php echo $match['COLOR_A']; ?>;">
                            <span class="tLogo <?php echo $match['teamAname']; ?>"></span>
                            <span class="result-teamA-name"><?php echo $match['teamAname']; ?></span>
                        </td>
                        <td width="6%" style="text-align:right;">
                            <input type="number" min="0" max="500" size="2"
                                 name="iptScoreTeam_A_<?php echo $match['matchID']; ?>"
                                 id="scoreTeam_A_<?php echo $match['matchID']; ?>"
                                 value="<?php echo $match['scoreMatchA']; ?>"
                                 onkeyup="showPny(<?php echo $match['matchID']; ?>)"/>
                        </td>
                        <td width="6%" style="text-align: left;">
                            <input type="number" min="0" max="500" size="2"
                                 name="iptScoreTeam_B_<?php echo $match['matchID']; ?>"
                                 id="scoreTeam_B_<?php echo $match['matchID']; ?>"
                                 value="<?php echo $match['scoreMatchB']; ?>"
                                 onkeyup="showPny(<?php echo $match['matchID']; ?>)" />
                        </td>
                        <td id="m_<?php echo $match['matchID']; ?>_team_B" width="42%" class="result-teamB" style="background-color: <?php echo $match['COLOR_B']; ?>;">
                            <span class="tLogo <?php echo $match['teamBname']; ?>"></span>
                            <span class="result-teamB-name"><?php echo $match['teamBname']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align:right;"><select size="1" name="sltBonus_A_<?php echo $match['matchID']; ?>">
                                <option value="0"<?php if ($match['bonusA'] == 0) echo "selected=\"selected\""; ?>>0
                                    pt
                                </option>
                                <option value="1"<?php if ($match['bonusA'] == 1) echo "selected=\"selected\""; ?>>1
                                    pt
                                </option>
                                <option value="2"<?php if ($match['bonusA'] == 2) echo "selected=\"selected\""; ?>>2
                                    pts
                                </option>
                            </select></td>
                        <td colspan="2" style="text-align:left;"><select size="1" name="sltBonus_B_<?php echo $match['matchID']; ?>">
                                <option value="0"<?php if ($match['bonusB'] == 0) echo "selected=\"selected\""; ?>>0
                                    pt
                                </option>
                                <option value="1"<?php if ($match['bonusB'] == 1) echo "selected=\"selected\""; ?>>1
                                    pt
                                </option>
                                <option value="2"<?php if ($match['bonusB'] == 2) echo "selected=\"selected\""; ?>>2
                                    pts
                                </option>
                            </select></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4" align="center">
                            <div id="pny_<?php echo $match['matchID']; ?>" class="pny"
                                 style="display:<?php echo $pnyDisplay; ?>">
                                <input type="radio" id="rbPny_A_<?php echo $match['matchID']; ?>"
                                       name="iptPny_<?php echo $match['matchID']; ?>" value="A"<?php echo $pnyA; ?> />
                                drop-goals
                                <input type="radio" id="rbPny_B_<?php echo $match['matchID']; ?>"
                                       name="iptPny_<?php echo $match['matchID']; ?>" value="B"<?php echo $pnyB; ?> />
                            </div>
                        </td>
                    </tr>
<?php } ?>
                <!-- END matchs -->
                <tr>
                    <td colspan="6" style="text-align: center;">
                        <br><br>
                        <input type="submit" name="iptSubmit" value="Valider" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('input[type="number"]').on('change', checkScore);
</script>
