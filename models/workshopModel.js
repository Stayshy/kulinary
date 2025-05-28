const db = require('../config/db');

const getAllWorkshops = async () => {
    const result = await db.query('SELECT * FROM workshops');
    return result.rows;
};

const addWorkshop = async (title, description, date, chef_id, recipe_id) => {
    const result = await db.query(
        'INSERT INTO workshops (title, description, date, chef_id, recipe_id) VALUES ($1, $2, $3, $4, $5) RETURNING *',
        [title, description, date, chef_id, recipe_id]
    );
    return result.rows[0];
};

const updateWorkshop = async (id, title, description, date, recipe_id) => {
    const result = await db.query(
        'UPDATE workshops SET title = $1, description = $2, date = $3, recipe_id = $4 WHERE id = $5 RETURNING *',
        [title, description, date, recipe_id, id]
    );
    return result.rows[0];
};

const deleteWorkshop = async (id) => {
    await db.query('DELETE FROM workshops WHERE id = $1', [id]);
};

module.exports = { getAllWorkshops, addWorkshop, updateWorkshop, deleteWorkshop };