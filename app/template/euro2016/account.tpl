<section id="mainarea">

	<div class="maincontent">
		<div class="headline">
			<div class="headline-title">
				<h1>Mon compte</h1>
			</div>
		</div>

		<div class="ppp">
			<center><span style="color: red;"><b>{WARNING}</b></span></center>
			<ul>
				<li>
					<h2><a href="/?act=change_account">Modifier mon compte</a></h2>
				</li>
				<!-- BEGIN join_group -->
				<li>
					<h2><a href="/?act=create_group">Créer un groupe</a></h2>
				</li>
				<li>
					<h2><a href="/?act=join_group">Rejoindre un groupe</a></h2>
				</li>
				<!-- END join_group -->
				<!-- BEGIN leave_group1 -->
				<li>
					<h2><a href="/?act=leave_group&groupID={leave_group1.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group1.GROUP_NAME_JS} ?'));">
						Quitter mon groupe ({leave_group1.GROUP_NAME})
					</a></h2>
				</li>
				<!-- END leave_group1 -->
				<!-- BEGIN leave_group2 -->
				<li>
				<h2><a href="/?act=leave_group&groupID={leave_group2.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group2.GROUP_NAME_JS} ?'));">
					Quitter mon groupe ({leave_group2.GROUP_NAME})</a></h2>
				</li>
				<!-- END leave_group2 -->
				<!-- BEGIN leave_group3 -->
				<li>
				<h2><a href="/?act=leave_group&groupID={leave_group3.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group3.GROUP_NAME_JS} ?'));">
					Quitter mon groupe ({leave_group3.GROUP_NAME})</a></h2>
				</li>
				<!-- END leave_group3 -->
				<li>
					<h2><a href="/?act=invite_friends">Inviter des amis</a></h2>
				</li>
			</ul>			
		</div>
	</div>
</section>
