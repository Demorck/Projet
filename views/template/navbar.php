<nav>
    <ul>
        <a href="/"><li <?php if ($title == "Accueil") echo 'class="active"' ?>>Accueil</li></a>
        <a href="/recherche"><li <?php if ($title == "Rechercher") echo 'class="active"' ?> >Recherche des recettes</li></a>
        <?php
            if (isset($_SESSION['login'])) {
                $active = $title == "Panier" ? 'class="active"' : '';
                echo '<a href="/panier"><li '. $active . '>Mon panier</li></a>';
                echo '<a href="/deconnect"><li>Déconnexion</li></a>';
            } else {
                $active = $title == "Connexion" ? 'class="active"' : '';
                echo '<a href="/connect"><li ' . $active . '>Connexion</li></a>';
            }
        ?>
    </ul>
</nav>
