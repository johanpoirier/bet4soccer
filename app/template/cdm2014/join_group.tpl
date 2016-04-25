<script language="JavaScript" type="text/javascript">

var is_need_auth = Array();
var group_names = Array();

<!-- BEGIN groups -->
group_names[{groups.ID}] = "{groups.NAME_STRIP}";
<!-- END groups -->

var auto_join = {AUTO_JOIN};
var code = "{CODE}";

function display_auto_join()
{
	if(auto_join > 0) {
		var group_name = group_names[auto_join];
		document.getElementById('code').value = code;
		if(confirm('Souhaitez-vous rejoindre le groupe \''+group_name+'\' ?')) {
			document.join_group_form.submit();
		}
	}
}
function valid_form()
{
	var group = "";
	if(document.getElementById('group') != null) {
		group = document.getElementById('group').value;
	}
	return true;
}
</script>
<div id="mainarea">

		<div id="headline"><h1>Rejoindre un groupe</h1></div>


		<div class="maincontent">

		<div class="ppp">

		        <center><span style="color:red;"><b>{WARNING}</b></span></center>
		<form method="post" id="join_group_form"  name="join_group_form" action="/?act=join_group" onsubmit="return valid_form();">
		<input type="hidden" name="code" id="code">
				<br>
				<div class="formfield"><b>Sélectionner le groupe que vous souhaitez rejoindre</b></div>
				<select name="group" id="group">
				<!-- BEGIN groups -->
				<option name="{groups.ID}" value="{groups.ID}"{groups.SELECTED}>{groups.NAME} ({groups.OWNER_NAME})</option>
				<!-- END groups -->
				</select>
				<br /><br />
				<div class="formfield"><b>Veuillez entrer le mot de passe du groupe :</b></div>
				<input type="password" name="password" size="12" id="password"/>
				<br /><br /><br />
		<input class="image" type="image" src="{TPL_WEB_PATH}/images/submit.gif" value="create it">
		</form>
	
		</div>
		
		
		
		</div>

	</div>
<script language="JavaScript" type="text/javascript">
display_auto_join();
</script>
