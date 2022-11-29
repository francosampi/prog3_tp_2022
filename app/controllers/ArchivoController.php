<?php

require_once './models/CSV.php';

class ArchivoController
{
    public function crearCSVDeDatosTabla($request, $response, $args)
    {
        try
        {
          $archivo=$_FILES["archivo"]["tmp_name"];
          $carga=CSV::crearCSVDeDatosTabla($archivo);
          
          if($carga)
          {
            readfile($archivo);
            $response->withHeader('Content-Type', 'text/csv');
          }
          else
            throw new Exception("Ocurrio un error");
        }
        catch(Exception $e)
        {
            $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(array("error" => $e->getMessage())));
        }

        return $response;
    }

    public function crearCSVConDatosDeUnaTabla($request, $response, $args)
    {
      if(Csv::crearCSVDeDatosTabla())
        $payload=json_encode(array("mensaje" => "El CSV se ha cargado exitosamente"));
      else
        $payload=json_encode(array("error" => "Faltan datos..."));
      
      $response->getBody()->write($payload);
   
      return $response->withHeader('Content-Type', 'application/json');;
    }
}

?>
