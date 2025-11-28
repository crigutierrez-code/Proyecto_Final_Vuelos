<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

/**
 * Middleware para validar roles específicos
 * Uso: ->add(roleValidation('administrador'))
 */
function roleValidation($requiredRole) {
    return function (Request $request, $handler) use ($requiredRole) {
        // Obtener el usuario del atributo (viene del TokenValidation)
        $user = $request->getAttribute('user');

        if (!$user || $user->role !== $requiredRole) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'error' => 'No tienes permisos para realizar esta acción',
                'required_role' => $requiredRole
            ]));
            return $response
                ->withStatus(403)
                ->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    };
}