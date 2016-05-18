<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
    $(document).ready(headlineButtonsInit);
</script>
<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1 style="float: left;">Pronostics de {CURRENT_USER}</h1>
            </div>
            <div class="headline-menu">
                <button class="headline-button phase" data-value="finals_bets">Phase finale</button>
            </div>
        </div>

        <div class="tag_cloud user-infos">
            <!-- BEGIN stats -->
            <div class="user-stats">
                <h4 class="user-stats-title">{stats.TYPE}</h4>
                <div class="user-stats-chart" id="stats_{stats.ID}"></div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $.plot("#stats_{stats.ID}", {stats.DATA}, {
                        colors: [ "{stats.COLOR}" ],
                        xaxis: {
                            ticks: {stats.XSERIE}
                        },
                        yaxis: {
                            min: {stats.YMIN},
                            max: {stats.YMAX},
                            {stats.INVERSE}
                            ticks: {stats.YTICKS},
                            tickDecimals: 0
                        },
                        grid: {
                            backgroundColor: "#ffffff",
                            hoverable: true
                        }
                    });

                    var previousPoint = null;
                    $("#stats_{stats.ID}").bind("plothover", function (event, pos, item) {
                        if (item) {
                            if (previousPoint != item.dataIndex) {
                                previousPoint = item.dataIndex;

                                $("#tooltip").remove();
                                var x = item.datapoint[0].toFixed(2);
                                var y = item.datapoint[1].toFixed(2);

                                showTooltip(item.pageX, item.pageY, parseInt(y));
                            }
                        } else {
                            $("#tooltip").remove();
                            previousPoint = null;
                        }
                    });
                });
            </script>
            <!-- END stats -->
        </div>

        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <span style="font-size: 150%">Groupe {pools.POOL}</span>
            <table width="100%">
                <!-- BEGIN bets -->
                <!-- BEGIN view -->
                <tr>
                    <td colspan="5" style="text-align:center;">
                        <i>{pools.bets.view.DATE}</i></td>
                </tr>
                <tr>
                    <td id="{pools.bets.view.ID}_team_A" width="35%" rowspan="2"
                        style="text-align:right;background-color:{pools.bets.view.TEAM_COLOR_A};">
                        {pools.bets.view.TEAM_NAME_A}
                        <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_A_URL}.png"/></td>
                    <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                        {pools.bets.view.SCORE_A}</td>
                    <td width="10%"
                        style="text-align:center;font-weight:300;font-size:9px;color:{pools.bets.view.COLOR};"
                        rowspan="2">
                        {pools.bets.view.POINTS}<br/>
          <span style="color:black;">
            {pools.bets.view.DIFF}
          </span></td>
                    <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
                        {pools.bets.view.SCORE_B}</td>
                    <td id="{pools.bets.view.ID}_team_B" width="35%" rowspan="2"
                        style="text-align:left;background-color:{pools.bets.view.TEAM_COLOR_B};">
                        <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_B_URL}.png"/>
                        {pools.bets.view.TEAM_NAME_B}</td>
                </tr>
                <tr>
                    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                        {pools.bets.view.RESULT_A}</td>
                    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">
                        {pools.bets.view.RESULT_B}</td>
                </tr>
                <!-- END view -->
                <!-- END bets -->
            </table>
        </div>
        <!-- END pools -->
    </div>

    <aside>
        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <div>
                <h3>Groupe {pools.POOL}</h3>
            </div>
            <div id="pool_{pools.POOL}_ranking">
                <table class="ranking-pool">
                    <tr>
                        <td width="80%">
                            <b>Nations</b></td>
                        <td width="10%">
                            <b>Pts</b></td>
                        <td width="10%">
                            <b>Diff</b></td>
                    </tr>
                    <!-- BEGIN teams -->
                    <tr>
                        <td id="{pools.teams.ID}_team">
                            <img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png"/>
                            {pools.teams.NAME}</td>
                        <td>
                            {pools.teams.POINTS}</td>
                        <td>
                            {pools.teams.DIFF}</td>
                    </tr>
                    <!-- END teams -->
                </table>
            </div>
        </div>
        <!-- END pools -->
    </aside>
</section>