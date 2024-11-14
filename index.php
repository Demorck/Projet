<?php 
    $title = "Home";
    include_once("./include/header.php");
    include_once("./include/Donnees.inc.php");
    include_once("./include/Helpers.php");
    
    ?>
<body>
    <?php include_once("./include/navbar.php"); ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <?php
        foreach ($Recettes as $recette) {
            ?>
            <div class="recette flex flex-col w-1/6">
                <h3><?php echo $recette["titre"]; ?></h3>
                <img src="<?php echo Helpers::getImage($recette["titre"]); ?>">
                <!-- <p><?php echo $recette["ingredients"]; ?></p>s -->
            </div>

            <?php
        } ?>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>