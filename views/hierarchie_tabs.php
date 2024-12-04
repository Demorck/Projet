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
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 justify-start">
        <div class="">
            <form action="" method="GET">
                <div id="test">

                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Rechercher</button>
            </form>
        </div>
        <div id="recettes" class="flex flex-wrap flex-row gap-4 items-start justify-center flex-1">
            <?php foreach ($Recettes as $recette) : 
                if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                    $ingredients = explode('&', $_SERVER['QUERY_STRING']); 
                    foreach ($ingredients as $ingredientParam) {
                        $ingredient = explode('=', $ingredientParam)[0];
                        $ingredient = str_replace('+', ' ', $ingredient);
                        $ingredient = strtolower($ingredient);
                        $liste = strtolower($recette['ingredients']);
                        $preparation = strtolower($recette['preparation']);

                        if (!str_contains($liste, $ingredient) OR !str_contains($preparation, $ingredient)) {
                            continue 2;
                        }
                    }
                }
                ?>
                <div class="flex-col w-1/6 border py-2 px-4">
                    <h3 class="text-xl font-bold"><?= $recette['titre'] ?></h3>
                    <ul class="list-disc ml-4"><?php
                        $ingredients = explode("|", $recette['ingredients']);
                        foreach ($ingredients as $ingredient) {
                            echo "<li>" . $ingredient . "</li>";
                        }
                    
                    
                    ?></ul>
                    <p><?= $recette['preparation'] ?></p>
                    <img src="<?= Utils::getImage($recette['titre']) ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        json = <?= json_encode($hierarchie) ?>;
        function generateHierarchy(hierarchy, parentElement) {
            for (const key in hierarchy) {
                const value = hierarchy[key];

                // Conteneur
                const nodeContainer = document.createElement('div');
                nodeContainer.classList.add('ml-4', 'flex', 'flex-col', 'gap-1');

                // Nœud parent
                const nodeElement = document.createElement('div');
                nodeElement.classList.add('cursor-pointer', 'font-semibold', 'text-gray-700', 'hover:text-blue-500', 'flex', 'items-center', 'gap-2');

                // Label
                const label = document.createElement('label');

                // Icône pour indiquer que le nœud peut se dérouler
                const toggleIcon = document.createElement('span');
                toggleIcon.textContent = '+';
                toggleIcon.classList.add('text-sm', 'text-gray-500', 'font-bold');

                nodeElement.appendChild(toggleIcon);
                nodeElement.appendChild(label)

                // Conteneur pour les enfants
                const childrenContainer = document.createElement('div');
                childrenContainer.classList.add('hidden', 'flex', 'flex-col');

                // Si le nœud a des enfants, on génère les sous-nœuds
                if (typeof value === 'object' && value !== null) {
                    label.textContent = key;
                    nodeElement.addEventListener('click', () => {
                        childrenContainer.classList.toggle('hidden');
                        toggleIcon.textContent = childrenContainer.classList.contains('hidden') ? '+' : '-';
                    });

                    generateHierarchy(value, childrenContainer);
                } else {
                    label.textContent = value;
                    label.setAttribute('for', value);
                    label.classList.add('cursor-pointer', 'flex', 'flex-row-reverse', 'gap-2');
                    // Ajouter une checkbox si c'est une feuille
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.classList.add('rounded', 'border-gray-300', 'focus:ring-blue-500');
                    checkbox.name = value;
                    checkbox.id = value;
                    label.appendChild(checkbox);
                }

                ;
                // Ajouter le nœud et les enfants au conteneur
                nodeContainer.appendChild(nodeElement);
                nodeContainer.appendChild(childrenContainer);
                parentElement.appendChild(nodeContainer);
            }
        }

        const testDiv = document.getElementById('test');
        console.log(json);
        generateHierarchy(json, testDiv);
    </script>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>