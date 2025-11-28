<?php
namespace App\Controllers;

use App\Models\Flight;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VuelosController
{
    private $codesError = [
        1 => 404,
        2 => 409,
        'default' => 400
    ];

    /**
     * Obtener todos los vuelos
     */
    public function getAll(Request $request, Response $response)
    {
        try {
            $flights = Flight::with('nave')->get();
            
            if (count($flights) == 0) {
                return $response->withStatus(204);
            }

            $response->getBody()->write($flights->toJson());
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Obtener vuelo por ID
     */
    public function getById(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $flight = Flight::with('nave')->find($id);
            
            if (empty($flight)) {
                throw new Exception("Vuelo no encontrado", 1);
            }

            $response->getBody()->write($flight->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Buscar vuelos por origen, destino o fecha
     */
    public function search(Request $request, Response $response)
    {
        try {
            $params = $request->getQueryParams();
            $query = Flight::with('nave');

            if (isset($params['origin'])) {
                $query->where('origin', 'LIKE', '%' . $params['origin'] . '%');
            }

            if (isset($params['destination'])) {
                $query->where('destination', 'LIKE', '%' . $params['destination'] . '%');
            }

            if (isset($params['date'])) {
                $query->whereDate('departure', $params['date']);
            }

            $flights = $query->get();

            if (count($flights) == 0) {
                return $response->withStatus(204);
            }

            $response->getBody()->write($flights->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Crear nuevo vuelo (solo administrador)
     */
    public function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $flight = new Flight();
            $flight->nave_id = $data['nave_id'];
            $flight->origin = $data['origin'];
            $flight->destination = $data['destination'];
            $flight->departure = $data['departure'];
            $flight->arrival = $data['arrival'];
            $flight->price = $data['price'];
            $flight->save();

            $response->getBody()->write($flight->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            $response->getBody()->write(json_encode([
                'error' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($status)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Actualizar vuelo (solo administrador)
     */
    public function update(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $flight = Flight::find($id);

            if (empty($flight)) {
                throw new Exception("Vuelo no encontrado", 1);
            }

            if (isset($data['nave_id'])) $flight->nave_id = $data['nave_id'];
            if (isset($data['origin'])) $flight->origin = $data['origin'];
            if (isset($data['destination'])) $flight->destination = $data['destination'];
            if (isset($data['departure'])) $flight->departure = $data['departure'];
            if (isset($data['arrival'])) $flight->arrival = $data['arrival'];
            if (isset($data['price'])) $flight->price = $data['price'];

            $flight->save();

            $response->getBody()->write($flight->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Eliminar vuelo (solo administrador)
     */
    public function delete(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $flight = Flight::find($id);
            
            if (empty($flight)) {
                throw new Exception("Vuelo no encontrado", 1);
            }

            if (!$flight->delete()) {
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