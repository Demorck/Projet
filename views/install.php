<?php
require_once __DIR__ . "/template/header.php";
?>
<body>
    <?php require  __DIR__ . "/template/navbar.php"; ?>
    <div class="wrapper-recettes flex flex-wrap flex-col gap-4 items-center justify-center">
        <h2 class="text-2xl flex items-center flex-col">Cliquer sur le bouton pour installer la database. <br><span class="font-bold">Attention, toutes les données (utilisateurs et favoris) vont être supprimées !</span></h2>
        <?php if(isset($success)) echo '<p class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">' . $success . '</p>' ?>
        <?php if(isset($error)) echo '<p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">' . $error . '</p>' ?>
        <form action="" method="post">
            <button type="submit" name="install" class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'">Installer</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>