<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
function changePhase(action) {
    window.location.href = "?act="+action+"";
}

</script>
<div id="mainarea">
	<div class="maincontent">
		<div id="headline" style="height:20px">
			<h1 style="float: left;">Pronostics de {CURRENT_USER}</h1>
			<select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
				<option value="view_bets{USER_URL}">Poules</option>
				<option selected="selected" value="view_finals_bets{USER_URL}">Phase finale</option>
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
	
	<div class="maincontent_large">
		<div class="tag_cloud" style="text-align:center;">
			<b>Les scores de la phase finale sont ceux à l'issue des éventuelles prolongations.</b>
		</div>
	</div>

	<div class="maincontent_large">
		<div class="tag_cloud">
		<!-- BEGIN finals -->
		<table border="0" cellpadding="0" cellspacing="0" style="font-size: 90%;  margin-left: 20px; margin-right: 20px; width: 100%;">
			<tr>
<!-- BEGIN rounds -->
<td>
<!-- BEGIN merge_top -->
<table border="0" cellpadding="0" cellspacing="0" style="font-size: 90%; margin:0;">
<!-- END merge_top -->
<tr height="25px" >
<td align="center" colspan="2" style="border:1px solid #999999;" bgcolor="#CFCFCF">{finals.rounds.NAME}</td>
<td colspan="3"></td>
</tr>

<tr>
<td align="center" colspan="2" style="border:1px solid #999999;"></td>
<td colspan="3"></td>
</tr>

<tr>
<td width="150">&#160;</td>
<td width="30">&#160;</td>
<td width="10">&#160;</td>
<td width="15">&#160;</td>
</tr>
<!-- BEGIN ranks -->
<tr>
<td colspan="2" height="{finals.rounds.ranks.HEIGHT_TOP}">&#160;</td>
<!-- BEGIN bottom_line -->
<td rowspan="3" style="border-width:0 3px 2px 0; border-style: solid;border-color:black;">&#160;</td>
<td rowspan="3" style="border-width:2px 0 0 0; border-style: solid;border-color:black;">&#160;</td>
<!-- END bottom_line -->
</tr>
<tr>
<td colspan="2">{finals.rounds.ranks.DATE}</td>
</tr>
<input type="hidden" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_W" value="{finals.finals.rounds.ranks.TEAM_W}" />
<!-- BEGIN teams -->
<input type="hidden" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_ID" value="{finals.rounds.ranks.teams.ID}" />
<tr height="25px">
<td style="border:1px solid #999999;" bgcolor="{finals.rounds.ranks.teams.COLOR}" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_NAME">{finals.rounds.ranks.teams.IMG}&nbsp;{finals.rounds.ranks.teams.NAME}</td>
<td style="border:1px solid #999999; text-align:center;font-weight:600;font-size:15px;" bgcolor="{finals.rounds.ranks.teams.COLOR}">
<b>{finals.rounds.ranks.teams.SCORE}</b>&nbsp;<span style="text-align:center;color:blue;font-weight:300;font-size:9px;">{finals.rounds.ranks.teams.RESULT}</span></td>
<!-- BEGIN points -->
<td style="text-align:center;font-weight:300;font-size:9px;color:{finals.rounds.ranks.COLOR};">{finals.rounds.ranks.POINTS}<br /><span style="color:black;">{finals.rounds.ranks.DIFF}</span></td>
<!-- END points -->
<!-- BEGIN top_line -->
<td rowspan="2" style="border-width:2px 3px 0 0; border-style: solid;border-color:black;">&#160;</td>
<!-- END top_line -->
</tr>
<!-- END teams -->
<tr>
<td colspan="2" height="{finals.rounds.ranks.HEIGHT_BOTTOM}">&#160;</td>
</tr>
<!-- END ranks -->
<!-- BEGIN merge_bottom -->
</table>
<!-- END merge_bottom -->
</td>
<!-- END rounds -->
</tr>
</table>
<!-- END finals --> 
</div>
 </div></div>
