<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
function changePhase(action) {
    window.location.href = "?act="+action+"";
}
</script>
<div id="mainarea">
	<div class="maincontent">
		<div id="headline" style="height:20px"><h1 style="float: left;">Pronostics de {CURRENT_USER}</h1><select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
			<option selected="selected" value="view_bets{USER_URL}">Poules</option>
			<option value="view_finals_bets{USER_URL}">Phase finale</option>
			</select>
		</div> 
	</div>
  
    <div class="maincontent">
		<!-- BEGIN stats -->
		<div style="text-align:center;width:50%;float:left;"><br/><strong>{stats.TYPE}</strong></div>
		<!-- END stats -->
		<!-- BEGIN stats -->
		<div class="stats" id="stats_{stats.ID}" style="height: 120px;width:50%;float:left;"></div>
		<script type="text/javascript">
			var data = {stats.DATA};
			$.plot("#stats_{stats.ID}", data,
			{
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

			function showTooltip(x, y, contents) {
				$("<div id='tooltip'>" + contents + "</div>").css({
					position: "absolute",
					display: "none",
					top: y - 30,
					left: x - 10,
					border: "1px solid #fdd",
					padding: "2px",
					"background-color": "#fee",
					opacity: 0.80
				}).appendTo("body").fadeIn(200);
			}

			var previousPoint = null;
			$("#stats_{stats.ID}").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.dataIndex) {

						previousPoint = item.dataIndex;

						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
								y = item.datapoint[1].toFixed(2);

						showTooltip(item.pageX, item.pageY, parseInt(y));
					}
				} else {
					$("#tooltip").remove();
					previousPoint = null;
				}
			});
		</script>
		<!-- END stats -->
	</div>
	
<!-- BEGIN pools -->
<div class="maincontent"> 
  <div class="tag_cloud">
    <span style="font-size: 150%">
      Groupe {pools.POOL}
    </span>
    <table width="100%">
      <!-- BEGIN bets -->
      <!-- BEGIN view -->
      <tr>
        <td colspan="5" style="text-align:center;">
          <i>{pools.bets.view.DATE}</i></td>
      </tr>
      <tr>
        <td id="{pools.bets.view.ID}_team_A" width="35%" rowspan="2" style="text-align:right;background-color:{pools.bets.view.TEAM_COLOR_A};">
          {pools.bets.view.TEAM_NAME_A} 
          <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_A_URL}.png" /></td>
        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
          {pools.bets.view.SCORE_A}</td>
        <td width="10%" style="text-align:center;font-weight:300;font-size:9px;color:{pools.bets.view.COLOR};" rowspan="2" >
          {pools.bets.view.POINTS}<br />
          <span style="color:black;">
            {pools.bets.view.DIFF}
          </span></td>
        <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">
          {pools.bets.view.SCORE_B}</td>
        <td id="{pools.bets.view.ID}_team_B" width="35%" rowspan="2" style="text-align:left;background-color:{pools.bets.view.TEAM_COLOR_B};">
          <img src="{TPL_WEB_PATH}images/flag/{pools.bets.view.TEAM_NAME_B_URL}.png" />
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
</div> 
<div id="rightcolumn"> 
  <div class="tag_cloud"> 
    <div class="rightcolumn_headline">
      <h1>Groupe {pools.POOL}</h1>
    </div> 
    <div id="pool_{pools.POOL}_ranking">
      <table style="font-size:9px;">
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
            <img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png" />
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
</div> 
<!-- END pools -->
</div>