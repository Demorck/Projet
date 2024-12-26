/**
 * @class RecipeSearch
    * @description Class qui gère la recherche de recettes
    * @param {HTMLElement} containerElement - L'élément HTML dans lequel afficher le formulaire de recherche
    * @property {HTMLElement} container - L'élément HTML dans lequel afficher le formulaire de recherche
    * @property {Array} includedIngredients - Les ingrédients inclus dans la recherche
    * @property {Array} excludedIngredients - Les ingrédients exclus de la recherche
 */
class RecipeSearch {
    constructor(containerElement) {
        this.container = containerElement;
        this.changeSearch = document.getElementById('change-search');
        this.includedIngredients = [];
        this.excludedIngredients = [];
        this.hierarchy = {};
        this.init();
    }

    /**
     * @method init
        * @description Initialise le formulaire de recherche
     */
    async init() {
        await this.fetchHierarchy();
        this.setupEventListeners();
        await this.updateResults();
        
        

        const testDiv = document.getElementById('test');
        this.generateHierarchy(this.hierarchy, testDiv);

    }

    async fetchHierarchy() {
        const response = await fetch('/search/hierarchy');
        this.hierarchy = await response.json();
    }

    /**
     * Cherche un ingrédient
     * @param {string} term Un terme de recherche
     * @returns 
     */
    async searchIngredients(term) {
        const response = await fetch(`/search/searchIngredients?term=${encodeURIComponent(term)}`);
        return await response.json();
    }

    /**
     * Cherche des recettes par rapport aux ingrédients inclus et exclus
     * @returns JSON
     */
    async searchRecipes() {
        const response = await fetch('/search/searchRecipes', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                included: this.includedIngredients.map(i => i.id_aliment),
                excluded: this.excludedIngredients.map(i => i.id_aliment)
            })
        });
        return await response.json();
    }

    /**
     * @method setupEventListeners
        * @description Initialise les écouteurs d'événements pour le formulaire de recherche
     */
    setupEventListeners() {
        const searchInput = this.container.querySelector('#searchInput');
        const suggestionsDiv = this.container.querySelector('#suggestions');

        searchInput.addEventListener('input', async (e) => {
            let type = e.target.dataset.type === 'true';
            let value = e.target.value;
            
            if (type) {
                this.scoreAndSortRecipes(value);
            } else {
                const term = e.target.value;
                if (term.length < 2) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }

                const suggestions = await this.searchIngredients(term);
                suggestionsDiv.innerHTML = suggestions
                    .map(s => `
                        <div class="suggestion-item" data-id="${s.id_aliment}">
                            ${s.path ? s.path + ' > ' : ''}${s.nom}
                        </div>
                    `)
                    .join('');
                suggestionsDiv.style.display = 'block';
            }
        });

        suggestionsDiv.addEventListener('click', async (e) => {
            if (e.target.classList.contains('suggestion-item')) {
                const id = parseInt(e.target.dataset.id);
                const name = e.target.textContent;
                this.includedIngredients.push({ id_aliment: id, name });
                this.updateTags();
                searchInput.value = '';
                suggestionsDiv.style.display = 'none';

                this.checkCheckbox(name);
            }
        });

        suggestionsDiv.addEventListener('contextmenu', async (e) => {
            e.preventDefault();
            if (e.target.classList.contains('suggestion-item')) {
                const id = parseInt(e.target.dataset.id);
                const name = e.target.textContent;
                this.excludedIngredients.push({ id_aliment: id, name });
                this.updateTags();
                searchInput.value = '';
                suggestionsDiv.style.display = 'none';

                this.checkCheckbox(name);
            }
        });

        const includedDiv = this.container.querySelector('#includedTags');
        includedDiv.addEventListener("click", (event) => {
            if (event.target.classList.contains("remove-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                
                this.uncheckCheckbox(this.includedIngredients[index].name);
                this.removeIngredient(index, type);                
            } else if (event.target.classList.contains("move-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                if (type === 'included') {
                    const ingredient = this.includedIngredients.splice(index, 1)[0];
                    this.excludedIngredients.push(ingredient);
                } else {
                    const ingredient = this.excludedIngredients.splice(index, 1)[0];
                    this.includedIngredients.push(ingredient);
                }
        
                this.updateTags();
            }
        });

        const excludedDiv = this.container.querySelector('#excludedTags');
        excludedDiv.addEventListener("click", (event) => {
            if (event.target.classList.contains("remove-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                this.uncheckCheckbox(this.excludedIngredients[index].name);
                this.removeIngredient(index, type);
            } else if (event.target.classList.contains("move-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                if (type === 'included') {
                    const ingredient = this.includedIngredients.splice(index, 1)[0];
                    this.excludedIngredients.push(ingredient);
                } else {
                    const ingredient = this.excludedIngredients.splice(index, 1)[0];
                    this.includedIngredients.push(ingredient);
                }
        
                this.updateTags();
            }
        });

        this.changeSearch.addEventListener('click', async (e) => {
            let input = document.getElementById('searchInput');
            input.dataset.type = e.target.checked;
        });
    }

    async addIngredient(id, name, type) {
        if (type === 'included') {
            this.includedIngredients.push({ id_aliment: id, name });
        } else {
            this.excludedIngredients.push({ id_aliment: id, name });
        }

        this.updateTags();
    }

    async addIngredientByName(name, type)  {
        const response = await fetch('/search/id', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: name
            })
        });

        let res = await response.json();
        console.log(res.path != null);
        
        if (res.path != null) {
            res.nom = res.path + ' > ' + res.nom;
        }
        
        if (type === 'included') {
            this.includedIngredients.push({ id_aliment: res.id_aliment, name: res.nom });
        } else {
            this.excludedIngredients.push({ id_aliment: res.id_aliment, name: res.nom });
        }

        this.updateTags();
    }

    async removeIngredient(index, type) {
        if (type === 'included') {
            this.includedIngredients.splice(index, 1);
        } else {
            this.excludedIngredients.splice(index, 1);
        }

        this.updateTags();
    }

    async removeIngredientByName(name) {
        const response = await fetch('/search/id', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: name
            })
        });

        let res = await response.json();

        this.includedIngredients = this.includedIngredients.filter(i => i.id_aliment !== res.id_aliment);
        this.excludedIngredients = this.excludedIngredients.filter(i => i.id_aliment !== res.id_aliment);

        console.log(this.includedIngredients);
        
        this.updateTags();
    }

    uncheckCheckbox(name) {
        name = name.trim();
        name = name.split(' > ').pop();
        console.log(name);
        
        let checkboxes = document.querySelectorAll('input[name="' + name + '"]');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
            console.log(checkbox);
            
        });
    }

    checkCheckbox(name) {
        name = name.trim();
        name = name.split(' > ').pop();
        let checkboxes = document.querySelectorAll('input[name="' + name + '"]');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = true;
        });
    }

    async updateTags() {
        const includedDiv = this.container.querySelector('#includedTags');
        const excludedDiv = this.container.querySelector('#excludedTags');

        includedDiv.innerHTML = this.generateTags(this.includedIngredients, 'included');
        console.log(this.includedIngredients);
        
        excludedDiv.innerHTML = this.generateTags(this.excludedIngredients, 'excluded');

        await this.updateResults();
    }

    async updateResults() {
        const resultsDiv = this.container.querySelector('#results');
        const recipes = await this.searchRecipes();

        resultsDiv.innerHTML = recipes
            .map(recipe => this.generateRecipeCard(recipe))
            .join('');
    }

    generateTags(ingredients, type) {
        return ingredients
            .map((ing, i) => this.generateTagCard(ing, type, i))
            .join('');
    }

    generateTagCard(ingredient, type, i) {
        return `
            <span class="tag ${type}">
                ${ingredient.name}
                <span class="remove-tag" data-index="${i}" data-type="${type}">×</span>&nbsp;&nbsp;
                <span class="move-tag" data-index="${i}" data-type="${type}">${type === 'included' ? '→' : '←'}</span>
            </span>
        `;
    }

    generateRecipeCard(recipe) {
        if (recipe.total_ingredients == undefined) {
            recipe.total_ingredients = 1;
            recipe.matched_ingredients = 1;
        }
        
        return `
            <div class="recipe-card">
                <h3>${recipe.nom}</h3>
                <p>Score: ${(recipe.matched_ingredients / recipe.total_ingredients * 100).toFixed(1)}%</p>
                <p class="description">${recipe.description}</p>
            </div>
        `;
    }

    generateHierarchy(hierarchy, parentElement) {
        for (const key in hierarchy) {
            const value = hierarchy[key];

            // Conteneur
            const nodeContainer = document.createElement('div');
            nodeContainer.classList.add('ml-4', 'flex', 'flex-col', 'gap-1');

            // Nœud parent
            const nodeElement = document.createElement('div');
            nodeElement.classList.add('cursor-pointer', 'font-semibold', 'text-gray-700', 'hover:text-blue-500', 'flex', 'items-center', 'gap-2');

            // Label
            const label = document.createElement('label');
            label.classList.add('flex', 'flex-row-reverse', 'gap-2',   'font-semibold');

            // Icône pour indiquer que le nœud peut se dérouler
            const toggleIcon = document.createElement('span');
            toggleIcon.classList.add('text-sm', 'text-gray-500', 'font-bold');

            nodeElement.appendChild(toggleIcon);
            nodeElement.appendChild(label)

            // Conteneur pour les enfants
            const childrenContainer = document.createElement('div');
            childrenContainer.classList.add('hidden', 'flex', 'flex-col');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.classList.add('rounded', 'border-gray-300', 'focus:ring-blue-500');

            // Si le nœud a des enfants, on génère les sous-nœuds
            if (typeof value === 'object' && value !== null) {
                label.textContent = key;
                
                toggleIcon.textContent = '+';
                nodeElement.addEventListener('click', () => {
                    childrenContainer.classList.toggle('hidden');
                    toggleIcon.textContent = childrenContainer.classList.contains('hidden') ? '+' : '-';
                });

                
                checkbox.name = key;
                checkbox.id = key;

                this.generateHierarchy(value, childrenContainer);
            } else {
                
                toggleIcon.textContent = "\u00A0\u00A0";
                label.textContent = value;
                checkbox.name = value;
                checkbox.id = value;
            }

            checkbox.addEventListener('change', async (event) => {
                const checked = event.target.checked;
                if (checked) {
                    this.addIngredientByName(event.target.name, "included");
                } else {
                    this.removeIngredientByName(event.target.name);
                }
            });

            label.appendChild(checkbox);

            // Ajouter le nœud et les enfants au conteneur
            nodeContainer.appendChild(nodeElement);
            nodeContainer.appendChild(childrenContainer);
            parentElement.appendChild(nodeContainer);
        }

        return parentElement;
    }

    scoreAndSortRecipes(value) {
        const recettes = document.querySelectorAll('.recipe-card');
    
        // Pondération des critères
        const WEIGHTS = {
            nameMatch: 50,
            nameExactMatch: 30,
            descriptionMatch: 20,
        };
    
        // Fonction pour calculer le score d'une recette
        const calculateScore = (recette, searchTerms) => {
            let score = 0;
            const description = recette.querySelector('p.description').textContent.toLowerCase();
            const recetteName = recette.querySelector('h3').textContent.toLowerCase();
    
            // 1. Présence dans le nom de la recette
            const nameMatches = searchTerms.filter(term => recetteName.includes(term)).length;
            if (nameMatches > 0) {
                score += nameMatches * WEIGHTS.nameMatch;
    
                // Bonus si tous les mots correspondent exactement
                if (searchTerms.every(term => recetteName.includes(term))) {
                    score += WEIGHTS.nameExactMatch;
                }
            }
    
            // 2. Présence dans la description
            const descriptionMatches = searchTerms.filter(term => description.includes(term)).length;
            if (descriptionMatches > 0) {
                score += descriptionMatches * WEIGHTS.descriptionMatch;
            }
    
            searchTerms.forEach(term => {
                const nameIndex = recetteName.indexOf(term);
                const descriptionIndex = description.indexOf(term);
    
                if (nameIndex >= 0) {
                    score += Math.max(0, WEIGHTS.nameMatch - nameIndex);
                }
    
                if (descriptionIndex >= 0) {
                    score += Math.max(0, WEIGHTS.descriptionMatch - descriptionIndex);
                }
            });
    
            // Normalisation du score entre 0 et 100
            return Math.min(100, Math.round(score));
        };
    
        const searchTerms = value.toLowerCase().split(' ');
    
        const scoredRecettes = [...recettes].map(recette => {
            const score = calculateScore(recette, searchTerms);
            return { recette, score };
        });
    
        // 3. Trier les recettes par score décroissant
        scoredRecettes.sort((a, b) => b.score - a.score);
    
        // Mettre à jour l'affichage
        scoredRecettes.forEach(({ recette, score }) => {
            const scoreElement = recette.querySelector('p');
            scoreElement.textContent = 'Score: ' + score + '%';
    
            // Cacher les recettes avec un score négatif
            if (score <= 0) {
                recette.style.display = 'none';
            } else {
                recette.style.display = 'block';
            }
        });
    
        const parent = document.querySelector('#results');
        parent.innerHTML = ''; 
        scoredRecettes.forEach(({ recette }) => {
            parent.appendChild(recette); 
        });
    }
}