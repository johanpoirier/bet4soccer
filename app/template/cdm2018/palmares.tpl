<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Palmares : {COMPETITION_NAME}</h1>
            </div>
        </div>

        <table class="ranking">
            <tr>
                <th width="10%">Rang</th>
                <th width="25%" class="aligned">Parieur</th>
                <th width="12%">Points</th>
                <th width="12%">Scores Exacts</th>
                <th width="12%">Résultats Justes</th>
            </tr>

            <!-- BEGIN users -->
            <tr class="list_element">
                <td><b>{users.RANK}</b></td>
                <td class="aligned">
                    <b>{users.NAME}</b>
                </td>
                <td><b>{users.POINTS}</b></td>
                <td>{users.SCORES}</td>
                <td>{users.RESULTS}</td>
            </tr>
            <!-- END users -->
        </table>
    </div>

    <aside>
        <div class="headline">
            <div class="headline-title">
                <h2>Compétitions</h2>
            </div>
        </div>
        <div class="tag_cloud">
            <!-- BEGIN competitions -->
            <div class="competition">
                <a href="?act=palmares&id={competitions.ID}">{competitions.NAME}</a>
            </div>
            <!-- END competitions -->
        </div>
    </aside>
</section>
