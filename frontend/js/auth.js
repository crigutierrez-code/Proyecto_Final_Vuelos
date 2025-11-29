/**
 * Manejo de Login
 */
async function login(email, password) {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        if (!response.ok) {
            throw new Error('Credenciales incorrectas');
        }
        
        const user = await response.json();
        
        // Guardar token y usuario en localStorage
        localStorage.setItem('token', user.token);
        localStorage.setItem('user', JSON.stringify(user));
        
        return user;
    } catch (error) {
        console.error('Error en login:', error);
        throw error;
    }
}

/**
 * Manejo de Registro
 */
async function register(name, email, password, role = 'gestor') {
    try {
        const response = await fetch(`${API_CONFIG.USUARIOS}/usuarios/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email, password, role })
        });
        
        if (!response.ok) {
            throw new Error('Error al registrar usuario');
        }
        
        const user = await response.json();
        return user;
    } catch (error) {
        console.error('Error en registro:', error);
        throw error;
    }
}

/**
 * Cerrar sesión
 */
async function logoutUser() {
    try {
        await fetch(`${API_CONFIG.USUARIOS}/usuarios/logout`, {
            method: 'POST',
            headers: getAuthHeaders()
        });
    } catch (error) {
        console.error('Error al cerrar sesión:', error);
    } finally {
        logout();
    }
}