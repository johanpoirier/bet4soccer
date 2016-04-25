<div id="mainarea">

		<div id="headline"><h1>Envoyer des invitations</h1></div>


		<div class="maincontent">

		<div class="ppp">
				        <center><span style="color:red;"><b>{WARNING}</b></span></center>
<!-- BEGIN is_send -->
		<h2>Historique de vos invitations à des groupes</h2>
		<!-- BEGIN send_invitations -->		
		{is_send.send_invitations.NAME} ({is_send.send_invitations.GROUP_NAME}) : {is_send.send_invitations.STATUS}<br/>
		<!-- END send_invitations -->		
<!-- END is_send -->	
<!-- BEGIN is_group -->	
		<h2>Inviter des inscrits à rejoindre vos groupes</h2>
				<form method="post" name="IN" action="/?act=invite_friends">
				<br>
				<input type="hidden" name="type" id="type" value="IN">
				<div class="formfield"><b>Choisisser un ou plusieurs inscrits à rejoindre vos groupes</b></div><br />
				<!-- BEGIN invit_in -->
				Inviter <select name="userID{is_group.invit_in.ID}" id="userID{is_group.invit_in.ID}">
				<option name="0" value="0"> - </option>
				<!-- BEGIN users -->
				<option name="{is_group.invit_in.users.ID}" value="{is_group.invit_in.users.ID}">{is_group.invit_in.users.NAME}</option>
				<!-- END users -->
				</select> au groupe <select name="groupID{is_group.invit_in.ID}" id="groupID{is_group.invit_in.ID}">
				<!-- BEGIN groups -->
				<option name="{is_group.invit_in.groups.ID}" value="{is_group.invit_in.groups.ID}">{is_group.invit_in.groups.NAME}</option>
				<!-- END groups -->
				</select><br /><br />
				<!-- END invit_in -->
				<br /><br />
				<center><input class="image" type="image" src="{TPL_WEB_PATH}/images/submit.gif" value="Valider"></center>
				</form>
<!-- END is_group -->				
		<h2>Inviter des amis</h2>
				<form method="post" name="OUT"  action="/?act=invite_friends">
				<br>
				<input type="hidden" name="type" id="type" value="OUT">
				<div class="formfield"><b>Entrez un ou plusieurs emails de vos amis pour les inviter à pronostiquer avec vous !</b></div><br />
				<!-- BEGIN invit_out -->
				<input type="text" id="email{invit_out.ID}" name="email{invit_out.ID}" /> qui sera inscrit à <select name="groupID{invit_out.ID}" id="groupID{invit_out.ID}">
				<option name="0" value="0">Aucun groupe</option>
				<!-- BEGIN groups -->
				<option name="{invit_out.groups.ID}" value="{invit_out.groups.ID}">{invit_out.groups.NAME}</option>
				<!-- END groups -->
				</select><br /><br />
				<!-- END invit_out -->
				<br /><br />
				<center><input class="image" type="image" src="{TPL_WEB_PATH}/images/submit.gif" value="Valider"></center>
				</form>
	
		</div>
		
		</div>

	</div>
