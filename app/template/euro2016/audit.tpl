<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Audit log</h1>
            </div>
        </div>

        <div class="tag_cloud">
            <table width="100%">
                <tr>
                    <th width="20%">Date</th>
                    <th width="80%">Action</th>
                </tr>
                <!-- BEGIN logs -->
                <tr>
                    <td>{logs.DATE}</td>
                    <td><a href="/?act=bets&user={logs.USER_ID}">{logs.USER_NAME}</a> {logs.ACTION}</td>
                </tr>
                <!-- END logs -->
            </table>
        </div>
    </div>
</section>
