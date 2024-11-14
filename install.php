<?php

include_once("./include/Donnees.inc.php");

$titre = "Installation";
include_once("./include/header.php");

function aliments(PDO $pdo){
    foreach ($Hierarchie as $key => $aliment) {    
        $sql = "INSERT INTO aliments (nom) VALUES (:nom)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":nom" => $key]);

    }
} 

if (isset($_POST["install"])) {
    try {
        $host = "localhost";
        $dbname = "recettes";
        $user = "root";
        $password = "";

        $dsn = "mysql:host=$host;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $password);

        // aliments($pdo);

        foreach ($Hierarchie as $key => $aliment) {    
            if (isset($aliment["sous-categorie"]))
            {
                foreach ($aliment["sous-categorie"] as $sousCategorie) {
                    $sqlSuper = "SELECT id_aliment FROM aliments WHERE nom = :nom";
                    $stmt = $pdo->prepare($sqlSuper);
                    $stmt->execute([":nom" => $key]);
                    $resSuper = $stmt->fetchAll()[0];
                    print_r($resSuper);

        
                    $sqlSous = "SELECT id_aliment FROM aliments WHERE nom = :nom";
                    $stmt = $pdo->prepare($sqlSous);
                    $stmt->execute([":nom" => $sousCategorie]);
                    $resSous = $stmt->fetchAll()[0];

                    $sql = "INSERT INTO hierarchie (id_super, id_sous) VALUES (:id_super, :id_sous)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([":id_super" => $resSuper["id_aliment"], ":id_sous" => $resSous["id_aliment"]]);
                }
            }
        }

        
        echo "Installation terminée";
    } catch (PDOException $e) {
        echo "Erreur d'installation: " . $e->getMessage();
    }
}




?>
<body>
    <?php include_once("./include/navbar.php"); ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        <form action="#" method="post">
            <button type="submit" name="install">Installer</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>