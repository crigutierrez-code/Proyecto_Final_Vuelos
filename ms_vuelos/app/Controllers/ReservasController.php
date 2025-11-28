<?php
namespace App\Controllers;

use App\Models\Reservation;
use App\Models\Flight;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservasController
{
    private $codesError = [
        1 => 404,
        2 => 409,
        'default' => 400
    ];

    /**
     * Obtener todas las reservas
     */
    public function getAll(Request $request, Response $response)
    {
        try {
            $reservations = Reservation::with(['flight.nave'])->get();
            
            if (count($reservations) == 0) {
                return $response->withStatus(204);
            }

            $response->getBody()->write($reservations->toJson());
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Obtener reservas por usuario
     */
    public function getByUser(Request $request, Response $response, $args)
    {
        try {
            $userId = $args['userId'];

            if (empty($userId)) {
                throw new Exception("ID de usuario requerido", 2);
            }

            $reservations = Reservation::with(['flight.nave'])
                ->where('user_id', $userId)
                ->get();
            
            if (count($reservations) == 0) {
                return $response->withStatus(204);
            }

            $response->getBody()->write($reservations->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Crear nueva reserva (solo gestor)
     */
    public function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            // Verificar que el vuelo existe
            $flight = Flight::find($data['flight_id']);
            if (empty($flight)) {
                throw new Exception("El vuelo no existe", 1);
            }

            $reservation = new Reservation();
            $reservation->user_id = $data['user_id'];
            $reservation->flight_id = $data['flight_id'];
            $reservation->status = 'activa';
            $reservation->reserved_at = date('Y-m-d H:i:s');
            $reservation->save();

            // Cargar relaciones para la respuesta
            $reservation->load(['flight.nave']);

            $response->getBody()->write($reservation->toJson());
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
     * Cancelar reserva (solo gestor)
     */
    public function cancel(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $reservation = Reservation::find($id);
            
            if (empty($reservation)) {
                throw new Exception("Reserva no encontrada", 1);
            }

            $reservation->status = 'cancelada';
            $reservation->save();

            $response->getBody()->write($reservation->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

    /**
     * Obtener reserva por ID
     */
    public function getById(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];

            if (empty($id)) {
                throw new Exception("ID requerido", 2);
            }

            $reservation = Reservation::with(['flight.nave'])->find($id);
            
            if (empty($reservation)) {
                throw new Exception("Reserva no encontrada", 1);
            }

            $response->getBody()->write($reservation->toJson());
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $status = $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }
}