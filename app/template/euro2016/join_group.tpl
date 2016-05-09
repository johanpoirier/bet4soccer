<script type="text/javascript">

    var is_need_auth = [];
    var group_names = [];

    <!-- BEGIN groups -->
    group_names[{groups.ID}] = "{groups.NAME_STRIP}";
    <!-- END groups -->

    var auto_join = {AUTO_JOIN};
    var code = "{CODE}";

    function display_auto_join() {
        if (auto_join > 0) {
            var group_name = group_names[auto_join];
            document.getElementById('code').value = code;
            if (confirm('Souhaitez-vous rejoindre le groupe \'' + group_name + '\' ?')) {
                document.join_group_form.submit();
            }
        }
    }

    function valid_form() {
        var group = "";
        if (document.getElementById('group') != null) {
            group = document.getElementById('group').value;
        }
        return true;
    }
</script>

<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Rejoindre un groupe</h1>
            </div>
        </div>

        <div class="ppp">
            <center><span style="color:red;"><b>{WARNING}</b></span></center>
            <form method="post" id="join_group_form" name="join_group_form" action="/?act=join_group"
                  onsubmit="return valid_form();">
                <input type="hidden" name="code" id="code">

                <div class="formfield"><strong>Sélectionner le groupe que vous souhaitez rejoindre :</strong></div>
                <select name="group" id="group">
                    <!-- BEGIN groups -->
                    <option name="{groups.ID}" value="{groups.ID}"{groups.SELECTED}>
                        {groups.NAME} ({groups.OWNER_NAME})
                    </option>
                    <!-- END groups -->
                </select>

                <div class="formfield"><strong>Veuillez entrer le mot de passe du groupe :</strong></div>
                <input type="password" name="password" size="12" id="password"/>

                <input type="submit" value="Rejoindre">
            </form>
        </div>
    </div>

</section>
<script type="text/javascript">
    display_auto_join();
</script>
