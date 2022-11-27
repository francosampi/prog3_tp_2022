<?php
require_once './models/Plato.php';

class PlatoController extends Plato
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $payload = json_encode(array("error" => "Faltan datos..."));

        if(isset($parametros['nombre']) && isset($parametros['sector']) && isset($parametros['precio']))
        {
          $nombre = $parametros['nombre'];
          $sector = $parametros['sector'];
          $precio = $parametros['precio'];
          $stock = 1;

          if (isset($parametros['stock']))
            $stock = $parametros['stock'];
          
          if ($stock<1)
            $stock = 1;
  
          if($precio>0)
          {
            if($sector=="cocina" || $sector=="barra" || $sector=="cerveza artesanal")
            {
              $plato = new Plato();
              $plato->nombre = $nombre;
              $plato->sector = $sector;
              $plato->precio = $precio;
              $plato->stock = $stock;
              $plato->crearPlato();
      
              $payload = json_encode(array("mensaje" => "Plato creado con exito"));
            }
            else
              $payload = json_encode(array("error" => "Los sectores permitidos son (cocina/barra/cerveza artesanal)"));
          }
          else
            $payload = json_encode(array("error" => "El precio debe ser mayor a 0"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $plato = Plato::obtenerPlato($id);
        $payload = json_encode($plato);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Plato::obtenerTodos();
        $payload = json_encode(array("platos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Plato::borrarPlato($id);

        $payload = json_encode(array("mensaje" => "Plato borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}