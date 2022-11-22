<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function CargarUna($request, $response, $args)
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

    public function TraerUnaDisponible($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Ocurrio un error..."));

      $mesa = Mesa::obtenerMesaDisponible();

      if ($mesa!=NULL)
        $payload = json_encode(array("mesa" => $mesa));
      else
        $payload = json_encode(array("mensaje" => "No hay mesas disponibles..."));

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

    public function CerrarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['id']))
        {
          $id = $parametros['id'];

          $mesa=Mesa::obtenerMesaPorId($id);

          if($mesa->estado != "Cerrada")
          {
            if($mesa->estado == "Con cliente pagando")
            {
              $mesa->estado="Cerrada";
              $mesa->idEmpleado=NULL;
              Mesa::actualizarMesa($mesa);

              $payload = json_encode(array("mensaje" => "Mesa cerrada con exito"));
            }
            else
              $payload = json_encode(array("error" => "La mesa se encuentra en uso..."));
          }
          else
            $payload = json_encode(array("error" => "La mesa se encuentra cerrada..."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUna($request, $response, $args)
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