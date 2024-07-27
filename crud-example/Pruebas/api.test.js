const chai = require('chai');
const chaiHttp = require('chai-http');
const app = require('../index');
const mysql = require('mysql2');

chai.use(chaiHttp);
const { expect } = chai;

// Configuración de la conexión a la base de datos
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '1234',
  database: 'crud_example'
});

// Limpiar la tabla antes de ejecutar las pruebas
before((done) => {
  connection.query('DELETE FROM users WHERE Correo = ?', ['testuser@example.com'], (err) => {
    if (err) throw err;
    done();
  });
});

describe('API Tests', () => {
  it('should list all users', (done) => {
    chai.request(app)
      .get('/users')
      .end((err, res) => {
        expect(res).to.have.status(200);
        expect(res.body).to.be.an('array');
        done();
      });
  });

  it('should create a new user', (done) => {
    chai.request(app)
      .post('/users')
      .send({ Nombre: 'Test User', Correo: 'testuser@example.com' })
      .end((err, res) => {
        expect(res).to.have.status(200);
        expect(res.body).to.have.property('DPI');
        done();
      });
  });
});

// Cerrar la conexión después de las pruebas
after((done) => {
  connection.end(done);
});
