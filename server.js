const express = require('express');
const app = express();
const recipesRoutes = require('./routes/recipesRoutes');
const workshopsRoutes = require('./routes/workshopsRoutes');
const registrationsRoutes = require('./routes/registrationsRoutes');

app.use(express.json());
app.use('/api', recipesRoutes);
app.use('/api', workshopsRoutes);
app.use('/api', registrationsRoutes);

const port = 3000;
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});