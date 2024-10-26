<?php

namespace tests\acceptance;

use _generated\AcceptanceTesterActions;

class SignUpFormTest extends \Codeception\AcceptanceTester
{
    public function _before()
    {
        // Aquí puedes agregar configuraciones previas si es necesario
    }

    // Prueba para verificar el registro exitoso
    public function testUserRegistration()
    {
        // Configura la URL de tu formulario de registro
        $this->amOnPage('/bits&bytes/includes/registration.php');
        
        // Llenar el formulario
        $this->fillField('fullname', 'Juan Perez');
        $this->fillField('emailid', 'juanperez@example.com');
        $this->fillField('mobileno', '1234567890');
        $this->fillField('password', 'password123');
        $this->fillField('confirmpassword', 'password123');
        $this->checkOption('#terms_agree');
        
        // Enviar el formulario
        $this->click('Registrarse');
        
        // Verificar el resultado de registro
        $this->see('Registro exitoso. Ahora puedes iniciar sesión', 'script');
    }

    // Prueba para verificar el mensaje de error cuando las contraseñas no coinciden
    public function testPasswordMismatch()
    {
        $this->amOnPage('/bits&bytes/includes/registration.php');
        
        // Llenar el formulario con contraseñas diferentes
        $this->fillField('fullname', 'Carlos Lopez');
        $this->fillField('emailid', 'carloslopez@example.com');
        $this->fillField('mobileno', '0987654321');
        $this->fillField('password', 'password123');
        $this->fillField('confirmpassword', 'password456'); // Contraseña diferente
        $this->checkOption('#terms_agree');
        
        // Intentar registrar y verificar el mensaje de error de coincidencia de contraseñas
        $this->click('Registrarse');
        // Aquí cambiamos la forma en que verificamos el mensaje de error
        $this->see('El campo Contraseña y Confirmar contraseña no coinciden !!', 'script');
    }

    // Prueba para verificar que el correo esté disponible (opcional)
    public function testEmailAvailability()
    {
        $this->amOnPage('/bits&bytes/includes/registration.php');

        // Llenar el formulario
        $this->fillField('fullname', 'Ana Torres');
        $this->fillField('mobileno', '1234567890');
        $this->fillField('emailid', 'juanperez@example.com'); // Usar un correo que sabemos que se usa
        $this->fillField('password', 'password123');
        $this->fillField('confirmpassword', 'password123');
        $this->checkOption('#terms_agree');
        
        // Intentar registrar
        $this->click('Registrarse');
        
        // Verificar el mensaje de disponibilidad de correo
        // Asegúrate de que este mensaje sea el correcto basado en tu lógica de negocio
        $this->see('El correo electrónico ya está en uso', 'script'); // Este mensaje debería reflejar la lógica de tu aplicación
    }
}
