<div id="mainarea">
  <div class="maincontent">
		<div id="headline">
			<table width="100%">
				<tr>
					<td width="80%" colspan="4"><h1>Classement général après {NB_MATCHES}</h1></td>
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
					<td align="center" width="20%">{NB_ACTIVE_USERS} parieurs</td>
					<td align="center" width="20%"><a href="/?act=view_users_ranking"><strong>Général</strong></a></td>
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
  <!-- BEGIN mine -->
  <br/>	
  <div class="focus_mine">
  <b>Votre classement</b><br />
  <div style="float:left;font-size:23px;height:45px;margin-left:10px;margin-top:10px;margin-right:5px;vertical-align:top;text-align:center;">
	<b>{mine.RANK}</b><sup>e</sup><br/>
	<span style="font-size: 0.4em;">{mine.POINTS} pts</span>
  </div>
  <span style="font-size:11px;";><br /><a href="#{mine.ID}">{USERNAME}</a></span><br />
  <span style="font-size:10px;";><i>{mine.EVOL}</i></span><br /><br />
  </div>
  <!-- END mine -->
  <!-- BEGIN max -->
  <div class="focus_most">
  <b>+ forte hausse</b><br /><br />
  <div style="float:left;margin:2px;"><img src="{TPL_WEB_PATH}images/hausse.png" width="30px" /></div>
  <span style="font-size:11px;";><a href="#{max.ID}">{max.NAME}</a></span><br />
  <span style="font-size:10px;";><i>{max.EVOL}</i></span><br /><br />
  </div>	
  <!-- END max -->
  <!-- BEGIN min -->
  <div class="focus_most">
 <b>+ forte baisse</b><br /><br />
  <div style="float:left;margin:2px;"><img src="{TPL_WEB_PATH}images/baisse.png" width="30px" /></div>
  <span style="font-size:11px;";><a href="#{min.ID}">{min.NAME}</a></span><br />
  <span style="font-size:10px;";><i>{min.EVOL}</i></span><br /><br />
  </div>
  <!-- END min -->
  <div class="spacer"></div>
    <table>
      <tr>
        <td width="35" style="font-size:80%;text-align:center;"><b>Rang</b></td>
        <td width="{WIDTH_USERS}" style="font-size:80%"><b>Parieur</b></span>
        <td width="{WIDTH_TEAMS}" style="font-size:80%"><b>Equipe</b></span>
        <td width="65" style="font-size:80%;text-align:center;"><b>Points</b></td>
        <td width="65" style="font-size:80%;text-align:center;"><b>Scores Exacts</b></td>
        <td width="65" style="font-size:80%;text-align:center;"><b>R&eacute;sultats Justes</b></td>
        <td width="65" style="font-size:80%;text-align:center;"><b>Ecart Scores</b></td>
      </tr>
    </table>
    
    <!-- BEGIN users -->
    <a name="{users.ID}"></a>
    <div class="list_element">
      <table style="background-color:{users.COLOR};">
        <tr>
          <td width="35" style="font-size:80%;text-align:center;"><b>{users.RANK}</b> {users.LAST_RANK}</td>
          <td width="{WIDTH_USERS}" style="font-size:70%">
          	<b>{users.VIEW_BETS}{users.NAME}</a></b> {users.NB_MISS_BETS}
          </td>
          <td width="{WIDTH_TEAMS}" style="font-size:70%"><i>{users.GROUP}</i>
          <td width="65" style="font-size:70%;text-align:center;"><b>{users.POINTS}</b></td>
          <td width="65" style="font-size:70%;text-align:center;">{users.NBSCORES}</td>
          <td width="65" style="font-size:70%;text-align:center;">{users.NBRESULTS}</td>
          <td width="65" style="font-size:70%;text-align:center;">{users.DIFF}</td>
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
