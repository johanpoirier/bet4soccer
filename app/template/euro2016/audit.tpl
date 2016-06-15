<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Audit log</h1>
            </div>
        </div>

        <div class="tag_cloud">
            <form action="/?act=audit" method="post" class="audit-form">
                <div class="formfield">
                    Catégorie :
                    <select id="audit-category">
                        <option value="">Toutes</option>
                        <!-- BEGIN categories -->
                        <option value="{categories.CATEGORY}">{categories.CATEGORY}</option>
                        <!-- END categories -->
                    </select>
                </div>
                <div class="formfield">
                    Utilisateur :
                    <select id="audit-user">
                        <option value="">Tous</option>
                        <!-- BEGIN users -->
                        <option value="{users.ID}">{users.NAME}</option>
                        <!-- END users -->
                    </select>
                </div>
            </form>
        </div>

        <div class="tag_cloud">
            <table width="100%">
                <tr>
                    <th width="15%">Date</th>
                    <th width="10%">Catégorie</th>
                    <th width="75%">Action</th>
                </tr>
                <!-- BEGIN logs -->
                <tr>
                    <td>{logs.DATE}</td>
                    <td>{logs.CATEGORY}</td>
                    <td><a href="/?act=bets&user={logs.USER_ID}">{logs.USER_NAME}</a> {logs.ACTION}</td>
                </tr>
                <!-- END logs -->
            </table>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').on('change', function () {
            var action = $('form.audit-form').attr('action');
            action += '&user=' + $('#audit-user').val();
            action += '&category=' + $('#audit-category').val();
            window.location.assign(action);
        });

        var userID = getUrlArgValue('user');
        if (userID) {
            $('#audit-user').val(userID);
        }

        var category = getUrlArgValue('category');
        if (category) {
            $('#audit-category').val(category);
        }
    });
</script>