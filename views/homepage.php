<?php
    require_once __DIR__ . "/template/header.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php";
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <?php
        foreach ($Recettes as $recette) {
            ?>
            <div class="recette flex flex-col w-1/6">
                <h3><?php echo $recette["titre"]; ?></h3>
                <p><?php echo $recette["ingredients"]; ?></p>s 
            </div>

            <?php
        } ?>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>
