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

function changePhase(action) {
    window.location.href = "?act="+action+"";
}

</script>
<div id="mainarea">
  <div class="maincontent">
    <div id="headline" style="height:20px"><h1 style="float: left;">Resultats & Cotes</h1><select class="compact" onchange="changePhase(this.value)" name="sltPhase" style="float: right;">
<option selected="selected" value="view_odds">Poules</option>
<option value="view_finals_odds">Phase finale</option>
</select></div> 
  </div>

<div class="maincontent"><div class="tag_cloud" style="text-align:center;">
<i>Vous trouverez sur cette page, les scores moyens et les cotes des diff&eacute;rents r&eacute;sultats (&Eacute;quipe A gagnante, nul, &eacute;quipe B gagnante). Pour rappel, une cote de 5/1 se lit "5 contre 1" et signifie que dans un syst&egrave;me de paris financiers (ce qui n'est pas le cas ici), une mise serait multipli&eacute;e par 5 si ce r&eacute;sultat s'av&eacute;rait. Ainsi, plus un r&eacute;sultat est sollicit&eacute; plus sa cote est basse. Ces cotes ne sont donc ici qu'une valeur indicative et ne seront pas prises en compte dans le syst&egrave;me des points.</i><br />
<br /><b>Légende :</b><br />
<img src="{TPL_WEB_PATH}/images/cotes_legende.png" />
</div></div>

<!-- BEGIN pools -->  
  <div class="maincontent">
    
    <div class="tag_cloud">
      <span style="font-size: 150%">Groupe {pools.POOL}</span>
      <table width="100%">
        <!-- BEGIN matches -->
        <tr><td colspan="5" style="text-align:center;"><i>{pools.matches.DATE}</i></td></tr>
        <tr>
          <td id="{pools.matches.ID}_team_A" width="35%" style="text-align:right;background-color:{pools.matches.TEAM_COLOR_A};" rowspan="3">{pools.matches.TEAM_NAME_A} <img src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_A_URL}.png" /></td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">{pools.matches.SCORE_A}</td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">&nbsp;</td>
          <td width="10%" style="text-align:center;font-weight:600;font-size:15px;">{pools.matches.SCORE_B}</td>
          <td id="{pools.matches.ID}_team_B" width="35%" style="text-align:left;background-color:{pools.matches.TEAM_COLOR_B};" rowspan="3"><img src="{TPL_WEB_PATH}images/flag/{pools.matches.TEAM_NAME_B_URL}.png" />  {pools.matches.TEAM_NAME_B}</td>
        </tr>
        <tr>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{pools.matches.AVG_A}</td>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">&nbsp;</td>
          <td style="text-align:center;color:blue;font-weight:300;font-size:9px;">{pools.matches.AVG_B}</td>
        </tr>
        <tr>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{pools.matches.ODD_A}</td>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{pools.matches.ODD_NUL}</td>
          <td style="text-align:center;color:green;font-weight:300;font-size:9px;">{pools.matches.ODD_B}</td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
          <a href="javascript:toggle_exact_bets({pools.matches.ID});"><span style="color:red;">{pools.matches.EXACT_BETS}</span></a>
          <div id="exact_bets_{pools.matches.ID}" style="display:none;">
          <!-- BEGIN exact_bets -->
          <br /><a href="?act=view_bets&user={pools.matches.exact_bets.USERID}"><b>{pools.matches.exact_bets.NAME}</b></a>
          <!-- END exact_bets -->
          </div>
          </td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
          <a href="javascript:toggle_good_bets({pools.matches.ID});"><span style="color:red;">{pools.matches.GOOD_BETS}</span></a>
          <div id="good_bets_{pools.matches.ID}" style="display:none;">
          <!-- BEGIN good_bets -->
          <br /><a href="?act=view_bets&user={pools.matches.good_bets.USERID}">{pools.matches.good_bets.NAME}</a>
          <!-- END good_bets -->
          </div>
          </td>
        </tr>
        <tr>
        <tr><td colspan="5" style="text-align:center;">&nbsp;</td></tr>
        </tr>
        <!-- END matches -->
      </table>
    </div>
          
  </div>
  

  <div id="rightcolumn">
  
    <div class="tag_cloud">
    
      <div class="rightcolumn_headline"><h1>Groupe {pools.POOL}</h1></div>
      
      <div id="pool_{pools.POOL}_ranking">
        <table style="font-size:9px;">
          <tr>
            <td width="80%"><b>Nations</b></td><td width="10%"><b>Pts</b></td><td width="10%"><b>Diff</b></td>
          </tr>
          <!-- BEGIN teams -->
          <tr>
            <td id="{pools.teams.ID}_team"><img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png" /> {pools.teams.NAME}</td>
            <td>{pools.teams.POINTS}</td>
            <td>{pools.teams.DIFF}</td>
          </tr>
          <!-- END teams -->
        </table>
      </div>
      
    </div>
    
  </div>
  
<!-- END pools -->  
  </div>
