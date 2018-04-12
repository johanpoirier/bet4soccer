<section id="mainarea">
    <div class="maincontent">
        <div class="headline">
            <div class="headline-title">
                <h1>Règlement</h1>
            </div>
        </div>

        <div>
            <p>
                <span class="rule_title">I. Réalisation des votes</span>
            </p>
            <p>
                Le pronostic d'un match peut être effectué jusqu'à 15 minutes avant le début du match.<br/>
                Pour être valide, le pronostic doit comporter le score marqué de chaque équipe.<br/>
            </p>
            <p>
                <span class="rule_title">II. Attribution des points</span>
                <br/>
            </p>
            <p>
                <span class="rule_subtitle">A. Points "résultat exact"</span> :
            </p>
            <ul class="rules">
                <li>Accordés lorsque le pronostiqueur a trouvé le résultat du match.</li>
                <li><u>Résultat Juste</u> : Bon pronostic de 'Victoire équipe A', 'Victoire équipe B', ou 'Match nul'
                    <i>(à
                        l'issue des éventuelles prolongations)</i>.
                </li>
                <li><u>Qualifié Trouvé (en phase finale)</u> : Bon pronostic de 'Victoire équipe A' ou 'Victoire équipe
                    B'
                    <i>(à l'issue des éventuels tirs aux buts)</i>.
                </li>
                <li>Les points s'ajoutent lorsque les 2 conditions sont remplies.</li>
                <br/><br/>
                <table class="rules">
                    <caption>Règles d'attribution des points par phase :</caption>
                    <tr>
                        <th>Phase</th>
                        <th>Pts "résultat juste"</th>
                        <th>Pts "qualifié trouvé"</th>
                    </tr>
                    <!-- BEGIN rounds -->
                    <tr>
                        <td>{rounds.NAME}</td>
                        <td>{rounds.POINTS_GOOD_RESULT}</td>
                        <td>{rounds.POINTS_QUALIFY}</td>
                    </tr>
                    <!-- END rounds -->
                </table>
                </li>
            </ul>
            <br/>
            <p>
                <span class="rule_subtitle">B. Points "score exact"</span> :
            </p>
            <ul class="rules">
                <li>Les points de score sont attribués si le pronostiqueur a trouvé le nombre de buts final de chacune
                    des
                    équipes.
                </li>
                <br/><br/>
                <table class="rules">
                    <caption>Règles d'attribution des points par phase :</caption>
                    <tr>
                        <th>Phase</th>
                        <th>Pts "score exact"</th>
                    </tr>
                    <!-- BEGIN rounds -->
                    <tr>
                        <td>{rounds.NAME}</td>
                        <td>{rounds.POINTS_EXACT_SCORE}</td>
                    </tr>
                    <!-- END rounds -->
                </table>
                </li>
            </ul>
            <br/>
            <p>
                <span class="rule_subtitle">C. Synthèse des points</span> :
            </p>
            <table class="rules">
                <tr>
                    <th>Phase</th>
                    <th>Pts "résultat juste"</th>
                    <th>Pts "qualifié trouvé"</th>
                    <th>Pts "score exact"</th>
                    <th>Max pts / match</th>
                    <th>Total phase</th>
                </tr>
                <!-- BEGIN rounds -->
                <tr>
                    <td>{rounds.NAME}</td>
                    <td>{rounds.POINTS_GOOD_RESULT}</td>
                    <td>{rounds.POINTS_QUALIFY}</td>
                    <td>{rounds.POINTS_EXACT_SCORE}</td>
                    <td>{rounds.POINTS_SUM}</td>
                    <td>{rounds.POINTS_TOTAL}</td>
                </tr>
                <!-- END rounds -->
            </table>
            <br/>
            <ul>
                <li>Soit <b>{POINTS_POOL_TOTAL} points</b> possibles en phase de poules et <b>{POINTS_FINALS_TOTAL}
                        points</b> en phase finale.
                </li>
            </ul>
        </div>
    </div>
</section>
