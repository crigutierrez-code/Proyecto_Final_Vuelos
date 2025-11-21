<?php
use App\Controllers\UsuariosController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$tokenValidation = require __DIR__ . '/../Middleware/TokenValidation.php';

return function (App $app) use ($tokenValidation) {
    
    // Ruta de prueba
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write("Microservicio de Usuarios - Gestión de Vuelos");
        return $response;
    });

    // ========== RUTAS PÚBLICAS (sin token) ==========
    
    // Login
    $app->post('/login', [UsuariosController::class, 'login']);
    
    // Registrar usuario (público para este ejemplo, luego lo proteges)
    $app->post('/register', [UsuariosController::class, 'register']);

    // ========== RUTAS PROTEGIDAS (requieren token) ==========
    
    $app->group('/usuarios', function (RouteCollectorProxy $group) {
        // Listar todos los usuarios
        $group->get('', [UsuariosController::class, 'queryAllUsers']);
        
        // Obtener usuario por ID
        $group->get('/{id}', [UsuariosController::class, 'getUserById']);
        
        // Actualizar usuario
        $group->put('/{id}', [UsuariosController::class, 'updateUser']);
        
        // Eliminar usuario
        $group->delete('/{id}', [UsuariosController::class, 'deleteUser']);
        
    })->add($tokenValidation);

    // Cerrar sesión
    $app->post('/logout', [UsuariosController::class, 'logout'])
        ->add($tokenValidation);
};