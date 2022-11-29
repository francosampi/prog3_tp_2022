<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function TraerTodosCliente($request, $response, $args)
    {
        $lista = Plato::obtenerTodosCliente();
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

    /*
    public function crearCSVConPlatos($request, $response, $args)
    {
      header("Cache-Control: must-revalidate");
      header("Pragma: must-revalidate");
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment; filename=platos.csv');

      //$payload=json_encode(array("error" => "Ocurrio un error al descargar el archivo"));

      try
      {
        //$archivo=$_FILES["archivo"]["tmp_name"];
        //$carga=CSV::cargarTablaConDatosDeCSV($archivo);
        //readfile($archivo);
        //$payload=json_encode(array("mensaje" => "El archivo 'platos.csv' fue descargado correctamente"));

        $out = fopen('php://output', 'w');
        $platos=Plato::obtenerTodos();

        if($out)
        {
          foreach ($platos as $plato) {
            $platoRegistro=implode(",", (array)$plato) . PHP_EOL;
            fwrite($out, $platoRegistro);
          }
          fclose($out);
        }
        else
          throw new Exception("Ocurrio un error cargando el archivo...");
      }
      catch(Exception $e)
      {
          $payload=json_encode(array("error" => $e->getMessage()));
          $response->getBody()->write($payload);
      }

      return $response->withHeader('Content-Type', 'text/csv');
    }
    */
    
    public function restaurarPlatosDesdeCSV($request, $response, $args)
    {
      /*
      header("Cache-Control: must-revalidate");
      header('Content-Description: File Transfer');
      header('Content-Type: application/download');
      header('Expires: 0');
      header("Pragma: public");
      header('Content-Type: application/force-download');
      header('Content-Type: application/csv');
      */

      $archivo = $_FILES["archivo"]["tmp_name"];

      try{
        $input = fopen($archivo, "r");
        
        if($input)
        {
            Plato::borrarTodas();
  
            while(!feof($input))
            {
                $datoplato = fgets($input);
  
                if(!empty($datoplato))
                {         
                    $plato=new plato();
  
                    $arr=explode(",", $datoplato);
                    $plato->nombre=$arr[1];
                    $plato->sector=$arr[2];
                    $plato->precio=$arr[3];
                    $plato->stock=$arr[4];
                    $plato->crearplato();
                }
            }
            fclose($input);

          $out = fopen('php://temp', 'w');
          $platos=Plato::obtenerTodos();

          if($out)
          {
            foreach ($platos as $plato) {
              $platoRegistro=implode(",", (array)$plato) . PHP_EOL;
              fwrite($out, $platoRegistro);
            }
            rewind($out);
            $datosCsv=stream_get_contents($out);
            fclose($out);

            $response->getBody()->rewind();
            $response->getBody()->write($datosCsv);
          }
          else
            throw new Exception("Ocurrio un error cargando el archivo...");
        }
        else
            throw new Exception("Ocurrio un error restaurando la base...");
      }
      catch(Exception $e)
      {
        $payload=json_encode(array("error" => $e->getMessage()));
        $response->getBody()->write($payload);
      }

      return $response->withHeader('Content-Disposition', 'attachment; filename="platos.csv"')
                      ->withHeader('Content-Type', 'application/force-download');
    }
}