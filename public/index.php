<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\loginController;
use Controllers\dashboardController;
$router = new Router();

//Login
$router->get('/', [loginController::class,'login']);
$router->post('/', [loginController::class,'login']);
//Lougout
$router->get('/logout', [loginController::class,'logout']);
//Crear Cuenta
$router->get('/crear', [loginController::class,'crear']);
$router->post('/crear', [loginController::class,'crear']);
//Olvide Contrasenia
$router->get('/olvide', [loginController::class,'olvide']);
$router->post('/olvide', [loginController::class,'olvide']);
//Reestablecer contrasenia
$router->get('/reestablecer', [loginController::class,'reestablecer']);
$router->post('/reestablecer', [loginController::class,'reestablecer']);
//mensaje Cuenta creada
$router->get('/mensaje', [loginController::class,'mensaje']);

//Dashboard
$router->get('/dashboard', [dashboardController::class,'index']);

$router->get('/mis-libros', [dashboardController::class,'mislibros']);
$router->post('/mis-libros', [dashboardController::class,'mislibros']);

$router->get('/registrar', [dashboardController::class,'registrar']);
$router->post('/registrar', [dashboardController::class,'registrar']);

$router->get('/permisos', [dashboardController::class,'permisos']);
$router->post('/permisos', [dashboardController::class,'permisos']);

$router->get('/modificar', [dashboardController::class,'modificar']);
$router->post('/modificar', [dashboardController::class,'modificar']);

$router->get('/estadisticas', [dashboardController::class,'estadisticas']);
$router->post('/estadisticas', [dashboardController::class,'estadisticas']);

$router->post('/libro', [dashboardController::class,'libro']);
$router->get('/libro', [dashboardController::class,'libro']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();