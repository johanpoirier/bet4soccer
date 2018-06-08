<section id="mainarea">
    <div class="maincontent">

        <div class="headline">
            <div class="headline-title">
                <h1>Création de groupe</h1>
            </div>
        </div>

        <div class="ppp">
            <center><span style="color:red;"><strong>{WARNING}</strong></span></center>
            <form method="post" action="/?act=create_group">
                <div class="formfield"><strong>Saisissez votre nom de groupe</strong></div>
                <input type="text" name="group_name" value="" class="textinput" maxlength="100"/>
                <div class="formfield"><strong>Saisissez un code d'accès pour contrôler l'adhésion au groupe</strong></div>
                <input type="password" name="password1" value="" class="textinput" maxlength="100"/>
                <div class="formfield"><strong>Saisissez à nouveau votre code d'accès du groupe</strong></div>
                <input type="password" name="password2" value="" class="textinput" maxlength="100"/>
                <input type="submit" value="Créer" />
            </form>
        </div>
    </div>
</section>
