// Funciones para gestión de usuarios

// Obtener todos los usuarios
async function getAllUsers() {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/all`, {
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Error al obtener usuarios');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Obtener usuario por ID
async function getUserById(id) {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/${id}`, {
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Usuario no encontrado');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Crear usuario
async function createUser(userData) {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });

        if (!response.ok) {
            throw new Error('Error al crear usuario');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Actualizar usuario
async function updateUser(id, userData) {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/${id}`, {
            method: 'PUT',
            headers: getAuthHeaders(),
            body: JSON.stringify(userData)
        });

        if (!response.ok) {
            throw new Error('Error al actualizar usuario');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Eliminar usuario
async function deleteUser(id) {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Error al eliminar usuario');
        }

        return true;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}
