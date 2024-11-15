<?php 
    use App\Controllers\Database as Database;
    
    $title = "Home";
    $db = new Database();
    $pdo = $db->getConnection();
    $db->connectToDatabase();

    $sql = "SELECT id_sous FROM hierarchie WHERE id_super IN (SELECT id_aliment FROM aliments WHERE nom = 'Aliment')";
    $stmt = $pdo->prepare($sql);
    // $stmt->bindParam(":nom_aliment", "Aliment");
    $stmt->execute();

    $hierarchie = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($hierarchie);

    require_once __DIR__ . "/template/header.php";
    ?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">
        
    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>