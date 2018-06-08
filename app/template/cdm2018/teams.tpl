<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Equipes</h1>
            </div>
        </div>

        <div class="tag_cloud">
            <form name="add_team" action="/?act=add_team" method="post">
                <table>
                    <tr>
                        <td>Equipe :</td>
                        <td>Groupe / Niv. phase finale :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="name" size="26"/></td>
                        <td><input type="text" name="pool" size="24"/></td>
                        <td><input type="hidden" name="fifaRank" size="10" value="1" /></td>
                        <td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="add_team" value="Ajouter"/></td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- BEGIN pools -->
        <div class="tag_cloud">
            <span style="font-size: 150%">Groupe {pools.NAME}</span>
            <!-- BEGIN teams -->
            <br/><img src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png"/>&nbsp;{pools.teams.NAME}
            <!-- END teams -->
        </div>
        <!-- END pools -->
    </div>
</section>
