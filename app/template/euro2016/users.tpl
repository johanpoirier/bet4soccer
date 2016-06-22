<script type="text/javascript">
    var groups = [];
    <!-- BEGIN groups -->
    groups[{groups.ID_GROUP}] = "{groups.NAME}";
    <!-- END groups -->

    function getUser(userID) {
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_user&userID=" + userID,
            success: handleGetUserResponse
        });
        window.location.hash = '#user_form';
    }

    function handleGetUserResponse(user) {
        window.location.hash = '';

        $('#name').val(user.name);
        $('#realname').val(user.name);
        $('#login').val(user.login);
        $('#email').val(user.email);
        $('#idUser').val(user.userID);

        var groupObj = document.getElementById('group');
        var statusObj = document.getElementById('admin');

        var delButton = document.getElementById('del_user');
        var addButton = document.getElementById('add_user');
        var idUser = document.getElementById('idUser');

        for (var i = 0; i < groupObj.options.length; i++) {
            if (groupObj.options[i].value == user.groupID) {
                groupObj.options[i].selected = true;
            }
        }

        statusObj.checked = (parseInt(user.status, 10) === 1);
        addButton.value = "Ajouter / Modifier";
        delButton.style.display = "inline";
    }

    function saveUser() {
        var name = document.getElementById('name').value;
        var login = document.getElementById('login').value;
        var pass = document.getElementById('pass').value;
        var email = document.getElementById('email').value;
        var groupID = document.getElementById('group').options[document.getElementById('group').selectedIndex].value;
        var status = document.getElementById('admin').checked;

        $.ajax({
            type: "GET",
            url: "/",
            data: "act=save_HTTP_user&name=" + name + "&login=" + login + "&pass=" + pass + "&email=" + email + "&groupID=" + groupID + "&status=" + (status ? 1 : 0),
            success: getUsers
        });
    }

    function delUser() {
        var idUser = $('#idUser').val();
        var realname = $('#realname').val();
        if (confirm('Etes-vous sûr de vouloir supprimer ' + realname + '?')) {
            $.ajax({
                type: "GET",
                url: "/",
                data: "act=del_HTTP_user&userID=" + idUser,
                success: getUsers
            });
        }
    }

    function getUsers() {
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_users",
            success: handleGetUsersResponse
        });
    }

    function handleGetUsersResponse(users) {
        var list = $('#list_users tbody');
        list.html('');

        for (var i = 0; i < users.length; i++) {
            var user = users[i];
            var groups = [];
            if (user.groupName) {
                groups.push(user.groupName);
            }
            if (user.groupName2) {
                groups.push(user.groupName2);
            }
            if (user.groupName3) {
                groups.push(user.groupName3);
            }

            var userLine = "<tr id=\"user_" + user.userID + "\" onclick=\"getUser(" + user.userID + ");\">";
            if (parseInt(user.status, 10) === 1) {
                userLine += "<td><strong>" + user.name + "</strong></td>";
            }
            else {
                userLine += "<td>" + user.name + "</td>";
            }
            userLine += "<td>" + user.login + "</td><td>" + groups.join(', ') + "</td>";
            userLine += "<td>" + (user.last_connection ? user.last_connection : '') + "</td><td>" + (user.last_bet ? user.last_bet : '') + "</td><td></td>";
            userLine += "</tr>";
            list.append(userLine);
        }
    }


    function getGroup(groupID) {
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_group&groupID=" + groupID,
            success: handleGetGroupResponse
        });
    }

    function deleteGroup() {
        var idGroup = document.getElementById('group_id').value;
        var groupname = document.getElementById('group_name').value;
        if (confirm('Etes-vous sûr de vouloir supprimer ' + groupname + '?')) {
            $.ajax({
                type: "GET",
                url: "/",
                data: "act=del_HTTP_group&groupID=" + idGroup,
                success: function() {
                    document.getElementById('group_id').value = "";
                    document.getElementById('group_name').value = "";
                    getGroups();
                }
            });
        }
    }

    function handleGetGroupResponse(data) {
        var results = data.split("|");
        var groupID = results[0];
        var name = results[1];

        var groupNameObj = document.getElementById('group_name');

        var addButton = document.getElementById('add_group');
        var delButton = document.getElementById('del_group');
        var idGroup = document.getElementById('group_id');

        groupNameObj.value = name;

        addButton.value = "Ajouter / Modifier";
        delButton.style.display = "inline";
        idGroup.value = groupID;
    }

    function saveGroup() {
        var group_id = document.getElementById('group_id').value;
        var group_name = document.getElementById('group_name').value;

        $.ajax({
            type: "GET",
            url: "/",
            data: "act=save_HTTP_group&group_id=" + group_id + "&group_name=" + group_name,
            success: getGroups
        });
    }

    function getGroups() {
        $.ajax({
            type: "GET",
            url: "/",
            data: "act=get_HTTP_groups",
            success: handleGetGroupsResponse
        });
    }

    function handleGetGroupsResponse(data) {
        var results = data.split("|");
        var list = document.getElementById('list_groups');

        var HTML_list = "";
        for (i = 0; i < (results.length - 1); i++) {
            var result = results[i].split(";");
            var IDgroup = result[0];
            var name = result[1];
            var nb_users = result[2];
            HTML_list += "<div id=\"group" + IDgroup + "\" onClick=\"getGroup(" + IDgroup + ")\">";
            HTML_list += name + " - " + nb_users + " joueur(s)";
            HTML_list += "</div>";
        }
        list.innerHTML = HTML_list;
    }


</script>

<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Parieurs</h1>
            </div>
        </div>

        <div class="tag_cloud">
            <form name="add_user" action="/?act=add_user" method="post" id="user_form">
                <input type="hidden" id="idUser" value=""/>
                <input type="hidden" id="realname" value=""/>
                <table>
                    <tr>
                        <td width="40%">Login :</td>
                        <td width="40%">Pass :</td>
                        <td width="20%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><input type="text" size="25" name="login" id="login"/></td>
                        <td><input type="text" size="25" name="pass" id="pass"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Nom :</td>
                        <td>Groupe :</td>
                        <td>Admin :</td>
                    </tr>
                    <tr>
                        <td><input type="text" size="25" name="name" id="name"/></td>
                        <td><select name="group" id="group">
                                <option name="" value=""></option>
                                <!-- BEGIN groups -->
                                <option name="{groups.ID_GROUP}" value="{groups.ID_GROUP}">{groups.NAME}</option>
                                <!-- END groups -->
                            </select></td>
                        <td style="text-align:center;"><input type="checkbox" name="admin" id="admin" value="1"/></td>
                    </tr>
                    <tr>
                        <td colspan=3>Email :</td>
                    </tr>
                    <tr>
                        <td colspan=3><input type="text" size="50" name="email" id="email"/></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align:center;">
                            <input type="button" name="add_user" id="add_user" onclick="saveUser()" value="Ajouter"/>
                            &nbsp;&nbsp;
                            <input type="button" name="del_user" id="del_user" onclick="delUser()" value="Supprimer"
                                   style="display:none;"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="tag_cloud" id="list_users">
            <table width="100%">
                <thead>
                    <tr>
                        <th width="30%">Nom</th>
                        <th width="20%">Login</th>
                        <th width="20%">Groupes</th>
                        <th width="10%">Cnx</th>
                        <th width="10%">Vote</th>
                        <th width="10%">Paris</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN users -->
                    <tr id="user_{users.ID}" onclick="getUser({users.ID})">
                        <!-- BEGIN user -->
                        <td>{users.user.NAME}</td>
                        <td>{users.user.LOGIN}</td>
                        <td>{users.user.GROUP_NAME}</td>
                        <td>{users.user.LAST_CONNECTION}</td>
                        <td>{users.user.LAST_BET}</td>
                        <td>{users.user.BETS_COUNT}</td>
                        <!-- END user -->
                        <!-- BEGIN admin -->
                        <td><b>{users.admin.NAME}</b></td>
                        <td>{users.admin.LOGIN}</td>
                        <td>{users.admin.GROUP_NAME}</td>
                        <td>{users.admin.LAST_CONNECTION}</td>
                        <td>{users.admin.LAST_BET}</td>
                        <td>{users.admin.BETS_COUNT}</td>
                        <!-- END admin -->
                    </tr>
                    <!-- END users -->
                </tbody>
            </table>
        </div>


        <div class="headline">
            <div class="headline-title">
                <h1>Groupes</h1>
            </div>
        </div>

        <div class="tag_cloud">
            <form name="add_group" action="/?act=add_group" method="post">
                <input type="hidden" id="group_id" value=""/>
                <table>
                    <tr>
                        <td width="40%">Nom :</td>
                    </tr>
                    <tr>
                        <td><input type="text" size="25" name="group_name" id="group_name"/></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <input type="button" name="add_group" id="add_group" onclick="saveGroup()" value="Ajouter"/>
                            <input type="button" name="del_group" id="del_group" onclick="deleteGroup()"
                                   value="Supprimer" style="display:none;"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="tag_cloud" id="list_groups">
            <!-- BEGIN groups -->
            <div id="group{groups.ID_GROUP}" onclick="getGroup({groups.ID_GROUP})">{groups.NAME} - {groups.COUNT}
                joueur(s)
            </div>
            <!-- END groups -->
        </div>
    </div>
</section>
