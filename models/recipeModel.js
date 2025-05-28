const db = require('../config/db');

const getAllRecipes = async () => {
    const result = await db.query('SELECT * FROM recipes');
    return result.rows;
};

const addRecipe = async (title, description, ingredients, instructions, chef_id) => {
    const result = await db.query(
        'INSERT INTO recipes (title, description, ingredients, instructions, chef_id) VALUES ($1, $2, $3, $4, $5) RETURNING *',
        [title, description, ingredients, instructions, chef_id]
    );
    return result.rows[0];
};

const updateRecipe = async (id, title, description, ingredients, instructions) => {
    const result = await db.query(
        'UPDATE recipes SET title = $1, description = $2, ingredients = $3, instructions = $4 WHERE id = $5 RETURNING *',
        [title, description, ingredients, instructions, id]
    );
    return result.rows[0];
};

const deleteRecipe = async (id) => {
    await db.query('DELETE FROM recipes WHERE id = $1', [id]);
};

module.exports = { getAllRecipes, addRecipe, updateRecipe, deleteRecipe };