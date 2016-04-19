<div class="maincontent login">
    <div class="headline">
        <div class="headline-title">
            <h1>Connexion</h1>
        </div>
    </div>
    <div class="login-content">
        <h2>Connexion</h2>

        <form method="post" action="/?op=login" class="login">
            <input type="hidden" name="login" value="1" />
            <input type="hidden" name="redirect" value="" />
            <input type="hidden" name="code" value="<?php if (isset($_GET['s'])) {
                echo $_GET['s'];
            } ?>"/>

            <div class="formfield"><b>Nom d'utilisateur</b></div>
            <input type="text" name="login" value="" class="textinput" maxlength="100" autofocus required/>

            <div class="formfield"><b>Mot de passe</b></div>
            <input type="password" name="pass" class="textinput" maxlength="20" required />

            <span class="error"><?php echo $message; ?></span>

            <input type="submit" value="Connexion" />
        </form>
    </div>
</div>
