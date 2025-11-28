/**
 * Funciones para gestión de naves
 */

/**
 * Obtener todas las naves
 */
async function getAllNaves() {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/naves`, {
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Error al obtener naves');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Obtener nave por ID
 */
async function getNaveById(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/naves/${id}`, {
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Nave no encontrada');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Crear nave
 */
async function createNave(naveData) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/naves`, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(naveData)
        });

        if (!response.ok) {
            throw new Error('Error al crear nave');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Actualizar nave
 */
async function updateNave(id, naveData) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/naves/${id}`, {
            method: 'PUT',
            headers: getAuthHeaders(),
            body: JSON.stringify(naveData)
        });

        if (!response.ok) {
            throw new Error('Error al actualizar nave');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

/**
 * Eliminar nave
 */
async function deleteNave(id) {
    try {
        const response = await fetch(`${API_CONFIG.VUELOS}/admin/naves/${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Error al eliminar nave');
        }

        return true;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}