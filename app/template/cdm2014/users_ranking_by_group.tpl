<div id="mainarea">
  <div class="maincontent">
		<div id="headline">
			<table width="100%">
				<tr>
					<td width="80%" colspan="4"><h1>{GROUP_NAME} : classement après {NB_MATCHES}</h1></td>
					<td align="center" width="20%" rowspan="2">
					<!-- BEGIN g1 -->
					<a href="/?act=view_users_ranking_by_group&groupID={g1.GROUP_ID}">{g1.GROUP_NAME}</a><br/>
					<!-- END g1 -->
					<!-- BEGIN g2 -->
					<a href="/?act=view_users_ranking_by_group&groupID={g2.GROUP_ID2}">{g2.GROUP_NAME2}</a><br/>
					<!-- END g2 -->
					<!-- BEGIN g3 -->
					<a href="/?act=view_users_ranking_by_group&groupID={g3.GROUP_ID3}">{g3.GROUP_NAME3}</a><br/>
					<!-- END g3 -->
					</td>
				</tr>
				<tr>
					<td align="center" width="20%">({NB_ACTIVE_USERS}/{NB_USERS} parieurs)</td>
					<td align="center" width="20%"><a href="/?act=view_users_ranking">Général</a></td>
					<td align="center" width="20%"><a href="/?act=view_users_visual_ranking">Relief</a></td>
					<td align="center" width="20%"><a href="/?act=view_groups_ranking">{LABEL_TEAMS_RANKING}</a></td>
				</tr>
			</table>
		</div>
	</div>
<!-- BEGIN money -->
 <div style="float:right;width:160px;">
 <i>Montant de la cagnotte</i> :<br />
 <center><span style="font-size:20px;"><a href="/?act=money"><b>{money.AMOUNT} €</b></a></span></center>
 </div>
<!-- END money -->
  <div class="maincontent">		
  	<table>
  		<tr>
  			<td width="45" style="font-size:80%;text-align:center;"><b>Rang</b></td>
  			<td width="200" style="font-size:80%"><b>Parieur</b></span>
  			<td width="60" style="font-size:80%;text-align:center;"><b>Points</b></td>
  			<td width="60" style="font-size:80%;text-align:center;"><b>Scores Exacts</b></td>
  			<td width="60" style="font-size:80%;text-align:center;"><b>R&eacute;sultats Justes</b></td>
  			<td width="60" style="font-size:80%;text-align:center;"><b>Ecart de scores</b></td>
  		</tr>
  	</table>
  
  	<!-- BEGIN users -->
  	<div class="list_element">
  		<table style="background-color:{users.COLOR};">
  			<tr>
  				<td width="45" style="font-size:80%;text-align:center;"><strong>{users.RANK}</strong></td>
  				<td width="200" style="font-size:70%"><strong>{users.VIEW_BETS}{users.NAME}</a></strong> {users.NB_MISS_BETS}</td>
  				<td width="60" style="font-size:70%;text-align:center;"><strong>{users.POINTS}</strong></td>
  				<td width="60" style="font-size:70%;text-align:center;">{users.NBSCORES}</td>
  				<td width="60" style="font-size:70%;text-align:center;">{users.NBRESULTS}</td>
  				<td width="60" style="font-size:70%;text-align:center;">{users.DIFF}</td>
  			</tr>
  		</table>
  	</div>
  	<!-- END users -->
  </div>

	<div id="rightcolumn">
		<div class="tag_cloud">
			<div class="rightcolumn_headline"><h1 style="color:black;">Team TagBoard</h1></div>
			<div id="tag_0" style="text-align:center;">
        <br />
				<form onsubmit="return saveTag({GROUP_ID});">
					<input type="text" id="tag" value="" size="18" />
          <br />
					<span style="font-size:8px;">(Entrée pour envoyer)</span>
          <br />
          <br />
				</form>
			</div>
			<div id="tags">
         <!-- BEGIN tags -->      
        <div id="tag_{tags.ID}">
          {tags.DEL_IMG}
          <u>{tags.DATE}{TAG_SEPARATOR}<b>{tags.USER}</b></u>
          <br />
          {tags.TEXT}
          <br />
          <br />
        </div>
        <!-- END tags -->
  			<div id="navig" style="font-size:10px;text-align:center;">
  				{NAVIG}
  			</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
<!--
  getTags({GROUP_ID});
//-->
</script>
