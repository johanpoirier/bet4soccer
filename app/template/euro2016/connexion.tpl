<div id="mainarea">

    <div id="headline"><h1>Connexion</h1></div>

    <div class="maincontent">
        <div class="ppp">
            <center><span style="color:red;"><b>{WARNING}</b></span></center>

            <form method="post" action="/?act=login">
                <input type="hidden" name="redirect" value="">
                <input type="hidden" name="code" value="{CODE}">
                <br>
                <div class="formfield"><b>{LABEL_LOGIN}</b></div>
                <input type="text" name="login" value="" class="textinput" maxlength="100" tabindex="1" /><br><br>

                <div class="formfield"><b>Mot de passe</b></div>

                <input type="password" name="pass" class="textinput" maxlength="20" tabindex="2" /><br><br>

                <input class="imageinput" type="image" src="{TPL_WEB_PATH}/images/login.gif" value="logga in" tabindex="3" />
            </form>
        </div>
    </div>
</div>