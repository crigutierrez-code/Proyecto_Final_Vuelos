/**
 * Configuración global de URLs de microservicios
 */
const API_CONFIG = {
    USUARIOS: 'http://127.0.0.1:8000',
    VUELOS: 'http://127.0.0.1:8001'
};

/**
 * Obtener el token del localStorage
 */
function getToken() {
    return localStorage.getItem('token');
}

/**
 * Obtener el usuario del localStorage
 */
function getUser() {
    const userJson = localStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated() {
    return !!getToken();
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin() {
    const user = getUser();
    return user && user.role === 'administrador';
}

/**
 * Verificar si el usuario es gestor
 */
function isGestor() {
    const user = getUser();
    return user && user.role === 'gestor';
}

/**
 * Cerrar sesión
 */
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login.html';
}

/**
 * Redirigir si no está autenticado
 */
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/login.html';
    }
}

/**
 * Redirigir si no es administrador
 */
function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        alert('Acceso denegado. Solo administradores.');
        window.location.href = '/index.html';
    }
}

/**
 * Redirigir si no es gestor
 */
function requireGestor() {
    requireAuth();
    if (!isGestor()) {
        alert('Acceso denegado. Solo gestores.');
        window.location.href = '/index.html';
    }
}

/**
 * Headers comunes para peticiones con autenticación
 */
function getAuthHeaders() {
    return {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${getToken()}`
    };
}