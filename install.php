<?php

include($_SERVER['DOCUMENT_ROOT'] . "/include/Helpers/InstallDatabase.php");

$titre = "Installation";
include($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");

if (isset($_POST["install"])) {
    try {
        $install = new InstallDatabase();
        $install->dropTables();
        $install->createTables();
        $install->insertAliments();
        $install->insertHierarchie();

        $finished = "Installation terminée";
    } catch (PDOException $e) {
        $finished = "Erreur d'installation: " . $e->getMessage();
    } finally {
        $install = null;
        $_POST = [];
    }
}

?>
<body>
    <?php include_once("./include/navbar.php"); ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <p><?php if(isset($install)) echo $install?></p>
        <form action="#" method="post">
            <button type="submit" name="install">Installer</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>