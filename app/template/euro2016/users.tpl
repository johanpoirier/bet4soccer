<script type="text/javascript">

    var xmlhttp = getHTTPObject();

    var groups = new Array()
    <!-- BEGIN groups -->
    groups[{groups.ID_GROUP}] = "{groups.NAME}";
    <!-- END groups -->

    function getUser(userID) {
        xmlhttp.open("GET", "/?act=get_HTTP_user&userID=" + userID + "", true);
        xmlhttp.onreadystatechange = handleGetUserResponse;
        xmlhttp.send(null);
    }

    function handleGetUserResponse() {
        if (xmlhttp.readyState == 4) {
            var results = xmlhttp.responseText.split("|");
            var IDuser = results[0];
            var name = results[1];
            var login = results[2];
            var password = results[3];
            var email = results[4];
            var IDgroup = results[5];
            var status = results[6];

            var nameObj = document.getElementById('name');
            var loginObj = document.getElementById('login');
            var emailObj = document.getElementById('email');
            var groupObj = document.getElementById('group');
            var statusObj = document.getElementById('admin');

            var delButton = document.getElementById('del_user');
            var addButton = document.getElementById('add_user');
            var idUser = document.getElementById('idUser');
            var realname = document.getElementById('realname');

            nameObj.value = name;
            realname.value = name;
            loginObj.value = login;
            emailObj.value = email;

            for (i = 0; i < groupObj.options.length; i++) {
                if (groupObj.options[i].value == IDgroup) {
                    groupObj.options[i].selected = true;
                }
            }

            if (status == 1) statusObj.checked = true;
            else statusObj.checked = false;
            addButton.value = "Ajouter / Modifier";
            delButton.style.display = "inline";
            idUser.value = IDuser;
        }
    }

    function saveUser() {
        var name = document.getElementById('name').value;
        var login = document.getElementById('login').value;
        var pass = document.getElementById('pass').value;
        var email = document.getElementById('email').value;
        var groupID = document.getElementById('group').options[document.getElementById('group').selectedIndex].value;
        var status = document.getElementById('admin').checked;

        if (status) status = 1;
        else status = 0;

        xmlhttp.open("GET", "/?act=save_HTTP_user&name=" + name + "&login=" + login + "&pass=" + pass + "&email=" + email + "&groupID=" + groupID + "&status=" + status + "", true);
        xmlhttp.onreadystatechange = handleSaveUserResponse;
        xmlhttp.send(null);
    }

    function handleSaveUserResponse() {
        if (xmlhttp.readyState == 4) {
            getUsers()
        }
    }

    function delUser() {
        var idUser = document.getElementById('idUser').value;
        var realname = document.getElementById('realname').value;
        if (confirm('Etes-vous sûr de vouloir supprimer ' + realname + '?')) {
            xmlhttp.open("GET", "/?act=del_HTTP_user&userID=" + idUser + "", true);
            xmlhttp.onreadystatechange = handleDelUserResponse;
            xmlhttp.send(null);
        }
    }

    function handleDelUserResponse() {
        if (xmlhttp.readyState == 4) {
            getUsers();
        }
    }

    function getUsers() {
        xmlhttp.open("GET", "/?act=get_HTTP_users", true);
        xmlhttp.onreadystatechange = handleGetUsersResponse;
        xmlhttp.send(null);
    }

    function handleGetUsersResponse() {
        if (xmlhttp.readyState == 4) {
            var results = xmlhttp.responseText.split("|");
            var list = document.getElementById('list_users');

            var HTML_list = "<table width=\"100%\"><tr><th width=\"40%\">Nom</th><th width=\"30%\">Login</th><th width=\"30%\">Groupe</th></tr>";
            for (i = 0; i < (results.length - 1); i++) {
                var result = results[i].split(";");
                var IDuser = result[0];
                var login = result[1];
                var name = result[2];
                var email = result[3];
                var IDgroup = result[4];
                var group_name = groups[IDgroup];
                if (!group_name) {
                    group_name = "";
                }
                var status = result[5];
                HTML_list += "<tr id=\"user_" + IDuser + "\" onclick=\"getUser(" + IDuser + ");\">";
                if (status == 1) {
                    HTML_list += "<td><b>" + name + "</b></td><td>" + login + "</td><td>" + group_name + "</td>";
                }
                else {
                    HTML_list += "<td>" + name + "</td><td>" + login + "</td><td>" + group_name + "</td>";
                }
                HTML_list += "</tr>";
            }
            HTML_list += "</table>";
            list.innerHTML = HTML_list;
        }
    }


    function getGroup(groupID) {
        xmlhttp.open("GET", "/?act=get_HTTP_group&groupID=" + groupID + "", true);
        xmlhttp.onreadystatechange = handleGetGroupResponse;
        xmlhttp.send(null);
    }

    function deleteGroup() {
        var idGroup = document.getElementById('group_id').value;
        var groupname = document.getElementById('group_name').value;
        if (confirm('Etes-vous sûr de vouloir supprimer ' + groupname + '?')) {
            xmlhttp.open("GET", "/?act=del_HTTP_group&groupID=" + idGroup, true);
            xmlhttp.onreadystatechange = handleDeleteGroupResponse;
            xmlhttp.send(null);
        }
    }

    function handleGetGroupResponse() {
        if (xmlhttp.readyState == 4) {
            var results = xmlhttp.responseText.split("|");
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
    }

    function saveGroup() {
        var group_id = document.getElementById('group_id').value;
        var group_name = document.getElementById('group_name').value;

        xmlhttp.open("GET", "/?act=save_HTTP_group&group_id=" + group_id + "&group_name=" + group_name + "", true);
        xmlhttp.onreadystatechange = handleSaveGroupResponse;
        xmlhttp.send(null);
    }

    function handleSaveGroupResponse() {
        if (xmlhttp.readyState == 4) {
            getGroups();
        }
    }

    function handleDeleteGroupResponse() {
        if (xmlhttp.readyState == 4) {
            document.getElementById('group_id').value = "";
            document.getElementById('group_name').value = "";
            getGroups();
        }
    }

    function getGroups() {
        xmlhttp.open("GET", "/?act=get_HTTP_groups", true);
        xmlhttp.onreadystatechange = handleGetGroupsResponse;
        xmlhttp.send(null);
    }

    function handleGetGroupsResponse() {
        if (xmlhttp.readyState == 4) {
            var results = xmlhttp.responseText.split("|");
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
    }


</script>
<div id="mainarea">

    <div class="maincontent">
        <div id="headline"><h1>Parieurs</h1></div>
    </div>

    <div class="maincontent">

        <div class="tag_cloud" id="list_users">
            <table width="100%">
                <tr>
                    <th width="30%">Nom</th>
                    <th width="30%">Login</th>
                    <th width="20%">Groupe</th>
                    <th width="10%">Cnx</th>
                    <th width="10%">Vote</th>
                </tr>
                <!-- BEGIN users -->
                <tr id="user_{users.ID}" onclick="javascript:getUser({users.ID});">
                    <!-- BEGIN user -->
                    <td>{users.user.NAME}</td>
                    <td>{users.user.LOGIN}</td>
                    <td>{users.user.GROUP_NAME}</td>
                    <td>{users.user.LAST_CONNECTION}</td>
                    <td>{users.user.LAST_BET}</td>
                    <!-- END user -->
                    <!-- BEGIN admin -->
                    <td><b>{users.admin.NAME}</b></td>
                    <td>{users.admin.LOGIN}</td>
                    <td>{users.admin.GROUP_NAME}</td>
                    <td>{users.admin.LAST_CONNECTION}</td>
                    <td>{users.admin.LAST_BET}</td>
                    <!-- END admin -->
                </tr>
                <!-- END users -->
            </table>
        </div>

        <div class="tag_cloud">
            <form name="add_user" action="/?act=add_user" method="post">
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

        <div class="tag_cloud">
            <form name="money" action="/?act=set_setting" method="post">
                <input type="hidden" name="setting" value="MONEY"/>
                <input type="hidden" name="act" value="edit_users"/>
                Montant de la <a href="/?act=money">cagnotte</a> : <br/><br/>
                <input type="text" name="value" size="4" value="{MONEY}"/>&nbsp;<input type="submit" name="submit"
                                                                                       value="Ok"/>
            </form>
        </div>

        <div class="tag_cloud">
            <form name="add_csv_users" action="/?act=import_csv_file" method="post" enctype="multipart/form-data">
                Importer un fichier csv ('login;pass;nom;email;group_name;admin') : <br/><br/>
                <input type="file" name="csv_file" size="40"/>&nbsp;<input type="submit" name="submit" value="Ok"/>
            </form>
        </div>

        <br/>
        <br/>
        <br/>
    </div>

    <div class="maincontent">
        <div id="headline"><h1>Groupes</h1></div>
    </div>
    <div class="maincontent">
        <div class="tag_cloud" id="list_groups">
            <!-- BEGIN groups -->
            <div id="group{groups.ID_GROUP}" onclick="getGroup({groups.ID_GROUP})">{groups.NAME} - {groups.COUNT}
                joueur(s)
            </div>
            <!-- END groups -->
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

        <br/>
        <br/>
        <br/>
    </div>

    <div class="maincontent">
        <div id="headline"><h1>Losers</h1></div>
    </div>
    <div class="maincontent">
        <div class="tag_cloud" id="list_losers">
            <!-- BEGIN losers -->
            <div id="loser{losers.ID}">{losers.NAME};{losers.EMAIL}</div>
            <!-- END losers -->
        </div>
    </div>
</div>
