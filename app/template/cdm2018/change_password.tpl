<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title"><h1>Modification de mot de passe</h1></div>
        </div>

        <div class="ppp">
            <h2>Modifiez votre mot de passe</h2>
            <center><span style="color:red;"><b>{WARNING}</b></span></center>
            <form method="post" action="/?act=change_password">
                <br>
                <div class="formfield"><b>Saisissez votre ancien mot de passe</b></div>
                <input type="password" name="old_password" value="" class="textinput" maxlength="100"/><br><br>
                <br>
                <div class="formfield"><b>Saisissez votre nouveau mot de passe</b></div>
                <input type="password" name="new_password1" value="" class="textinput" maxlength="100"/><br><br>
                <br>
                <div class="formfield"><b>Retaper votre nouveau mot de passe</b></div>
                <input type="password" name="new_password2" value="" class="textinput" maxlength="100"/>

                <input type="submit" value="Modifier" />
            </form>
        </div>
    </div>
</section>