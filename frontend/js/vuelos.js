/**
 * Funciones para gestión de vuelos
 */

/**
 * Obtener todos los vuelos
 */
async function getAllFlights() {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/vuelos`);
        
        if (!response.ok) {
            throw new Error('Error al obtener vuelos');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Obtener vuelo por ID
 */
async function getFlightById(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/vuelos/${id}`);
        
        if (!response.ok) {
            throw new Error('Vuelo no encontrado');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Buscar vuelos
 */
async function searchFlights(params) {
    try {
        let url = `${API_CONFIG.VUELOS}/vuelos/search?`;
        
        if (params.origin) url += `origin=${params.origin}&`;
        if (params.destination) url += `destination=${params.destination}&`;
        if (params.date) url += `date=${params.date}`;
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error('Error en la búsqueda');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Crear vuelo (solo admin)
 */
async function createFlight(flightData) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/vuelos`, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(flightData)
        });
        
        if (!response.ok) {
            throw new Error('Error al crear vuelo');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Actualizar vuelo (solo admin)
 */
async function updateFlight(id, flightData) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/vuelos/${id}`, {
            method: 'PUT',
            headers: getAuthHeaders(),
            body: JSON.stringify(flightData)
        });
        
        if (!response.ok) {
            throw new Error('Error al actualizar vuelo');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Eliminar vuelo (solo admin)
 */
async function deleteFlight(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/vuelos/${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });
        
        if (!response.ok) {
            throw new Error('Error al eliminar vuelo');
        }
        
        return true;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}