<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Mot de passe oublié</h1>
            </div>
        </div>

        <div class="ppp">
            <h2>Un nouveau mot de passe vous sera envoyé par courriel :</h2>
            <br />
            <form method="post" action="/?act=forgot_password">
                <div class="formfield"><strong>{LABEL_FORGOTTEN_PASSWORD}</strong></div>
                <input type="text" name="login" value="" class="textinput" maxlength="100"/><br><br>

                <input type="submit" value="Envoyer" />
            </form>
        </div>
    </div>
</section>
