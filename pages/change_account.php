<div class="maincontent">
    <div class="headline">
        <div class="headline-title">
            <h1>Mon Compte</h1>
        </div>
    </div>
<?php
    if (isset($_SESSION["userID"])) {
        $user = $engine->getUser($_SESSION["userID"]);
    }
?>
    <form id="formProfile" method="POST" action="/?op=update_profile">
        <p>
            <br/>
            <u>Login</u> : <?php echo $user['login']; ?>
            <br/>
            <br/>
            <u>Nom</u> : <input type="text" name="name" value="<?php echo $user['name']; ?>" size="40" placeholder="Nom"
                                required/>
            <br/>
            <br/>
            <u>Equipe</u> : <?php echo $user['team']; ?>
            <br/>
            <br/>
            <u>Courriel</u> : <input type="email" name="email" value="<?php echo $user['email']; ?>"
                                     placeholder="Adresse de courriel" size="40" required/>
            <br/>
            <br/>
            <u>Nouveau mot de passe</u> :
            <br/>
            <br/>
            <input type="password" name="pwd1"/> (à confirmer pour changer : <input type="password" name="pwd2"/>)
        </p>
        <br/>

        <p align="center">
            <font color="#ff0000"><?php echo $message; ?></font>
            <br/>
            <br/>
            <input type="submit" name="submit" value="Valider" alt="Valider"/>
        </p>
    </form>
</div>