<?php
require_once './models/Pedido.php';

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
            $pedido->nombreCliente = $nombreCliente;
  
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
            $mesa->estado="Con cliente esperando el pedido";
            Mesa::actualizarMesa($mesa);
  
            $payload = json_encode(array("mensaje" => "Pedido creado con exito",
                                         "infoImagen" => "La imagen se ha guardado correctamente en ".$fotoDir,
                                         "codigoMesa" => $mesa->codigo));
          }
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
        $horaEstimada = new DateTime($pedido->horaEstimada);
        $ahora = new DateTime();

        if ($ahora>$pedido->horaEstimada)
          $tiempoRestante = date_diff($horaEstimada, $ahora);
        else
          $payload = json_encode(array("tiempoRestante" => "El pedido está tardando mas de lo esperado"));

        $tiempoRestante->format('%R%a days');
        $min = $tiempoRestante->days * 24 * 60;
        $min += $tiempoRestante->h * 60;
        $min += $tiempoRestante->i;

        if ($pedido!=NULL)
          $payload = json_encode(array("tiempoRestante" => $min. " minutos"));
        else
          $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosConTiempoEstimado($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Faltan datos..."));

      $lista = Pedido::obtenerPedidosConTiempoEstimado();

      if ($lista!=NULL)
        $payload = json_encode(array("pedidos" => $lista));
      else
        $payload = json_encode(array("mensaje" => "Pedidos no encontrados..."));

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