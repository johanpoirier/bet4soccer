<main>
    <header>
        <div class="block">
            <a href="index.php">
                <img src="{TPL_WEB_PATH}images/logo.jpg" alt="UEFA Euro 2016" border="0" height="66"/>
            </a>
        </div>
        <div class="block">
            <!-- BEGIN matches -->
            {matches.MATCH_STR}
            <!-- BEGIN ext_list -->
            <strong>{matches.ext_list.TEAM_NAME_A} - {matches.ext_list.TEAM_NAME_B}</strong>
            <!-- END ext_list -->
            <!-- BEGIN list -->
            <a href="#" onclick="window.open('/?act=view_match_stats&matchID={matches.list.ID}','statistiques','menubar=no, status=no, scrollbars=no, menubar=no, location=no, width=555, height=555')">
                <strong>{matches.list.TEAM_NAME_A} - {matches.list.TEAM_NAME_B}</strong>
            </a>
            <!-- END list -->
            <!-- END matches -->
        </div>
        <!-- BEGIN logged_in -->
        <div class="block">
            <!-- BEGIN account -->
            <a href="/?act=account" class="account-link">
                <strong>{USERNAME}</strong>
                <span>Mon compte</span>
            </a>
            <!-- END account -->
            <i>{HEADER_GROUP_NAME}</i>
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
