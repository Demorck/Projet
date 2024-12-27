<?php
    require_once __DIR__ . "/template/header.php";
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <div class="flex flex-col items-center justify-center min-h-screen">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Trouvez et enregistrez vos recettes préférées</h1>
            <p class="text-lg text-gray-600 mb-8 text-center max-w-xl">
                Bienvenue dans votre espace culinaire ! Explorez un large choix de recettes, connectez-vous pour les personnaliser, et créez votre propre collection.
            </p>

            <div class="flex space-x-4">
                <a href="/recherche" class="bg-blue-500 text-white py-3 px-6 rounded-lg text-lg font-medium hover:bg-blue-600">
                    Chercher des recettes
                </a>
                <?php if (!isset($_SESSION['login'])): ?>
                <a href="/connect" class="bg-green-500 text-white py-3 px-6 rounded-lg text-lg font-medium hover:bg-green-600">
                    Se connecter
                </a>
                <?php endif; ?>
                <a href="/panier" class="bg-yellow-500 text-white py-3 px-6 rounded-lg text-lg font-medium hover:bg-yellow-600">
                    Enregistrer des recettes
                </a>
            </div>
        </div>

    </div>
    <?php require_once __DIR__ . "/template/footer.php"; ?>
</body>
</html>
