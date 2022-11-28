<?php
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta
{
    public function CargarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if($args['codigoMesa'] && $args['nroOrden'])
        {
          $codigoMesa = $args['codigoMesa'];
          $nroOrden = $args['nroOrden'];

          $pedido = Pedido::obtenerPedidoPorNroOrden($nroOrden);

          if($pedido!=NULL)
          {
            if(isset($parametros['puntajeMesa'])
            && isset($parametros['puntajeResto'])
            && isset($parametros['puntajeMozo'])
            && isset($parametros['puntajeCocinero'])
            && isset($parametros['descripcion']))
            {
              $puntajeMesa = $parametros['puntajeMesa'];
              $puntajeResto = $parametros['puntajeResto'];
              $puntajeMozo = $parametros['puntajeMozo'];
              $puntajeCocinero = $parametros['puntajeCocinero'];
              $descripcion = $parametros['descripcion'];

              if (isset($parametros['cliente']))
                $cliente=$parametros['cliente'];
              else
                $cliente="Anonimo";
    
              $encuesta = new Encuesta();
              $encuesta->cliente = $cliente;
              $encuesta->codigoMesa = $codigoMesa;
              $encuesta->puntajeMesa = $puntajeMesa;
              $encuesta->puntajeResto = $puntajeResto;
              $encuesta->puntajeMozo = $puntajeMozo;
              $encuesta->puntajeCocinero = $puntajeCocinero;
              $encuesta->descripcion = $descripcion;
              $encuesta->crearEncuesta();
        
              $payload = json_encode(array("mensaje" => "Encuesta creado con exito"));
            }
          }
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $encuesta = Encuesta::obtenerEncuesta($id);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("Encuestas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}