const express = require('express');
const router = express.Router();
const registrationsController = require('../controllers/registrationsController');

router.post('/registrations', registrationsController.createRegistration);
router.delete('/registrations/:id', registrationsController.deleteRegistration);

module.exports = router;