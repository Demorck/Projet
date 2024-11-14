<?php 
    $title = "Home";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php");
    
    ?>
<body>
    <?php include_once("./include/navbar.php"); ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <?php
        foreach ($Hierarchie as $key => $aliment) {
            ?>
            <div class="recette flex flex-col w-1/6">
                <h3 class="font-bold"><?php echo $key; ?></h3>
                <div class="flex flex-row">
                    <div class="flex w-1/2">
                        <?php if (isset($aliment["sous-categorie"])) { ?>
                            <ul>
                                <?php foreach ($aliment["sous-categorie"] as $sous_categorie) { ?>
                                    <li><?php echo $sous_categorie; ?></li>
                                <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <p><?php echo "Pas de sous-catégorie"; ?></p>
                        <?php } ?>
                    </div>

                    <div class="flex w-1/2">
                    <?php if (isset($aliment["super-categorie"])) { ?>
                        <ul>
                            <?php foreach ($aliment["super-categorie"] as $super_categorie) { ?>
                                <li><?php echo $super_categorie; ?></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p><?php echo "Pas de super-catégorie"; ?></p>
                    <?php } ?>
                    </div>
                </div>
            </div>

            <?php
        } ?>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>