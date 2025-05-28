const workshopModel = require('../models/workshopModel');

const getWorkshops = async (req, res) => {
    try {
        const workshops = await workshopModel.getAllWorkshops();
        res.status(200).json(workshops);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const createWorkshop = async (req, res) => {
    const { title, description, date, chef_id, recipe_id } = req.body;
    try {
        const newWorkshop = await workshopModel.addWorkshop(title, description, date, chef_id, recipe_id);
        res.status(201).json(newWorkshop);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const updateWorkshop = async (req, res) => {
    const { id } = req.params;
    const { title, description, date, recipe_id } = req.body;
    try {
        const updatedWorkshop = await workshopModel.updateWorkshop(id, title, description, date, recipe_id);
        if (!updatedWorkshop) return res.status(404).json({ message: 'Workshop not found' });
        res.status(200).json(updatedWorkshop);
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

const deleteWorkshop = async (req, res) => {
    const { id } = req.params;
    try {
        await workshopModel.deleteWorkshop(id);
        res.status(204).send();
    } catch (error) {
        res.status(500).json({ message: 'Server Error' });
    }
};

module.exports = { getWorkshops, createWorkshop, updateWorkshop, deleteWorkshop };