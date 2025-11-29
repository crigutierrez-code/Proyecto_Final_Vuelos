<?php
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

return function (Request $request, $handler) {
    // Obtener el token del header Authorization
    $headers = $request->getHeader('Authorization');
    $token = $headers[0] ?? null;

    // Extraer el token (formato: "Bearer TOKEN")
    if ($token && strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    // Validar que el token existe en la base de datos
    $user = User::where('token', $token)->first();

    if (!$user) {
        $response = new Response();
        $response->getBody()->write(json_encode([
            'error' => 'Token inválido o sesión expirada'
        ]));
        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json');
    }

    // Guardar información del usuario en el request para usarla después
    $request = $request->withAttribute('user', $user);

    // Si el token es válido, continuar con la petición
    return $handler->handle($request);
};