<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Résultats & Cotes</h1>
            </div>
            <div class="headline-menu">
                <button class="headline-button phase" data-value="view_finals_odds"><i class="icon-final"></i>Phase finale</button>
                <button class="headline-button order" data-page="view_odds" data-value="pool"><i class="icon-sort-name-up"></i> Trier par poule</button>
            </div>
        </div>

        <div class="tag_cloud odds-help">
            <i>Vous trouverez sur cette page, les scores moyens et les cotes des diff&eacute;rents r&eacute;sultats
                (&Eacute;quipe A gagnante, nul, &eacute;quipe B gagnante). Pour rappel, une cote de 5/1 se lit "5
                contre 1" et signifie que dans un syst&egrave;me de paris financiers (ce qui n'est pas le cas ici),
                une mise serait multipli&eacute;e par 5 si ce r&eacute;sultat s'av&eacute;rait. Ainsi, plus un r&eacute;sultat
                est sollicit&eacute; plus sa cote est basse. Ces cotes ne sont donc ici qu'une valeur indicative et
                ne seront pas prises en compte dans le syst&egrave;me des points.</i><br/>
            <br/><b>Légende :</b><br/>
            <img src="{TPL_WEB_PATH}/images/cotes_legende.png"/>
        </div>

        <div class="tag_cloud">
            <table width="100%">
                <!-- BEGIN matches -->
                <tr>
                    <td colspan="6" style="text-align:center;"><i>{matches.DATE}</i></td>
                </tr>
                <tr>
                    <td><i>({matches.POOL})</i></td>
                    <td id="{matches.ID}_team_A" width="35%"
                        style="text-align:right;background-color:{matches.TEAM_COLOR_A};" rowspan="3">
                        {matches.TEAM_NAME_A}
                        <img src="{TPL_WEB_PATH}images/flag/{matches.TEAM_NAME_A_URL}.png"/>
                    </td>
                    <td width="10%"
                        style="text-align:center;font-weight:600;font-size:15px;">{matches.SCORE_A}</td>
                    <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">&nbsp;</td>
                    <td width="10%"
                        style="text-align:center;font-weight:600;font-size:15px;">{matches.SCORE_B}</td>
                    <td id="{matches.ID}_team_B" width="35%"
                        style="text-align:left;background-color:{matches.TEAM_COLOR_B};" rowspan="3">
                        <img src="{TPL_WEB_PATH}images/flag/{matches.TEAM_NAME_B_URL}.png"/>
                        {matches.TEAM_NAME_B}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{matches.AVG_A}</td>
                    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">&nbsp;</td>
                    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{matches.AVG_B}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{matches.ODD_A}</td>
                    <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{matches.ODD_NUL}</td>
                    <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{matches.ODD_B}</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:center;color:red;font-weight:300;font-size:9px;">
                        <button onclick="toggle_exact_bets({matches.ID})"
                                class="link red">{matches.EXACT_BETS}</button>
                        <div id="exact_bets_{matches.ID}" style="display:none;">
                            <!-- BEGIN exact_bets -->
                            <br/><a href="/?act=view_bets&user={matches.exact_bets.USERID}"><b>{matches.exact_bets.NAME}</b></a>
                            <!-- END exact_bets -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:center;color:red;font-weight:300;font-size:9px;">
                        <button onclick="toggle_good_bets({matches.ID})"
                                class="link red">{matches.GOOD_BETS}</button>
                        <div id="good_bets_{matches.ID}" style="display:none;">
                            <!-- BEGIN good_bets -->
                            <br/><a href="/?act=view_bets&user={matches.good_bets.USERID}">{matches.good_bets.NAME}</a>
                            <!-- END good_bets -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:center;">&nbsp;</td>
                </tr>
                <!-- END matches -->
            </table>
        </div>
    </div>

    <aside>
        <div class="headline">
            <div class="headline-title">
                <h2>Classements</h2>
            </div>
        </div>
        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h2>Groupe {pools.POOL}</h2></div>
            <div id="pool_{pools.POOL}_ranking">
                <table class="ranking-pool">
                    <tr>
                        <td width="80%"><b>Nations</b></td>
                        <td width="10%"><b>Pts</b></td>
                        <td width="10%"><b>Diff</b></td>
                    </tr>
                    <!-- BEGIN teams -->
                    <tr>
                        <td id="{pools.teams.ID}_team">
                            <img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png"/>
                            {pools.teams.NAME}
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
<script type="text/javascript">
    $(document).ready(headlineButtonsInit);
</script>
