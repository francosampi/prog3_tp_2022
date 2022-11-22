<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/AutentificadorController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';

require_once './middlewares/MWAutenticadorPerfiles.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

//RUTAS: ABM Empleados (Socio)
$app->group('/empleado', function (RouteCollectorProxy $group){
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/{usuario}', \EmpleadoController::class . ':TraerUno');
    $group->post('[/]', \EmpleadoController::class . ':CargarUno');
    $group->put('[/]', \EmpleadoController::class . ':ModificarUno');
    $group->delete('[/]', \EmpleadoController::class . ':BorrarUno');
})->add(new MWAutenticadorSocio());

//RUTAS: pedidos
$app->group('/pedido', function (RouteCollectorProxy $group){
    $group->post('[/]', \PedidoController::class . ':CargarUno')
        ->add(new MWAutenticadorMozo());
    $group->get('/{codigoMesa}/{nroOrden}', \PedidoController::class . ':TraerUnoConTiempoEstimado');
});

//RUTAS: pedidos (Socio)
$app->group('/pedido', function (RouteCollectorProxy $group){
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/id/{pedido}', \PedidoController::class . ':TraerUno');
    $group->get('/tiempo', \PedidoController::class . ':TraerTodosConTiempoEstimado');
})->add(new MWAutenticadorSocio());

//RUTAS: productos (Socio)
$app->group('/producto', function (RouteCollectorProxy $group){
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/orden/{nroOrden}', \ProductoController::class . ':TraerPorNroOrden');
})->add(new MWAutenticadorSocio());

//RUTAS: productos (Empleado)
$app->group('/producto', function (RouteCollectorProxy $group){
    $group->get('/sector/cocina', \ProductoController::class . ':TraerPorSectorCocina')->add(new MWAutenticadorCocinero());
    $group->get('/sector/barra', \ProductoController::class . ':TraerPorSectorBarra')->add(new MWAutenticadorBartender());
    $group->get('/sector/cerveza', \ProductoController::class . ':TraerPorSectorCerveza')->add(new MWAutenticadorCervecero());
});

$app->group('/producto', function (RouteCollectorProxy $group){
    $group->put('/preparar', \ProductoController::class . ':PrepararProducto');
    $group->put('/finalizar', \ProductoController::class . ':FinalizarProducto');
})->add(new MWAutenticadorEmpleado());

//RUTAS: productos (Mozo)
$app->group('/producto', function (RouteCollectorProxy $group){
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->put('[/]', \ProductoController::class . ':ModificarUno');
    $group->delete('[/]', \ProductoController::class . ':BorrarUno');
})->add(new MWAutenticadorMozo());

//RUTAS: mesas (Mozo)
$app->group('/mesa', function (RouteCollectorProxy $group){
    $group->get('/disponible', \MesaController::class . ':TraerUnaDisponible');
    $group->put('/abrir', \MesaController::class . ':AbrirUna');
})->add(new MWAutenticadorMozo());

//RUTAS: mesas (Socio)
$app->group('/mesa', function (RouteCollectorProxy $group){
    $group->get('[/]', \MesaController::class . ':TraerTodas');
    $group->post('[/]', \MesaController::class . ':CargarUna');
    $group->put('/cerrar', \MesaController::class . ':CerrarUna');
    $group->delete('[/]', \MesaController::class . ':BorrarUna');
})->add(new MWAutenticadorSocio());

//RUTA: Login cuenta
$app->post('/login', \AutentificadorController::class . ':LoginCuenta');

$app->get('[/]', function (Request $request, Response $response) : Response{
    $response->getBody()->write("Programacion 3 - TP - Franco Sampietro");
    return $response;
});

$app->run();
