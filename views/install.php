<?php
require_once __DIR__ . "/template/header.php";
?>
<body>
    <?php require  __DIR__ . "/template/navbar.php"; ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <p><?php if(isset($install)) echo $install?></p>
        <form action="" method="post">
            <button type="submit" name="install">Installer</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>