<?php
namespace App\Controllers;

use App\Models\Nave;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NavesController
{
    private $codesError = [
        1 => 404,
        2 => 409,
        'default' => 400
    ];

    /**
     * Obtener todas las naves
     */
    public function getAll(Request $request, Response $response)
    {
        try {
            $naves = Nave::all();
            
            if (count($naves) == 0) {
                return $response->withStatus(204);
            }

            $response->getBody()->write($naves->toJson());
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Obtener nave por ID
     */
    public function getById(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $nave = Nave::find($id);
            
            if (empty($nave)) {
                throw new Exception("Nave no encontrada", 1);
            }

            $response->getBody()->write($nave->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Crear nueva nave (solo administrador)
     */
    public function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $nave = new Nave();
            $nave->name = $data['name'];
            $nave->capacity = $data['capacity'];
            $nave->model = $data['model'];
            $nave->save();

            $response->getBody()->write($nave->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Actualizar nave (solo administrador)
     */
    public function update(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $nave = Nave::find($id);

            if (empty($nave)) {
                throw new Exception("Nave no encontrada", 1);
            }

            if (isset($data['name'])) $nave->name = $data['name'];
            if (isset($data['capacity'])) $nave->capacity = $data['capacity'];
            if (isset($data['model'])) $nave->model = $data['model'];

            $nave->save();

            $response->getBody()->write($nave->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Eliminar nave (solo administrador)
     */
    public function delete(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $nave = Nave::find($id);
            
            if (empty($nave)) {
                throw new Exception("Nave no encontrada", 1);
            }

            if (!$nave->delete()) {
                throw new Exception("Error al eliminar", 3);
            }

            return $response
                ->withStatus(204)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }
}