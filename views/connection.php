<?php
    require_once __DIR__ . "/template/header.php";

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/Donnees.inc.php";
?>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-center">

<!--
login mdp nom prénom sexe (homme ou femme), adresse électronique, date de naissance,
adresse postale (décomposée en adresse, code postal et ville) et numéro de téléphone
obligatoires = login + mdp
-->

    <form action="/register" method="post">

    <fieldset>
        <legend>Informations personnelles</legend>
        Login : <input type="text" name="login" required="required" ><br>   
        Mot de passe : <input type="text" name="password" required="required" ><br>
        Nom : <input type="text" name="last_name"><br>   
        Prénom : <input type="text" name="first_name"><br>
        Genre : <input type="radio" name="gender" value="h"> Homme 	
                <input type="radio" name="gender" value="f"> Femme
                <input type="radio" name="gender" value="v"> Autre/ne se prononce pas
        <br >
        Email : ? <br> 
        Adresse <br>

    </fieldset>

    <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" value="Valider" name="submit" >
            
    </form>

    </div>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>
