<script type="text/javascript" src="{TPL_WEB_PATH}js/jquery.flot.min.js"> </script>
<script type="text/javascript">
var xmlhttp = getHTTPObject();
var k = 0;
var queue = new Array();
var inProgress = false;

function debug(str) {
  document.getElementById('debug').innerHTML += str+"<br/>";
}

function reloadMatch(matchID,team,round,rank) {
	var teamReal = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_TEAM_REAL').value;
	var prev_round = round * 2;
	var prev_rank = 0
	if(team == 'A') prev_rank =	(rank * 2) - 1;
	if(team == 'B') prev_rank =	(rank * 2);
	if(teamReal != '') {
    document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_ID').value = document.getElementById(prev_round+'TH_'+prev_rank+'_TEAM_'+teamReal+'_ID').value;  
    document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_NAME').innerHTML = document.getElementById(prev_round+'TH_'+prev_rank+'_TEAM_'+teamReal+'_NAME').innerHTML;  
	}
}

function setWinner(matchID,team,round,rank) {
	var score = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_SCORE').value;
	document.getElementById(round+'TH_'+rank+'_TEAM_W').value = team;
	reloadMatch(matchID,team,round,rank);
	saveBet(score,matchID,team,round,rank);
}

function saveBet(score,matchID,team,round,rank) {
  if(!inProgress) {
    inProgress = true;
  	var scoreA = document.getElementById(round+'TH_'+rank+'_TEAM_A_SCORE').value;
  	var scoreB = document.getElementById(round+'TH_'+rank+'_TEAM_B_SCORE').value;
  	var teamID = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_ID').value;
  	var teamW = '';
  	if(scoreA > scoreB) teamW = 'A';
  	else if(scoreB > scoreA) teamW = 'B';
  	else teamW = document.getElementById(round+'TH_'+rank+'_TEAM_W').value;
    xmlhttp.open("GET", "/?act=save_HTTP_final_bet&userID={CURRENT_USER_ID}&matchID="+matchID+"&team="+team+"&score="+score+"&teamID="+teamID+"&teamW="+teamW+"&j="+k,true); 
    xmlhttp.onreadystatechange = handleHttpResponse;
    xmlhttp.send(null);
  } else {
    queue.push(score+'|'+matchID+'|'+team+'|'+round+'|'+rank+'|'+k);
  }
  k++;
}   

function handleHttpResponse() {
   if (xmlhttp.readyState == 4) {
     if (xmlhttp.status == 200) {
        var results = xmlhttp.responseText.split("|");
	if(xmlhttp.responseText.length > 15) alert(xmlhttp.responseText);
        var matchID = results[0];
        var round = results[1];
        var rank = results[2];
        var teamW = results[3];
        var num = results[4];
        
        // stocke l'équipe gagnante
  		  document.getElementById(round+'TH_'+rank+'_TEAM_W').value = teamW;
  
        // récupére les scores
        var scoreA = document.getElementById(round+'TH_'+rank+'_TEAM_A_SCORE').value;
        var scoreB = document.getElementById(round+'TH_'+rank+'_TEAM_B_SCORE').value;
  
        // récupére les lignes des équipes
        var teamA = document.getElementById(round+'TH_'+rank+'_TEAM_A_NAME');
        var teamB = document.getElementById(round+'TH_'+rank+'_TEAM_B_NAME');
        
        // récupére la ligne de l'équipe gagnante      
        var team = document.getElementById(round+'TH_'+rank+'_TEAM_'+teamW+'_NAME');
        var winner = team.innerHTML;
        
        // couleurs de base
        teamA.style.backgroundColor = '#F9F9F9';
        teamB.style.backgroundColor = '#F9F9F9';
            
        // equipe gagnante en vert
        team.style.backgroundColor = '#99FF99';
        
        // qualifie l'equipe gagnante pour la phase suivante (sauf pour la finale et le match de la 3ème place)
        if(round != 3 && round != 1) {
          var next_rank = Math.ceil(rank/2);
          var next_round =  Math.ceil(round/2);
          var next_team = '';
          if(rank % 2 == 1) next_team = 'A';
          else next_team = 'B';
          
          var next_match = document.getElementById(next_round+'TH_'+next_rank+'_TEAM_'+next_team+'_NAME');
		  if(next_match) {
			next_match.innerHTML = winner;
			next_match.style.backgroundColor = '#F9F9F9';
          
			// stocke l'ID de l'equipe qualifiee
			document.getElementById(next_round+'TH_'+next_rank+'_TEAM_'+next_team+'_ID').value = document.getElementById(round+'TH_'+rank+'_TEAM_'+teamW+'_ID').value;  
		  } else {
			console.warn("game not found : ", next_round, next_rank, next_team);
		  }
        }
       
        // les perdants pour le match de la 3ème place
        if(round == 2) {
          var next_team = '';
          if(rank % 2 == 1) next_team = 'A';
          else next_team = 'B';
          
          var lost_team = ''
          if(teamW == 'A') lost_team = 'B';
          else lost_team = 'A';
   
          team = document.getElementById(round+'TH_'+rank+'_TEAM_'+lost_team+'_NAME');
        }
      }

    if(queue.length > 0) {
      var request = queue.shift().split("|");
      var score = request[0];
      matchID = request[1];
      team = request[2];
      round = request[3];
      rank = request[4];
      var k = request[5];
  		var scoreA = document.getElementById(round+'TH_'+rank+'_TEAM_A_SCORE').value;
  		var scoreB = document.getElementById(round+'TH_'+rank+'_TEAM_B_SCORE').value;
  		var teamID = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_ID').value;
  		var teamW = '';
  		if(scoreA > scoreB) teamW = 'A';
  		else if(scoreB > scoreA) teamW = 'B';
  		else teamW = document.getElementById(round+'TH_'+rank+'_TEAM_W').value;
      xmlhttp.open("GET", "/?act=save_HTTP_final_bet&userID={USERID}&matchID="+matchID+"&team="+team+"&score="+score+"&teamID="+teamID+"&teamW="+teamW+"&j="+k,true); 
      xmlhttp.onreadystatechange = handleHttpResponse;
      xmlhttp.send(null);
    } else inProgress = false;
  }
}

function changePhase(action) {
    window.location.href = "?act="+action+"";
}

</script>
<div id="mainarea">
	<div class="maincontent">
		<div id="headline" style="height:20px">
			<h1 style="float: left;">Pronostics de {CURRENT_USER}</h1>
			<select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
				<option value="bets">Poules</option>
				<option selected="selected" value="finals_bets">Phase finale</option>
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
	
	<form name="save_finals_bets" action="/?act=save_finals_bets" method="post">
		<div class="maincontent_large">
			<div class="tag_cloud" style="text-align:center;">
				<b>Les scores de la phase finale sont ceux à l'issue des éventuelles prolongations. En cas de tirs au but, cliquez sur le nom de l'équipe pour la qualifier pour le tour suivant.</b>
			</div>
		</div>
		<div class="maincontent_large">
			<div class="tag_cloud">
				<!-- BEGIN finals -->
				<table border="0" cellpadding="0" cellspacing="0" style="font-size: 90%; margin-left: 20px; margin-right: 20px; width: 100%;">
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
									<td width="250">&#160;</td>
									<td width="30">&#160;</td>
									<td width="10">&#160;</td>
									<td width="30">&#160;</td>
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
								<input type="hidden" name="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_MATCH_ID" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_MATCH_ID" value="{finals.rounds.ranks.MATCH_ID}" />
								<input type="hidden" name="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_W" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_W" value="{finals.rounds.ranks.TEAM_W}" />
							<!-- BEGIN teams -->
								<input type="hidden" name="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_ID" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_ID" value="{finals.rounds.ranks.teams.ID}" />
								<input type="hidden" name="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_TEAM_REAL" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_TEAM_REAL" value="{finals.rounds.ranks.teams.TEAM_REAL}" />
								<tr height="25px">
								<!-- BEGIN edit -->
									<td style="border:1px solid #999999;" bgcolor="{finals.rounds.ranks.teams.COLOR}" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_NAME" onClick="javascript:setWinner({finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});">{finals.rounds.ranks.teams.IMG}&nbsp;{finals.rounds.ranks.teams.NAME}</td>
									<td style="border:1px solid #999999; text-align:center;font-weight:600;font-size:15px;" bgcolor="{finals.rounds.ranks.teams.COLOR}">
										<input type="text" size="1" name="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_SCORE" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_SCORE" value="{finals.rounds.ranks.teams.SCORE}" onKeyUp="javascript:saveBet(this.value,{finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});" onChange="javascript:saveBet(this.value,{finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});" />
									</td>
								<!-- END edit -->
								<!-- BEGIN view -->
									<td style="border:1px solid #999999;" bgcolor="{finals.rounds.ranks.teams.COLOR}" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_NAME">{finals.rounds.ranks.teams.IMG}&nbsp;{finals.rounds.ranks.teams.NAME}</td>
									<td style="border:1px solid #999999; text-align:center;font-weight:600;font-size:15px;" bgcolor="{finals.rounds.ranks.teams.COLOR}">
										<b>{finals.rounds.ranks.teams.SCORE}</b>&nbsp;<span style="text-align:center;color:blue;font-weight:300;font-size:9px;">{finals.rounds.ranks.teams.RESULT}</span>
									</td>
								<!-- END view -->
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
		</div>
		<div class="maincontent_large" style="text-align:center;">
			<p><input type="image" name="submit" {SUBMIT_STATE} src="{TPL_WEB_PATH}/images/submit.gif" /></p>    
		</div>
	</form>
</div>