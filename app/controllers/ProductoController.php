<?php
require_once './models/Producto.php';

class ProductoController extends Producto
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['nombre']) &&
        isset($parametros['sector']) &&
        isset($parametros['precio']) &&
        isset($parametros['cantidad']) &&
        isset($parametros['nroOrden']))
        {
          $nombre = $parametros['nombre'];
          $sector = $parametros['sector'];
          $precio = $parametros['precio'];
          $cantidad = $parametros['cantidad'];
          $nroOrden = $parametros['nroOrden'];
  
          $usr = new Producto();
          $usr->nombre = $nombre;
          $usr->sector = $sector;
          $usr->precio = $precio;
          $usr->cantidad = $cantidad;
          $usr->nroOrden = $nroOrden;
          $usr->crearProducto();
  
          $pedido=Pedido::obtenerPedidosPorNroOrden($nroOrden);

          if($pedido!=NULL)
          {
            
          }
          
          $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("productos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorSectorCocina($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));
      $lista = Producto::obtenerProductosPorSector("cocina");

      if ($lista!=NULL)
        $payload = json_encode(array("productos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Productos no encontrados..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorSectorBarra($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      $lista = Producto::obtenerProductosPorSector("barra");

      if ($lista!=NULL)
        $payload = json_encode(array("productos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Productos no encontrados..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorSectorCerveza($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      $lista = Producto::obtenerProductosPorSector("cerveza artesanal");

      if ($lista!=NULL)
        $payload = json_encode(array("productos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Productos no encontrados..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorNroOrden($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      if (isset($args['nroOrden']))
      {
        $nroOrden = $args['nroOrden'];
        $lista = Producto::obtenerProductosPorNroOrden($nroOrden);

        if ($lista!=NULL)
          $payload = json_encode(array("productos" => $lista));
        else
          $payload = json_encode(array("mensaje" => "Productos no encontrados..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function PrepararProducto($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $header = $request->getHeaderLine("Authorization");
      $payload = json_encode(array("error" => "Faltan datos..."));

      if(isset($parametros['idProducto']) && isset($parametros['minutos']))
      {
        $idProducto = $parametros['idProducto'];
        $minutos = $parametros['minutos'];

        $token = trim(explode("Bearer", $header)[1]);
        $datos = AutentificadorJWT::ObtenerData($token);

        $empleado = Empleado::obtenerEmpleado($datos->usuario);
        $producto=Producto::obtenerProductoPorId($idProducto);

        $idEmpleado = $empleado->id;
        $empleadoNombre = $empleado->nombre;
        $empleadoPerfil = $empleado->perfil;
        
        if($producto!=NULL)
        {
          if($producto->idEmpleado==NULL)
          {
            $horaEstimada = new DateTime($producto->horaInicio);
            $horaEstimada->modify("+{$minutos} minutes");
            $horaEstimada = $horaEstimada->format("Y-m-d H:i:s");

            if($empleadoPerfil=="cocinero" && ($producto->sector=="cocina" || $producto->sector=="candybar") ||
               $empleadoPerfil=="bartender" && $producto->sector=="barra" ||
               $empleadoPerfil=="cervecero" && $producto->sector=="cerveza artesanal")
              {
                Producto::actualizarEstadoPreparacion($idProducto, $idEmpleado, $horaEstimada);
                $payload = json_encode(array("mensaje" => "Producto actualizado...", "horaEstimada" => $horaEstimada, "empleado" => $empleadoNombre));
              }
              else
               $payload = json_encode(array("error" => "El producto pertenece a otro sector..."));
          }
          else
            $payload = json_encode(array("error" => "Este producto ya se encuentra en preparacion..."));
        }
        else
          $payload = json_encode(array("error" => "Ese producto no existe..."));
        }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if (isset($parametros['id']))
        {
          $id = $parametros['id'];
          Producto::borrarProducto($id);

          $payload = json_encode(array("mensaje" => "Producto borrado con exito (id: ".$id.")"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
