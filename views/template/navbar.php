<nav>
    <ul>
        <a href="/"><li class="active">Accueil</li></a>
        <a href="/recherche"><li>Recherche des recettes</li></a>
        <?php
            if (isset($_SESSION['user'])) {
                echo '<a href="/panier"><li>Mon panier</li></a>';
            }
        ?>
        <a href="/login"><li>Connexion</li></a>
    </ul>
</nav>
