<script src="{TPL_WEB_PATH}js/jquery.selectboxes.pack.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	var xmlhttp = getHTTPObject();
	
	document.onload = loadTeams();
	
	function loadTeams(idTeamA, idTeamB) {
		pool = $("#pool").val();
		$.ajax({
			type: "GET",
			url: "/",
			data: "act=get_HTTP_teams&pool="+pool,
			success: function(datas){
				handleHttpResponse(datas, idTeamA, idTeamB);
			}
		});
	
		round = $("#round");
		rank = $("#rank");

		if((round != null) && (rank != null))  {
			if(pool == "PF") {
				round.css("visibility", "visible");   
				rank.css("visibility", "visible");   
			} else {
				round.css("visibility", "hidden"); 
				rank.css("visibility", "hidden"); 
			}
		}
	}

	function handleHttpResponse(datas, idTeamA, idTeamB) {
		if (datas.length > 0) {
			var results = datas.split("|");
			var teamA = $("#teamA");
			var teamB = $("#teamB");

			teamA.find('option').remove().end().addOption("", "");
			teamB.find('option').remove().end().addOption("", "");

			for(i=0; i < results.length-1; i++) {
				var result = results[i].split(",");
				teamA.addOption(result[0], result[1]);
				teamB.addOption(result[0], result[1]);
			}
			teamA.sortOptions();
			teamB.sortOptions();

			// select teams
			if(idTeamA) {
				selectListValue('teamA', idTeamA);
			}
			else {
				teamA.selectOptions("", true);
			}
			if(idTeamB) {
				selectListValue('teamB', idTeamB);
			}
			else {
				teamB.selectOptions("", true);
			}
		}
	}

	function loadMatchPool(matchID) {
		loadMatch(matchID, 0);
	}

	function loadMatchRounds(matchID) {
		loadMatch(matchID, 1);
	}

	function loadMatch(matchID, isRounds) {
		var XHR = new XHRConnection();	
		XHR.resetData();
		XHR.appendData("act", "get_HTTP_match");
		XHR.appendData("matchID", matchID);
		XHR.appendData("isRounds", isRounds);
		XHR.sendAndLoad("/", "GET", handleMatchHttpResponse);
	}

	var handleMatchHttpResponse = function(obj) {
		matchDatas = obj.responseText.split("|");

		// group & round
		selectListValue('pool', matchDatas[0]);
		selectListValue('round', matchDatas[1]);
		selectListValue('rank', matchDatas[2]);

		// date & hour
		selectListValue('day', matchDatas[3]);
		selectListValue('month', matchDatas[4]);
		//selectListValue('year', matchDatas[5]);
		$("#hour").val(matchDatas[6]);
		$("#minute").val(matchDatas[7]);

		// teams
		loadTeams( matchDatas[8], matchDatas[9]);

		// id match for update
		$("#matchID").val(matchDatas[10]);
	}
        
        $(document).ready(function() {
            lastPool = "{LAST_POOL}";
            if(lastPool.length > 0) {
                round = $("#round");
		rank = $("#rank");
		if((round != null) && (rank != null))  {
                    if(lastPool == "PF") {
                        round.css("visibility", "visible");   
                        rank.css("visibility", "visible");   
                    } else {
                        round.css("visibility", "hidden"); 
                        rank.css("visibility", "hidden"); 
                    }
		}
                
                selectListValue('pool', lastPool);
                loadTeams();
            }
        });
</script>
<div id="mainarea">
	<div id="headline">
		<h1>Matches</h1>
	</div>

	<div class="maincontent">
		<!-- BEGIN pools -->
		<div class="tag_cloud">
			<span style="font-size: 150%">Groupe {pools.NAME}</span>
			<!-- BEGIN matches -->
			<div id="match_{pools.matches.ID}" onclick="loadMatchPool({pools.matches.ID})">
				{pools.matches.DATE} - {pools.matches.TEAM_NAME_A} - {pools.matches.TEAM_NAME_B}
			</div>
			<!-- END matches -->
		</div>
		<!-- END pools -->

		<!-- BEGIN rounds -->
		<div class="tag_cloud">
			<span style="font-size: 150%">{rounds.NAME}</span>
			<!-- BEGIN matches -->
			<div id="match_{rounds.matches.ID}" onclick="loadMatchRounds({rounds.matches.ID})">
				{rounds.matches.DATE} - {rounds.matches.TEAM_NAME_A} - {rounds.matches.TEAM_NAME_B}
			</div>
			<!-- END matches -->
		</div>
		<!-- END rounds -->

		<div class="tag_cloud">
			<form name="add_team" action="/?act=add_match" method="post">
				<input type="hidden" id="matchID" name="matchID" value="" />
				<table>
					<tr>
						<td>Groupe/Phase finale :</td>
					</tr>
				
					<tr>
						<td><select name="pool" id="pool" onchange="loadTeams()">
							<option name="0" value="0"></option>
							<!-- BEGIN pools -->
							<option name="{pools.VALUE}" value="{pools.VALUE}" {pools.SELECTED}>{pools.NAME}</option>
							<!-- END pools -->
							<option name="PF" value="PF">PF</option>
						</select>&nbsp;<select name="round" id="round">
							<option name="0" value="0"></option>
							<!-- BEGIN rounds -->
							<option name="{rounds.VALUE}" value="{rounds.VALUE}">{rounds.NAME}</option>
							<!-- END rounds -->
						</select>&nbsp;<select name="rank" id="rank">
							<option name="0" value="0"></option>
							<!-- BEGIN rank -->
							<option name="{rank.RANK}" value="{rank.RANK}">{rank.RANK}</option>
							<!-- END rank -->
						</select></td>
					</tr>
					<tr>
						<td>Date :</td>
					</tr>
					<tr>
						<td><select name="day" id="day">
							<!-- BEGIN days -->
							<option name="{days.DAY}" value="{days.DAY}" {days.SELECTED}>{days.DAY}</option>
							<!-- END days -->
						</select>&nbsp;<select name="month" id="month">
							<!-- BEGIN months -->
							<option name="{months.VALUE}" value="{months.VALUE}" {months.SELECTED}>{months.NAME}</option>
							<!-- END months -->
						</select> {YEAR}
						<input type="text" size="2" name="hour" id="hour" value="21" />h<input type="text" size="2" name="minute" id="minute" value="00" />
						</td>
					</tr>
					<tr>
						<td>Equipe A :</td>
						<td>Equipe B :</td>
					</tr>
					<tr>
						<td><select name="teamA" id="teamA" /></td>
						<td><select name="teamB" id="teamB" /></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;"><input type="submit"
							name="add_match" value="Ajouter/Modifier" /></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>