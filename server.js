const express = require('express');
const app = express();
const PORT = 3000;

app.use(express.json());

let recipes = [
  { id: 1, name: 'Pancakes', description: 'Fluffy pancakes' },
  // Add more sample recipes
];

// Endpoint to get all recipes
app.get('/api/recipes', (req, res) => {
  res.json(recipes);
});

// Endpoint to get a recipe by ID
app.get('/api/recipes/:id', (req, res) => {
  const recipe = recipes.find(r => r.id === parseInt(req.params.id));
  if (recipe) {
    res.json(recipe);
  } else {
    res.status(404).send('Recipe not found');
  }
});

// Endpoint to add an ingredient to a recipe
app.post('/api/recipes/:id/ingredients', (req, res) => {
  const recipeID = parseInt(req.params.id);
  const { ingredientID, quantity, unit } = req.body;
  const recipe = recipes.find(r => r.id === recipeID);

  if (recipe) {
    if (!recipe.ingredients) {
      recipe.ingredients = [];
    }
    recipe.ingredients.push({ ingredientID, quantity, unit });
    res.status(200).json({ message: 'Ingredient added successfully' });
  } else {
    res.status(404).send('Recipe not found');
  }
});

// Sample data for testing
const ingredients = [
  { id: 1, name: 'Flour' },
  { id: 2, name: 'Sugar' },
];

// Endpoint to get ingredients (for testing)
app.get('/api/ingredients', (req, res) => {
  res.json(ingredients);
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
