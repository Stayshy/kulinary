const db = require('../config/db');

const addRegistration = async (user_id, workshop_id) => {
    const result = await db.query(
        'INSERT INTO registrations (user_id, workshop_id) VALUES ($1, $2) RETURNING *',
        [user_id, workshop_id]
    );
    return result.rows[0];
};

const deleteRegistration = async (id) => {
    await db.query('DELETE FROM registrations WHERE id = $1', [id]);
};

module.exports = { addRegistration, deleteRegistration };