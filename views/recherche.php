<?php 
    use App\Helpers\Utils;
    require_once __DIR__ . "/template/header.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php";
    ?>
    <style>
        .hierarchy {
            display: none;
        }
        .hierarchy:first-child {
            display: flex;
        }
        .hierarchy:hover > div {
            display: flex;
        }
    </style>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <div class="container">
        <h1>Recherche de Recettes</h1>
        
        <div id="recipe-search" class="search-container flex flex-row gap-10 items-start">
            <div id="test">

            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold">Trouvez la recette de vos rêves</h2>
                <div class="flex flex-row gap-6 items-center justify-center">
                <label class="inline-flex gap-4 items-center me-5 cursor-pointer">
                    <span class="text-sm font-medium">Chercher par ingrédient</span>
                    <input id="change-search" type="checkbox" class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                    <span class="text-sm font-medium">Chercher par texte</span>
                </label>
                    <div class="search-box flex flex-1">
                        <input type="text" id="searchInput" placeholder="Rechercher un ingrédient..." autocomplete="off" data-type="false">
                        <div id="suggestions" class="suggestions"></div>
                    </div>
                </div><br>
                <hr>
                <div class="ingredients-container flex flex-row gap-6 items-start justify-center">
                    <div id="includedTags-container" class="tags w-1/2">
                        <h3>Ingrédients souhaités</h3>
                        <div id="includedTags"></div>
                    </div>

                    <div id="excludedTags-container" class="tags w-1/2">
                        <h3>Ingrédients exclus</h3>
                        <div id="excludedTags"></div>
                    </div>
                </div>

                <div id="results-container" class="results-container">
                    <h2 class="text-xl font-bold">Les recettes dont VOUS êtes le héros</h2>
                    <div id="results"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/js/recipe-search.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new RecipeSearch(document.getElementById('recipe-search'));
        });
    </script>

    <?php require_once __DIR__ . "/template/footer.php"; ?>
</body>