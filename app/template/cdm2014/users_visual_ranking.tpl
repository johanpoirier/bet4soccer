<div id="mainarea">
  <div class="maincontent">
		<div id="headline">
			<table width="100%">
				<tr>
					<td width="80%" colspan="4"><h1>Classement en relief après {NB_MATCHES}</h1></td>
					<td align="center" width="20%" rowspan="2"></td>
				</tr>
				<tr>
					<td align="center" width="20%">{NB_ACTIVE_USERS} parieurs</td>
					<td align="center" width="20%"><a href="/?act=view_users_ranking">Général</a></td>
					<td align="center" width="20%"><a href="/?act=view_users_visual_ranking"><strong>Relief</strong></a></td>
					<td align="center" width="20%"><a href="/?act=view_groups_ranking">{LABEL_TEAMS_RANKING}</a></td>
				</tr>
			</table>
		</div>
  </div>

  <div class="maincontent">
    <div class="spacer"></div>
    <table>
      <tr>
        <td width="40" style="font-size:80%;text-align:center;"><b>Rang</b></td>
        <td width="425" style="font-size:80%"><b>Parieurs</b></span>
        <td width="40" style="font-size:80%;text-align:center;"><b>Points</b></td>
      </tr>
    </table>
    
	<!-- BEGIN users -->
	<div class="list_element">
		<table class="{users.CLASS}">
			<tr>
				<td width="40" style="font-size:80%;text-align:center;"><strong>{users.RANK}</strong></td>
				<td width="425" class="user_visual">{users.NB}{users.NAME}</td>
				<td width="40" style="font-size:70%;text-align:center;"><strong>{users.POINTS}</strong></td>
			</tr>
		</table>
	</div>
	<!-- END users -->
  </div>

  <div id="rightcolumn">
    <div class="tag_cloud">
      <div class="rightcolumn_headline"><h1>TagBoard</h1></div>
      <div id="tag_0" style="text-align:center;">
        <br />
        <form onsubmit="return saveTag('');">
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
      </div>
      <div id="navig" style="font-size:10px;text-align:center;">
        {NAVIG}
      </div>  
    </div>
  </div>
  <div class="hr"></div>
</div>
<script type="text/javascript">
<!--
  getTags();
//-->
</script>
