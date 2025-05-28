const registrationModel = require('../models/registrationModel');

const createRegistration = async (req, res) => {
    const { user_id, workshop_id } = req.body;
    try {
        const newRegistration = await registrationModel.addRegistration(user_id, workshop_id);
        res.status(201).json(newRegistration);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const deleteRegistration = async (req, res) => {
    const { id } = req.params;
    try {
        await registrationModel.deleteRegistration(id);
        res.status(204).send();
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

module.exports = { createRegistration, deleteRegistration };