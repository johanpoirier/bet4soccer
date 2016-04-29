<main>
  <header>
    <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td>
          <div id="logo">
            <a href="index.php"><img src="{TPL_WEB_PATH}images/euro2016.svg" alt="UEFA Euro 2016" border="0" width="50" /></a>
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
  </header>

  <nav>
    <!-- BEGIN admin_bar -->
    <ul class="nav-group">
      <li class="nav-group-item"><a href="/?act=view_ranking">Classement</a></li>
      <li class="nav-group-item"><a href="/?act={FINALS}bets{MATCH_DISPLAY}">Mes pronostics</a></li>
      <li class="nav-group-item"><a href="/?act=view_{FINALS}odds{MATCH_DISPLAY}">Résultats</a></li>
    </ul>
    <ul class="nav-group admin">
      <li class="nav-group-item"><a href="/?act=edit_users">Joueurs</a></li>
      <li class="nav-group-item"><a href="/?act=edit_{FINALS}results">Saisie Résultats</a></li>
      <li class="nav-group-item"><a href="/?act=edit_matches">Saisie Matchs</a></li>
      <li class="nav-group-item"><a href="/?act=edit_teams">Saisie Équipes</a></li>
    </ul>
    <!-- END admin_bar -->
    <!-- BEGIN user_bar -->
    <ul class="nav-group">
      <li class="nav-group-item"><a href="/?act=view_ranking">Classement</a></li>
      <li class="nav-group-item"><a href="/?act={FINALS}bets{MATCH_DISPLAY}">Mes pronostics</a></li>
      <li class="nav-group-item"><a href="/?act=view_{FINALS}odds{MATCH_DISPLAY}">Résultats</a></li>
    </ul>
    <!-- END user_bar -->
    <!-- BEGIN logout_bar -->
    &nbsp;
    <!-- END logout_bar -->
  </nav>