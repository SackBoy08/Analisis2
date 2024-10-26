<?php

use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    protected $dbh;

    protected function setUp(): void
    {
        // Establecer conexión a la base de datos
        $this->dbh = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'tu_usuario', 'tu_contraseña');
    }

    public function testChangePasswordSuccess()
    {
        // Simular sesión
        $_SESSION['alogin'] = 'admin';
        
        // Datos de prueba
        $currentPassword = md5('contraseñaActual'); // Cambia 'contraseñaActual' por la actual en la base de datos
        $newPassword = md5('nuevaContraseña');
        
        // Simular el cambio de contraseña
        $username = $_SESSION['alogin'];

        // Verificar la contraseña actual
        $sql = "SELECT Password FROM admin WHERE UserName=:username and Password=:password";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $currentPassword);
        $query->execute();

        $this->assertGreaterThan(0, $query->rowCount(), "La contraseña actual no es válida.");

        // Actualizar la contraseña
        $con = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
        $chngpwd1 = $this->dbh->prepare($con);
        $chngpwd1->bindParam(':username', $username);
        $chngpwd1->bindParam(':newpassword', $newPassword);
        $chngpwd1->execute();

        // Verificar que la nueva contraseña se haya guardado
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $newPassword);
        $query->execute();

        $this->assertGreaterThan(0, $query->rowCount(), "La nueva contraseña no se guardó correctamente.");
    }

    public function testChangePasswordFail()
    {
        // Simular sesión
        $_SESSION['alogin'] = 'admin';

        // Datos de prueba
        $currentPassword = md5('contraseñaIncorrecta'); // Contraseña incorrecta para la prueba
        $newPassword = md5('nuevaContraseña');

        // Simular el cambio de contraseña
        $username = $_SESSION['alogin'];

        // Verificar la contraseña actual
        $sql = "SELECT Password FROM admin WHERE UserName=:username and Password=:password";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $currentPassword);
        $query->execute();

        $this->assertEquals(0, $query->rowCount(), "Se debería haber encontrado que la contraseña actual es válida.");

        // Intentar actualizar la contraseña sin haber validado correctamente
        $con = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
        $chngpwd1 = $this->dbh->prepare($con);
        $chngpwd1->bindParam(':username', $username);
        $chngpwd1->bindParam(':newpassword', $newPassword);
        $chngpwd1->execute();

        // Verificar que la nueva contraseña no se haya guardado
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $newPassword);
        $query->execute();

        $this->assertEquals(0, $query->rowCount(), "No debería haberse guardado la nueva contraseña.");
    }

    protected function tearDown(): void
    {
        // Cerrar conexión a la base de datos
        $this->dbh = null;
    }
}
