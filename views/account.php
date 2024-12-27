<?php
    require_once __DIR__ . "/template/header.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php";
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>

    <div class="wrapper-recettes flex flex-wrap flex-col gap-4 items-center justify-center">

        <div id="connectionDiv" >

            <form id='change' class="" action="/change" method="post">
                
                <fieldset>
                    <legend>Informations personnelles</legend>
                    <br>
                    Login : <?= htmlspecialchars($informations["login"]) ?><br><br>   
                    Mot de passe : <input type="password" name="password" class="w-full focus:scale-105 duration-300 border border-gray-400"><br><br>
                    Nom : <input type="text" name="last_name" class="w-full focus:scale-105 duration-300 border border-gray-400" value="<?= htmlspecialchars($informations["nom"]) ?>"><br><br>   
                    Prénom : <input type="text" name="first_name" class="w-full focus:scale-105 hover:scale-105 duration-300 border border-gray-950" value="<?= htmlspecialchars($informations["prenom"]) ?>"><br><br>
                    Genre :
                    <input type="radio" name="gender" value="h" <?= $informations["genre"] == "h" ? "checked" : "" ?>> Homme 	
                    <input type="radio" name="gender" value="f" <?= $informations["genre"] == "f" ? "checked" : "" ?>> Femme
                    <input type="radio" name="gender" value="v" <?= $informations["genre"] == "v" ? "checked" : "" ?>> Autre/ne se prononce pas
                    <br ><br>
                    Email :<input type="email" id="email" name="email" class="w-full focus:scale-105 duration-300 border border-gray-400"  value="<?= htmlspecialchars($informations["email"]) ?>"> <br><br>
                    Adresse : <input type="text" id="adresse" name="adresse" class="w-full focus:scale-105 duration-300 border border-gray-400"  value="<?= htmlspecialchars($informations["adresse"]) ?>"><br>
                    Code postal : <input type="text" id="zipcode" name="zipcode" maxlength="6" class="w-full focus:scale-105 duration-300 border border-gray-400"  value="<?= htmlspecialchars($informations["code_postal"]) ?>">
                    Ville: <input type="text" id="ville" name="ville" class="w-full focus:scale-105 duration-300 border border-gray-400"  value="<?= htmlspecialchars($informations["ville"]) ?>"> <br>
                    
                </fieldset><br>
    
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" value="Valider" name="submit" >
        
            </form>
        </div>

        <?php

        if(isset($error)){
            echo "<div class='error'>$error</div>";
        } else if(isset($success)){
            echo "<div class='success'>$success</div>";
        }
        ?>

    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>
