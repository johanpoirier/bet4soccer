<script language="JavaScript" type="text/javascript">

function toggle_exact_bets(id) {
  var exact_bets = document.getElementById('exact_bets_'+id);
  if(exact_bets.style.display == 'inline') exact_bets.style.display = 'none';
  else exact_bets.style.display = 'inline';
}

function toggle_good_bets(id) {
  var good_bets = document.getElementById('good_bets_'+id);
  if(good_bets.style.display == 'inline') good_bets.style.display = 'none';
  else good_bets.style.display = 'inline';
}

var previous_users_div = 0;

function toggle_score(score) {
  var users_div = document.getElementById('score_'+score);
  if(users_div.style.display == 'inline'){
	  users_div.style.display = 'none';
  } else {
	  users_div.style.display = 'inline';
	  if(previous_users_div != 0) previous_users_div.style.display = 'none';
	  previous_users_div = users_div;
  }
}
</script>
<div id="mainarea">

  <div class="maincontent">
    
    <div class="tag_cloud">
      <span style="font-size: 150%">{NAME}</span>
      <table width="100%">
        <tr><td colspan="5" style="text-align:center;"><i>{DATE}</i></td></tr>
        <tr>
          <td id="{ID}_team_A" width="35%" style="text-align:right;background-color:{TEAM_COLOR_A};" rowspan="3">{TEAM_NAME_A} <img src="{TPL_WEB_PATH}images/flag/{TEAM_NAME_A_URL}.png" /></td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;color:{COLOR};">{SCORE_A}</td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">&nbsp;</td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;color:{COLOR};">{SCORE_B}</td>
          <td id="{ID}_team_B" width="35%" style="text-align:left;background-color:{TEAM_COLOR_B};" rowspan="3"><img src="{TPL_WEB_PATH}images/flag/{TEAM_NAME_B_URL}.png" />  {TEAM_NAME_B}</td>
        </tr>
        <!-- BEGIN avg -->
        <tr>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{avg.A}</td>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">&nbsp;</td>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{avg.B}</td>
        </tr>
        <!-- END avg -->
        <tr>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{ODD_A}</td>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{ODD_NUL}</td>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{ODD_B}</td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
          <a href="javascript:toggle_exact_bets({ID});"><span style="color:red;">{EXACT_BETS}</span></a>
          <div id="exact_bets_{ID}" style="display:none;">
          <!-- BEGIN exact_bets -->
          <br /><a href="?act=view_bets&user={exact_bets.USERID}"><b>{exact_bets.NAME}</b></a>
          <!-- END exact_bets -->
          </div>
          </td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
          <a href="javascript:toggle_good_bets({ID});"><span style="color:red;">{GOOD_BETS}</span></a>
          <div id="good_bets_{ID}" style="display:none;">
          <!-- BEGIN good_bets -->
          <br /><a href="?act=view_bets&user={good_bets.USERID}">{good_bets.NAME}</a>
          <!-- END good_bets -->
          </div>
          </td>
        </tr>
        <tr>
          <td style="text-align:center;color:green;font-weight:300;font-size:11px;">
          <!-- BEGIN score_A -->
          <a href="javascript:toggle_score('{score_A.SCORE}');"><span style="color:black;"><span style="font-size:14px;"><b>{score_A.SCORE}</b></span><br/><i>{score_A.POURCENTAGE}%</i></span></a><br />
          <div id="score_{score_A.SCORE}" style="display:none;">
          <!-- BEGIN users -->
          <a href="?act=view_bets&user={score_A.users.ID}">{score_A.users.NAME}</a><br />
          <!-- END users -->
          </div>
          <!-- END score_A -->
          </td>
          <td style="text-align:center;color:green;font-weight:300;font-size:11px;" colspan="3">
          <!-- BEGIN score_N -->
          <a href="javascript:toggle_score('{score_N.SCORE}');"><span style="color:black;"><span style="font-size:14px;"><b>{score_N.SCORE}</b></span><br/><i>{score_N.POURCENTAGE}%</i></span></a><br />
          <div id="score_{score_N.SCORE}" style="display:none;">
          <!-- BEGIN users -->
          <a href="?act=view_bets&user={score_N.users.ID}">{score_N.users.NAME}</a><br />
          <!-- END users -->
          </div>
          <!-- END score_N -->
          </td>
          <td style="text-align:center;color:green;font-weight:300;font-size:11px;">
          <!-- BEGIN score_B -->
          <a href="javascript:toggle_score('{score_B.SCORE}');"><span style="color:black;"><span style="font-size:14px;"><b>{score_B.SCORE}</b></span><br/><i>{score_B.POURCENTAGE}%</i></span></a><br />
          <div id="score_{score_B.SCORE}" style="display:none;">
          <!-- BEGIN users -->
          <a href="?act=view_bets&user={score_B.users.ID}">{score_B.users.NAME}</a><br />
          <!-- END users -->
          </div>
          <!-- END score_B -->
          </td>
        </tr>
        <tr>
        <tr><td colspan="5" style="text-align:center;">&nbsp;</td></tr>
        </tr>
      </table>
    </div>
          
  </div>
   
  </div>
