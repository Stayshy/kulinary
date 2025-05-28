const recipeModel = require('../models/recipeModel');

const getRecipes = async (req, res) => {
    try {
        const recipes = await recipeModel.getAllRecipes();
        res.status(200).json(recipes);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const createRecipe = async (req, res) => {
    const { title, description, ingredients, instructions, chef_id } = req.body;
    try {
        const newRecipe = await recipeModel.addRecipe(title, description, ingredients, instructions, chef_id);
        res.status(201).json(newRecipe);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const updateRecipe = async (req, res) => {
    const { id } = req.params;
    const { title, description, ingredients, instructions } = req.body;
    try {
        const updatedRecipe = await recipeModel.updateRecipe(id, title, description, ingredients, instructions);
        if (!updatedRecipe) return res.status(404).json({ message: 'Recipe not found' });
        res.status(200).json(updatedRecipe);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const deleteRecipe = async (req, res) => {
    const { id } = req.params;
    try {
        await recipeModel.deleteRecipe(id);
        res.status(204).send();
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

module.exports = { getRecipes, createRecipe, updateRecipe, deleteRecipe };