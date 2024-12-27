<?php
    require_once __DIR__ . "/template/header.php";
    use App\Helpers\Utils;
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <div class="wrapper-recettes flex flex-wrap flex-col gap-4 items-start justify-center mx-4">
        <?php if(sizeof($data['favorites']) != 0): foreach ($data['favorites'] as $favorite): ?>
            <div class="recette relative flex w-full flex-col  justify-start border-grey-500 border-solid border-b-2 p-4 gap-2">
                <div class="items-center justify-center">
                    <h3 class="text-3xl font-bold font-['Comic_sans_Ms'] text-"><?= $favorite['nom']; ?></h3>
                </div>
                <div class="flex flex-row gap-4 items-center">
                    <div>
                        <img src="<?= Utils::getImage($favorite['nom']); ?>" alt="<?= $favorite['nom']; ?>" class="w-24">
                    </div>
                    <div class="flex flex-1 flex-col gap-4">
                        <ul class="list-disc list-inside"><span class="font-bold underline">Ingrédients: </span>
                            <?php
                                $splitted = explode("|", $favorite['ingredients']);
                                foreach ($splitted as $value): ?>
                                    <li><?= $value; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><span class="font-bold underline">Recette:</span> <?= $favorite['description']; ?></p>
                        
                    </div>
                </div>
                <span class="favorite absolute flex flex-row">
                        <input type="checkbox" id="recipe-<?= $favorite['id_recette'] ?>" name="recipe-<?= $favorite['id_recette'] ?>" class="hidden" checked>
                        <label for="recipe-<?= $favorite['id_recette'] ?>" class="w-6 h-6">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32c-5.15-4.67-8.55-7.75-8.55-11.53 0-3.08 2.42-5.5 5.5-5.5 1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.42 5.5 5.5 0 3.78-3.4 6.86-8.55 11.54l-1.45 1.31z"/>
                            </svg>
                        </label>
                </span>
            </div>
        <?php endforeach;
            else: ?>
            <div class="flex flex-col items-center justify-center w-full mt-10 gap-6">
                <h2 class="text-3xl font-bold">Votre panier de recettes est vide :(</h2>
                <p class="text-lg">Ajoutez des recettes à votre panier pour les retrouver ici</p>
                <p>Retrouvez les recettes à cette page: <a href="/recherche" class="underline bg-blue-400 cursor-pointer px-4 py-2 rounded-xl hover:bg-blue-300 text-xl">ICI</a></p>
            </div>
        <?php endif; ?>
    </div>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const favorites = document.querySelectorAll('.favorite input');
            favorites.forEach(favorite => {
                favorite.addEventListener('click', async (e) => {
                    const id = e.target.name.split('-')[1];
                    const response = await fetch('/favorite/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({id: id})
                    });
                    if (response.ok) {
                        e.target.parentElement.parentElement.remove();
                    }
                    
                    let length = document.querySelectorAll('.favorite input').length;
                    
                    if (length === 0) {
                        const container = document.querySelector('.wrapper-recettes');
                        container.innerHTML = `
                            <div class="flex flex-col items-center justify-center w-full mt-10 gap-6">
                                <h2 class="text-3xl font-bold">Votre panier de recettes est vide :(</h2>
                                <p class="text-lg">Ajoutez des recettes à votre panier pour les retrouver ici</p>
                                <p>Retrouvez les recettes à cette page: <a href="/recherche" class="underline bg-blue-400 cursor-pointer px-4 py-2 rounded-xl hover:bg-blue-300 text-xl">ICI</a></p>
                            </div>
                        `;
                    }
                });
            });
        });
    </script>
    <?php require_once __DIR__ . "/template/footer.php"; ?>
</body>
</html>
