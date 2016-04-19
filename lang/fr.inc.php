<?php

unset($lang);
$lang = array();

$lang['months'] = array(
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre"
);

$lang['day_week'] = array(
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
);

$lang['messages'][FORGOT_IDS_OK] = "Vos identifiants de connexion viennent d'être envoyés à votre adresse email.";
$lang['messages'][FORGOT_IDS_KO] = "Il y a eu un problème lors de l'envoi de vos identifiants, veuillez contactez l'administrateur.";
$lang['messages'][USER_UNKNOWN] = "Utilisateur inconnu.";
$lang['messages'][EMAIL_UNKNOWN] = "L'adresse email est inconnue.";
$lang['messages'][PASSWORD_MISMATCH] = "Les mots de passe ne correspondent pas.";
$lang['messages'][INCORRECT_PASSWORD] = "Mot de passe incorrect.";
$lang['messages'][INCORRECT_CREDENTIALS] = "Login et/ou mot de passe incorrect(s).";
$lang['messages'][CHANGE_PASSWORD_OK] = "Votre mot de passe a bien été changé.";
$lang['messages'][GROUP_ALREADY_EXISTS] = "Ce nom de groupe existe déjà.";
$lang['messages'][GROUP_UNKNOWN] = "Ce groupe n'existe pas.";
$lang['messages'][CREATE_GROUP_OK] = "Le groupe a bien été crée.<br/>Vous appartenez dès à présent au groupe et vous pouvez <a href='/?op=invite_friends'>inviter des amis.</a>";
$lang['messages'][JOIN_GROUP_FORBIDDEN] = "Vous n'êtes pas autorisé à rejoindre ce groupe.";
$lang['messages'][JOIN_GROUP_OK] = "Vous faites désormais parti du groupe demandé.";
$lang['messages'][INCORRECT_EMAIL] = "L'adresse email saisie est incorrecte.";
$lang['messages'][LOGIN_ALREADY_EXISTS] = "Ce login existe déjà.";
$lang['messages'][FIELDS_EMPTY] = "Certains champs obligatoires ne sont pas renseignés.";
$lang['messages'][REGISTER_OK] = "Votre compte a bien été crée. Vous pouvez vous connecter avec vos identifiants.";
$lang['messages'][CHANGE_ACCOUNT_OK] = "Vos informations ont bien été modifiées.";
$lang['messages'][EMAIL_ALREADY_EXISTS] = "Cet email existe déjà.";
$lang['messages'][INVITE_WITHOUT_GROUP] = "Vous souhaitez inviter des amis alors que vous n'êtes membre d'aucun groupe.\nNous vous conseillons de <a href='/?act=create_group'>créer</a> ou de <a href='/?act=join_group'>joindre</a> un groupe avant d'envoyer vos invitations";
$lang['messages'][SEND_INVITATIONS_OK] = "Vos invitations ont bien été envoyées";
$lang['messages'][SEND_INVITATIONS_ERROR] = "Une erreur est survenue lors de l'envoi des invitations.";
$lang['messages'][USERNAME_ALREADY_EXISTS] = "Ce nom d'utilisateur existe déjà.";
$lang['messages'][UNKNOWN_ERROR] = "Erreur inconnue, contactez l'administrateur.";
