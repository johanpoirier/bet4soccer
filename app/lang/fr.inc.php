<?php

unset($lang);
$lang=array();

$lang['months'] = array (
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

$lang['day_week'] = array (
	"Dimanche",
	"Lundi",
	"Mardi",
	"Mercredi",
	"Jeudi",
	"Vendredi",
	"Samedi"
);

$lang['LABEL_TEAMS_RANKING'] = "Par groupe";
$lang['LABEL_LOGIN'] = "Utilisateur";

$lang['LABEL_FORGOTTEN_PASSWORD'] = "Renseignez votre nom d'utilisateur.";
$lang['LABEL_FORGOTTEN_LOGIN'] = "Renseignez l'adresse email utilisée lors de votre inscription.";

$lang['LABEL_POOL'] = "Poules";
$lang['LABEL_8_FINAL'] = "<small style=\"font-size:0.8em\"><sup>1</sup></small>/<small style=\"line-height:1.5em;font-size:80%;\"><sub>8</sub></small> de finale";
$lang['LABEL_4_FINAL'] = "<small style=\"font-size:0.8em\"><sup>1</sup></small>/<small style=\"line-height:1.5em;font-size:80%;\"><sub>4</sub></small> de finale";
$lang['LABEL_2_FINAL'] = "<small style=\"font-size:0.8em\"><sup>1</sup></small>/<small style=\"line-height:1.5em;font-size:80%;\"><sub>2</sub></small> finales";
$lang['LABEL_3_FINAL'] = "Match 3<small style=\"font-size:0.8em\"><sup>ème</sup></small> place";
$lang['LABEL_1_FINAL'] = "Finale";

$lang['warning'][FORGOT_PASSWORD_OK] = "Votre mot de passe vient d'être envoyé à votre adresse email.";
$lang['warning'][USER_UNKNOWN] = "Utilisateur inconnu.";
$lang['warning'][PASSWORD_MISMATCH] = "Les mots de passe ne correspondent pas.";
$lang['warning'][INCORRECT_PASSWORD] = "Mot de passe incorrect.";
$lang['warning'][CHANGE_PASSWORD_OK] = "Votre mot de passe a bien été changé.";
$lang['warning'][GROUP_ALREADY_EXISTS] = "Ce nom de groupe existe déjà.";
$lang['warning'][CREATE_GROUP_OK] = "Le groupe a bien été crée.<br/>Vous pouvez à présent vous le <a href='/?act=join_group'>rejoindre</a>, ou <a href='?act=invite_friends'>inviter des amis.</a>";
$lang['warning'][JOIN_GROUP_FORBIDDEN] = "Vous n'êtes pas autorisé à rejoindre ce groupe.";
$lang['warning'][JOIN_GROUP_OK] = "Vous faites désormais parti du groupe demandé.";
$lang['warning'][INCORRECT_EMAIL] = "L'adresse email saisie est incorrecte.";
$lang['warning'][LOGIN_ALREADY_EXISTS] = "Ce login existe déjà.";
$lang['warning'][FIELDS_EMPTY] = "Certains champs obligatoires ne sont pas renseignés.";
$lang['warning'][REGISTER_OK] = "Votre compte a bien été crée. Vous pouvez vous connecter avec vos identifiants.";
$lang['warning'][INVITE_WITHOUT_GROUP] = "Vous souhaitez inviter des amis alors que vous n'êtes membre d'aucun groupe.\nNous vous conseillons de <a href='/?act=create_group'>créer</a> ou de <a href='/?act=join_group'>joindre</a> un groupe avant d'envoyer vos invitations";
$lang['warning'][SEND_INVITATIONS_OK] = "Vos invitations ont bien été envoyées";
$lang['warning'][SEND_INVITATIONS_ERROR] = "Une erreur est survenue lors de l'envoi des invitations.";
$lang['warning'][AWL_NOT_GOOD_SITE] = "Le site de pronostics n'est actuellement ouvert qu'au site de Lyon. Le système vous a identifié comme n'en faisant pas partie. Si vous pensez qu'il existe une erreur, contactez-nous.";
$lang['warning'][LDAP_CONNECT_ERROR] = "Erreur de connection LDAP, merci de reessayer plus tard.";
$lang['warning'][LDAP_BIND_ERROR] = "Erreur de bind LDAP, merci de reessayer plus tard.";
$lang['warning'][JOIN_GROUP_FULL] = "Vous êtes déjà dans 3 groupes. Vous devez quitter un groupe d'abord.";
$lang['warning'][CHANGE_ACCOUNT_OK] = "Vos informations ont bien été modifiées.";
$lang['warning'][EMAIL_ALREADY_EXISTS] = "Cet email existe déjà.";
$lang['warning'][FORGOT_LOGIN_OK] = "Votre login vient de vous être envoyé par email";
$lang['warning'][USERNAME_ALREADY_EXISTS] = "Ce nom d'utilisateur existe déjà.";

$lang['warning'][UNKNOWN_ERROR] = "Erreur inconnue, contactez l'administrateur.";
