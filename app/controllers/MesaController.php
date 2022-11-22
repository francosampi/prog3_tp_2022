<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args)
    {
        $payload = json_encode(array("error" => "Faltan datos..."));

        $mesa = new Mesa();
        $mesa->codigo = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $mesa->estado = "Cerrada";
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorCodigo($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      if (isset($args['codigo']))
      {
        $codigo = $args['codigo'];
        $mesa = Mesa::obtenerMesaPorCodigo($codigo);

        if ($mesa!=NULL)
          $payload = json_encode(array("mesa" => $mesa));
        else
          $payload = json_encode(array("mensaje" => "Productos no encontrados..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodas($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      $mesa = Mesa::obtenerTodos();

      if ($mesa!=NULL)
        $payload = json_encode(array("mesa" => $mesa));
      else
        $payload = json_encode(array("mensaje" => "Mesas no encontradas..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function AbrirUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $header = $request->getHeaderLine("Authorization");
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['id']))
        {
          $token = trim(explode("Bearer", $header)[1]);
          $datos = AutentificadorJWT::ObtenerData($token);
          $idEmpleado = Empleado::obtenerEmpleado($datos->usuario)->id;

          $id = $parametros['id'];
          $mesa=Mesa::obtenerMesaPorId($id);

          if($mesa->estado == "Cerrada")
          {
            Mesa::cambiarEstadoMesa($id, $idEmpleado, "Con cliente esperando pedido");
            $payload = json_encode(array("mensaje" => "Mesa abierta con exito"));
          }
          else
            $payload = json_encode(array("error" => "La mesa ya se encuentra abierta..."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['id']))
        {
          $id = $parametros['id'];

          $mesa=Mesa::obtenerMesaPorId($id);

          if($mesa->estado == "Con cliente pagando")
          {
            Mesa::cambiarEstadoMesa($id, NULL, "Cerrada");
            $payload = json_encode(array("mensaje" => "Mesa cerrada con exito"));
          }
          else
            $payload = json_encode(array("mensaje" => "La mesa se encuentra en uso..."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['id']))
        {
          $id = $parametros['id'];
          Mesa::borrarMesa($id);
  
          $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}