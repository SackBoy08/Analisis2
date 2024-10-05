<?php

use Codeception\Util\Fixtures;
use Codeception\UnitTester; // Asegúrate de que esta línea esté presente y correcta

class GuardarProductoCest
{
    public function _before(UnitTester $I)
    {
        // Inicializa cualquier dato o estado necesario aquí
    }

    public function testAddVehicle(UnitTester $I)
    {
        // Simular datos de entrada
        $_POST['vehicletitle'] = 'Prueba Vehículo';
        $_POST['brandname'] = 1; // Asumiendo que 1 es un ID de marca válido
        $_POST['vehicalorcview'] = 'Descripción de prueba';
        $_POST['priceperday'] = 100;
        $_POST['fueltype'] = 'Petrol';
        $_POST['modelyear'] = '2024';
        $_POST['seatingcapacity'] = '5';
        $_POST['img1'] = 'imagen1.jpg'; 
        $_POST['img2'] = 'imagen2.jpg';
        $_POST['img3'] = 'imagen3.jpg';

        // Simular el comportamiento de la carga de archivos
        $_FILES['img1'] = ['name' => 'imagen1.jpg', 'tmp_name' => '/path/to/temp/img1.jpg'];
        $_FILES['img2'] = ['name' => 'imagen2.jpg', 'tmp_name' => '/path/to/temp/img2.jpg'];
        $_FILES['img3'] = ['name' => 'imagen3.jpg', 'tmp_name' => '/path/to/temp/img3.jpg'];

        // Incluir el archivo del que se quiere probar la función
        include 'post-avehical.php'; 

        // Verificar que se ha añadido correctamente el vehículo
        $I->seeInDatabase('tblvehicles', ['VehiclesTitle' => 'Prueba Vehículo']);
    }
}
