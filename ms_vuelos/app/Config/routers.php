<?php
use App\Controllers\VuelosController;
use App\Controllers\NavesController;
use App\Controllers\ReservasController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Cargar middlewares
$tokenValidation = require __DIR__ . '/../Middleware/TokenValidation.php';
require __DIR__ . '/../Middleware/RoleValidation.php'; // Función roleValidation()

return function (App $app) use ($tokenValidation) {
    
    // ============ Ruta de prueba ============
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write("Microservicio de Vuelos - Gestión de Vuelos y Reservas");
        return $response;
    });

    // ============ RUTAS PÚBLICAS ============
    // Buscar vuelos (sin autenticación para que cualquiera pueda buscar)
    $app->get('/vuelos/search', [VuelosController::class, 'search']);
    
    // Ver todos los vuelos disponibles
    $app->get('/vuelos', [VuelosController::class, 'getAll']);
    
    // Ver vuelo específico
    $app->get('/vuelos/{id}', [VuelosController::class, 'getById']);

    // ============ GESTIÓN DE VUELOS (Solo Administrador) ============
    $app->group('/admin/vuelos', function (RouteCollectorProxy $group) {
        $group->post('', [VuelosController::class, 'create']);
        $group->put('/{id}', [VuelosController::class, 'update']);
        $group->delete('/{id}', [VuelosController::class, 'delete']);
    })->add($tokenValidation)->add(roleValidation('administrador'));

    // ============ GESTIÓN DE NAVES (Solo Administrador) ============
    $app->group('/admin/naves', function (RouteCollectorProxy $group) {
        $group->get('', [NavesController::class, 'getAll']);
        $group->get('/{id}', [NavesController::class, 'getById']);
        $group->post('', [NavesController::class, 'create']);
        $group->put('/{id}', [NavesController::class, 'update']);
        $group->delete('/{id}', [NavesController::class, 'delete']);
    })->add($tokenValidation)->add(roleValidation('administrador'));

    // ============ GESTIÓN DE RESERVAS (Solo Gestor) ============
    $app->group('/gestor/reservas', function (RouteCollectorProxy $group) {
        $group->get('', [ReservasController::class, 'getAll']);
        $group->get('/{id}', [ReservasController::class, 'getById']);
        $group->get('/usuario/{userId}', [ReservasController::class, 'getByUser']);
        $group->post('', [ReservasController::class, 'create']);
        $group->put('/{id}/cancelar', [ReservasController::class, 'cancel']);
    })->add($tokenValidation)->add(roleValidation('gestor'));
};