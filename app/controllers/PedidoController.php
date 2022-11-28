<?php
require_once './models/Pedido.php';
require_once './models/Factura.php';

class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $header = $request->getHeaderLine("Authorization");
      $files = $request->getUploadedFiles();

      $payload = json_encode(array("error" => "Faltan datos..."));

      if(isset($parametros['idMesa'])
      && isset($parametros['nombreCliente'])
      && isset($files))
      {
        $idMesa=$parametros['idMesa'];
        $nombreCliente=$parametros['nombreCliente'];

        $token = trim(explode("Bearer", $header)[1]);
        $datos = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleado($datos->usuario);
        $idEmpleado = $empleado->id;
        
        $mesa = Mesa::obtenerMesaPorId($idMesa);

        if ($mesa!=NULL)
        {
          if($mesa->estado=="Cerrada")
          {
            $pedido = new Pedido();
            $pedido->idMesa = $idMesa;
            $pedido->nroOrden = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
            $pedido->nombreCliente = $nombreCliente;
            $pedido->estado = "Pendiente";
            $pedido->fechaAlta = date("Y-m-d H:i:s");
  
            $foto = $files["img"];
            $fotoCarpeta = './media/pedidos/';
            $fotoExt = pathinfo($foto->getClientFilename(), PATHINFO_EXTENSION); 
            $fotoNombre = $nombreCliente . '-' . date("Y-m-d-h-i-s") . '.' . $fotoExt;
            $fotoDir = $fotoCarpeta . $fotoNombre;
  
            if (!file_exists($fotoCarpeta))
                mkdir($fotoCarpeta, 0777, true);
  
            $foto->moveTo($fotoDir);
            
            $pedido->pathFoto=$fotoDir;
            $pedido->crearPedido();
            
            $mesa->idEmpleado = $idEmpleado;
            $mesa->estado="Con clientes esperando el pedido";
            Mesa::actualizarMesa($mesa);
  
            $payload = json_encode(array("mensajePedido" => "Pedido creado con exito",
                                         "mensajeMesa" => "Mesa actualizada con exito",
                                         "infoImagen" => "La imagen se ha guardado correctamente en ".$fotoDir,
                                         "codigoMesa" => $mesa->codigo));
          }
          else
            $payload = json_encode(array("error" => "Hay clientes ocupando esa mesa..."));
        }
        else
          $payload = json_encode(array("error" => "Esa mesa no existe..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      if (isset($args['pedido']))
      {
        $id = $args['pedido'];
        $pedido = Pedido::obtenerPedidoPorId($id);

        if ($pedido!=NULL)
          $payload = json_encode(array("pedido" => $pedido));
        else
          $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      $lista = Pedido::obtenerTodos();

      if ($lista!=NULL)
        $payload = json_encode(array("pedidos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoConTiempoEstimado($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      if(isset($args['codigoMesa']) && isset($args['nroOrden']))
      {
        $codigoMesa = $args['codigoMesa'];
        $nroOrden = $args['nroOrden'];

        $pedido = Pedido::obtenerTiempoEstimadoPedido($codigoMesa, $nroOrden);

        if ($pedido!=NULL)
        {
          if($pedido->tiempoEstimadoEnMinutos>0)
            $payload = json_encode(array("pedido" => $pedido));
          else
            $payload = json_encode(array("pedido" => "Su pedido está tardando mas de lo esperado"));
        }
        else
          $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosConTiempoEstimado($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Ocurrio un error..."));

      $lista = Pedido::obtenerPedidosConTiempoEstimado();

      if ($lista!=NULL)
        $payload = json_encode(array("pedidos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosListos($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Ocurrio un error..."));

      $lista = Pedido::obtenerPedidosListos();

      if ($lista!=NULL)
        $payload = json_encode(array("pedidos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "No hay pedidos listos por el momento..."));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ServirUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['nroOrden']))
        {
          $nroOrden = $parametros['nroOrden'];
          $pedido=Pedido::obtenerPedidoPorNroOrden($nroOrden);

          if ($pedido!=NULL)
          {
            if($pedido->estado == "Listo para servir")
            {
              $mesa = Mesa::obtenerMesaPorId($pedido->idMesa);
              if ($mesa->estado=="Con clientes esperando el pedido")
              {
                $pedido->estado = "Servido";
                Pedido::actualizarPedido($pedido);
                Producto::actualizarProductosAServidos($nroOrden);

                $mesa->estado = "Con clientes comiendo";
                Mesa::actualizarMesa($mesa);

                $payload = json_encode(array("mensajePedido" => "Pedido servido con exito", "mensajeMesa" => "Mesa actualizada con exito"));
              }
            }
            else
              $payload = json_encode(array("error" => "Este pedido sigue en preparación o ya fue servido..."));
          }
          else
            $payload = json_encode(array("error" => "Este pedido no existe..."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CobrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['nroOrden']))
        {
          $nroOrden = $parametros['nroOrden'];
          $pedido=Pedido::obtenerPedidoPorNroOrden($nroOrden);

          if ($pedido!=NULL)
          {
            if($pedido->estado == "Servido")
            {
              $mesa = Mesa::obtenerMesaPorId($pedido->idMesa);

              if ($mesa->estado=="Con clientes comiendo")
              {
                $pedido->estado = "Cobrado";
                Pedido::actualizarPedido($pedido);

                $mesa->estado = "Con clientes pagando";
                Mesa::actualizarMesa($mesa);

                //FACTURA
                $precioTotal = $pedido->precioTotal;
                $codigoMesa = $mesa->codigo;

                $factura = new Factura();
                $factura->nroOrden = $nroOrden;
                $factura->codigoMesa = $codigoMesa;
                $factura->precioTotal = $precioTotal;
                $factura->crearFactura();

                $payload = json_encode(array("mensajePedido" => "Pedido actualizado con exito",
                                             "mensajeMesa" => "Mesa actualizada con exito",
                                             "mensajeFactura" => "Factura cargada con exito"));
              }
            }
            else
              $payload = json_encode(array("error" => "Este pedido aún no fue servido o ya fue cobrado..."));
          }
          else
            $payload = json_encode(array("error" => "Este pedido no existe..."));
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