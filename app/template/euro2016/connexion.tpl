<section id="mainarea">
    <div class="maincontent login">
        <h1>Bonjour !</h1>
        <div class="login-content">
            <form method="post" action="/?act=login" class="login-block login">
                <input type="hidden" name="login" value="1" />
                <input type="hidden" name="redirect" value="" />
                <input type="hidden" name="code" value="{CODE}"/>

                <div class="formfield"><strong>{LABEL_LOGIN}</strong></div>
                <input type="text" name="login" value="" autofocus required/>

                <div class="formfield"><strong>Mot de passe</strong></div>
                <input type="password" name="pass" required />

                <span class="error">{WARNING}</span>

                <input type="submit" value="Connexion" />
            </form>

            <div class="login-block signup">
                <span>Pas encore de compte ?</span>
                <a href="/?act=register">Je m'en crée un ici</a>
            </div>
        </div>
    </div>
</section>
