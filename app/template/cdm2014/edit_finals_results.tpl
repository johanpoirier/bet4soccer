<script language="JavaScript" type="text/javascript">

var xmlhttp = getHTTPObject();
var k = 0;
var queue = new Array();
var inProgress = false;

function debug(str) {
  document.getElementById('debug').innerHTML += str+"<br/>";
}

function setWinner(matchID,team,round,rank) {
	var score = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_SCORE').value;
	document.getElementById(round+'TH_'+rank+'_TEAM_W').value = team;
	saveFinalResult(score,matchID,team,round,rank);
}

function saveFinalResult(score,matchID,team,round,rank) {
  if(!inProgress) {
    inProgress = true;
  	var scoreA = document.getElementById(round+'TH_'+rank+'_TEAM_A_SCORE').value;
  	var scoreB = document.getElementById(round+'TH_'+rank+'_TEAM_B_SCORE').value;
  	var teamID = document.getElementById(round+'TH_'+rank+'_TEAM_'+team+'_ID').value;
  	var teamW = '';
  	if(scoreA > scoreB) teamW = 'A';
  	else if(scoreB > scoreA) teamW = 'B';
  	else teamW = document.getElementById(round+'TH_'+rank+'_TEAM_W').value;
    xmlhttp.open("GET", "/?act=save_HTTP_final_result&matchID="+matchID+"&team="+team+"&score="+score+"&teamID="+teamID+"&teamW="+teamW+"&j="+k,true); 
    xmlhttp.onreadystatechange = handleHttpFinalResponse;
    xmlhttp.send(null);
  } else {
    queue.push(score+'|'+matchID+'|'+team+'|'+round+'|'+rank+'|'+k);
  }
  k++;
}   

function handleHttpFinalResponse() {
   if (xmlhttp.readyState == 4) {
     if (xmlhttp.status == 200) {
        var results = xmlhttp.responseText.split("|");
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
          next_match.innerHTML = winner;
          next_match.style.backgroundColor = '#F9F9F9';
          
          // stocke l'ID de l'equipe qualifiee
          document.getElementById(next_round+'TH_'+next_rank+'_TEAM_'+next_team+'_ID').value = document.getElementById(round+'TH_'+rank+'_TEAM_'+teamW+'_ID').value;  
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
        
        document.getElementById('update_ranking').innerHTML = "<b><a href='#' onClick='javascript:updateRanking();'>Classement obsoléte.</a><b>";
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
      xmlhttp.open("GET", "/?act=save_HTTP_final_result&matchID="+matchID+"&team="+team+"&score="+score+"&teamID="+teamID+"&teamW="+teamW+"&j="+k,true); 
      xmlhttp.onreadystatechange = handleHttpFinalResponse;
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
    <div id="headline" style="height:20px"><h1 style="float: left;">Resultats</h1><select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
<option value="edit_results">Poules</option>
<option selected="selected" value="edit_finals_results">Phase finale</option>
</select></div> 
    <div id="update_ranking" class="headline" style="color:red;text-align:right;">{UPDATE_RANK_LINK}</div>
    <div id="generate_stats" class="headline" style="color:red;text-align:right;"><a href="#" onclick="updateStats()">Générer les stats.</a></div>
  </div>


  <div class="maincontent_large"><div class="tag_cloud" style="text-align:center;">
    <b>Les scores de la phase finale sont ceux à l'issue des éventuelles prolongations. En cas de tirs au but, cliquez sur le nom de l'équipe pour la qualifier pour le tour suivant.</b>
  </div></div>
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
<td colspan="2"></td>
</tr>

<tr>
<td align="center" colspan="2" style="border:1px solid #999999;"></td>
<td colspan="2"></td>
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
<td style="border:1px solid #999999;" bgcolor="{finals.rounds.ranks.teams.COLOR}" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_NAME" onClick="javascript:setWinner({finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});">{finals.rounds.ranks.teams.IMG}&nbsp;{finals.rounds.ranks.teams.NAME}</td>
<td style="border:1px solid #999999; text-align:center;font-weight:600;font-size:15px;" bgcolor="{finals.rounds.ranks.teams.COLOR}">
<input type="number" min="0" max="99" size="1" id="{finals.rounds.ROUND}TH_{finals.rounds.ranks.RANK}_TEAM_{finals.rounds.ranks.teams.TEAM}_SCORE" value="{finals.rounds.ranks.teams.SCORE}" onKeyUp="javascript:saveFinalResult(this.value,{finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});" onChange="javascript:saveFinalResult(this.value,{finals.rounds.ranks.MATCH_ID},'{finals.rounds.ranks.teams.TEAM}',{finals.rounds.ROUND},{finals.rounds.ranks.RANK});" />
</td>
<!-- BEGIN top_line -->
<td rowspan="2" style="border-width:2px 3px 0 0; border-style: solid;border-color:black;">&#160;</td>
<!-- END top_line -->
</tr>
<!-- END teams -->
<tr>
<td height="{finals.rounds.ranks.HEIGHT_BOTTOM}">&#160;</td>
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
</div>
