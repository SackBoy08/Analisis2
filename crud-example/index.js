const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const swaggerUi = require('swagger-ui-express');
const swaggerJsdoc = require('swagger-jsdoc');

const app = express();
app.use(bodyParser.json());

// Configuración de Swagger
const swaggerOptions = {
  definition: {
    openapi: '3.0.0',
    info: {
      title: 'CRUD API',
      version: '1.0.0',
      description: 'Una API simple para gestionar usuarios.',
    },
  },
  apis: ['./index.js'], // Ruta al archivo con anotaciones
};

const swaggerSpec = swaggerJsdoc(swaggerOptions);
app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));

// Configurar la conexión a la base de datos
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '1234',
  database: 'crud_example'
});

db.connect((err) => {
  if (err) {
    throw err;
  }
  console.log('Conectado a la base de datos MySQL');
});

// Endpoints CRUD

/**
 * @openapi
 * /users:
 *   get:
 *     summary: Obtiene una lista de usuarios
 *     responses:
 *       200:
 *         description: Lista de usuarios
 */
app.get('/users', (req, res) => {
  let sql = 'SELECT * FROM users';
  db.query(sql, (err, results) => {
    if (err) throw err;
    res.json(results);
  });
});

/**
 * @openapi
 * /users:
 *   post:
 *     summary: Crea un nuevo usuario
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               Nombre:
 *                 type: string
 *               Correo:
 *                 type: string
 *     responses:
 *       200:
 *         description: Usuario creado
 */
app.post('/users', (req, res) => {
  const { Nombre, Correo } = req.body;
  let sql = 'INSERT INTO users (Nombre, Correo) VALUES (?, ?)';
  db.query(sql, [Nombre, Correo], (err, result) => {
    if (err) throw err;
    res.json({ DPI: result.insertId, Nombre, Correo });
  });
});

/**
 * @openapi
 * /users/{dpi}:
 *   put:
 *     summary: Actualiza un usuario existente
 *     parameters:
 *       - in: path
 *         name: dpi
 *         required: true
 *         description: DPI del usuario
 *         schema:
 *           type: integer
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               Nombre:
 *                 type: string
 *               Correo:
 *                 type: string
 *     responses:
 *       200:
 *         description: Usuario actualizado
 */
app.put('/users/:dpi', (req, res) => {
  const { Nombre, Correo } = req.body;
  let sql = 'UPDATE users SET Nombre = ?, Correo = ? WHERE DPI = ?';
  db.query(sql, [Nombre, Correo, req.params.dpi], (err, result) => {
    if (err) throw err;
    res.json({ DPI: req.params.dpi, Nombre, Correo });
  });
});

/**
 * @openapi
 * /users/{dpi}:
 *   delete:
 *     summary: Elimina un usuario
 *     parameters:
 *       - in: path
 *         name: dpi
 *         required: true
 *         description: DPI del usuario
 *         schema:
 *           type: integer
 *     responses:
 *       200:
 *         description: Usuario eliminado
 */
app.delete('/users/:dpi', (req, res) => {
  let sql = 'DELETE FROM users WHERE DPI = ?';
  db.query(sql, [req.params.dpi], (err, result) => {
    if (err) throw err;
    res.json({ message: 'Usuario eliminado' });
  });
});

// Iniciar el servidor
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Servidor corriendo en el puerto ${PORT}`);
});

module.exports = app; // Exporta la app para pruebas
