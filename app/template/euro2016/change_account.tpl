<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Modification de votre profil</h1>
            </div>
        </div>

        <div class="ppp">
            <center><span style="color: red;"><b>{WARNING}</b></span></center>
            <h2>Modifiez vos informations personnelles</h2>
            <form method="post" action="/?act=change_account">
                <div class="formfield"><b>Login</b></div>
                <input type="text" name="login" value="{LOGIN}" class="textinput" maxlength="100" disabled/>
                <br/>
                <br/>
                <div class="formfield"><b>Nom d'utilisateur</b></div>
                <input type="text" name="username" value="{USERNAME}" class="textinput" maxlength="100"/>
                <br/>
                <br/>
                <div class="formfield"><b>Email</b></div>
                <input type="text" name="email" value="{EMAIL}" class="textinput" size="40" maxlength="200"/>

                <input type="submit" value="Modifier"/>
            </form>
            <br/>
            <br/>

            <h2>Modifiez votre mot de passe</h2>
            <form method="post" action="/?act=change_password">
                <div class="formfield"><b>Saisissez votre ancien mot de passe</b></div>
                <input type="password" name="old_password" value="" class="textinput" maxlength="100"/>
                <br/>
                <br/>
                <div class="formfield"><b>Saisissez votre nouveau mot de passe</b></div>
                <input type="password" name="new_password1" value="" class="textinput" maxlength="100"/>
                <br/>
                <br/>
                <div class="formfield"><b>Retaper votre nouveau mot de passe</b></div>
                <input type="password" name="new_password2" value="" class="textinput" maxlength="100"/>

                <input type="submit" value="Modifier"/>
            </form>
            <br/>
            <br/>

            <h2>Modifiez vos préférences</h2>
            <form method="post" action="/?act=change_preferences">
                <div class="formfield"><b>Thème d'affichage</b></div>
                <select id="theme" name="theme">
                    <!-- BEGIN themes -->
                    <option value="{themes.ID}"{themes.SELECTED}>{themes.NAME}</option>
                    <!-- END themes -->
                </select>
                <br/>
                <br/>
                <div class="formfield"><b>Affichage des matchs par défaut</b></div>
                <select id="match_display" name="match_display">
                    <!-- BEGIN match_display -->
                    <option value="{match_display.ID}"{match_display.SELECTED}>{match_display.LABEL}</option>
                    <!-- END match_display -->
                </select>

                <input type="submit" value="Modifier"/>
            </form>
        </div>
    </div>
</section>
