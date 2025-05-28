const express = require('express');
const router = express.Router();
const recipesController = require('../controllers/recipesController');

router.get('/recipes', recipesController.getRecipes);
router.post('/recipes', recipesController.createRecipe);
router.put('/recipes/:id', recipesController.updateRecipe);
router.delete('/recipes/:id', recipesController.deleteRecipe);

module.exports = router;