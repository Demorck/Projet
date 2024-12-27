<?php
    require_once __DIR__ . "/template/header.php";
    use App\Helpers\Utils;
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <?php foreach ($data['favorites'] as $favorite): ?>
            <div class="recette flex w-1/6 flex-col items-center justify-center">
                <h3 class="text-xl "><?= $favorite['nom']; ?></h3>
                <img src="<?= Utils::getImage($favorite['nom']); ?>" alt="<?= $favorite['nom']; ?>">
                <p><?= $favorite['description']; ?></p>
                <ul>
                    <?php
                        $splitted = explode("|", $favorite['ingredients']);
                        foreach ($splitted as $value): ?>
                            <li><?= $value; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <?php require_once __DIR__ . "/template/footer.php"; ?>
</body>
</html>
