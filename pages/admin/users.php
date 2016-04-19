<?php
$teams = $engine->getUserTeams();
$teams[] = array('userTeamID' => 0, 'name' => 'Sans équipe');
?>
<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Parieurs</h1>
        </div>
    </div>

    <div class="tag_cloud">
        <form name="add_user" action="/?op=add_user" method="post">
            <input type="hidden" id="idUser" value=""/>
            <input type="hidden" id="realname" value=""/>
            <table>
                <tr>
                    <td width="45%">Login :</td>
                    <td width="45%">Pass :</td>
                </tr>
                <tr>
                    <td><input type="text" size="25" name="login" id="login" required/></td>
                    <td><input type="text" size="25" name="pass" id="pass"/></td>
                </tr>
                <tr>
                    <td>Nom :</td>
                    <td>Courriel :</td>
                </tr>
                <tr>
                    <td><input type="text" size="25" name="name" id="name" required/></td>
                    <td><input type="email" size="25" name="mail" id="mail" required/></td>
                </tr>
                <tr>
                    <td>Equipe :</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <select name="sltUserTeam" id="sltUserTeam">
                            <?php foreach ($teams as $team) { ?>
                                <option value="<?php echo $team['userTeamID']; ?>"><?php echo $team['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>Admin <input type="checkbox" name="admin" id="admin" value="1"/></td>
                </tr>
                <tr>
                    <td colspan=2 style="text-align:center;">
                        <input type="submit" name="add_user" class="big" id="add_user" value="Ajouter / Modifier"/>
                        <input type="submit" name="add_user" id="del_user" value="Supprimer"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php foreach ($teams as $team) { ?>
        <div id="<?php echo $team['userTeamID']; ?>">
            <?php
            $users = $engine->getUsersByUserTeam($team['userTeamID'], 'all');
            ?>
            <div class="tag_cloud" id="list_users">
                <h3><?php echo $team['name']; ?></h3>
                <?php foreach ($users as $user) { ?>
                    <div id="user_<?php echo $user['userID']; ?>" onclick="getUser(<?php echo $user['userID']; ?>)">
                        <?php echo $user['name']; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="tag_cloud">
        <form name="add_csv_users" action="/?op=import_csv_file" method="post" enctype="multipart/form-data">
            Importer un fichier csv ('login;pass;nom;teamname;admin') : <br/><br/>
            <input type="file" name="csv_file" size="40"/>&nbsp;<input type="submit" name="submit" value="Ok"/>
        </form>
    </div>
</div>
