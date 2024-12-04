<?php 
    require_once __DIR__ . "/template/header.php";
    ?>
    <style>
        .hierarchy {
            display: none;
        }
        .hierarchy:first-child {
            display: flex;
        }
        .hierarchy:hover > div {
            display: flex;
        }
    </style>
<body>
    <?php require_once __DIR__ . "/template/navbar.php"; ?>
    <h2>Les recettes du moment</h2>
    <div class="wrapper-recettes flex flex-wrap flex-row gap-4 items-end justify-start">
        <div id="test">

        </div>
    </div>
    <script>
        json = <?= json_encode($hierarchie) ?>;
        function generateHierarchy(hierarchy, parentElement) {
            for (const key in hierarchy) {
                const value = hierarchy[key];

                const div = document.createElement('div');
                div.classList.add('hierarchy');

                if (typeof value === 'object') {
                    const divEnglobe = document.createElement('div');
                    divEnglobe.classList.add('hierarchy');
                    const h2 = document.createElement('h2');
                    h2.textContent = key;
                    divEnglobe.appendChild(h2);
                    generateHierarchy(value, divEnglobe);
                    divEnglobe.appendChild(div);
                    parentElement.appendChild(divEnglobe);
                } else {
                    const p = document.createElement('p');
                    p.textContent = value;
                    parentElement.appendChild(p);
                }

            }
        }

        const testDiv = document.getElementById('test');
        console.log(json);
        generateHierarchy(json, testDiv);
    </script>
    <footer>
        <p>&copy; 2024 - Recettes de cuisine</p>
    </footer>
</body>
</html>