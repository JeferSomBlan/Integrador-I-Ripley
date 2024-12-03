<?php
use PHPUnit\Framework\TestCase;

class ActualizarPerfilTest extends TestCase {

    protected function setUp(): void {
        // Inicialización que se ejecuta antes de cada prueba
        $_SESSION['user_id'] = 1; // Simulamos que el usuario está logueado
    }

    protected function tearDown(): void {
        // Limpiar cualquier estado después de cada prueba
        $_SESSION = [];
    }

    public function testActualizarPerfilExitoso() {
        // Simulamos una solicitud POST
        $_POST['nombre'] = 'Nuevo Nombre';
        $_POST['telefono'] = '987654321';
        $_POST['direccion'] = 'Nueva Dirección';
        $_POST['email'] = 'nuevo_email@dominio.com';
        
        // Iniciamos el buffer de salida para capturar los encabezados
        ob_start();
        include_once '../actualizar_perfil.php'; // Incluimos el archivo PHP a probar
        $output = ob_get_clean(); // Capturamos la salida generada
        
        // Verificamos si la redirección se realizó correctamente
        $this->assertStringContainsString('Location: mi_cuenta.php?actualizado=1', $output);
    }

    public function testCorreoInvalido() {
        $_POST['nombre'] = 'Nuevo Nombre';
        $_POST['telefono'] = '987654321';
        $_POST['direccion'] = 'Nueva Dirección';
        $_POST['email'] = 'correo_invalido'; // Correo inválido

        ob_start();
        include_once '../actualizar_perfil.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Location: mi_cuenta.php?error=correo_invalido', $output);
    }

    public function testActualizacionFallida() {
        $_POST['nombre'] = 'Nuevo Nombre';
        $_POST['telefono'] = '987654321';
        $_POST['direccion'] = 'Nueva Dirección';
        $_POST['email'] = 'nuevo_email@dominio.com';

        // Hacemos que la actualización falle forzando un error
        // Puedes simular un fallo modificando la consulta SQL o la conexión a la base de datos

        ob_start();
        include_once '../actualizar_perfil.php'; 
        $output = ob_get_clean();

        $this->assertStringContainsString('Location: mi_cuenta.php?error=actualizacion_fallida', $output);
    }

    public function testPreparacionFallida() {
        $_POST['nombre'] = 'Nuevo Nombre';
        $_POST['telefono'] = '987654321';
        $_POST['direccion'] = 'Nueva Dirección';
        $_POST['email'] = 'nuevo_email@dominio.com';

        // Simulamos que la preparación de la consulta falle
        // Puedes hacer esto modificando el código de conexión de la base de datos

        ob_start();
        include_once '../actualizar_perfil.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Location: mi_cuenta.php?error=preparacion_fallida', $output);
    }
}
?>
