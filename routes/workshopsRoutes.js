const express = require('express');
const router = express.Router();
const workshopsController = require('../controllers/workshopsController');

router.get('/workshops', workshopsController.getWorkshops);
router.post('/workshops', workshopsController.createWorkshop);
router.put('/workshops/:id', workshopsController.updateWorkshop);
router.delete('/workshops/:id', workshopsController.deleteWorkshop);

module.exports = router;