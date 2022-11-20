<?php
require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['nombre']) && isset($parametros['perfil']) && isset($parametros['usuario']) && isset($parametros['clave']))
        {
          $nombre = $parametros['nombre'];
          $perfil = $parametros['perfil'];
          $usuario = $parametros['usuario'];
          $clave = $parametros['clave'];
  
          if($perfil=="mozo" || $perfil=="cocinero" || $perfil=="bartender" || $perfil=="cervecero" || $perfil=="socio")
          {
            $empleado = new Empleado();
            $empleado->nombre = $nombre;
            $empleado->perfil = $perfil;
            $empleado->usuario = $usuario;
            $empleado->clave = $clave;
            $empleado->crearEmpleado();
    
            $payload = json_encode(array("mensaje" => "Empleado creado con exito"));
          }
          else
            $payload = json_encode(array("mensaje" => "Los perfiles permitidos son (mozo/cocinero/bartender/cervecero/socio)"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $usuario = $args['usuario'];
        $empleado = Empleado::obtenerEmpleado($usuario);
        $payload = json_encode($empleado);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::obtenerTodos();
        $payload = json_encode(array("empleados" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Empleado::borrarEmpleado($id);

        $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}