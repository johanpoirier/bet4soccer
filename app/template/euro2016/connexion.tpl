<section id="mainarea">
    <div class="maincontent login">
        <div class="headline">
            <div class="headline-title">
                <h1>Connexion</h1>
            </div>
        </div>

        <div class="login-content">
            <form method="post" action="/?act=login" class="login">
                <input type="hidden" name="login" value="1" />
                <input type="hidden" name="redirect" value="" />
                <input type="hidden" name="code" value="{CODE}"/>

                <div class="formfield"><b>{LABEL_LOGIN}</b></div>
                <input type="text" name="login" value="" maxlength="100" autofocus required/>

                <div class="formfield"><b>Mot de passe</b></div>
                <input type="password" name="pass" maxlength="20" required />

                <span class="error">{WARNING}</span>

                <input type="submit" value="Connexion" />
            </form>
        </div>
    </div>
</section>
