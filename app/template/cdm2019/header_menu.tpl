<main>
    <header>
        <div class="block logo">
        </div>
        <div class="block nextGameHeader">
            <!-- BEGIN matches -->
            {matches.MATCH_STR}
            <!-- BEGIN ext_list -->
            <strong>{matches.ext_list.TEAM_NAME_A} - {matches.ext_list.TEAM_NAME_B}</strong>
            <!-- END ext_list -->
            <div class="nextGames">
                <!-- BEGIN list -->
                <div class="nextGame" data-game-id="{matches.list.ID}">
                    <span class="nextGameLabel">{matches.list.TEAM_NAME_A} - {matches.list.TEAM_NAME_B}</span>
                    <div class="nextGameCard"></div>
                </div>
                <!-- END list -->
            </div>
            <!-- END matches -->
        </div>
        <!-- BEGIN logged_in -->
        <div class="block account">
            <!-- BEGIN account -->
            <a href="/?act=account" class="account-link">
                <strong>{USERNAME}</strong>
                <span>Mon compte</span>
            </a>
            <!-- END account -->
        </div>
        <!-- END logged_in -->
    </header>

    <!-- BEGIN logged_in -->
    <nav>
        <!-- BEGIN admin_bar -->
        <ul class="nav-group">
            <li class="nav-group-item"><a href="/?act=view_ranking">Classement</a></li>
            <li class="nav-group-item"><a href="/?act={FINALS}bets{MATCH_DISPLAY}">Mes pronostics</a></li>
            <li class="nav-group-item"><a href="/?act=view_{FINALS}odds{MATCH_DISPLAY}">Résultats</a></li>
        </ul>
        <ul class="nav-group admin">
            <li class="nav-group-item"><a href="/?act=edit_users">Joueurs</a></li>
            <li class="nav-group-item"><a href="/?act=edit_{FINALS}results">Saisie Résultats</a></li>
            <li class="nav-group-item"><a href="/?act=edit_matches">Saisie Matchs</a></li>
            <li class="nav-group-item"><a href="/?act=edit_teams">Saisie Équipes</a></li>
            <li class="nav-group-item"><a href="/?act=audit">Audit</a></li>
        </ul>
        <!-- END admin_bar -->
        <!-- BEGIN user_bar -->
        <ul class="nav-group">
            <li class="nav-group-item"><a href="/?act=view_ranking">Classement</a></li>
            <li class="nav-group-item"><a href="/?act={FINALS}bets{MATCH_DISPLAY}">Mes pronostics</a></li>
            <li class="nav-group-item"><a href="/?act=view_{FINALS}odds{MATCH_DISPLAY}">Résultats</a></li>
        </ul>
        <!-- END user_bar -->
    </nav>
    <!-- END logged_in -->
