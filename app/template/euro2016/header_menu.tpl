<div id="mainn">
  <div id="head1" style="height:110px">
    <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td>
          <div id="logo">
            <a href="index.php"><img src="{TPL_WEB_PATH}images/euro2016.svg" alt="UEFA Euro 2016" border="0" /></a>
          </div>
		</td>
	    <td valign="middle">
			<table>
			  <tr>
				<td style="font-size:80%" align="right" valign="top">
				  <!-- BEGIN matches -->
				  {matches.MATCH_STR}
				  <!-- BEGIN ext_list -->
				  <b>{matches.ext_list.TEAM_NAME_A} - {matches.ext_list.TEAM_NAME_B}</b><br />
				  <!-- END ext_list -->
				  <!-- BEGIN list -->
				  <b><a href="#" onclick="window.open('/?act=view_match_stats&matchID={matches.list.ID}','statistiques','menubar=no, status=no, scrollbars=no, menubar=no, location=no, width=555, height=555')">{matches.list.TEAM_NAME_A} - {matches.list.TEAM_NAME_B}</a></b><br />
				  <!-- END list -->
				  <!-- END matches -->
				</td>
			  <tr>
				<td>
					<div>
						<br />
						<!-- BEGIN account -->
						<a href="/?act=account">
							<img src="{TPL_WEB_PATH}images/account.gif" width="20px" border="0" alt="Mon compte" />
							<b>{USERNAME}</b>
						</a>
						<!-- END account -->
						<i>{HEADER_GROUP_NAME}</i>
					</div>
			    </td>
			  </tr>
		    </table>
		</td>
		<td valign="middle"></td>
      </tr>
    </table>
  </div>

  <div id="nav_area">
    <!-- BEGIN admin_bar -->
    <img src="{TPL_WEB_PATH}images/admin_bar.png" usemap="#testbar5" border="0" alt="" />
    <map name="testbar5">
      <area shape="rect" coords="12,4,115,30" href="/?act=view_ranking" target="" />
      <area shape="rect" coords="117,4,220,30" href="/?act={FINALS}bets{MATCH_DISPLAY}" target="" />
      <area shape="rect" coords="222,4,325,30" href="/?act=view_{FINALS}odds{MATCH_DISPLAY}" target="" />
      <area shape="rect" coords="327,4,430,30" href="/?act=edit_users" target="" />
      <area shape="rect" coords="432,4,535,30" href="/?act=edit_{FINALS}results" target="" />
      <area shape="rect" coords="537,4,640,30" href="/?act=edit_matches" target="" />
      <area shape="rect" coords="642,4,744,30" href="/?act=edit_teams" target="" />
    </map>
    <!-- END admin_bar -->
    <!-- BEGIN user_bar -->
    <img src="{TPL_WEB_PATH}images/user_bar.png" usemap="#testbar5" border="0" alt="" />
    <map name="testbar5">
      <area shape="rect" coords="12,4,254,30" href="/?act=view_ranking" target="" />
      <area shape="rect" coords="256,4,498,30" href="/?act={FINALS}bets{MATCH_DISPLAY}" target="" />
      <area shape="rect" coords="500,4,742,30" href="/?act=view_{FINALS}odds{MATCH_DISPLAY}" target="" />
   </map>
    <!-- END user_bar -->
    <!-- BEGIN logout_bar -->
    &nbsp;
    <!-- END logout_bar -->
  </div>