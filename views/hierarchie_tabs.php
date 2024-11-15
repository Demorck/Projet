<?php 
    use App\Controllers\Database as Database;
    
    $title = "Home";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php");
    


    $db = new Database();
    $pdo = $db->getConnection();

    $sql = "SELECT id_super FROM hierarchie WHERE id_super IN (SELECT id_aliment FROM aliments WHERE nom_aliment = :nom_aliment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nom_aliment", "Aliment");
    $stmt->execute();

    $hierarchie = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($hierarchie);

    ?>
<body>
    <?php include_once("./include/navbar.php"); ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>