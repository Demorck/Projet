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
    <h2>Les recettes du moment</h2>
    <div class="container">
        <h1>Recherche de Recettes</h1>
        
        <div id="recipe-search" class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Rechercher un ingrédient..." autocomplete="off">
                <div id="suggestions" class="suggestions"></div>
            </div>

            <div class="ingredients-container">
                <div id="includedTags-container" class="tags">
                    <h3>Ingrédients souhaités</h3>
                    <div id="includedTags"></div>
                </div>

                <div id="excludedTags-container" class="tags">
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
    
    <script src="/js/recipe-search.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new RecipeSearch(document.getElementById('recipe-search'));
        });
    </script>
    <?php require_once __DIR__ . "/template/footer.php"; ?>
</body>