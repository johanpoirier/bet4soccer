<section id="mainarea">

	<div class="maincontent">
		<div class="headline">
			<div class="headline-title">
				<h1>Mon compte</h1>
			</div>
		</div>

		<div class="user-account">
			<span class="warning" style="{WARNING_STYLE}">{WARNING}</span>
			<ul>
				<li>
					<a href="/?act=change_account">
						<i class="icon-user"></i>
						Modifier mon compte
					</a>
				</li>
			</ul>
			<ul>
				<!-- BEGIN join_group -->
				<li>
					<a href="/?act=create_group">
						<i class="icon-plus"></i>
						Créer un groupe
					</a>
				</li>
				<li>
					<a href="/?act=join_group">
						<i class="icon-group"></i>
						Rejoindre un groupe
					</a>
				</li>
				<!-- END join_group -->
				<!-- BEGIN leave_group1 -->
				<li>
					<a href="/?act=leave_group&groupID={leave_group1.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group1.GROUP_NAME_JS} ?'));">
						<i class="icon-exit"></i>
						Quitter mon groupe ({leave_group1.GROUP_NAME})
					</a>
				</li>
				<!-- END leave_group1 -->
				<!-- BEGIN leave_group2 -->
				<li>
					<a href="/?act=leave_group&groupID={leave_group2.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group2.GROUP_NAME_JS} ?'));">
						<i class="icon-exit"></i>
						Quitter mon groupe ({leave_group2.GROUP_NAME})
					</a>
				</li>
				<!-- END leave_group2 -->
				<!-- BEGIN leave_group3 -->
				<li>
					<a href="/?act=leave_group&groupID={leave_group3.GROUP_ID}" onclick="return(confirm('Êtes-vous sûr de vouloir quitter le groupe {leave_group3.GROUP_NAME_JS} ?'));">
						<i class="icon-exit"></i>
						Quitter mon groupe ({leave_group3.GROUP_NAME})
					</a>
				</li>
				<!-- END leave_group3 -->
			</ul>
			<ul>
				<li>
					<a href="/?act=invite_friends">
						<i class="icon-invite"></i>
						Inviter des amis
					</a>
				</li>
			</ul>			
		</div>
	</div>
</section>
