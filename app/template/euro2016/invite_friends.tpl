<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Envoyer des invitations</h1>
            </div>
        </div>

        <div class="ppp">
            <center><span style="color:red;"><b>{WARNING}</b></span></center>
            <!-- BEGIN is_send -->
            <h2>Historique de vos invitations à des groupes</h2>
            <br/>
            <!-- BEGIN send_invitations -->
            {is_send.send_invitations.NAME} ({is_send.send_invitations.GROUP_NAME}) : {is_send.send_invitations.STATUS}
            <br/>
            <!-- END send_invitations -->
            <!-- END is_send -->
            <br/>
            <br/>
            <!-- BEGIN is_group -->
            <h2>Inviter des inscrits à rejoindre vos groupes</h2>
            <form method="post" name="IN" action="/?act=invite_friends">
                <br>
                <input type="hidden" name="type" id="type" value="IN">
                <div class="formfield"><b>Choisisser un ou plusieurs inscrits à rejoindre vos groupes</b></div>
                <br/>
                <!-- BEGIN invit_in -->
                <div>
                    Inviter
                    <select name="userID{is_group.invit_in.ID}" id="userID{is_group.invit_in.ID}">
                        <option name="0" value="0"> -</option>
                        <!-- BEGIN users -->
                        <option name="{is_group.invit_in.users.ID}" value="{is_group.invit_in.users.ID}">{is_group.invit_in.users.NAME}</option>
                        <!-- END users -->
                    </select>
                    au groupe
                    <select name="groupID{is_group.invit_in.ID}" id="groupID{is_group.invit_in.ID}">
                        <!-- BEGIN groups -->
                        <option name="{is_group.invit_in.groups.ID}" value="{is_group.invit_in.groups.ID}">{is_group.invit_in.groups.NAME}</option>
                        <!-- END groups -->
                    </select>
                </div>
                <!-- END invit_in -->
                <input type="submit" value="Inviter"/>
            </form>
            <br/>
            <br/>
            <!-- END is_group -->

            <h2>Inviter des amis</h2>
            <form method="post" name="OUT" action="/?act=invite_friends">
                <input type="hidden" name="type" id="type" value="OUT">
                <div class="formfield">
                    <br/>
                    <strong>Entrez un ou plusieurs emails de vos amis pour les inviter à pronostiquer avec vous !</strong>
                </div>

                <!-- BEGIN invit_out -->
                <div>
                    <input type="email" id="email{invit_out.ID}" name="email{invit_out.ID}"/>
                    qui sera inscrit à
                    <select name="groupID{invit_out.ID}" id="groupID{invit_out.ID}">
                        <option name="0" value="0">Aucun groupe</option>
                        <!-- BEGIN groups -->
                        <option name="{invit_out.groups.ID}" value="{invit_out.groups.ID}">{invit_out.groups.NAME}</option>
                        <!-- END groups -->
                    </select>
                </div>
                <!-- END invit_out -->
                <input type="submit" value="Inviter"/>
            </form>
        </div>
    </div>
</section>
