<?php
$phases = $engine->getPhases();

?>
<div class="maincontent">

    <div class="headline">
        <div class="headline-title">
            <h1>Règlement</h1>
        </div>
    </div>

    <p>
        <span class="rule_title">I. Réalisation des votes</span>
        <br/>
        Le pronostic d'un match peut être effectué jusqu'à 15 minutes avant le début du match.<br/>
        Pour être valide, le pronostic doit comporter le score marqué de chaque équipe.<br/>
        <br/>
        <br/>
    </p>

    <p>
        <span class="rule_title">II. Attribution des points</span>
        <br/>

    <p>
        <span class="rule_subtitle">A. Points "résultat"</span> :
    <ul class="rules">
        <li>Accordés lorsque le pronostiqueur a trouvé le vainqueur du match ou le match nul le cas échéant.</li>
        <li>Tableau des points "résultat" :
            <br/><br/>
            <table class="rules">
                <caption>Règles d'attribution des points par phase :</caption>
                <tr>
                    <th>Phase</th>
                    <th>Nb de matchs</th>
                    <th>Résultat juste</th>
                </tr>
                <?php foreach ($phases as $phase) { ?>
                    <tr>
                        <td><?php echo $phase['name']; ?></td>
                        <td><?php echo $phase['nb_matchs']; ?></td>
                        <td><?php echo $phase['nbPointsRes']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </li>
    </ul>
    <br/>
    </p>

    <p>
        <span class="rule_subtitle">B. Points "bonus"</span> :
    <ul class="rules">
        <li>Les points de bonus sont déterminés en calculant la différence entre la valeur pronostiquée et la valeur
            réelle :
            <ul>
                <li>pour le score de l'équipe A,</li>
                <li>pour le score de l'équipe B,</li>
                <li>ainsi que pour l'écart du match (différence entre les 2 scores).</li>
            </ul>
        </li>
        <li>Précision importante : les points de bonus sont attribués si et seulement si le bon résultat a été
            pronostiqué. Exemple : un pronostic de 15-10 pour un match se terminant par un score de 15-18 ne rapporte
            aucun point, même si le pronostiqueur avait prévu 15 points pour l'équipe A.
        </li>
        <li>
            Il existe deux sortes de bonus : le bonus "juste" et le bonus "proche". Les points bonus "juste" sont
            accordés lorsque la valeur pronostiquée et la valeur réelle sont identiques ou quasi-identiques. Les points
            bonus "proche" sont accordés lorsque la valeur pronostiquée et la valeur réelle sont relativement proches.
            Le tableau suivant définit les zones "bonus juste" et "bonus proche".
            <table class="rules">
                <tr>
                    <th>Si le score ou l'écart réel est compris entre :</th>
                    <th>L'intervalle de tolérance (IT) autour de la valeur réelle (du score ou de l'écart) permettant
                        d'obtenir les points du "bonus juste" est de :
                    </th>
                    <th>L'intervalle de tolérance (IT) autour de la valeur réelle (du score ou de l'écart) permettant
                        d'obtenir les points du "bonus proche" est de :
                    </th>
                </tr>
                <tr>
                    <td>[ 00 ; 20 ]</td>
                    <td>0</td>
                    <td>[-4;-1] [+1;+4]</td>
                </tr>
                <tr>
                    <td>[ 21 ; 40 ]</td>
                    <td>[-2;+2]</td>
                    <td>[-8;-3] [+3;+8]</td>
                </tr>
                <tr>
                    <td>[ 41 ; 60 ]</td>
                    <td>[-4;+4]</td>
                    <td>[-12;-5] [+5;+12]</td>
                </tr>
                <tr>
                    <td>[ 61 ; +oo ]</td>
                    <td>[-6;+6]</td>
                    <td>[-20;-7] [+7;+20]</td>
                </tr>
            </table>
        </li>
        <li>Tableau des points bonus "juste" :
            <br/><br/>
            <table class="rules">
                <tr>
                    <th>Phase</th>
                    <th>Nb de points</th>
                </tr>
                <?php foreach ($phases as $phase) { ?>
                    <tr>
                        <td><?php echo $phase['name']; ?></td>
                        <td><?php echo $phase['nbPointsScoreNiv1']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </li>
        <li>Tableau des points bonus "proche" :
            <br/><br/>
            <table class="rules">
                <tr>
                    <th>Phase</th>
                    <th>Nb de points</th>
                </tr>
                <?php foreach ($phases as $phase) { ?>
                    <tr>
                        <td><?php echo $phase['name']; ?></td>
                        <td><?php echo $phase['nbPointsScoreNiv2']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </li>
    </ul>
    <br/>
    </p>

    <p>
        <span class="rule_subtitle">C. Synthèse des points</span> :
    <table class="rules">
        <tr>
            <th>Phase</th>
            <th>Pts "résultat match"</th>
            <th>Pts "bonus" maxi / match</th>
            <th>Total pts / match</th>
            <th>Nb de matchs</th>
            <th>Total de pts</th>
        </tr>
        <?php foreach ($phases as $phase) { ?>
            <tr>
                <td><?php echo $phase['name']; ?></td>
                <td><?php echo $phase['nbPointsRes']; ?></td>
                <td><?php echo $phase['nbPointsScoreNiv1']; ?> x 3</td>
                <td><?php echo($phase['nbPointsRes'] + $phase['nbPointsScoreNiv1'] * 3); ?></td>
                <td><?php echo $phase['nb_matchs']; ?></td>
                <td><?php echo($phase['nb_matchs'] * ($phase['nbPointsRes'] + $phase['nbPointsScoreNiv1'] * 3)); ?></td>
            </tr>
        <?php } ?>
    </table>
    <br/>
    </p>

    <p>
        <span class="rule_subtitle">D. Exemples (pour bien comprendre ...)</span> :
    <ul class="rules">
        <li>Exemple 1 : le résultat d'un match de poule est de 87-10.
            <ul class="rules">
                <li>Le joueur P1 a pronostiqué 44-3. Il marque 10 points, soit :
                    <ul>
                        <li>10 points pour le résultat,</li>
                        <li>0 point de bonus pour le score de l'équipe A car 44-87 = -33 (-33 n'étant pas dans les IT de
                            bonus pour un score de 87 supérieur ou égal à 61),
                        </li>
                        <li>0 point de bonus pour le score de l'équipe B car 3-10 = -7, (-7 n'étant pas dans les IT de
                            bonus pour un score de 3 compris entre 0 et 20),
                        </li>
                        <li>0 point de bonus pour l'écart car 41-77 = -36 (-36 n'étant pas dans les IT de bonus pour un
                            écart de 77 supérieur ou égal à 61).
                        </li>
                    </ul>
                </li>
                <li>Le joueur P2 a pronostiqué 72-14. Il marque 13 points, soit :
                    <ul>
                        <li>10 points pour le résultat,</li>
                        <li>1 point de bonus pour le score de l'équipe A car 72-87 = -15 (-15 étant dans l'IT du bonus
                            proche pour un score de 87 supérieur ou égal à 61),
                        </li>
                        <li>1 point de bonus pour le score de l'équipe B car 14-10 = 4, (4 étant dans l'IT du bonus
                            proche pour un score de 3 compris entre 0 et 20),
                        </li>
                        <li>1 point de bonus pour l'écart car 58-77 = -19 (-19 étant dans l'IT du bonus proche pour un
                            écart de 77 supérieur ou égal à 61).
                        </li>
                    </ul>
                </li>
                <li>Le joueur P3 a pronostiqué 10-10 : bien qu'il ait trouvé le score de l'équipe B, il ne marque aucun
                    point car il n'a pas trouvé le résultat (il n'a pas désigné le bon vainqueur).
                </li>
            </ul>
        </li>
        <li>Exemple 2 : le résultat d'une demi-finale est de 25-12.
            <ul class="rules">
                <li>Le joueur P1 a pronostiqué 12-16 : il ne marque aucun point car il n'a pas trouvé le résultat (il
                    n'a pas désigné le bon vainqueur).
                </li>
                <li>Le joueur P2 a pronostiqué 32-13. Il marque 20 points, soit :
                    <ul>
                        <li>16 points pour le résultat,</li>
                        <li>2 points de bonus pour le score de l'équipe A car 32-25 = 7 (7 étant dans l'IT du bonus
                            proche pour un score de 25 compris entre 20 et 40),
                        </li>
                        <li>2 points de bonus pour le score de l'équipe B car 13-12 = 1, (1 étant dans l'IT du bonus
                            proche pour un score de 12 compris entre 0 et 20),
                        </li>
                        <li>0 point de bonus pour l'écart car 19-13 = 6 (6 n'étant pas dans les IT de bonus pour un
                            écart de 13 compris entre 0 et 20).
                        </li>
                    </ul>
                </li>
                <li>Le joueur P3 a pronostiqué 27-12. Il marque 28 points, soit :
                    <ul>
                        <li>16 points pour le résultat,</li>
                        <li>5 points de bonus pour le score de l'équipe A car 27-25 = 2 (2 étant dans l'IT du bonus
                            juste pour un score de 25 compris entre 20 et 40),
                        </li>
                        <li>5 points de bonus pour le score de l'équipe B car 12-12 = 0, (0 étant dans l'IT du bonus
                            juste pour un score de 12 compris entre 0 et 20),
                        </li>
                        <li>2 points de bonus pour l'écart car 15-13 = 2 (2 étant dans l'IT du bonus proche pour un
                            écart de 13 compris entre 0 et 20).
                        </li>
                    </ul>
                </li>
                <li>Le joueur P4 a pronostiqué 38-25. Il marque 21 points, soit :
                    <ul>
                        <li>16 points pour le résultat,</li>
                        <li>0 point de bonus pour le score de l'équipe A car 38-25 = 13 (13 n'étant pas dans les IT de
                            bonus pour un score de 25 compris entre 20 et 40),
                        </li>
                        <li>0 point de bonus pour le score de l'équipe B car 25-12 = 13, (13 n'étant pas dans les IT de
                            bonus pour un score de 12 compris entre 0 et 20),
                        </li>
                        <li>5 points de bonus pour l'écart car 13-13 = 0 (0 étant dans l'IT du bonus juste pour un écart
                            de 13 compris entre 0 et 20).
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    </p>
    </p>
</div>
