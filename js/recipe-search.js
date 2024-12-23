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
        this.includedIngredients = [];
        this.excludedIngredients = [];
        this.init();
    }

    /**
     * @method init
        * @description Initialise le formulaire de recherche
     */
    async init() {
        this.setupEventListeners();
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
        });

        suggestionsDiv.addEventListener('click', async (e) => {
            if (e.target.classList.contains('suggestion-item')) {
                const id = e.target.dataset.id;
                const name = e.target.textContent;
                this.includedIngredients.push({ id_aliment: id, name });
                this.updateTags();
                searchInput.value = '';
                suggestionsDiv.style.display = 'none';
            }
        });

        suggestionsDiv.addEventListener('contextmenu', async (e) => {
            e.preventDefault();
            if (e.target.classList.contains('suggestion-item')) {
                const id = e.target.dataset.id;
                const name = e.target.textContent;
                this.excludedIngredients.push({ id_aliment: id, name });
                this.updateTags();
                searchInput.value = '';
                suggestionsDiv.style.display = 'none';
            }
        });

        const includedDiv = this.container.querySelector('#includedTags');
        includedDiv.addEventListener("click", (event) => {
            if (event.target.classList.contains("remove-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                this.removeIngredient(index, type);
            }
        });

        const excludedDiv = this.container.querySelector('#excludedTags');
        excludedDiv.addEventListener("click", (event) => {
            if (event.target.classList.contains("remove-tag")) {
                const index = event.target.dataset.index;
                const type = event.target.dataset.type; 
        
                this.removeIngredient(index, type);
            }
        });
    }

    

    async removeIngredient(index, type) {
        if (type === 'included') {
            this.includedIngredients.splice(index, 1);
        } else {
            this.excludedIngredients.splice(index, 1);
        }

        this.updateTags();
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
                <span class="remove-tag" data-index="${i}" data-type="${type}">×</span>
            </span>
        `;
    }

    generateRecipeCard(recipe) {
        return `
            <div class="recipe-card">
                <h3>${recipe.nom}</h3>
                <p>Score: ${(recipe.matched_ingredients / recipe.total_ingredients * 100).toFixed(1)}%</p>
                <p>${recipe.description}</p>
            </div>
        `;
    }
}


const searchContainer = document.getElementById('recipe-search');
const recipeSearch = new RecipeSearch(searchContainer);