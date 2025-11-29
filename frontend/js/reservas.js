/**
 * Funciones para gestión de reservas
 */

/**
 * Obtener todas las reservas
 */
async function getAllReservations() {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/gestor/reservas`, {
            headers: getAuthHeaders()
        });
        
        if (!response.ok) {
            throw new Error('Error al obtener reservas');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Obtener reserva por ID
 */
async function getReservationById(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/gestor/reservas/${id}`, {
            headers: getAuthHeaders()
        });
        
        if (!response.ok) {
            throw new Error('Reserva no encontrada');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Obtener reservas por usuario
 */
async function getReservationsByUser(userId) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/gestor/reservas/usuario/${userId}`, {
            headers: getAuthHeaders()
        });
        
        if (!response.ok) {
            throw new Error('Error al obtener reservas del usuario');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Crear reserva
 */
async function createReservation(reservationData) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/gestor/reservas`, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(reservationData)
        });
        
        if (!response.ok) {
            throw new Error('Error al crear reserva');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Cancelar reserva
 */
async function cancelReservation(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/gestor/reservas/${id}/cancelar`, {
            method: 'PUT',
            headers: getAuthHeaders()
        });
        
        if (!response.ok) {
            throw new Error('Error al cancelar reserva');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}